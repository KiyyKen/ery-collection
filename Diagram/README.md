# Diagram/ — PlantUML Workspace

## Tujuan Folder Ini

Folder ini berisi seluruh diagram UML (ERD, LRS, Use Case, Activity, Sequence, Class) untuk dokumentasi skripsi **"Implementasi Sistem Distribusi untuk Memprediksi Produk Terlaris dengan Algoritma C4.5 Berbasis Website pada Toko Yeri Collection"**. Diagram ditulis sebagai kode (PlantUML), bukan gambar statis, supaya:

- Mudah di-review lewat diff seperti kode biasa.
- Konsisten secara visual (satu tema bersama, lihat `theme.puml`).
- Mudah diekspor ulang ke PNG/SVG kapan pun struktur diagram berubah.

## Struktur Folder

```
Diagram/
├── theme.puml          # Tema bersama (styling), di-include oleh semua diagram
├── README.md           # Dokumen ini
├── ERD/
│   ├── ERD.puml         # Entity Relationship Diagram
│   └── LRS.puml         # Logical Record Structure
├── UseCase/
│   └── UseCase.puml     # Use Case Diagram
├── Activity/
│   ├── Login.puml
│   ├── Produk.puml
│   ├── Distribusi.puml
│   ├── Prediksi.puml
│   └── Laporan.puml
├── Sequence/
│   ├── Login.puml
│   ├── Produk.puml
│   ├── Distribusi.puml
│   ├── Prediksi.puml
│   └── Laporan.puml
└── Class/
    └── ClassDiagram.puml
```

## Konvensi Penamaan

- Nama file mengikuti nama modul/halaman aplikasi dalam Bahasa Indonesia dan `PascalCase`: `Produk.puml`, `Distribusi.puml`, `Prediksi.puml`, `Laporan.puml`, `Login.puml`.
- Setiap folder kategori (`Activity/`, `Sequence/`, dst.) berisi satu file per modul, dengan nama file yang **sama** di kedua folder (mis. `Activity/Prediksi.puml` dan `Sequence/Prediksi.puml`) — mempermudah mencari diagram yang berpasangan untuk modul yang sama.
- `ERD/` dan `Class/` hanya punya satu diagram per jenis karena sifatnya menyeluruh (bukan per-modul).
- Setiap file diawali `@startuml`, meng-include `theme.puml`, lalu `title` deskriptif, dan diakhiri `@enduml`.

## Tema Bersama (`theme.puml`)

Semua diagram meng-include `theme.puml` (path relatif `../theme.puml` dari tiap sub-folder) agar seluruh diagram memakai styling yang sama: latar putih, tanpa shadow, monochrome-friendly untuk cetak hitam-putih, garis ortogonal, DPI 180, dan font seragam. Lihat isi `theme.puml` untuk detail lengkap — file ini **hanya berisi `skinparam`**, tidak ada actor/class/entity apa pun di dalamnya, jadi aman di-include dari diagram jenis apa saja.

## Cara Preview Diagram (VS Code + jebbs.plantuml)

1. Install extension **PlantUML** oleh `jebbs` (ID: `jebbs.plantuml`) — VS Code akan otomatis menyarankan ini karena sudah terdaftar di `.vscode/extensions.json`.
2. Buka salah satu file `.puml`, misalnya `Diagram/UseCase/UseCase.puml`.
3. Tekan **Alt + D** untuk membuka panel preview di sebelah editor. Preview akan otomatis update saat file disimpan/diedit (`plantuml.previewAutoUpdate` sudah diaktifkan di `.vscode/settings.json`).
4. Alternatif: buka Command Palette (`Ctrl+Shift+P`) → ketik `PlantUML: Preview Current Diagram`.

## Cara Export ke PNG

1. Buka file `.puml` yang ingin diekspor.
2. Command Palette (`Ctrl+Shift+P`) → `PlantUML: Export Current Diagram`.
3. Pilih format `png` (ini juga format default yang sudah diset lewat `plantuml.exportFormat` di `.vscode/settings.json`, jadi biasanya langsung ter-pilih).
4. Hasil export akan tersimpan di folder `out/` relatif terhadap file `.puml` sumbernya (`plantuml.exportOutDir`).

## Cara Export ke SVG

1. Sama seperti export PNG di atas, tapi pilih `PlantUML: Export Current Diagram As...` lalu pilih format `svg` secara eksplisit dari daftar pilihan.
2. SVG cocok dipakai untuk lampiran skripsi yang butuh kualitas vektor (tidak pecah saat diperbesar), sedangkan PNG lebih universal untuk ditempel langsung ke dokumen Word.

## Kebutuhan Java

PlantUML **membutuhkan Java** untuk rendering, baik untuk mode local maupun sebagai bagian dari cara kerja extension. Cek apakah Java sudah terpasang:

```
java -version
```

Kalau belum ada, download dari [https://adoptium.net](https://adoptium.net) (rekomendasi: distribusi Temurin, versi LTS terbaru) lalu install dengan opsi default.

## Rekomendasi Graphviz

**Graphviz bersifat opsional** untuk versi PlantUML modern (mesin layout bawaan "Smetana" sudah cukup untuk sebagian besar diagram di folder ini), tapi tetap **direkomendasikan** untuk hasil layout yang lebih rapi pada diagram yang kompleks (terutama Class Diagram dan ERD dengan banyak relasi).

Cek apakah sudah terpasang:

```
dot -V
```

Kalau belum ada, download installer Windows dari [https://graphviz.org/download/](https://graphviz.org/download/), jalankan installer-nya, dan **pastikan mencentang opsi "Add Graphviz to the system PATH"** saat instalasi. Setelah itu, buka terminal baru dan jalankan ulang `dot -V` untuk memastikan sudah terdeteksi.

## Local Rendering vs PlantUML Server

Extension `jebbs.plantuml` punya dua mode render, diatur lewat `plantuml.render` di `.vscode/settings.json`:

| Mode | Kapan dipakai | Kelebihan | Kekurangan |
|---|---|---|---|
| `"Local"` | Java **dan** Graphviz sudah terpasang | Tidak butuh internet, lebih cepat, data diagram tidak keluar dari komputer | Perlu instalasi Java + Graphviz terlebih dahulu |
| `"PlantUMLServer"` | Salah satu (atau keduanya) belum terpasang | Langsung bisa dipakai tanpa instalasi tambahan | Butuh koneksi internet; isi diagram dikirim ke server publik `plantuml.com` |

Workspace ini sudah dikonfigurasi otomatis mengikuti kondisi environment saat setup dilakukan — cek nilai `plantuml.render` saat ini di `.vscode/settings.json`. Kalau Anda baru saja menginstall Graphviz, ubah nilainya secara manual ke `"Local"` agar rendering tidak lagi bergantung pada server publik.
