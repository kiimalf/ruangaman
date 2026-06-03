<?php

namespace Database\Seeders;

use App\Models\Hypothesis;
use Illuminate\Database\Seeder;

class HypothesisSeeder extends Seeder
{
    public function run(): void
    {
        $hypotheses = [
            [
                'label' => 'Pelecehan Seksual Nonfisik',
                'pasal_uutpks' => 'Pasal 5',
                'description' => 'Setiap orang yang melakukan perbuatan seksual secara nonfisik yang ditujukan terhadap tubuh, keinginan seksual, dan/atau organ reproduksi dengan maksud merendahkan harkat dan martabat seseorang berdasarkan seksualitas dan/atau kesusilaannya.',
            ],
            [
                'label' => 'Pelecehan Seksual Fisik',
                'pasal_uutpks' => 'Pasal 6',
                'description' => 'Setiap orang yang melakukan perbuatan seksual secara fisik yang ditujukan terhadap tubuh, keinginan seksual, dan/atau organ reproduksi dengan maksud merendahkan harkat dan martabat seseorang berdasarkan seksualitas dan/atau kesusilaannya, yang tidak termasuk dalam tindak pidana perkosaan.',
            ],
            [
                'label' => 'Pemaksaan Kontrasepsi',
                'pasal_uutpks' => 'Pasal 8',
                'description' => 'Setiap orang yang menyalahgunakan kekuasaan, penyalahgunaan kepercayaan, atau penyalahgunaan situasi, memaksa atau menipu seseorang untuk menggunakan alat kontrasepsi dengan kekerasan atau ancaman kekerasan, menyebabkan hilangnya fungsi reproduksi untuk sementara waktu.',
            ],
            [
                'label' => 'Pemaksaan Sterilisasi',
                'pasal_uutpks' => 'Pasal 9',
                'description' => 'Setiap orang yang menyalahgunakan kekuasaan, penyalahgunaan kepercayaan, atau penyalahgunaan situasi, memaksa seseorang untuk menjalani sterilisasi dan/atau tindakan medis lainnya yang mengakibatkan hilangnya fungsi reproduksi secara permanen.',
            ],
            [
                'label' => 'Pemaksaan Perkawinan',
                'pasal_uutpks' => 'Pasal 10',
                'description' => 'Setiap orang yang memaksa, menempatkan seseorang di bawah kekuasaannya atau orang lain, atau menyalahgunakan kekuasaannya untuk melakukan atau membiarkan dilakukannya perkawinan, termasuk perkawinan anak.',
            ],
            [
                'label' => 'Penyiksaan Seksual',
                'pasal_uutpks' => 'Pasal 11',
                'description' => 'Setiap pejabat atau orang yang bertindak dalam kapasitas pejabat resmi yang melakukan kekerasan seksual untuk tujuan intimidasi, mendapatkan pengakuan, penghukuman, atau diskriminasi.',
            ],
            [
                'label' => 'Eksploitasi Seksual',
                'pasal_uutpks' => 'Pasal 12',
                'description' => 'Setiap orang yang menyalahgunakan kekuasaan, penyalahgunaan kepercayaan, atau penyalahgunaan situasi rentan untuk mendapatkan keuntungan dari praktik eksploitasi seksual.',
            ],
            [
                'label' => 'Perbudakan Seksual',
                'pasal_uutpks' => 'Pasal 13',
                'description' => 'Setiap orang yang menempatkan seseorang di bawah kekuasaan atau kendalinya sehingga orang tersebut tidak dapat menolak suatu perbuatan seksual, yang dilakukan secara berulang dan/atau terus-menerus.',
            ],
            [
                'label' => 'Kekerasan Seksual Berbasis Elektronik (KSBE)',
                'pasal_uutpks' => 'Pasal 14',
                'description' => 'Setiap orang yang tanpa hak merekam dan/atau mengambil gambar atau tangkapan layar yang bermuatan seksual, mentransmisikan informasi elektronik bermuatan seksual, melakukan penguntitan dan/atau pelacakan menggunakan sistem elektronik terhadap orang yang menjadi obyek dalam informasi/dokumen elektronik untuk tujuan seksual.',
            ],
            [
                'label' => 'Tindak Pidana Seksual Lainnya',
                'pasal_uutpks' => 'Pasal 4 Ayat (2)',
                'description' => 'Tindak pidana kekerasan seksual lainnya sebagaimana ditetapkan dalam undang-undang sepanjang ditentukan dalam Undang-Undang ini. Merupakan klasifikasi fallback apabila tidak ada hipotesis spesifik (H1-H9) yang terpenuhi namun terdapat indikasi kekerasan seksual.',
            ],
        ];

        foreach ($hypotheses as $hypothesis) {
            Hypothesis::create($hypothesis);
        }
    }
}
