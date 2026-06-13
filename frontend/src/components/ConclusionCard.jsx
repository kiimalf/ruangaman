export default function ConclusionCard({ conclusion }) {
  if (!conclusion) return null;

  return (
    <div className="w-full animate-fade-in flex flex-col gap-6">

      {/* Teks utama */}
      <p className="text-center" style={{
        color: '#181818',
        fontSize: 16,
        fontFamily: 'Plus Jakarta Sans, sans-serif',
        fontWeight: 500, lineHeight: 1.6,
      }}>
        Pengalaman yang Anda bagikan sangat valid. Sistem kami telah merangkum jawaban Anda menjadi sebuah draf kronologis berdasarkan UU TPKS. Anda dapat mengunduh dokumen ini sebagai rujukan awal saat mencari bantuan ke Satgas PPKS atau Lembaga Bantuan Hukum (LBH), agar Anda tidak perlu lagi menceritakan kejadian tersebut dari awal.
      </p>
     
      {/* Info privasi — fixed seperti di gambar */}
      <div className="flex items-start gap-2 justify-center">
        <span style={{ flexShrink: 0, fontSize: 18, marginTop: 2 }}>💡</span>
        <p style={{
          color: '#75A000', fontSize: 14,
          fontFamily: 'Plus Jakarta Sans, sans-serif',
          fontWeight: 400, textDecoration: 'underline',
          lineHeight: 1.6, textAlign: 'center',
        }}>
          Privasi Terjamin: Dokumen ini dibuat sekali pakai (ephemeral) dan tidak disimpan di dalam pangkalan data kami.
        </p>
      </div>

    </div>
  );
}