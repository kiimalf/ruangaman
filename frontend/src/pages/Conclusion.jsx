import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import useSessionStore from '../store/sessionStore';
import { getConclusion, API_URL } from '../services/api';
import ConclusionCard from '../components/ConclusionCard';
import ProgressBar from '../components/ProgressBar';
import QuickExitButton from '../components/QuickExitButton';
import AdminLoginButton from '../components/AdminLoginButton';
import HelpButton from '../components/HelpButton';

export default function Conclusion() {
  const navigate = useNavigate();
  const { sessionToken, conclusion, setConclusion, resetSession } = useSessionStore();
  const [isLoading, setIsLoading] = useState(!conclusion);
  const [errorMsg, setErrorMsg] = useState(null);

  useEffect(() => {
    if (!sessionToken) {
      navigate('/', { replace: true });
      return;
    }
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
    window.open(`${API_URL}/session/export-pdf?session_token=${sessionToken}`);
  };

  const handleFinish = () => {
    resetSession();
    navigate('/');
  };

  if (isLoading) {
    return (
      <div
        className="flex items-center justify-center min-h-screen"
        style={{ background: '#FEFAEE' }}
      >
        <p className="animate-pulse" style={{
          color: '#BE0199', fontSize: 16,
          fontFamily: 'Plus Jakarta Sans, sans-serif', fontWeight: 500,
        }}>
          Memuat hasil...
        </p>
      </div>
    );
  }

  return (
    <div
      className="relative min-h-screen overflow-hidden flex flex-col"
      style={{ background: '#FEFAEE' }}
    >
      {/* ── Blobs ── */}
      <div aria-hidden="true" style={{
        position: 'absolute', width: '40vw', height: '55vh',
        left: '-5vw', top: '40vh', opacity: 0.5,
        background: '#FFE85C', filter: 'blur(50px)',
        transform: 'rotate(139deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      <div aria-hidden="true" style={{
        position: 'absolute', width: '50vw', height: '55vh',
        left: '30vw', bottom: '-10vh', opacity: 0.5,
        background: '#F9DBFF', filter: 'blur(50px)',
        transform: 'rotate(150deg)', transformOrigin: 'top left',
        borderRadius: '50%', pointerEvents: 'none',
      }} />
      <div aria-hidden="true" style={{
        position: 'absolute', width: '45vw', height: '58vh',
        right: '-5vw', top: '40vh', opacity: 0.5,
        background: '#DDFF82', filter: 'blur(50px)',
        borderRadius: '50%', pointerEvents: 'none',
      }} />

      <div className="relative z-10 flex flex-col min-h-screen">

        {/* ── Header ── */}
        <div className="w-full text-center pt-10 pb-4">
          <span style={{
            color: '#BE0199', fontSize: 24,
            fontFamily: 'Qaligo, serif', fontWeight: 400,
          }}>
            ruangaman
          </span>
        </div>

        {/* ── Progress Bar 100% ── */}
        <div className="w-full px-8 pt-0 pb-1">
          <ProgressBar depth={6} completed />
        </div>

        {/* ── Konten tengah ── */}
        <div className="flex-1 flex flex-col items-center justify-center px-6 max-w-2xl mx-auto w-full py-8">

          {errorMsg && (
            <div className="text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-200 w-full text-center"
              style={{ background: 'rgba(255,255,255,0.6)' }}>
              {errorMsg}
            </div>
          )}

          <ConclusionCard conclusion={conclusion} />

          {/* Tombol Unduh BAP */}
          <div className="w-full flex flex-col items-center gap-3 mt-6">
            <button
              onClick={handleDownloadPdf}
              style={{
                height: 50, paddingLeft: 32, paddingRight: 32,
                background: '#F3FDD8', borderRadius: 8,
                outline: '1px solid black', outlineOffset: '-1px',
                border: 'none', cursor: 'pointer',
                display: 'inline-flex', alignItems: 'center', gap: 10,
                transition: 'opacity 0.2s',
              }}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
                <line x1="8" y1="8" x2="16" y2="8"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
                <line x1="12" y1="15" x2="12" y2="20"/>
                <polyline points="9 17 12 20 15 17"/>
              </svg>
              <span style={{
                color: '#000', fontSize: 16,
                fontFamily: 'Plus Jakarta Sans, sans-serif', fontWeight: 600,
              }}>
                Unduh BAP
              </span>
            </button>

            <button
              onClick={handleFinish}
              style={{
                height: 44, paddingLeft: 32, paddingRight: 32,
                background: 'transparent', borderRadius: 8,
                outline: '1px solid rgba(0,0,0,0.2)', outlineOffset: '-1px',
                border: 'none', cursor: 'pointer',
                display: 'inline-flex', alignItems: 'center', gap: 10,
                transition: 'opacity 0.2s',
              }}
            >
              <span style={{
                color: '#444', fontSize: 14,
                fontFamily: 'Plus Jakarta Sans, sans-serif', fontWeight: 500,
              }}>
                Selesai & Kembali ke Awal
              </span>
            </button>
          </div>
        </div>

        {/*
              * ── 3 tombol fixed bawah ──
              *
              * Kiri   : AdminLoginButton  → /admin (Filament), tab baru, subtle
              * Tengah : QuickExitButton   → clear session + redirect google.com
              * Kanan  : HelpButton        → modal panduan penggunaan
              *
              * Semua komponen mengatur posisinya sendiri (position: fixed).
              */}
             <AdminLoginButton />
             <QuickExitButton />
             <HelpButton />
           </div>
    </div>
  );
}