<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // P1 — Premis Dasar (digunakan oleh hampir semua rule)
            [
                'text' => 'Apakah tindakan tersebut dilakukan tanpa persetujuan Anda, atau terjadi karena adanya ancaman, kekerasan, penyalahgunaan kekuasaan, tipu muslihat, atau pemanfaatan kondisi rentan yang Anda alami?',
                'help_text' => 'Persetujuan yang sah harus diberikan secara sukarela, tanpa tekanan, ancaman, atau manipulasi dalam bentuk apa pun. Kondisi rentan meliputi: ketergantungan ekonomi, hubungan kuasa tidak setara, atau kondisi fisik/psikis yang melemahkan.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P2 — Pelecehan Seksual Nonfisik
            [
                'text' => 'Apakah tindakan tersebut berupa perbuatan seksual secara nonfisik yang ditujukan kepada tubuh, organ reproduksi, atau seksualitas Anda?',
                'help_text' => 'Contoh: komentar bernuansa seksual, siulan, gestur cabul, mempertontonkan materi pornografi, atau percakapan bernuansa seksual yang tidak diinginkan.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P3 — Pelecehan Seksual Nonfisik (lanjutan)
            [
                'text' => 'Apakah tindakan nonfisik tersebut membuat Anda merasa direndahkan, dipermalukan, dilecehkan, atau terganggu berdasarkan seksualitas dan/atau kesusilaan?',
                'help_text' => 'Dampak yang dimaksud dapat berupa rasa malu, takut, tidak aman, terhina, atau tidak nyaman yang berkaitan dengan seksualitas.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P4 — Pelecehan Seksual Fisik
            [
                'text' => 'Apakah tindakan tersebut berupa kontak fisik bernuansa seksual terhadap tubuh atau organ reproduksi Anda?',
                'help_text' => 'Contoh: sentuhan, rabaan, atau kontak fisik lain yang memiliki unsur seksual dan ditujukan pada bagian tubuh Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P5 — Pelecehan Seksual Fisik (lanjutan)
            [
                'text' => 'Apakah kontak fisik tersebut dilakukan dengan tujuan merendahkan martabat Anda, menempatkan Anda di bawah kekuasaan pelaku, atau memaksa terjadinya hubungan seksual maupun perbuatan cabul?',
                'help_text' => 'Termasuk situasi di mana Anda merasa tidak berdaya, dikendalikan, atau terpaksa menerima perlakuan tersebut.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P6 — Pemaksaan Kontrasepsi
            [
                'text' => 'Apakah seseorang memaksa Anda menggunakan alat atau metode kontrasepsi yang menyebabkan hilangnya fungsi reproduksi untuk sementara waktu?',
                'help_text' => 'Contoh: dipaksa minum pil KB, dipasangkan IUD/implan, atau disuntik kontrasepsi tanpa persetujuan Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P7 — Pemaksaan Sterilisasi
            [
                'text' => 'Apakah seseorang memaksa Anda menjalani tindakan yang menyebabkan hilangnya fungsi reproduksi secara permanen?',
                'help_text' => 'Contoh: dipaksa menjalani operasi vasektomi atau tubektomi tanpa persetujuan Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P8 — Pemaksaan Perkawinan
            [
                'text' => 'Apakah Anda dipaksa melakukan perkawinan, termasuk perkawinan anak, perkawinan atas nama adat/budaya tertentu, atau dipaksa menikah dengan pelaku kekerasan seksual?',
                'help_text' => 'Termasuk pernikahan yang terjadi karena tekanan keluarga, tradisi, atau sebagai "solusi" atas kekerasan seksual yang terjadi.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P9 — Penyiksaan Seksual
            [
                'text' => 'Apakah pelaku merupakan pejabat, aparat, atau seseorang yang bertindak atas perintah, persetujuan, atau sepengetahuan pejabat?',
                'help_text' => 'Contoh: aparat keamanan, petugas penegak hukum, pejabat pemerintah, atau orang yang diberi wewenang oleh pejabat.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P10 — Penyiksaan Seksual (lanjutan)
            [
                'text' => 'Apakah kekerasan seksual tersebut dilakukan untuk menghukum, mengintimidasi, memperoleh pengakuan, mempersekusi, atau merendahkan martabat Anda?',
                'help_text' => 'Tujuan penyiksaan seksual biasanya terkait dengan kekuasaan, kontrol, atau pemaksaan kehendak oleh aparat.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P11 — Eksploitasi Seksual
            [
                'text' => 'Apakah pelaku memperoleh keuntungan ekonomi atau manfaat tertentu dari pemanfaatan tubuh atau aktivitas seksual Anda?',
                'help_text' => 'Contoh: pelaku mendapatkan uang, fasilitas, atau keuntungan lain dengan menjual atau memanfaatkan aktivitas seksual Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P12 — Perbudakan Seksual
            [
                'text' => 'Apakah pelaku menempatkan Anda di bawah kendali atau kekuasaannya sehingga Anda sulit menolak atau melarikan diri?',
                'help_text' => 'Contoh: dikurung, diawasi terus-menerus, diancam, dokumen identitas ditahan, atau diisolasi dari keluarga dan teman.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P13 — Perbudakan Seksual (lanjutan)
            [
                'text' => 'Apakah penguasaan tersebut dilakukan dengan tujuan mengeksploitasi Anda secara seksual secara terus-menerus?',
                'help_text' => 'Perbudakan seksual melibatkan kontrol berkelanjutan atas seseorang untuk tujuan eksploitasi seksual berulang.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P14 — KSBE: Perekaman
            [
                'text' => 'Apakah seseorang merekam, memotret, mengambil video, atau mengambil tangkapan layar bermuatan seksual tanpa persetujuan Anda?',
                'help_text' => 'Termasuk pembuatan konten intim yang dilakukan secara diam-diam, dipaksa, atau ditipu.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P15 — KSBE: Penyebaran
            [
                'text' => 'Apakah seseorang menyebarkan, mengirimkan, atau membagikan konten elektronik bermuatan seksual tanpa persetujuan Anda?',
                'help_text' => 'Contoh: menyebarkan foto/video intim melalui media sosial, aplikasi pesan, atau platform online lainnya tanpa izin Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P16 — KSBE: Penguntitan
            [
                'text' => 'Apakah seseorang melakukan penguntitan, pemantauan, atau pelacakan menggunakan media elektronik dengan tujuan seksual?',
                'help_text' => 'Contoh: stalking melalui GPS, media sosial, atau aplikasi mata-mata untuk mengawasi atau mengendalikan Anda.',
                'answer_type' => 'YA_TIDAK',
            ],

            // P17 — KSBE: Pemberatan (ancaman/pemerasan)
            [
                'text' => 'Apakah tindakan elektronik tersebut digunakan untuk mengancam, memeras, memaksa, memperdaya, atau mengendalikan Anda?',
                'help_text' => 'Contoh: mengancam akan menyebarkan foto intim kecuali Anda memenuhi permintaan tertentu (sextortion).',
                'answer_type' => 'YA_TIDAK',
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
