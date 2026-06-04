import { useEffect, useState } from 'react';
import { useNavigate, Navigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { getConclusion, API_URL } from '../services/api';
import ConclusionCard from '../components/ConclusionCard';

export default function Conclusion() {
  const navigate = useNavigate();
  const { sessionToken, conclusion, setConclusion, resetSession } = useSessionStore();
  const [isLoading, setIsLoading] = useState(!conclusion);
  const [errorMsg, setErrorMsg] = useState(null);

  useEffect(() => {
    // Jika tidak ada session token, redirect ke landing
    if (!sessionToken) {
      navigate('/', { replace: true });
      return;
    }

    // Jika konklusi belum ada di store (misal karena refresh), ambil dari API
    if (!conclusion) {
      const fetchConclusion = async () => {
        try {
          const result = await getConclusion(sessionToken);
          setConclusion(result);
        } catch (err) {
          setErrorMsg('Gagal memuat hasil evaluasi.');
        } finally {
          setIsLoading(false);
        }
      };
      
      fetchConclusion();
    }
  }, [sessionToken, conclusion, navigate, setConclusion]);

  const handleDownloadPdf = () => {
    // API endpoint PDF
    // Buka di tab baru / trigger download browser langsung dari URL backend
    window.open(`${API_URL}/session/export-pdf?session_token=${sessionToken}`);
  };

  const handleFinish = () => {
    resetSession();
    navigate('/');
  };

  if (isLoading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen">
        <p className="animate-pulse text-primary font-medium">Memuat hasil...</p>
      </div>
    );
  }

  return (
    <div className="flex flex-col items-center min-h-screen p-4 pb-24 max-w-md mx-auto pt-8">
      
      <div className="w-full mb-6">
        <h2 className="text-2xl font-bold text-primary">Hasil Evaluasi</h2>
        <p className="text-sm text-safe-muted mt-1">100% Anonim</p>
      </div>

      {errorMsg && (
        <div className="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm w-full border border-red-100">
          {errorMsg}
        </div>
      )}

      <ConclusionCard conclusion={conclusion} />

      <div className="w-full space-y-4 mt-4">
        <button 
          onClick={handleDownloadPdf}
          className="btn-primary w-full flex items-center justify-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fillRule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clipRule="evenodd" />
          </svg>
          Unduh Draf Kronologis (PDF)
        </button>
        
        <button 
          onClick={handleFinish}
          className="btn-outline w-full"
        >
          Selesai & Kembali ke Awal
        </button>
      </div>
    </div>
  );
}
