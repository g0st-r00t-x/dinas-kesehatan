# Laravel Project Documentation

## 📌 Project Description
Project ini adalah aplikasi berbasis Laravel yang mendukung notifikasi WhatsApp, pengelolaan izin pengguna dengan Filament, serta fitur real-time messaging menggunakan Broadcasting dan Reverb.

## 🛠️ Tools & Dependencies
Berikut adalah alat dan pustaka yang digunakan dalam proyek ini:

- **[Laravel](https://laravel.com/)** - Framework utama.
- **[Filament](https://filamentphp.com/)** - Panel admin untuk Laravel.
- **[Filament Shield](https://github.com/bezhanSalleh/filament-shield)** - Manajemen izin berbasis Filament.
- **[Dompdf](https://github.com/dompdf/dompdf)** - Untuk menghasilkan file PDF.
- **[Fonte](https://github.com/fonte/fonte)** - Untuk mengirim notifikasi WhatsApp.
- **[Laravel Broadcasting](https://laravel.com/docs/broadcasting)** - Untuk komunikasi real-time.
- **[Laravel Reverb](https://laravel.com/docs/10.x/broadcasting#driver-reverb)** - WebSocket berbasis Laravel.

## ⚙️ Installation Guide
Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini.

### 1️⃣ Clone Repository
```sh
git clone https://github.com/username/repository.git
cd repository
```

### 2️⃣ Install Dependencies
```sh
composer install
npm install
```

### 3️⃣ Setup Environment
Salin file `.env.example` menjadi `.env` dan konfigurasi database serta alat yang digunakan.
```sh
cp .env.example .env
```
Lalu, buat kunci aplikasi:
```sh
php artisan key:generate
```

### 4️⃣ Konfigurasi Database
Pastikan `.env` telah diatur dengan informasi database yang benar, lalu jalankan:
```sh
php artisan migrate --seed
```

### 5️⃣ Instalasi & Konfigurasi Filament
```sh
php artisan filament:install
php artisan shield:generate
```

### 6️⃣ Instalasi Fonte (WhatsApp Notification)
```sh
composer require fonte/fonte
```
Tambahkan konfigurasi API WhatsApp di `.env`:
```
FONTE_API_KEY=your_api_key_here
```

### 7️⃣ Instalasi dan Konfigurasi Broadcasting & Reverb
```sh
composer require laravel/reverb
```
Aktifkan broadcasting di `.env`:
```
BROADCAST_DRIVER=reverb
```
Jalankan WebSocket server:
```sh
php artisan reverb:start
```

### 8️⃣ Jalankan Aplikasi
```sh
php artisan serve
npm run dev
```

## 🚀 Usage Guide
- **Admin Panel**: Akses melalui `/admin` dengan login yang dibuat saat seeding.
- **Notifikasi WA**: Digunakan untuk mengirim notifikasi ke pengguna.
- **Real-time Messaging**: Digunakan untuk pengiriman pesan secara langsung.
- **PDF Generation**: Dompdf digunakan untuk men-generate laporan atau dokumen.

## 🤝 Contribution Guide
Jika ingin berkontribusi:
1. Fork repository ini.
2. Buat branch baru (`git checkout -b feature-branch`).
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`).
4. Push ke repository (`git push origin feature-branch`).
5. Buat Pull Request (PR).

## 📝 License
Project ini menggunakan lisensi MIT. Silakan digunakan dan dikembangkan lebih lanjut!

