export default function ConclusionCard({ conclusion }) {
  if (!conclusion) return null;

  const isProven = conclusion.type === 'TERPENUHI' || conclusion.type === 'FALLBACK';

  return (
    <div className="card w-full mb-6 animate-fade-in border-t-4 border-t-primary">
      {/* Status Header */}
      <div className={`p-4 rounded-t-xl mb-4 ${isProven ? 'bg-red-50 text-red-800' : 'bg-green-50 text-green-800'}`}>
        <h3 className="text-lg font-bold">
          {isProven ? 'Indikasi Kekerasan Seksual Ditemukan' : 'Belum Memenuhi Unsur Spesifik TPKS'}
        </h3>
      </div>

      {/* Message */}
      <p className="text-safe-text mb-6 leading-relaxed px-2">
        {conclusion.message}
      </p>

      {/* Hypotheses / Pasal Detail */}
      {conclusion.hypotheses && conclusion.hypotheses.length > 0 && (
        <div className="mb-6 px-2">
          <h4 className="font-semibold text-safe-text mb-3">Berdasarkan UU TPKS (No. 12 Tahun 2022):</h4>
          <div className="space-y-4">
            {conclusion.hypotheses.map((hyp) => (
              <div key={hyp.id} className="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p className="font-bold text-primary mb-1">{hyp.label}</p>
                <p className="text-sm text-secondary font-medium mb-2">{hyp.pasal}</p>
                <p className="text-sm text-safe-muted">{hyp.description}</p>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Recommendation */}
      <div className="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
        <h4 className="font-semibold text-primary-800 mb-2">Rekomendasi Langkah Selanjutnya:</h4>
        <p className="text-sm text-primary-900 leading-relaxed">
          {conclusion.recommendation}
        </p>
      </div>

      {/* Disclaimer */}
      <div className="text-xs text-safe-muted italic px-2 opacity-80 border-t pt-4">
        <strong>PENTING:</strong> {conclusion.disclaimer}
      </div>
    </div>
  );
}
