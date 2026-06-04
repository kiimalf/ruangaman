import { useEffect, useRef } from 'react';
import useSessionStore from '../store/sessionStore';

export default function QuickExitButton() {
  const resetSession = useSessionStore((state) => state.resetSession);
  const escapeCount = useRef(0);
  const escapeTimer = useRef(null);

  const handleQuickExit = () => {
    // 1. Reset state (termasuk sessionToken di sessionStorage)
    resetSession();
    sessionStorage.clear(); // just to be safe
    // 2. Replace history agar tombol back tidak kembali ke app
    window.location.replace('https://www.google.com');
  };

  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape') {
        escapeCount.current += 1;
        
        if (escapeCount.current >= 2) {
          handleQuickExit();
        }
        
        // Reset counter after 1 second
        clearTimeout(escapeTimer.current);
        escapeTimer.current = setTimeout(() => {
          escapeCount.current = 0;
        }, 1000);
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => {
      window.removeEventListener('keydown', handleKeyDown);
      clearTimeout(escapeTimer.current);
    };
  }, []);

  return (
    <button
      className="quick-exit-btn"
      onClick={handleQuickExit}
      title="Keluar Cepat (tekan Esc 2x)"
      aria-label="Keluar cepat dari aplikasi"
    >
      ✕ Keluar Cepat
    </button>
  )
}

