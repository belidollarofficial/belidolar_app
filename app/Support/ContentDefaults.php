<?php

namespace App\Support;

class ContentDefaults
{
    /**
     * Nilai bawaan konten landing page.
     *
     * Format tiap item: [key, label, type, value].
     *
     * @return array<string, array<int, array{0: string, 1: string, 2: string, 3: string}>>
     */
    public static function all(): array
    {
        return [
            'hero' => [
                ['badge_rating', 'Badge Rating', 'text', '★ 4.9'],
                ['badge_customers', 'Badge Pelanggan', 'text', '2K+ Pelanggan'],
                ['title', 'Judul Utama', 'text', 'Jasa Pembayaran Digital'],
                ['title_highlight', 'Judul Sorotan (kuning)', 'text', 'All-in-One Terpercaya'],
                ['description', 'Deskripsi Hero', 'textarea', 'Top Up Saldo PayPal, Convert USD ke IDR, dan Bayar Invoice PayPal dengan proses cepat, aman, dan transparan dari USD ke Rupiah.'],
                ['cta_primary', 'Tombol Utama', 'text', 'Hubungi Kami'],
                ['cta_secondary', 'Tombol Sekunder', 'text', 'Lihat Layanan'],
                ['card_label', 'Label Kartu Hero', 'text', 'Pengalaman Top Up Terbaik dengan'],
                ['card_title', 'Judul Kartu Hero', 'text', 'Beli Dolar'],
                ['card_subtitle', 'Subjudul Kartu Hero', 'text', 'Top up with Beli Dolar now'],
                ['card_stat_1_value', 'Kartu Statistik 1 - Nilai', 'text', '2K+'],
                ['card_stat_1_label', 'Kartu Statistik 1 - Label', 'text', 'Pelanggan'],
                ['card_stat_2_value', 'Kartu Statistik 2 - Nilai', 'text', '★ 4.9'],
                ['card_stat_2_label', 'Kartu Statistik 2 - Label', 'text', 'Rating'],
            ],
            'stats' => [
                ['title', 'Judul Banner Statistik', 'text', 'Koneksi Nyata,'],
                ['title_highlight', 'Judul Sorotan (kuning)', 'text', 'Kepercayaan Utama'],
                ['subtitle', 'Subjudul Banner', 'text', 'Kami telah melayani ribuan transaksi dengan kepuasan terbaik'],
                ['transaksi_sukses', 'Angka Transaksi Sukses', 'number', '36000'],
                ['transaksi_sukses_label', 'Label Transaksi Sukses', 'text', 'Transaksi Sukses'],
                ['tahun_pengalaman', 'Angka Tahun Pengalaman', 'number', '5'],
                ['tahun_pengalaman_label', 'Label Tahun Pengalaman', 'text', 'Tahun Pengalaman'],
                ['saldo_terkirim', 'Angka Saldo Terkirim', 'number', '3000000'],
                ['saldo_terkirim_label', 'Label Saldo Terkirim', 'text', 'Saldo Terkirim'],
            ],
            'layanan' => [
                ['title', 'Judul', 'text', 'Layanan'],
                ['title_highlight', 'Judul Sorotan (gradient)', 'text', 'Kami'],
                ['subtitle', 'Subjudul', 'text', 'Solusi lengkap untuk semua kebutuhan transaksi digital Anda'],
                ['item_1_title', 'Item 1 - Judul', 'text', 'Top Up Saldo'],
                ['item_1_desc', 'Item 1 - Deskripsi', 'text', 'Isi saldo PayPal instan'],
                ['item_2_title', 'Item 2 - Judul', 'text', 'Convert Mata Uang'],
                ['item_2_desc', 'Item 2 - Deskripsi', 'text', 'USD ↔ IDR dengan kurs terbaik'],
                ['item_3_title', 'Item 3 - Judul', 'text', 'Bayar Invoice'],
                ['item_3_desc', 'Item 3 - Deskripsi', 'text', 'Pembayaran invoice PayPal'],
                ['item_4_title', 'Item 4 - Judul', 'text', 'Jasa Bayar Online'],
                ['item_4_desc', 'Item 4 - Deskripsi', 'text', 'Bayar belanjaan di situs luar negeri'],
            ],
            'cara_order' => [
                ['title', 'Judul', 'text', 'Cara'],
                ['title_highlight', 'Judul Sorotan (gradient)', 'text', 'Order'],
                ['subtitle', 'Subjudul', 'text', 'Mudah, cepat, dan terpercaya'],
                ['step_1_title', 'Langkah 1 - Judul', 'text', 'Isi Form'],
                ['step_1_desc', 'Langkah 1 - Deskripsi', 'text', 'Lengkapi data order Anda'],
                ['step_2_title', 'Langkah 2 - Judul', 'text', 'Chat WhatsApp'],
                ['step_2_desc', 'Langkah 2 - Deskripsi', 'text', 'Auto redirect ke WhatsApp'],
                ['step_3_title', 'Langkah 3 - Judul', 'text', 'Konfirmasi'],
                ['step_3_desc', 'Langkah 3 - Deskripsi', 'text', 'Admin verifikasi & proses'],
                ['step_4_title', 'Langkah 4 - Judul', 'text', 'Selesai'],
                ['step_4_desc', 'Langkah 4 - Deskripsi', 'text', 'Saldo masuk & notifikasi'],
            ],
            'testimoni' => [
                ['title', 'Judul', 'text', 'Testimoni'],
                ['title_highlight', 'Judul Sorotan (gradient)', 'text', 'Pelanggan'],
                ['subtitle', 'Subjudul', 'text', 'Apa kata mereka tentang layanan kami'],
                ['item_1_name', 'Testimoni 1 - Nama', 'text', 'Andi S.'],
                ['item_1_text', 'Testimoni 1 - Isi', 'textarea', '"Proses cepat banget! Top up $50 cuma 3 menit. Recommended!"'],
                ['item_2_name', 'Testimoni 2 - Nama', 'text', 'Rina W.'],
                ['item_2_text', 'Testimoni 2 - Isi', 'textarea', '"Convert USD ke IDR dengan kurs bagus. CS ramah dan responsif."'],
                ['item_3_name', 'Testimoni 3 - Nama', 'text', 'Budi P.'],
                ['item_3_text', 'Testimoni 3 - Isi', 'textarea', '"Bayar invoice PayPal jadi mudah. Terima kasih Beli Dolar!"'],
            ],
            'footer' => [
                ['description', 'Deskripsi Footer', 'textarea', 'Solusi terpercaya untuk kebutuhan transaksi digital Anda sejak 2021.'],
                ['layanan_link_1', 'Footer Layanan 1', 'text', 'Top Up Saldo'],
                ['layanan_link_2', 'Footer Layanan 2', 'text', 'Convert USD→IDR'],
                ['layanan_link_3', 'Footer Layanan 3', 'text', 'Bayar Invoice'],
                ['layanan_link_4', 'Footer Layanan 4', 'text', 'Jasa Bayar Online'],
                ['informasi_link_1', 'Footer Informasi 1', 'text', 'Tentang Kami'],
                ['informasi_link_2', 'Footer Informasi 2', 'text', 'Syarat & Ketentuan'],
                ['informasi_link_3', 'Footer Informasi 3', 'text', 'Kebijakan Privasi'],
                ['informasi_link_4', 'Footer Informasi 4', 'text', 'Cara Order'],
                ['kontak_phone', 'Kontak WhatsApp', 'text', '+62 812-3456-7890'],
                ['kontak_email', 'Kontak Email', 'text', 'cs@belidolar.id'],
                ['kontak_hours', 'Kontak Jam', 'text', '24 Jam / 7 Hari'],
                ['jam_operasional', 'Jam Operasional - Baris 1', 'text', 'Senin - Minggu'],
                ['jam_value', 'Jam Operasional - Baris 2', 'text', '24 Jam Nonstop'],
                ['jam_status', 'Jam Operasional - Status', 'text', 'Online 24/7'],
                ['copyright_lhs', 'Copyright - Kiri', 'text', '© 2021 Beli Dolar. All rights reserved. Made with'],
                ['copyright_rhs', 'Copyright - Kanan', 'text', 'in Indonesia'],
            ],
            'syarat_ketentuan' => [
                ['title', 'Judul', 'text', 'Syarat & Ketentuan'],
                ['content', 'Isi Syarat & Ketentuan', 'wysiwyg',
                    '<h2>1. Penerimaan Syarat</h2>'
                    .'<p>Dengan mengakses dan menggunakan layanan Beli Dolar, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku. Jika Anda tidak setuju, mohon untuk tidak menggunakan layanan kami.</p>'
                    .'<h2>2. Penggunaan Layanan</h2>'
                    .'<ul>'
                    .'<li>Layanan hanya dapat digunakan oleh pengguna berusia minimal 17 tahun atau yang telah memiliki kapasitas hukum.</li>'
                    .'<li>Anda wajib memberikan data yang benar, lengkap, dan atas nama Anda sendiri.</li>'
                    .'<li>Anda dilarang menggunakan layanan untuk aktivitas ilegal, penipuan, pencucian uang, atau pelanggaran ketentuan PayPal.</li>'
                    .'<li>Beli Dolar berhak menolak atau membatalkan transaksi yang dicurigai melanggar hukum atau berpotensi merugikan pihak lain.</li>'
                    .'</ul>'
                    .'<h2>3. Nominal, Rate, dan Pembayaran</h2>'
                    .'<ul>'
                    .'<li>Nominal transaksi dan estimasi Rupiah yang tampil pada form order bersifat informatif dan dapat berubah mengikuti rate yang berlaku.</li>'
                    .'<li>Nominal akhir yang harus dibayar akan dikonfirmasi oleh admin melalui WhatsApp sebelum transaksi diproses.</li>'
                    .'<li>Pembayaran dilakukan melalui rekening bank resmi yang tercantum pada halaman pembayaran/form order.</li>'
                    .'<li>Kesalahan transfer ke rekening di luar yang kami sediakan bukan menjadi tanggung jawab Beli Dolar.</li>'
                    .'</ul>'
                    .'<h2>4. Proses dan Estimasi Waktu</h2>'
                    .'<p>Transaksi diproses secara manual pada jam operasional. Estimasi waktu proses dapat berbeda tergantung antrian, kelengkapan data, dan kebijakan penyedia layanan terkait. Keterlambatan di luar kendali kami bukan merupakan pelanggaran atas ketentuan ini.</p>'
                    .'<h2>5. Pembatalan dan Pengembalian Dana</h2>'
                    .'<ul>'
                    .'<li>Pembatalan hanya dapat dilakukan sebelum dana atau saldo diteruskan ke akun tujuan.</li>'
                    .'<li>Jika transaksi gagal karena kesalahan kami, dana akan dikembalikan sesuai mekanisme yang disepakati.</li>'
                    .'<li>Dana yang telah berhasil dikirim ke akun tujuan tidak dapat ditarik kembali.</li>'
                    .'</ul>'
                    .'<h2>6. Batasan Tanggung Jawab</h2>'
                    .'<p>Beli Dolar tidak bertanggung jawab atas kerugian yang timbul akibat data yang salah dari pelanggan, pemblokiran akun oleh penyedia layanan pihak ketiga, gangguan jaringan, atau kejadian di luar kendali kami (force majeure).</p>'
                    .'<h2>7. Perubahan Ketentuan</h2>'
                    .'<p>Kami dapat memperbarui syarat dan ketentuan ini sewaktu-waktu. Perubahan berlaku sejak dipublikasikan pada halaman ini. Penggunaan layanan setelah perubahan berarti Anda menyetujui ketentuan yang diperbarui.</p>',
                ],
                ['topup', 'Ketentuan Top Up Saldo PayPal', 'wysiwyg',
                    '<h3>Ketentuan Top Up Saldo PayPal</h3>'
                    .'<ul>'
                    .'<li>Nominal top up menggunakan USD dan dibayarkan dalam Rupiah sesuai rate yang berlaku.</li>'
                    .'<li>Email/username dan nama akun PayPal wajib benar dan aktif. Kesalahan data menjadi tanggung jawab pelanggan.</li>'
                    .'<li>Untuk akun luar negeri, estimasi fee $5-$15 akan diinformasikan sebelum pembayaran.</li>'
                    .'<li>Saldo diproses setelah pembayaran dan konfirmasi admin diterima.</li>'
                    .'</ul>',
                ],
                ['convert_usd', 'Ketentuan Convert USD ke IDR', 'wysiwyg',
                    '<h3>Ketentuan Convert USD ke IDR</h3>'
                    .'<ul>'
                    .'<li>Rate mengikuti bracket nominal yang berlaku dan dapat berubah sewaktu-waktu.</li>'
                    .'<li>Pastikan saldo PayPal mencukupi dan akun dalam kondisi aktif.</li>'
                    .'<li>Nomor rekening atau e-wallet tujuan wajib valid dan atas nama pelanggan.</li>'
                    .'<li>Pencairan dilakukan setelah saldo diterima dan diverifikasi oleh admin.</li>'
                    .'</ul>',
                ],
                ['invoice', 'Ketentuan Bayar Invoice PayPal', 'wysiwyg',
                    '<h3>Ketentuan Bayar Invoice PayPal</h3>'
                    .'<ul>'
                    .'<li>Link invoice wajib valid dan dapat diakses oleh admin.</li>'
                    .'<li>Nominal invoice dibayarkan dalam Rupiah sesuai rate yang berlaku.</li>'
                    .'<li>Pastikan invoice belum dibayar sebelum order dikirim.</li>'
                    .'<li>Pembayaran yang sudah diproses tidak dapat dibatalkan.</li>'
                    .'</ul>',
                ],
                ['pay_online', 'Ketentuan Jasa Bayar Online', 'wysiwyg',
                    '<h3>Ketentuan Jasa Bayar Online</h3>'
                    .'<ul>'
                    .'<li>Nama merchant dan URL situs wajib sesuai dengan transaksi yang diminta.</li>'
                    .'<li>Total bayar dihitung dari nominal pembelian dikalikan rate yang berlaku.</li>'
                    .'<li>Metode pembayaran mengikuti pilihan yang tersedia di situs merchant.</li>'
                    .'<li>Layanan tidak dapat digunakan untuk barang atau jasa yang melanggar hukum.</li>'
                    .'</ul>',
                ],
                ['wd_akun_180', 'Ketentuan WD Akun Permanen 180 Hari', 'wysiwyg',
                    '<h3>Ketentuan WD Akun Permanen 180 Hari</h3>'
                    .'<ul>'
                    .'<li>Layanan WD berlaku untuk akun permanen dengan masa aktif 180 hari.</li>'
                    .'<li>Pastikan nama lengkap dan nomor WhatsApp aktif agar admin dapat menghubungi Anda.</li>'
                    .'<li>Admin akan melakukan verifikasi data sebelum proses WD dijalankan.</li>'
                    .'<li>Proses dan nominal pencairan mengikuti hasil verifikasi serta kebijakan yang berlaku.</li>'
                    .'</ul>',
                ],
            ],
        ];
    }

    /**
     * Nilai default untuk satu item konten, atau null jika tidak ada.
     */
    public static function value(string $section, string $key): ?string
    {
        return static::find($section, $key)['value'] ?? null;
    }

    /**
     * Detail default (label, type, value) untuk satu item konten.
     *
     * @return array{label: string, type: string, value: string}|null
     */
    public static function find(string $section, string $key): ?array
    {
        foreach (static::all()[$section] ?? [] as [$itemKey, $label, $type, $value]) {
            if ($itemKey === $key) {
                return ['label' => $label, 'type' => $type, 'value' => $value];
            }
        }

        return null;
    }
}
