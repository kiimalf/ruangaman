import { useState } from 'react';

/**
 * HelpButton.jsx
 * Pojok kanan bawah — tombol bantuan dengan modal panduan penggunaan.
 * Muncul di semua halaman penyintas (Session, Conclusion, dll).
 */

import IconHelp from "../assets/HelpIcon.svg";
export default function HelpButton() {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <>
      {/* ── Tombol ? ── */}
      <button
        onClick={() => setIsOpen(true)}
        title="Bantuan"
        aria-label="Buka panduan bantuan"
        style={{
          position: 'fixed',
          bottom: 28,
          right: 28,
          zIndex: 9998,
          width: 40,
          height: 40,
          borderRadius: '50%',
          border: '1.5px solid rgba(0,0,0,0.15)',
          background: 'rgba(255,255,255,0.55)',
          backdropFilter: 'blur(6px)',
          WebkitBackdropFilter: 'blur(6px)',
          cursor: 'pointer',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          opacity: 0.5,
          transition: 'opacity 0.2s ease, transform 0.2s ease',
          boxShadow: '0 2px 8px rgba(0,0,0,0.08)',
        }}
        onMouseEnter={e => {
          e.currentTarget.style.opacity = '1';
          e.currentTarget.style.transform = 'scale(1.08)';
        }}
        onMouseLeave={e => {
          e.currentTarget.style.opacity = '0.5';
          e.currentTarget.style.transform = 'scale(1)';
        }}
      >
        <img
        src={IconHelp}
        alt="Bantuan"
        style={{
          width: '40px',
          height: '40px',
        }}
      />
      </button>

      {/* ── Modal Bantuan ── */}
      {isOpen && (
        <>
          {/* Backdrop */}
          <div
            onClick={() => setIsOpen(false)}
            style={{
              position: 'fixed',
              inset: 0,
              zIndex: 9999,
              background: 'rgba(0,0,0,0.35)',
              backdropFilter: 'blur(3px)',
              WebkitBackdropFilter: 'blur(3px)',
            }}
            aria-hidden="true"
          />

          {/* Modal box */}
          <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="help-modal-title"
            style={{
              position: 'fixed',
              top: '50%',
              left: '50%',
              transform: 'translate(-50%, -50%)',
              zIndex: 10000,
              width: 'min(520px, 90vw)',
              background: '#fff',
              borderRadius: 16,
              padding: '36px 32px 28px',
              boxShadow: '0 24px 60px rgba(0,0,0,0.18)',
              fontFamily: 'Plus Jakarta Sans, sans-serif',
            }}
          >
            {/* Close button */}
            <button
              onClick={() => setIsOpen(false)}
              aria-label="Tutup bantuan"
              style={{
                position: 'absolute',
                top: 16,
                right: 16,
                width: 32,
                height: 32,
                borderRadius: '50%',
                border: '1px solid #e0e0e0',
                background: '#f5f5f5',
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: 16,
                color: '#555',
                lineHeight: 1,
              }}
            >
              ✕
            </button>

            {/* Judul */}
            <h2
              id="help-modal-title"
              style={{
                fontSize: 18,
                fontWeight: 700,
                color: '#BE0199',
                marginBottom: 6,
                fontFamily: 'Qaligo, serif',
              }}
            >
              Panduan Penggunaan
            </h2>
            <p style={{ fontSize: 12, color: '#888', marginBottom: 24 }}>
              Bacalah sebelum memulai sesi Anda.
            </p>

            {/* Item panduan */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

              <HelpItem
                icon="🔒"
                title="100% Anonim"
                desc="Anda tidak perlu membuat akun atau memasukkan nama. Sesi Anda tidak terhubung ke identitas apapun."
              />

              <HelpItem
                icon="💬"
                title="Jawab Satu Pertanyaan"
                desc='Sistem akan menampilkan satu pertanyaan dalam satu waktu. Pilih "Ya", "Tidak", atau "Tidak Yakin" sesuai kondisi Anda.'
              />

              <HelpItem
                icon="📄"
                title="Unduh Draf Kronologis"
                desc="Di akhir sesi, Anda dapat mengunduh draf dokumen (BAP) yang bisa diserahkan ke Satgas PPKS atau LBH tanpa harus bercerita ulang."
              />

              <HelpItem
                icon="🚪"
                title="Keluar Cepat"
                desc='Tombol "✕ Keluar Cepat" di tengah bawah atau tekan Esc 2× akan langsung menghapus sesi dan mengalihkan browser ke halaman netral.'
              />

              <HelpItem
                icon="⚠️"
                title="Bukan Keputusan Hukum Final"
                desc="Hasil dari sistem ini adalah rujukan awal. Konsultasikan dengan LBH atau Satgas PPKS untuk penanganan resmi."
              />

            </div>

            {/* Tombol tutup bawah */}
            <button
              onClick={() => setIsOpen(false)}
              style={{
                marginTop: 28,
                width: '100%',
                height: 44,
                borderRadius: 10,
                border: '1.5px solid #BE0199',
                background: 'transparent',
                color: '#BE0199',
                fontSize: 14,
                fontWeight: 600,
                cursor: 'pointer',
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                transition: 'background 0.15s ease',
              }}
              onMouseEnter={e => e.currentTarget.style.background = 'rgba(190,1,153,0.06)'}
              onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
            >
              Mengerti, Tutup
            </button>
          </div>
        </>
      )}
    </>
  );
}

/* ── Sub-komponen item panduan ── */
function HelpItem({ icon, title, desc }) {
  return (
    <div style={{ display: 'flex', gap: 14, alignItems: 'flex-start' }}>
      <span style={{ fontSize: 20, flexShrink: 0, marginTop: 1 }}>{icon}</span>
      <div>
        <p style={{ fontSize: 13, fontWeight: 700, color: '#1a1a1a', marginBottom: 2 }}>
          {title}
        </p>
        <p style={{ fontSize: 12, color: '#555', lineHeight: 1.6 }}>
          {desc}
        </p>
      </div>
    </div>
  );
}