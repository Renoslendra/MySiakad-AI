<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Reminder;
use App\Models\Dosen;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        // Get Dosen PA
        $dosenPa = $mahasiswa->dosenPa;

        // Get other lecturers from enrolled classes
        $dosenLain = Dosen::whereHas('kelas.krsDetail.krs', function($query) use ($mahasiswa) {
            $query->where('mahasiswa_id', $mahasiswa->id);
        })->where('id', '!=', $mahasiswa->dosen_pa_id)->get();

        $reminders = Reminder::where('user_id', $user->id)
            ->with('dosen.user')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Attach latest pending reminder to each dosen for quick access
        $attachReminder = function($dosen) use ($user) {
            $dosen->latest_reminder = Reminder::where('user_id', $user->id)
                ->where('dosen_id', $dosen->id)
                ->where('status', 'pending')
                ->latest('scheduled_at')
                ->first();
            return $dosen;
        };

        if ($dosenPa) $dosenPa = $attachReminder($dosenPa);
        $dosenLain = $dosenLain->map($attachReminder);

        return view('mahasiswa.reminders.index', compact('dosenPa', 'dosenLain', 'reminders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'phone_override' => 'nullable|string|max:20',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'message' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $reminder = Reminder::create([
            'user_id' => auth()->id(),
            'dosen_id' => $request->dosen_id,
            'phone_override' => $request->phone_override,
            'title' => $request->title,
            'message' => $request->message,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
            'is_whatsapp' => true,
        ]);

        return redirect()->route('mahasiswa.reminders.index')->with('success', 'Alarm berhasil diatur!');
    }

    public function generateAiMessage(Request $request, \App\Services\AiAdvisorService $aiService)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'category' => 'required|string',
            'title' => 'required|string',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;
        $dosen = Dosen::find($request->dosen_id);
        
        $result = $aiService->generateLecturerMessage(
            $mahasiswa,
            $dosen->user->name,
            $request->category,
            $request->title
        );

        return response()->json($result);
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) {
            abort(403);
        }

        $reminder->delete();
        return back()->with('success', 'Pengingat berhasil dihapus.');
    }
}
