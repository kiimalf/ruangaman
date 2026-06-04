import { useNavigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { startSession } from '../services/api';
import { useState } from 'react';

export default function Landing() {
  const navigate = useNavigate();
  const setSessionToken = useSessionStore((state) => state.setSessionToken);
  const setCurrentQuestion = useSessionStore((state) => state.setCurrentQuestion);
  const [isStarting, setIsStarting] = useState(false);
  const [errorMsg, setErrorMsg] = useState(null);

  const handleStart = async () => {
    setIsStarting(true);
    setErrorMsg(null);
    try {
      const data = await startSession();
      setSessionToken(data.session_token);
      setCurrentQuestion(data.first_question);
      navigate('/session');
    } catch (err) {
      setErrorMsg('Gagal memulai sesi. Pastikan server berjalan.');
      setIsStarting(false);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen p-4 pb-20 animate-fade-in max-w-md mx-auto">
      
      {/* Hero section with logo/title */}
      <div className="text-center mb-8 mt-12">
        <h1 className="text-4xl font-bold text-primary mb-2">RuangAman</h1>
        <p className="text-lg text-safe-muted font-medium">Platform Identifikasi Dini Kekerasan Seksual</p>
      </div>

      {/* Main Card */}
      <div className="card w-full mb-8">
        <h2 className="text-xl font-semibold mb-4 text-safe-text">Apakah Anda Mengalami Kekerasan Seksual?</h2>
        <p className="text-safe-muted mb-4 leading-relaxed">
          RuangAman membantu Anda mengidentifikasi apakah kejadian yang dialami memenuhi unsur tindak pidana menurut <strong>UU TPKS</strong> (Undang-Undang Tindak Pidana Kekerasan Seksual).
        </p>
        
        <ul className="space-y-3 mb-6 text-sm text-safe-text">
          <li className="flex items-start">
            <span className="text-secondary mr-2">✓</span>
            <span><strong>100% Anonim:</strong> Kami tidak meminta nama, email, atau data pribadi apapun.</span>
          </li>
          <li className="flex items-start">
            <span className="text-secondary mr-2">✓</span>
            <span><strong>Aman:</strong> Terdapat tombol 'Keluar Cepat' untuk menutup layar seketika.</span>
          </li>
          <li className="flex items-start">
            <span className="text-secondary mr-2">✓</span>
            <span><strong>Dokumen BAP:</strong> Dapatkan draf kronologis yang bisa diunduh untuk laporan.</span>
          </li>
        </ul>

        {errorMsg && (
          <div className="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-100">
            {errorMsg}
          </div>
        )}

        <button 
          onClick={handleStart}
          disabled={isStarting}
          className="btn-primary w-full text-lg shadow-md flex justify-center items-center h-14"
        >
          {isStarting ? (
            <span className="animate-pulse">Memulai...</span>
          ) : (
            'Mulai Secara Anonim'
          )}
        </button>
      </div>

      {/* Footer Info */}
      <div className="text-center text-xs text-safe-muted mt-auto px-4 opacity-70">
        <p>Jika Anda dalam keadaan darurat, segera hubungi polisi di <strong>110</strong> atau SAPA 129.</p>
      </div>
    </div>
  );
}
