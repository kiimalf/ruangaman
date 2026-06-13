<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Draf Kronologis - RuangAman</title>
    <style>
        /* ============================================================
           FONT DECLARATIONS
           - Ganti src url() dengan path font aktual di storage Laravel
           - Contoh: storage_path('fonts/Qaligo.ttf')
           ============================================================ */
        @font-face {
            font-family: 'Qaligo';
            src: url('{{ storage_path("fonts/Qaligo.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'PlusJakartaSans';
            src: url('{{ storage_path("fonts/PlusJakartaSans-Regular.ttf") }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'PlusJakartaSans';
            src: url('{{ storage_path("fonts/PlusJakartaSans-Bold.ttf") }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        /* ============================================================
           RESET & BASE
           ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'PlusJakartaSans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #000000;
            background: #ffffff;
            line-height: 1.6;
        }

        /* ============================================================
           PAGE LAYOUT
           DomPDF page = 595px wide (A4), margin 32px kiri-kanan
           ============================================================ */
        .page {
            width: 595px;
            min-height: 842px;
            position: relative;
            background: #ffffff;
            padding: 0;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* ============================================================
           WATERMARK
           Logo ruangaman transparan di tengah halaman
           Ganti src dengan path asset aktual
           ============================================================ */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            margin-top: -150px;
            margin-left: -150px;
            opacity: 0.08;
            z-index: 0;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ============================================================
           HEADER — sama di semua halaman
           ============================================================ */
        .page-header {
            position: relative;
            z-index: 1;
            padding: 32px 32px 0 32px;
        }

        .logo-text {
            font-family: 'Qaligo', cursive;
            font-size: 16px;
            font-weight: 400;
            color: #BE0199;
            display: inline-block;
        }

        /* Garis kuning horizontal di bawah logo */
        .header-line {
            width: 100%;
            border: none;
            border-top: 4px solid #FFE85C;
            margin-top: 8px;
            margin-bottom: 0;
        }

        /* ============================================================
           META INFO — ID Sesi & Tanggal, rata kanan
           ============================================================ */
        .meta-info {
            position: relative;
            z-index: 1;
            text-align: right;
            padding: 16px 32px 0 32px;
            font-size: 12px;
            color: #000000;
            line-height: 1.5;
        }

        /* ============================================================
           FOOTER — www.ruangaman.co.id, posisi bawah
           ============================================================ */
        .page-footer {
            position: absolute;
            bottom: 20px;
            left: 32px;
            z-index: 1;
            font-size: 12px;
            color: #BE0199;
        }

        /* ============================================================
           CONTENT AREA — konten utama tiap halaman
           ============================================================ */
        .content {
            position: relative;
            z-index: 1;
            padding: 0 32px;
            margin-top: 24px;
        }

        /* ============================================================
           HALAMAN 1 — Pesan Empati
           ============================================================ */
        .empathy-text {
            text-align: justify;
            font-size: 12px;
            color: #000000;
            line-height: 1.7;
        }

        .empathy-text .bold {
            font-weight: 700;
        }

        /* ============================================================
           HALAMAN 2 — Resume Kronologis
           ============================================================ */
        .identity-section {
            font-size: 12px;
            color: #000000;
            line-height: 2;
            margin-bottom: 16px;
        }

        .identity-row {
            display: block;
            margin-bottom: 4px;
        }

        .identity-label {
            display: inline-block;
            width: 140px;
            vertical-align: top;
        }

        .identity-colon {
            display: inline-block;
            width: 12px;
            vertical-align: top;
        }

        .identity-value {
            display: inline-block;
            vertical-align: top;
        }

        .section-subtitle {
            font-size: 12px;
            font-weight: 700;
            color: #000000;
            margin-top: 12px;
            margin-bottom: 6px;
        }

        .rangkuman-text {
            font-size: 12px;
            color: #000000;
            text-align: justify;
            line-height: 1.7;
        }

        /* Deklarasi & tanda tangan */
        .declaration-section {
            margin-top: 40px;
            font-size: 12px;
            color: #000000;
        }

        .declaration-statement {
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 60px; /* ruang tanda tangan */
        }

        .signature-block {
            text-align: right;
            padding-right: 16px;
        }

        .signature-label {
            font-size: 12px;
            color: #000000;
            margin-bottom: 40px; /* ruang tanda tangan fisik */
        }

        .signature-name {
            font-size: 12px;
            color: #000000;
            border-top: 1px solid #000000;
            display: inline-block;
            padding-top: 4px;
            min-width: 140px;
        }

        /* ============================================================
           HALAMAN 3 — Log Q&A
           ============================================================ */
        .log-intro {
            font-size: 12px;
            color: #000000;
            text-align: justify;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .qa-item {
            margin-bottom: 14px;
            font-size: 12px;
            color: #000000;
            line-height: 1.6;
            text-align: justify;
        }

        .qa-question {
            font-weight: 400;
        }

        .qa-answer {
            font-weight: 400;
        }

        /* ============================================================
           DISCLAIMER — footer legal, semua halaman kecuali halaman 1
           ============================================================ */
        .disclaimer {
            position: absolute;
            bottom: 40px;
            left: 32px;
            right: 32px;
            z-index: 1;
            font-size: 10px;
            color: #626262;
            text-align: justify;
            line-height: 1.5;
        }

        .disclaimer strong {
            font-weight: 700;
            color: #626262;
        }
    </style>
</head>
<body>

{{-- ================================================================
     HALAMAN 1 — Pesan Empati & Pengantar
     ================================================================ --}}
<div class="page">

    {{-- Watermark --}}
    <div class="watermark">
        {{-- Ganti src dengan path asset logo RuangAman --}}
        <img src="{{ public_path('images/ruangaman-logo-watermark.png') }}" alt="">
    </div>

    {{-- Header --}}
    <div class="page-header">
        <span class="logo-text">ruangaman</span>
        <hr class="header-line">
    </div>

    {{-- Meta Info --}}
    <div class="meta-info">
        ID Sesi: {{ substr($session['id'], 0, 8) }}-****<br>
        Tanggal Dibuat: {{ \Carbon\Carbon::parse($session['started_at'])->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    </div>

    {{-- Konten Empati --}}
    <div class="content">
        <div class="empathy-text">
            <span class="bold">Tarik napas perlahan. Anda aman di sini.</span>
            <br><br>
            Kami memahami bahwa menceritakan kembali kejadian yang melukai Anda membutuhkan keberanian yang luar biasa. Mungkin saat ini Anda merasa bingung, takut, marah, atau bahkan tanpa sadar sedang menyalahkan diri sendiri atas apa yang terjadi. Jika Anda merasakan hal tersebut, kami ingin Anda membaca kalimat ini dan memercayainya: <span class="bold">Apa yang terjadi pada Anda bukanlah salah Anda.</span>
            <br><br>
            Tidak peduli apa yang Anda kenakan, di mana Anda berada, bagaimana situasi saat itu, atau respons Anda ketika kejadian itu berlangsung—Anda tidak mengundang tindakan tersebut, dan Anda sama sekali tidak pantas menerima perlakuan itu. <span class="bold">Tanggung jawab</span> atas kekerasan seksual selalu, dan akan selalu, <span class="bold">berada mutlak pada pelakunya.</span>
            <br><br>
            <span class="bold">Pengalaman, perasaan, dan rasa sakit Anda adalah nyata dan sangat valid.</span> Anda tidak sedang berlebihan. Berdasarkan cerita yang baru saja Anda bagikan, sistem kami telah memvalidasi bahwa tindakan yang Anda alami secara sah memenuhi unsur pelanggaran hukum dan Anda dilindungi penuh oleh Undang-Undang Tindak Pidana Kekerasan Seksual (UU TPKS No. 12 Tahun 2022).
            <br><br>
            Anda berhak atas ruang yang aman, keadilan, dan pemulihan. Mengisi formulir ini adalah langkah pertama yang sangat berani untuk mengambil kembali kendali atas diri Anda. Anda tidak harus melewati ini sendirian.
            <br><br>
            Sebagai bentuk dukungan, di halaman berikutnya sistem kami telah merangkum jawaban Anda menjadi sebuah <span class="bold">Draf Resume Kronologis.</span> Kami menyiapkan dokumen ini agar saat Anda siap untuk mencari bantuan ke Satgas PPKS, Lembaga Bantuan Hukum (LBH), atau konselor psikologi, Anda tidak perlu lagi memaksa diri Anda mengingat dan menceritakan ulang detail yang menyakitkan dari awal.
            <br><br>
            Anda cukup menyerahkan lembar berikutnya kepada mereka. <span class="bold">Ambil waktu sebanyak yang Anda butuhkan.</span> Kapan pun Anda siap, dukungan selalu ada untuk Anda.
        </div>
    </div>

    {{-- Footer --}}
    <div class="page-footer">www.ruangaman.co.id</div>

</div>


{{-- ================================================================
     HALAMAN 2 — Resume Kronologis (Data Diri + Rangkuman)
     ================================================================ --}}
<div class="page">

    {{-- Watermark --}}
    <div class="watermark">
        <img src="{{ public_path('images/ruangaman-logo-watermark.png') }}" alt="">
    </div>

    {{-- Header --}}
    <div class="page-header">
        <span class="logo-text">ruangaman</span>
        <hr class="header-line">
    </div>

    {{-- Meta Info --}}
    <div class="meta-info">
        ID Sesi: {{ substr($session['id'], 0, 8) }}-****<br>
        Tanggal Dibuat: {{ \Carbon\Carbon::parse($session['started_at'])->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    </div>

    {{-- Konten Resume --}}
    <div class="content">

        {{-- Identitas — Nama dikosongkan sesuai ketentuan anonimitas --}}
        <div class="identity-section">
            <div class="identity-row">
                <span class="identity-label">Nama</span>
                <span class="identity-colon">:</span>
                <span class="identity-value">&nbsp;</span>
            </div>
            <div class="identity-row">
                <span class="identity-label">Alamat</span>
                <span class="identity-colon">:</span>
                <span class="identity-value">&nbsp;</span>
            </div>
            <div class="identity-row">
                <span class="identity-label">Nomor Telpon</span>
                <span class="identity-colon">:</span>
                <span class="identity-value">&nbsp;</span>
            </div>
            <div class="identity-row">
                <span class="identity-label">Klasifikasi Potensi Hukum</span>
                <span class="identity-colon">:</span>
                {{-- Klasifikasi pasal adaptif dari hipotesis yang terbukti --}}
                <span class="identity-value">{{ $conclusion['klasifikasi'] }}</span>
            </div>
        </div>

        {{-- Rangkuman Kejadian — narasi adaptif dari bap_template hipotesis --}}
        <div class="section-subtitle">Rangkuman Kejadian (Berdasarkan Validasi Sistem)</div>
        <div class="rangkuman-text">
            {{-- $conclusion['rangkuman'] berisi gabungan bap_template dari semua hipotesis yang proven --}}
            {!! nl2br(e($conclusion['rangkuman'])) !!}
        </div>

        {{-- Deklarasi Kronologi Penyintas --}}
        <div class="declaration-section">
            <div class="declaration-statement">
                Dengan ini saya menyatakan bahwa resume kronologi yang dirangkum oleh sistem ini adalah benar dan sesuai dengan kejadian yang saya alami.
            </div>
            <div class="signature-block">
                <div class="signature-label">Deklarasi Kronologi Penyintas</div>
                <br><br><br>
                <div class="signature-name">&nbsp;</div>
            </div>
        </div>

    </div>

    {{-- Disclaimer --}}
    <div class="disclaimer">
        <strong>PENTING (DISCLAIMER HUKUM):</strong> Dokumen ini dihasilkan secara otomatis oleh sistem pakar RuangAman dan bersifat anonim. Dokumen ini BUKAN merupakan alat bukti hukum formal (seperti Berita Acara Pemeriksaan dari kepolisian) dan TIDAK dapat menggantikan proses penyelidikan resmi oleh aparat penegak hukum. Hasil identifikasi ini hanya bertujuan untuk memberikan edukasi dan rujukan awal bagi penyintas untuk menentukan langkah perlindungan atau pemulihan, serta memudahkan pelaporan ke Satgas PPKS atau Lembaga Bantuan Hukum (LBH).
    </div>

    {{-- Footer --}}
    <div class="page-footer">www.ruangaman.co.id</div>

</div>


{{-- ================================================================
     HALAMAN 3 — Log Interaksi Q&A (Adaptif per Sesi)
     ================================================================ --}}
<div class="page">

    {{-- Watermark --}}
    <div class="watermark">
        <img src="{{ public_path('images/ruangaman-logo-watermark.png') }}" alt="">
    </div>

    {{-- Header --}}
    <div class="page-header">
        <span class="logo-text">ruangaman</span>
        <hr class="header-line">
    </div>

    {{-- Meta Info --}}
    <div class="meta-info">
        ID Sesi: {{ substr($session['id'], 0, 8) }}-****<br>
        Tanggal Dibuat: {{ \Carbon\Carbon::parse($session['started_at'])->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    </div>

    {{-- Log Q&A --}}
    <div class="content">

        <div class="log-intro">
            Berikut adalah log interaksi penyintas dengan sistem yang mengonfirmasi pemenuhan unsur pelanggaran UU TPKS :
        </div>

        {{-- Loop semua jawaban sesi secara adaptif --}}
        @foreach($answers as $item)
            <div class="qa-item">
                <div class="qa-question">Q: {{ $item['question_text'] }}</div>
                <div class="qa-answer">
                    A: @if($item['answer'] === 'YA') Ya.
                       @elseif($item['answer'] === 'TIDAK') Tidak.
                       @else Tidak Yakin.
                       @endif
                </div>
            </div>
        @endforeach

        {{-- Deklarasi Kronologi Penyintas --}}
        <div class="declaration-section" style="margin-top: 32px;">
            <div class="signature-block">
                <div class="signature-label">Deklarasi Kronologi Penyintas</div>
                <br><br><br>
                <div class="signature-name">&nbsp;</div>
            </div>
        </div>

    </div>

    {{-- Disclaimer --}}
    <div class="disclaimer">
        <strong>PENTING (DISCLAIMER HUKUM):</strong> Dokumen ini dihasilkan secara otomatis oleh sistem pakar RuangAman dan bersifat anonim. Dokumen ini BUKAN merupakan alat bukti hukum formal (seperti Berita Acara Pemeriksaan dari kepolisian) dan TIDAK dapat menggantikan proses penyelidikan resmi oleh aparat penegak hukum. Hasil identifikasi ini hanya bertujuan untuk memberikan edukasi dan rujukan awal bagi penyintas untuk menentukan langkah perlindungan atau pemulihan, serta memudahkan pelaporan ke Satgas PPKS atau Lembaga Bantuan Hukum (LBH).
    </div>

    {{-- Footer --}}
    <div class="page-footer">www.ruangaman.co.id</div>

</div>

</body>
</html>