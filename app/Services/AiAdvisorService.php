<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\Mahasiswa;
use App\Models\AiConversationLog;
use App\Services\AcademicAdvisor\AdvisorContextBuilder;
use App\Services\AcademicAdvisor\AdvisorGuards;

class AiAdvisorService
{
    protected AdvisorContextBuilder $contextBuilder;
    protected AdvisorGuards $guards;
    protected string $apiKey;
    protected string $model;
    protected string $provider;

    protected const MAX_RETRIES = 1;

    public function __construct(
        AdvisorContextBuilder $contextBuilder,
        AdvisorGuards $guards
    ) {
        $this->contextBuilder = $contextBuilder;
        $this->guards = $guards;

        // Get AI provider from config (default: openrouter)
        $this->provider = config('services.ai_provider', 'openrouter');

        if ($this->provider === 'qwen') {
            $this->apiKey = (string) config('services.qwen.api_key', '');
            $this->model = (string) config('services.qwen.model', 'Qwen/Qwen2.5-72B-Instruct');
        } elseif ($this->provider === 'openrouter') {
            $this->apiKey = (string) config('services.openrouter.api_key', '');
            $this->model = (string) config('services.openrouter.model', 'google/gemini-2.0-flash-001');
        } else {
            $this->apiKey = (string) config('services.gemini.api_key', '');
            $this->model = (string) config('services.gemini.model', 'gemini-1.5-flash');
        }
    }

    /**
     * Send a chat message to Gemini with grounded student context
     */
    public function chat(Mahasiswa $mahasiswa, string $message, array $history = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key Gemini belum dikonfigurasi. Silakan hubungi administrator.',
            ];
        }

        try {
            // Step 1: Build context
            $startTime = microtime(true); // Start timer for logging
            $context = $this->contextBuilder->build($mahasiswa);

            // Step 2: Run pre-guards
            $this->guards->assertRulesPresent($context);
            $this->guards->validateContext($context);

            // Step 3: Build system prompt with context
            $systemPrompt = $this->buildSystemPrompt($context);

            // Step 4: Call LLM
            $response = $this->callLlm($systemPrompt, $message, $history);

            if (!$response['success']) {
                return $response;
            }

            $output = $response['message'];

            // Step 5: Run post-guards
            $guardResult = $this->guards->runPostGuards($context, $output);

            if (!$guardResult['passed']) {
                // Try retry if allowed
                if ($guardResult['should_retry'] && $guardResult['retry_prompt']) {
                    $retryResponse = $this->retryWithGuardPrompt(
                        $systemPrompt,
                        $message,
                        $output,
                        $guardResult['retry_prompt'],
                        $history
                    );

                    if ($retryResponse['success']) {
                        // Check guards again on retry
                        $retryGuardResult = $this->guards->runPostGuards($context, $retryResponse['message']);
                        if ($retryGuardResult['passed']) {
                            return $retryResponse;
                        }
                    }
                }

                // Use replacement output if guard provides one
                if ($guardResult['replacement_output']) {
                    $debugInfo = '';
                    if (config('app.debug')) {
                        $debugInfo = "\n\n---\n**[DEBUG INFO]**\n";
                        foreach ($guardResult['issues'] as $issue) {
                            $guard = $issue['guard'] ?? 'unknown';
                            $debugInfo .= "- Guard: `{$guard}`\n";
                            if (isset($issue['violations'])) {
                                $debugInfo .= "  - Violations: " . implode(', ', $issue['violations']) . "\n";
                            }
                            if (isset($issue['issue'])) {
                                $debugInfo .= "  - Issue: {$issue['issue']}\n";
                            }
                            if (isset($issue['invalid_courses'])) {
                                $debugInfo .= "  - Invalid courses: " . implode(', ', $issue['invalid_courses']) . "\n";
                            }
                            if (isset($issue['mismatches'])) {
                                foreach ($issue['mismatches'] as $m) {
                                    $debugInfo .= "  - Mismatch: {$m['field']} (claimed: {$m['claimed']}, actual: {$m['actual']})\n";
                                }
                            }
                        }
                    }

                    // Log guard-applied conversation
                    $responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);
                    $this->logConversation($mahasiswa, $message, $guardResult['replacement_output'], [
                        'response_time_ms' => $responseTimeMs,
                        'guard_applied' => true,
                        'guard_issues' => $guardResult['issues'],
                    ]);

                    return [
                        'success' => true,
                        'message' => $guardResult['replacement_output'] . $debugInfo,
                        'guard_applied' => true,
                    ];
                }
            }

            // Calculate response time
            $responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);

            // Log successful conversation
            $this->logConversation($mahasiswa, $message, $output, [
                'response_time_ms' => $responseTimeMs,
                'guard_applied' => false,
                'guard_issues' => null,
            ]);

            return [
                'success' => true,
                'message' => $output,
            ];

        } catch (\InvalidArgumentException $e) {
            return [
                'success' => false,
                'message' => 'Konfigurasi akademik tidak valid: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Log AI conversation to database
     */
    protected function logConversation(Mahasiswa $mahasiswa, string $question, string $answer, array $metadata = []): void
    {
        try {
            AiConversationLog::create([
                'user_id' => $mahasiswa->user_id,
                'mahasiswa_id' => $mahasiswa->id,
                'session_id' => session()->getId(),
                'question' => $question,
                'answer' => $answer,
                'context_summary' => "Semester {$mahasiswa->semester_aktif}, SKS: " . ($metadata['sks_lulus'] ?? 'N/A'),
                'response_time_ms' => $metadata['response_time_ms'] ?? 0,
                'model_used' => $this->model,
                'provider' => $this->provider,
                'guard_applied' => $metadata['guard_applied'] ?? false,
                'guard_issues' => $metadata['guard_issues'] ?? null,
                'was_retry' => $metadata['was_retry'] ?? false,
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break main functionality
            \Log::warning('Failed to log AI conversation: ' . $e->getMessage());
        }
    }

    /**
     * Build system prompt from template with context
     */
    protected function buildSystemPrompt(array $context): string
    {
        $templatePath = resource_path('prompts/academic_advisor_system.txt');

        if (File::exists($templatePath)) {
            $template = File::get($templatePath);
        } else {
            $template = $this->getDefaultPromptTemplate();
        }

        // Inject context JSON
        $contextJson = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $prompt = str_replace('{{CONTEXT_JSON}}', $contextJson, $template);

        return $prompt;
    }

    /**
     * Call LLM API (supports Gemini and Qwen via Bytez)
     */
    protected function callLlm(string $systemPrompt, string $message, array $history = []): array
    {
        $messages = [];

        // Add system prompt
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

        // Add conversation history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['content']
            ];
        }

        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];

        try {
            if ($this->provider === 'qwen') {
                return $this->callQwenApi($messages);
            } elseif ($this->provider === 'openrouter') {
                return $this->callOpenRouterApi($messages);
            }

            return $this->callGeminiApi($messages);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Call OpenRouter API (OpenAI Compatible)
     */
    protected function callOpenRouterApi(array $messages): array
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'), // Optional, for OpenRouter dashboard
                'X-Title' => 'SIAKAD AI Advisor', // Optional, for OpenRouter dashboard
            ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.1,
                'max_tokens' => 2048,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa memberikan respons saat ini.';

            return [
                'success' => true,
                'message' => $text,
            ];
        }

        $error = $response->json();
        $statusCode = $response->status();
        $errorMessage = $error['error']['message'] ?? json_encode($error) ?? 'Unknown error';

        return [
            'success' => false,
            'message' => "Gagal mendapatkan respons dari AI (OpenRouter). Status: {$statusCode}. Detail: {$errorMessage}",
        ];
    }

    /**
     * Call Qwen API via Bytez (OpenAI-compatible endpoint)
     */
    protected function callQwenApi(array $messages): array
    {
        // Bytez OpenAI-compatible endpoint
        $url = 'https://api.bytez.com/models/v2/openai/v1/chat/completions';

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 1024,
                'temperature' => 0,
                'stream' => false,
            ]);

        if ($response->successful()) {
            $data = $response->json();

            // OpenAI-compatible response format
            $text = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa memberikan respons saat ini.';

            // Clean thinking tags if present (Qwen3 uses <think> tags)
            $text = $this->cleanQwenThinkingTags($text);

            return [
                'success' => true,
                'message' => $text,
            ];
        }

        $error = $response->json();
        $statusCode = $response->status();
        $errorMessage = $error['error']['message'] ?? $error['error'] ?? $error['message'] ?? 'Unknown error';

        return [
            'success' => false,
            'message' => "Gagal mendapatkan respons dari AI (Qwen). Status: {$statusCode}. Detail: {$errorMessage}",
        ];
    }

    /**
     * Call Gemini API (OpenAI compatibility mode)
     */
    protected function callGeminiApi(array $messages): array
    {
        // Safety check for API Key format
        if (str_starts_with($this->apiKey, 'sk-')) {
            return [
                'success' => false,
                'message' => "Format API Key tidak valid untuk Gemini. Key Anda diawali dengan 'sk-', yang biasanya digunakan untuk OpenAI atau Groq. Silakan gunakan key yang diawali dengan 'AIzaSy' dari Google AI Studio.",
            ];
        }

        // Convert OpenAI-style messages to Gemini Native contents
        $contents = [];
        $systemText = "";

        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemText .= $msg['content'] . "\n\n";
                continue;
            }

            $contents[] = [
                'role' => ($msg['role'] === 'assistant' || $msg['role'] === 'model') ? 'model' : 'user',
                'parts' => [
                    ['text' => $msg['content']]
                ]
            ];
        }

        // Prepend system instruction to the first user message for maximum compatibility
        if (!empty($systemText) && !empty($contents)) {
            $firstUserIdx = -1;
            foreach ($contents as $idx => $content) {
                if ($content['role'] === 'user') {
                    $firstUserIdx = $idx;
                    break;
                }
            }

            if ($firstUserIdx !== -1) {
                $contents[$firstUserIdx]['parts'][0]['text'] = "INSTRUCTION:\n" . $systemText . "---\n\n" . $contents[$firstUserIdx]['parts'][0]['text'];
            }
        }

        // Native Gemini URL (using v1beta for widest model support)
        $modelName = str_contains($this->model, '/') ? explode('/', $this->model)[1] : $this->model;
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->apiKey}";

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.1,
                'maxOutputTokens' => 2048,
            ]
        ];

        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa memberikan respons saat ini.';

            return [
                'success' => true,
                'message' => $text,
            ];
        }

        $error = $response->json();
        $statusCode = $response->status();
        $errorMessage = $error['error']['message'] ?? json_encode($error) ?? 'Unknown error';

        return [
            'success' => false,
            'message' => "Gagal mendapatkan respons dari AI (Gemini Native). Status: {$statusCode}. Detail: {$errorMessage}",
        ];
    }

    /**
     * Clean Qwen3 thinking tags from response
     */
    protected function cleanQwenThinkingTags(string $text): string
    {
        // Remove <think>...</think> blocks
        $text = preg_replace('/<think>.*?<\/think>/s', '', $text);
        return trim($text);
    }

    /**
     * Retry with guard prompt
     */
    protected function retryWithGuardPrompt(
        string $systemPrompt,
        string $originalMessage,
        string $previousOutput,
        string $guardPrompt,
        array $history
    ): array {
        $messages = [];

        // Add system prompt
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

        // Add history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['content']
            ];
        }

        // Add original message
        $messages[] = [
            'role' => 'user',
            'content' => $originalMessage
        ];

        // Add previous (problematic) output
        $messages[] = [
            'role' => 'assistant',
            'content' => $previousOutput
        ];

        // Add guard retry prompt
        $messages[] = [
            'role' => 'user',
            'content' => $guardPrompt
        ];

        try {
            if ($this->provider === 'qwen') {
                $result = $this->callQwenApi($messages);
            } else {
                $result = $this->callGeminiApi($messages);
            }

            if ($result['success']) {
                $result['is_retry'] = true;
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Retry failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the context builder instance
     */
    public function getContextBuilder(): AdvisorContextBuilder
    {
        return $this->contextBuilder;
    }

    /**
     * Get the guards instance
     */
    public function getGuards(): AdvisorGuards
    {
        return $this->guards;
    }

    /**
     * Default prompt template if file not found
     */
    protected function getDefaultPromptTemplate(): string
    {
        return <<<'PROMPT'
<SYSTEM_IDENTITY>
Kamu adalah AI Academic Advisor untuk SIAKAD. Jawab HANYA berdasarkan data context JSON.
</SYSTEM_IDENTITY>

<GROUNDING_RULES>
1. HANYA gunakan data dari context JSON
2. JANGAN menggunakan asumsi umum (biasanya, umumnya, tergantung)
3. Jika data tidak ada, katakan "data belum tersedia"
4. Gunakan status: LULUS, SEDANG_DIAMBIL, TERSEDIA_DI_KURIKULUM
5. Jangan simpulkan presensi rendah jika attendance.data_available = false
</GROUNDING_RULES>

<DATA_CONTEXT>
{{CONTEXT_JSON}}
</DATA_CONTEXT>
PROMPT;
    }

    /**
     * Build context for external use (e.g., testing)
     */
    public function buildContext(Mahasiswa $mahasiswa): array
    {
        return $this->contextBuilder->build($mahasiswa);
    }

    /**
     * Calculate graduation progress
     */
    public function calculateGraduationProgress(Mahasiswa $mahasiswa): array
    {
        $context = $this->contextBuilder->build($mahasiswa);
        return $this->contextBuilder->calculateGraduationProgress($context);
    }

    /**
     * Find course by name
     */
    public function findCourse(Mahasiswa $mahasiswa, string $courseName): ?array
    {
        $context = $this->contextBuilder->build($mahasiswa);
        return $this->contextBuilder->findCourseByName($context, $courseName);
    }
}
