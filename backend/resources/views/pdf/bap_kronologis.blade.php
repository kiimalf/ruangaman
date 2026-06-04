<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Draf Kronologis - RuangAman</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1A5C7A;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1A5C7A;
            margin: 0;
            letter-spacing: 1px;
        }
        .sub-logo {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .meta-info {
            text-align: right;
            font-size: 12px;
            color: #777;
            margin-bottom: 30px;
        }
        .section-title {
            color: #1A5C7A;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .result-box {
            background-color: #f8f9fa;
            border-left: 4px solid #1A5C7A;
            padding: 15px;
            margin-bottom: 20px;
        }
        .result-title {
            font-weight: bold;
            color: #c0392b;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .result-title.safe {
            color: #27ae60;
        }
        .hypothesis-item {
            margin-bottom: 15px;
        }
        .hypothesis-label {
            font-weight: bold;
            font-size: 15px;
            color: #1A5C7A;
        }
        .hypothesis-pasal {
            font-size: 13px;
            color: #2E8B7A;
            font-weight: bold;
            margin: 3px 0;
        }
        .hypothesis-desc {
            font-size: 13px;
            color: #555;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }
        .answer-ya {
            color: #c0392b;
            font-weight: bold;
        }
        .answer-tidak {
            color: #27ae60;
            font-weight: bold;
        }
        .answer-ragu {
            color: #f39c12;
            font-weight: bold;
        }
        .recommendation {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .recommendation p {
            margin: 0;
            color: #0b3b54;
        }
        .footer {
            margin-top: 50px;
            font-size: 11px;
            color: #777;
            text-align: justify;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo">RuangAman</h1>
        <p class="sub-logo">Platform Identifikasi Dini Kekerasan Seksual Berdasarkan UU TPKS</p>
    </div>

    <div class="meta-info">
        ID Sesi: {{ substr($session['id'], 0, 8) }}-****<br>
        Tanggal Dibuat: {{ \Carbon\Carbon::parse($session['started_at'])->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    </div>

    <div class="result-box">
        <h2 class="result-title {{ $conclusion['type'] === 'BUKAN_TPKS' ? 'safe' : '' }}">
            @if(in_array($conclusion['type'], ['TERPENUHI', 'FALLBACK']))
                INDIKASI KEKERASAN SEKSUAL DITEMUKAN
            @else
                BELUM MEMENUHI UNSUR SPESIFIK TPKS
            @endif
        </h2>
        <p>{{ $conclusion['message'] }}</p>

        @if(!empty($conclusion['hypotheses']))
            <div style="margin-top: 15px;">
                <strong>Klasifikasi Berdasarkan UU TPKS (No. 12 Tahun 2022):</strong>
                @foreach($conclusion['hypotheses'] as $hyp)
                    <div class="hypothesis-item" style="margin-top: 10px; padding-left: 10px; border-left: 2px solid #ccc;">
                        <div class="hypothesis-label">{{ $hyp['label'] }}</div>
                        <div class="hypothesis-pasal">{{ $hyp['pasal'] }}</div>
                        <div class="hypothesis-desc">{{ $hyp['description'] }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <h3 class="section-title">Riwayat Jawaban (Kronologi)</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Daftar di bawah ini merupakan ringkasan jawaban Anda selama sesi berlangsung. Informasi ini dapat digunakan sebagai referensi awal untuk menceritakan kronologi kejadian.</p>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 75%;">Pertanyaan / Unsur Kejadian</th>
                <th style="width: 20%;">Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @foreach($answers as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['question_text'] }}</td>
                    <td>
                        @if($item['answer'] === 'YA')
                            <span class="answer-ya">Ya</span>
                        @elseif($item['answer'] === 'TIDAK')
                            <span class="answer-tidak">Tidak</span>
                        @else
                            <span class="answer-ragu">Tidak Yakin</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="section-title">Rekomendasi Langkah Selanjutnya</h3>
    <div class="recommendation">
        <p>{{ $conclusion['recommendation'] }}</p>
    </div>

    <div class="footer">
        <strong>PENTING (DISCLAIMER HUKUM):</strong> Dokumen ini dihasilkan secara otomatis oleh sistem pakar RuangAman dan bersifat anonim. Dokumen ini BUKAN merupakan alat bukti hukum formal (seperti Berita Acara Pemeriksaan dari kepolisian) dan TIDAK dapat menggantikan proses penyelidikan resmi oleh aparat penegak hukum. Hasil identifikasi ini hanya bertujuan untuk memberikan edukasi dan rujukan awal bagi penyintas untuk menentukan langkah perlindungan atau pemulihan, serta memudahkan pelaporan ke Satgas PPKS atau Lembaga Bantuan Hukum (LBH).
    </div>

</body>
</html>
