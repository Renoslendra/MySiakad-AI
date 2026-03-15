# Panduan Hosting SIAKAD (Laravel)

**Repo GitHub:** [https://github.com/Renoslendra/MySiakad-AI](https://github.com/Renoslendra/MySiakad-AI)

Proyek ini siap deploy untuk berbagai environment (Shared Hosting, VPS, atau Cloud). 

## Persyaratan Produksi
- **PHP**: 8.3+
- **Database**: MySQL atau PostgreSQL
- **Node.js**: Untuk build asset via Vite
- **Web Server**: Apache atau Nginx

## Langkah Dasar Deploy
1. **Clone Repository**: `git clone https://github.com/Renoslendra/MySiakad-AI.git`
2. **Setup Env**: Copy `.env.example` ke `.env` dan sesuaikan konfigurasinya (Database, App URL, dll).
3. **Install Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```
4. **Setup Database**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
5. **Optimize**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---
**Catatan:** Pastikan variabel AI seperti `GEMINI_API_KEY` atau `OPENROUTER_API_KEY` dikonfigurasi di environment production untuk fitur Academic Advisor.
