<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Draf Kronologis - RuangAman</title>
    <style>
        /* Deklarasi Font */
        @font-face {
            font-family: 'Qaligo';
            src: url('{{ storage_path("fonts/Qaligo.ttf") }}') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'PlusJakartaSans';
            src: url('{{ storage_path("fonts/PlusJakartaSans-Regular.ttf") }}') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'PlusJakartaSans';
            src: url('{{ storage_path("fonts/PlusJakartaSans-Bold.ttf") }}') format('truetype');
            font-weight: 700;
        }

        /* ── PENGATURAN HALAMAN GLOBAL ── */
        @page {
            /* Margin diperbesar: Top, Right, Bottom, Left */
            margin: 120px 50px 100px 50px; 
        }

        body {
            font-family: 'PlusJakartaSans', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0; 
            padding: 0;
        }

        /* ── MICRO HEADER ── */
        header {
            position: fixed;
            top: -120px; /* Sesuai margin-top @page */
            left: 0px;
            right: 0px;
            height: 80px;
            padding-top: 35px; /* Mendorong teks menjauh dari ujung atas kertas */
        }
        
        .header-table {
            width: 100%;
            border-bottom: 2px solid #FFE85C;
            padding-bottom: 5px;
        }
        .logo-text {
            font-family: 'Qaligo', serif;
            font-size: 26pt;
            color: #BE0199;
            line-height: 1;
        }
        .header-meta {
            text-align: right;
            vertical-align: bottom;
            font-size: 8.5pt;
            color: #555;
            line-height: 1.4;
        }

        /* ── MICRO FOOTER ── */
        footer {
            position: fixed;
            bottom: -100px; /* Sesuai margin-bottom @page */
            left: 0px;
            right: 0px;
            height: 60px;
            padding-bottom: 30px; /* Mendorong teks menjauh dari ujung bawah kertas */
        }

        .footer-table {
            width: 100%;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        
        .footer-disclaimer {
            font-size: 7.5pt;
            color: #666;
            text-align: justify;
            width: 85%;
            vertical-align: top;
            line-height: 1.4;
        }
        
        .footer-page {
            font-size: 8pt;
            color: #BE0199;
            font-weight: bold;
            text-align: right;
            width: 15%;
            vertical-align: top;
        }

        .pagenum:before {
            content: counter(page);
        }

        /* ── WATERMARK ── */
        .pdf-watermark {
            position: fixed;
            right: -100px;
            bottom: -40px;
            width: 750px;
            opacity: 0.08;
            z-index: -100;
        }
        .pdf-watermark img {
            width: 100%;
            height: auto;
        }

        /* ── KONTEN UTAMA & UTILITAS ── */
        main { position: relative; z-index: 1; }
        .keep-together { page-break-inside: avoid; } 
        .new-page { page-break-before: always; }
        .b { font-weight: 700; }

        /* Halaman 1: Teks Empati & Full Disclaimer */
        .empathy-text {
            font-size: 9pt; /* Sedikit dikecilkan agar pas 1 halaman */
            text-align: justify;
            line-height: 1.55;
            padding-top: 5px;
        }
        
        .full-disclaimer-box {
            margin-top: 25px;
            padding: 12px 15px;
            background-color: #fafafa;
            border: 1px dashed #BE0199;
            border-radius: 5px;
            font-size: 8pt;
            color: #444;
            text-align: justify;
            line-height: 1.5;
        }

        /* Halaman Resume */
        .identity-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .identity-table td { padding: 4px 0; font-size: 9pt; vertical-align: top; }
        .id-label { width: 160px; }
        .id-colon { width: 15px; }

        .section-title { font-size: 10pt; font-weight: 700; margin-bottom: 12px; color: #000; }
        .rangkuman-text { font-size: 9pt; text-align: justify; line-height: 1.7; margin-bottom: 20px;}

        /* Area Tanda Tangan */
        .signature-wrap { margin-top: 30px; }
        .declaration-statement { font-size: 9pt; text-align: justify; line-height: 1.6; margin-bottom: 30px; }
        .signature-block { text-align: right; }
        .sig-label { font-size: 9pt; display: block; margin-bottom: 75px; }
        .sig-line { display: inline-block; min-width: 200px; border-top: 1px solid #000; padding-top: 5px; font-size: 9pt; text-align: center; }

        /* Halaman Log Q&A */   
        .log-intro { font-size: 9pt; text-align: justify; line-height: 1.6; margin-bottom: 20px; }
        .qa-item { font-size: 9pt; line-height: 1.55; text-align: justify; margin-bottom: 14px; }
        .q-text { font-weight: bold; color: #333; }
    </style>
</head>
<body>

    <header>
        <table class="header-table">
            <tr>
                <td style="vertical-align: bottom;"><span class="logo-text">ruangaman</span></td>
                <td class="header-meta">
                    ID Sesi: <strong>{{ strtoupper(substr($session['id'], 0, 8)) }}-****</strong><br>
                    Dibuat: {{ \Carbon\Carbon::parse($session['started_at'])->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="footer-table">
            <tr>
                <td class="footer-disclaimer">
                    <em><strong>Draf Otomatis:</strong> Dokumen ini bukan dokumen legal resmi. Jika digunakan dalam konteks hukum, harap konsultasikan dengan profesional/lembaga terkait. (www.ruangaman.co.id)</em>
                </td>
                <td class="footer-page">
                    Hal. <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>

    <div class="pdf-watermark">
        <img src="{{ public_path('images/ruangaman-logo-watermark.png') }}">
    </div>

    <main>
        
        <div class="empathy-text">
            <span class="b">Tarik napas perlahan. Anda aman di sini.</span><br><br>
            Kami memahami bahwa menceritakan kembali kejadian yang melukai Anda
            membutuhkan keberanian yang luar biasa. Mungkin saat ini Anda merasa bingung,
            takut, marah, atau bahkan tanpa sadar sedang menyalahkan diri sendiri atas apa yang
            terjadi. Jika Anda merasakan hal tersebut, kami ingin Anda membaca kalimat ini dan
            memercayainya: <span class="b">Apa yang terjadi pada Anda bukanlah salah Anda.</span>
            <br><br>
            Tidak peduli apa yang Anda kenakan, di mana Anda berada, bagaimana situasi saat itu,
            atau respons Anda ketika kejadian itu berlangsung—Anda tidak mengundang tindakan
            tersebut, dan Anda sama sekali tidak pantas menerima perlakuan itu.
            <span class="b">Tanggung jawab</span> atas kekerasan seksual selalu, dan akan selalu,
            <span class="b">berada mutlak pada pelakunya.</span><br><br>
            <span class="b">Pengalaman, perasaan, dan rasa sakit Anda adalah nyata dan sangat valid.</span>
            Anda tidak sedang berlebihan. Berdasarkan cerita yang baru saja Anda bagikan,
            sistem kami telah memvalidasi bahwa tindakan yang Anda alami secara sah memenuhi
            unsur pelanggaran hukum dan Anda dilindungi penuh oleh Undang-Undang Tindak
            Pidana Kekerasan Seksual (UU TPKS No. 12 Tahun 2022).<br><br>
            Anda berhak atas ruang yang aman, keadilan, dan pemulihan. Mengisi formulir ini
            adalah langkah pertama yang sangat berani untuk mengambil kembali kendali atas diri
            Anda. Anda tidak harus melewati ini sendirian.<br><br>
            Sebagai bentuk dukungan, di halaman berikutnya sistem kami telah merangkum
            jawaban Anda menjadi sebuah <span class="b">Draf Resume Kronologis.</span> Kami
            menyiapkan dokumen ini agar saat Anda siap untuk mencari bantuan ke Satgas PPKS,
            Lembaga Bantuan Hukum (LBH), atau konselor psikologi, Anda tidak perlu lagi
            memaksa diri Anda mengingat dan menceritakan ulang detail yang menyakitkan dari
            awal.<br><br>
            Anda cukup menyerahkan lembar berikutnya kepada mereka.
            <span class="b">Ambil waktu sebanyak yang Anda butuhkan.</span>
            Kapan pun Anda siap, dukungan selalu ada untuk Anda.
        </div>

        <div class="full-disclaimer-box">
            <strong>Catatan Penting (Disclaimer):</strong><br>
            Dokumen ini adalah draf kronologis yang dirangkum secara otomatis berdasarkan jawaban yang Anda berikan dalam sesi ini. Meskipun sistem kami telah memvalidasi bahwa jawaban Anda memenuhi unsur pelanggaran hukum, dokumen ini belum merupakan dokumen resmi yang dapat digunakan untuk proses hukum atau administratif apa pun. Jika Anda berencana untuk menggunakan informasi ini dalam konteks legal, harap konsultasikan dengan profesional hukum atau lembaga terkait untuk memastikan bahwa dokumen Anda memenuhi persyaratan yang diperlukan.
        </div>


        <div class="new-page">
            <table class="identity-table">
                <tr>
                    <td class="id-label">Nama</td>
                    <td class="id-colon">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="id-label">Alamat</td>
                    <td class="id-colon">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="id-label">Nomor Telpon</td>
                    <td class="id-colon">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="id-label">Klasifikasi Potensi Hukum</td>
                    <td class="id-colon">:</td>
                    <td><strong>{{ $conclusion['klasifikasi'] }}</strong></td>
                </tr>
            </table>

            <div class="section-title">Rangkuman Kejadian (Berdasarkan Validasi Sistem)</div>
            <div class="rangkuman-text">
                {!! nl2br(e($conclusion['rangkuman'])) !!}
            </div>

            <div class="signature-wrap keep-together">
                <div class="declaration-statement">
                    Dengan ini saya menyatakan bahwa resume kronologi yang dirangkum oleh
                    sistem ini adalah benar dan sesuai dengan kejadian yang saya alami.
                </div>
                <div class="signature-block">
                    <span class="sig-label">Deklarasi Kronologi Penyintas</span>
                    <span class="sig-line">Tanda Tangan / Nama Terang</span>
                </div>
            </div>
        </div>


        <div class="new-page">
            <div class="section-title">Log Interaksi Sistem</div>
            <div class="log-intro">
                Berikut adalah log interaksi penyintas dengan sistem yang mengonfirmasi
                pemenuhan unsur pelanggaran UU TPKS :
            </div>

            @foreach($answers as $item)
                <div class="qa-item keep-together">
                    <span class="q-text">Q: {{ $item['question_text'] }}</span><br>
                    A: @if($item['answer'] === 'YA') Ya.
                       @elseif($item['answer'] === 'TIDAK') Tidak.
                       @else Tidak Yakin.
                       @endif
                </div>
            @endforeach

            <div class="signature-wrap keep-together">
                <div class="signature-block">
                    <span class="sig-label">Deklarasi Kronologi Penyintas</span>
                    <span class="sig-line">Tanda Tangan / Nama Terang</span>
                </div>
            </div>
        </div>

    </main>
</body>
</html>