# Panduan Hosting SIAKAD (Laravel) – Dapat Link Langsung

**Repo GitHub:** [https://github.com/Renoslendra/MySiakad-AI](https://github.com/Renoslendra/MySiakad-AI)

Proyek ini siap deploy. **Setup sekali**, lalu **setiap push ke `main` = hosting jalan otomatis** (dijalankan oleh GitHub Actions atau oleh Railway/Render). Link web Anda tetap sama.

---

## Alur otomatis: Push ke main → Hosting jalan sendiri

```
Anda push ke branch main di MySiakad-AI
        ↓
GitHub Actions / Railway / Render mendeteksi push
        ↓
Build & deploy otomatis
        ↓
Aplikasi live di link web Anda (termasuk fitur AI Academic Advisor)
```

- **Tanpa GitHub Actions (paling simpel):** Connect repo **MySiakad-AI** di Railway atau Render sekali. Setelah itu, **setiap push ke `main`** akan otomatis di-deploy oleh platform (Railway/Render). Tidak perlu isi secrets di GitHub.
- **Dengan GitHub Actions:** Jika Anda isi secrets di repo GitHub, workflow akan ikut menjalankan deploy setiap push ke `main` (berguna untuk Render deploy hook atau deploy via Railway CLI).

---

## Deploy otomatis (setelah setup sekali)

Workflow **Deploy SIAKAD** di folder `.github/workflows/` akan jalan setiap push ke `main` **jika** Anda sudah menambah secrets:

- **Railway**: Setelah langkah 1–7 di bawah selesai (connect repo, MySQL, Variables, Generate Domain), tambah secrets di repo [MySiakad-AI](https://github.com/Renoslendra/MySiakad-AI):
  - **Settings** → **Secrets and variables** → **Actions** → **New repository secret**
  - `RAILWAY_TOKEN`: Railway → Project → **Settings** → **Tokens** → **Create Token** (Project Token)
  - `RAILWAY_SERVICE_ID`: Railway → service **siakad** (bukan MySQL) → **Settings** → **General** → copy **Service ID** (UUID)
  - Setelah itu: **push ke `main`** = GitHub Actions + Railway deploy otomatis. Link = domain yang Anda generate.

- **Render**: Setelah deploy pertama (Blueprint), dapatkan **Deploy Hook**:
  - Render → service **siakad** → **Settings** → **Deploy Hook** → copy URL
  - Di repo MySiakad-AI → **Settings** → **Secrets** → tambah `RENDER_DEPLOY_HOOK_URL` = URL tadi
  - **Push ke `main`** = workflow memicu deploy di Render. Link = URL service di dashboard Render.

---

## Opsi 1: Railway (Disarankan – Paling Cepat)

Railway mendeteksi Dockerfile dan memberi Anda link domain otomatis.

### Langkah

1. **Push kode ke GitHub**  
   Pastikan repo **[MySiakad-AI](https://github.com/Renoslendra/MySiakad-AI)** sudah berisi kode terbaru (branch `main`).

2. **Buka [railway.app](https://railway.app)** → Login (bisa pakai GitHub).

3. **New Project** → **Deploy from GitHub repo** → pilih repo **MySiakad-AI** (`Renoslendra/MySiakad-AI`) → branch **main**.

4. **Tambah database MySQL**  
   - Di project yang sama: **+ New** → **Database** → **Add MySQL**.  
   - Setelah jadi, buka service MySQL → tab **Variables** → copy **`MYSQL_URL`** atau **`DATABASE_URL`** (kalau ada).

5. **Atur variabel environment**  
   Buka service **siakad** (bukan MySQL) → **Variables** → **Raw Editor**, lalu isi (sesuaikan nilai):

   ```env
   APP_NAME=SIAKAD
   APP_ENV=production
   APP_KEY=base64:XXXXX
   APP_DEBUG=false
   APP_URL=https://NAMA-SERVICE-ANDA.up.railway.app
   APP_LOCALE=id
   LOG_CHANNEL=stderr
   LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter

   DB_CONNECTION=mysql
   DB_HOST=...
   DB_PORT=3306
   DB_DATABASE=railway
   DB_USERNAME=root
   DB_PASSWORD=...

   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```

   - **APP_KEY**: jalankan di komputer Anda:  
     `php artisan key:generate --show`  
     Copy hasilnya ke `APP_KEY`.
   - **APP_URL**: isi **setelah** Anda generate domain (langkah 6). Kalau belum, isi dulu `http://localhost`, nanti diganti.
   - **DB_***: ambil dari service MySQL (Variables). Kalau Railway kasih **MYSQL_URL** satu string, biasanya bisa dipakai dengan memecah jadi `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE` dari URL itu.

6. **Generate domain (dapat link)**  
   - Di service **siakad** → **Settings** → **Networking** → **Generate Domain**.  
   - Copy URL yang muncul (mis. `https://siakad-production.up.railway.app`).  
   - Kembali ke **Variables** → ubah **APP_URL** ke URL tadi → **Redeploy** (Deployments → ⋮ → Redeploy).

7. **Tunggu deploy selesai**  
   Setelah status hijau, buka link dari langkah 6. Itu **link web** SIAKAD Anda.

---

## Opsi 2: Render (Blueprint – Satu Klik)

Render pakai **PostgreSQL** (bukan MySQL). Semua sudah diatur di `render.yaml`.

### Langkah

1. **Push kode ke GitHub** ke repo [MySiakad-AI](https://github.com/Renoslendra/MySiakad-AI) (pastikan ada `render.yaml`).

2. **Buka [render.com](https://render.com)** → Login (bisa GitHub).

3. **New** → **Blueprint** → Connect repository **MySiakad-AI** (`Renoslendra/MySiakad-AI`) → **Apply**.

4. Render akan membuat:
   - Web service **siakad** (dari Dockerfile),
   - Database PostgreSQL **siakad-db**,
   - Variabel seperti `APP_KEY`, `APP_URL`, `DB_URL` (dari blueprint).

5. Setelah deploy selesai, **link web** ada di dashboard service **siakad** (mis. `https://siakad.onrender.com`).

6. **(Opsional)** Tambah variabel lain (Mayar, OpenRouter, dll.) di **Environment** service **siakad**.

---

## Setelah Dapat Link

- **APP_URL** harus persis sama dengan link yang dipakai user (termasuk `https://`).
- Kalau pakai fitur bayar (Mayar), isi **MAYAR_*** di Variables.
- Kalau pakai AI (OpenRouter/Gemini), isi **OPENROUTER_API_KEY** / **GEMINI_API_KEY** di Variables.

---

## Troubleshooting Singkat

| Masalah | Cek |
|--------|-----|
| 500 / blank page | `APP_KEY` sudah di-set? `APP_DEBUG=false` (jangan `true` di production). Lihat log di dashboard. |
| CSS/JS tidak load | `APP_URL` harus sama dengan link browser (https://...). Lalu redeploy. |
| Database error | Pastikan `DB_*` (atau `DB_URL` di Render) benar dan database sudah jalan. |
| Build gagal | Pastikan `package-lock.json` ada dan ikut di repo. |

Dengan salah satu opsi di atas, Anda cukup **push ke GitHub** dan ikuti langkahnya; hasilnya berupa **link web** yang bisa dibuka langsung.

---

**Ringkasan otomatis:** Repo = **MySiakad-AI**. Setup sekali (connect repo + database + variables + generate domain) → dapat link web. Setelah itu **setiap push ke `main`** = deploy otomatis (oleh Railway/Render atau GitHub Actions). Link web tetap; fitur **AI Academic Advisor** (Gemini) jalan di hosting asal variabel `GEMINI_API_KEY` atau `OPENROUTER_API_KEY` sudah di-set di environment.
