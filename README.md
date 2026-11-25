# Todo List CLI (PHP)

Aplikasi pencatat tugas (*Todo List*) berbasis Command Line Interface (CLI) yang ringan dan cepat, dibangun menggunakan PHP murni tanpa ketergantungan (*dependency*) eksternal. Data disimpan secara lokal dalam format JSON.

---

## 🚀 Fitur Utama

- **Tambah Tugas**: Menambahkan tugas baru dengan auto-increment ID.
- **Daftar Tugas (Tabel CLI)**: Menampilkan seluruh tugas dalam format tabel terminal yang rapi.
- **Update Status**: Memperbarui status tugas (`Todo`, `In Progress`, `Done`) disertai pencatatan waktu pembaruan otomatis (`updated_at`).
- **Hapus Tugas**: Menghapus tugas berdasarkan ID secara aman.
- **Penyimpanan Lokal JSON**: Data tersimpan secara persisten pada file `task.json`.

---

## 🛠️ Persyaratan Sistem

- PHP versi 7.4 atau lebih tinggi (disarankan PHP 8.x).
- Terminal berbasis Linux/macOS (Bash/Zsh) atau WSL di Windows.

Untuk mengecek versi PHP di terminal:
```bash
php -v
```

---

## 📦 Panduan Instalasi & Menjalankan (Bash / Unix)

### 1. Clone Repository
```bash
git clone https://github.com/USERNAME/todo-list-cli.git
cd todo-list-cli
```

### 2. Beri Izin Eksekusi (Opsional - Sebagai Shortcut Perintah)
Anda bisa menjalankan aplikasi menggunakan perintah bawaan PHP:
```bash
php tugas.php [aksi] [parameter]
```

Atau membuat *alias* / symlink agar bisa dijalankan layaknya perintah sistem:
```bash
# Tambahkan alias ke konfigurasi shell Anda (~/.bashrc atau ~/.zshrc)
echo "alias todo='php $(pwd)/tugas.php'" >> ~/.bashrc
source ~/.bashrc

# Sekarang Anda bisa menjalankannya langsung:
todo daftar
```

---

## 📖 Cara Penggunaan

### 1. Menampilkan Daftar Tugas
Melihat seluruh daftar tugas dalam format tabel:
```bash
php tugas.php daftar
# atau
php tugas.php list
```

### 2. Menambah Tugas Baru
Menambahkan tugas baru (gunakan tanda petik dua jika nama tugas lebih dari satu kata):
```bash
php tugas.php tambah "Belajar PHP CLI"
php tugas.php tambah "Beli kopi susu"
```

### 3. Mengubah Status Tugas
Mengubah status tugas berdasarkan ID ke `todo`, `in-progress`, atau `done`:
```bash
# Menandai tugas sedang dikerjakan
php tugas.php update 1 in-progress

# Menandai tugas selesai
php tugas.php update 1 done

# Mengembalikan ke status todo
php tugas.php update 1 todo
```

### 4. Menghapus Tugas
Menghapus tugas berdasarkan ID:
```bash
php tugas.php hapus 2
```

---

## 📂 Struktur Proyek

```text
todo-list-cli/
├── tugas.php     # File utama penanganan argumen CLI dan logika bisnis
├── table.php     # Modul helper untuk mencetak tampilan tabel di terminal
├── task.json     # File penyimpanan database lokal (dibuat otomatis)
├── .gitignore    # Mengabaikan file log/data lokal
└── README.md     # Dokumentasi proyek
```

---

## 💡 Apa yang Dipelajari dari Proyek Ini?

Melalui pembuatan proyek mini ini, ada banyak konsep fundamental rekayasa perangkat lunak dan PHP yang dipelajari:

1. **Dasar Pemrograman CLI**:
   - Membaca argumen baris perintah menggunakan array bawaan `$argv`.
   - Menggunakan exit code status (`exit(0)` untuk sukses dan `exit(1)` untuk error).
   - Membuat antarmuka pengguna berbasis teks (*text-based interface*) di terminal.

2. **File Handling & Persistensi Data**:
   - Mengimplementasikan operasi CRUD tanpa database SQL.
   - Membaca dan menulis file lokal dengan `file_get_contents()` dan `file_put_contents()`.
   - Melakukan serialisasi dan deserialisasi data menggunakan `json_encode()` dan `json_decode()`.
   - Menjaga path file tetap aman dan konsisten menggunakan konstanta `__DIR__`.

3. **Manipulasi Data & Logika PHP**:
   - Sanitasi input teks pengguna menggunakan `trim()` dan penanganan *case-insensitive* dengan `strtolower()`.
   - Operator logika seperti negasi (`!`) dan logika *or* (`||`) beserta prinsip *short-circuit evaluation*.
   - Menggunakan fungsi array modern seperti `array_column()` dan `max()` untuk kalkulasi auto-increment ID yang aman terhadap penghapusan data.
   - Pemanfaatan *pass-by-reference* (`&$item`) di perulangan `foreach` untuk memodifikasi array secara langsung.

4. **Clean Code & Modularitas**:
   - Memisahkan logika presentasi tabel ke dalam file modul `table.php` menggunakan `require_once`.
   - Menerapkan *Defensive Programming* dengan pengecekan `isset()`, `is_array()`, dan `is_numeric()` untuk mencegah runtime error.
   - Standar dokumentasi fungsi dengan PHPDoc (`@param`, `@return`) dan deklarasi tipe pengembalian (`: void`).

---

## 📄 Lisensi

Proyek ini dibuat untuk tujuan pembelajaran dan pengembangan diri. Bebas dimodifikasi dan digunakan kembali.
