<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * HypothesisBapTemplateSeeder
 *
 * Mengisi kolom bap_template pada tabel hypotheses untuk H1–H10.
 * Narasi disusun berdasarkan unsur pidana masing-masing pasal UU TPKS
 * dan disesuaikan untuk dirangkai menjadi Rangkuman Kejadian di halaman 2 PDF.
 *
 * Cara menjalankan:
 *   php artisan db:seed --class=HypothesisBapTemplateSeeder
 *
 * Atau tambahkan ke DatabaseSeeder::run():
 *   $this->call(HypothesisBapTemplateSeeder::class);
 */
class HypothesisBapTemplateSeeder extends Seeder
{
    /**
     * Peta pasal_uutpks → narasi bap_template.
     * Key harus PERSIS sama dengan nilai kolom pasal_uutpks di DB.
     */
    private array $templates = [

        // ── H1 ─ Pelecehan Seksual Nonfisik (Pasal 5) ───────────────────────
        'Pasal 5' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa telah '
            . 'terjadi perbuatan seksual secara nonfisik yang ditujukan kepada tubuh, organ '
            . 'reproduksi, atau seksualitas penyintas — seperti ucapan, komentar, siulan, '
            . 'atau gestur seksual. Tindakan tersebut membuat penyintas merasa direndahkan, '
            . 'dipermalukan, dilecehkan, atau terganggu kenyamanannya berdasarkan seksualitas '
            . 'dan/atau kesusilaan. Seluruh tindakan di atas dikonfirmasi terjadi tanpa '
            . 'persetujuan (non-konsensual) dari pihak penyintas.',

        // ── H2 ─ Pelecehan Seksual Fisik (Pasal 6) ──────────────────────────
        'Pasal 6' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan adanya kontak '
            . 'fisik bernuansa seksual yang dilakukan pelaku terhadap tubuh atau organ '
            . 'reproduksi penyintas tanpa persetujuan. Tindakan fisik tersebut dilakukan '
            . 'dengan tujuan merendahkan martabat penyintas, menempatkan penyintas di bawah '
            . 'kekuasaan pelaku, atau memaksa terjadinya hubungan seksual maupun perbuatan '
            . 'cabul. Seluruh tindakan di atas dikonfirmasi terjadi mutlak tanpa persetujuan '
            . '(non-konsensual) dari pihak penyintas.',

        // ── H3 ─ Pemaksaan Kontrasepsi (Pasal 8) ────────────────────────────
        'Pasal 8' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa pelaku '
            . 'secara melawan hukum memaksa penyintas menggunakan alat atau metode kontrasepsi '
            . 'tertentu yang menyebabkan hilangnya fungsi reproduksi penyintas untuk sementara '
            . 'waktu. Pemaksaan ini dilakukan tanpa persetujuan dan tanpa alasan medis yang '
            . 'sah, serta bertentangan dengan hak reproduksi penyintas sebagaimana dilindungi '
            . 'oleh UU TPKS.',

        // ── H4 ─ Pemaksaan Sterilisasi (Pasal 9) ────────────────────────────
        'Pasal 9' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa pelaku '
            . 'secara melawan hukum memaksa penyintas menjalani tindakan medis atau prosedur '
            . 'tertentu yang mengakibatkan hilangnya fungsi reproduksi penyintas secara '
            . 'permanen (sterilisasi paksa). Tindakan ini dilakukan tanpa persetujuan penyintas '
            . 'dan/atau tanpa indikasi medis yang dapat dipertanggungjawabkan, sehingga '
            . 'melanggar hak reproduksi penyintas secara fundamental.',

        // ── H5 ─ Pemaksaan Perkawinan (Pasal 10) ────────────────────────────
        'Pasal 10' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa pelaku '
            . 'secara melawan hukum memaksa penyintas untuk melakukan perkawinan tanpa '
            . 'kehendak bebas penyintas. Pemaksaan perkawinan tersebut dapat berupa perkawinan '
            . 'anak, perkawinan yang mengatasnamakan praktik adat atau budaya tertentu, atau '
            . 'pemaksaan perkawinan dengan pelaku kekerasan seksual itu sendiri. Seluruh '
            . 'tindakan di atas dilakukan tanpa persetujuan yang bebas dari pihak penyintas.',

        // ── H6 ─ Penyiksaan Seksual (Pasal 11) ──────────────────────────────
        'Pasal 11' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa kekerasan '
            . 'seksual yang dialami dilakukan oleh pejabat negara, aparat, atau seseorang yang '
            . 'bertindak atas perintah, persetujuan, atau sepengetahuan pejabat yang berwenang. '
            . 'Tindakan tersebut dilakukan dengan tujuan mengintimidasi, menghukum, memperoleh '
            . 'pengakuan paksa, mempersekusi, atau merendahkan martabat penyintas atas dasar '
            . 'diskriminasi. Tindakan ini tergolong penyiksaan seksual sebagaimana dimaksud '
            . 'dalam Pasal 11 UU TPKS.',

        // ── H7 ─ Eksploitasi Seksual (Pasal 12) ─────────────────────────────
        'Pasal 12' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa pelaku '
            . 'secara melawan hukum memanfaatkan tubuh atau aktivitas seksual penyintas untuk '
            . 'memperoleh keuntungan ekonomi atau manfaat material tertentu. Pelaku diduga '
            . 'menggunakan jeratan hutang, iming-iming, atau bentuk tekanan lain guna '
            . 'mengeksploitasi penyintas secara seksual. Tindakan ini terjadi tanpa persetujuan '
            . 'yang bebas dari pihak penyintas dan tergolong eksploitasi seksual sebagaimana '
            . 'diatur dalam Pasal 12 UU TPKS.',

        // ── H8 ─ Perbudakan Seksual (Pasal 13) ──────────────────────────────
        'Pasal 13' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan bahwa pelaku '
            . 'secara melawan hukum menempatkan penyintas di bawah kendali atau kekuasaannya '
            . 'sehingga penyintas tidak berdaya untuk menolak atau melarikan diri. Penguasaan '
            . 'tersebut dilakukan murni dengan maksud mengeksploitasi penyintas secara seksual '
            . 'secara terus-menerus, memperlakukan penyintas layaknya properti atau barang. '
            . 'Tindakan ini tergolong perbudakan seksual sebagaimana diancam pidana dalam '
            . 'Pasal 13 UU TPKS.',

        // ── H9 ─ KSBE (Pasal 14) ─────────────────────────────────────────────
        'Pasal 14' => 'Melalui sistem penapisan RuangAman, penyintas mengonfirmasi bahwa pelaku '
            . 'secara diam-diam atau tanpa persetujuan merekam, memotret, mengambil video, atau '
            . 'mengambil tangkapan layar bermuatan seksual. Tindakan ini dilakukan pelaku untuk '
            . 'mengeksploitasi, mengintimidasi, memeras, atau memperoleh keuntungan atas situasi '
            . 'di mana penyintas berada di bawah kendali pelaku. Perbuatan tersebut tergolong '
            . 'Kekerasan Seksual Berbasis Elektronik (KSBE) sebagaimana diatur dalam Pasal 14 '
            . 'UU TPKS.',

        // ── H10 ─ TPKS Lainnya / Fallback (Pasal 4 Ayat (2)) ────────────────
        'Pasal 4 Ayat (2)' => 'Melalui sistem penapisan RuangAman, penyintas melaporkan adanya '
            . 'tindakan yang dilakukan tanpa persetujuan dan berdampak pada keselamatan serta '
            . 'kenyamanan penyintas secara seksual. Berdasarkan jawaban yang diberikan, tindakan '
            . 'yang dialami penyintas mengandung indikasi tindak pidana kekerasan seksual namun '
            . 'memerlukan pemeriksaan lebih lanjut oleh pihak yang berwenang untuk penetapan '
            . 'klasifikasi spesifik. Penanganan lebih lanjut dapat merujuk pada ketentuan Pasal 4 '
            . 'Ayat (2) UU TPKS yang mencakup tindak pidana kekerasan seksual lainnya seperti '
            . 'perkosaan, pencabulan, dan tindak pidana sejenis.',
    ];

    public function run(): void
    {
        foreach ($this->templates as $pasal => $template) {
            $updated = DB::table('hypotheses')
                ->where('pasal_uutpks', $pasal)
                ->update(['bap_template' => $template]);

            if ($updated === 0) {
                $this->command->warn("  ⚠ Tidak ada hipotesis dengan pasal_uutpks = '{$pasal}'. Lewati.");
            } else {
                $this->command->info("  ✓ bap_template diperbarui untuk: {$pasal} ({$updated} baris)");
            }
        }

        $this->command->info('');
        $this->command->info('HypothesisBapTemplateSeeder selesai.');
    }
}