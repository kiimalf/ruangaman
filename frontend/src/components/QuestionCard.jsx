import { useState, useEffect } from 'react';

export default function QuestionCard({ question, onAnswer, disabled }) {
  const [animClass, setAnimClass] = useState('animate-fade-in');

  useEffect(() => {
    setAnimClass('');
    const t = setTimeout(() => setAnimClass('animate-fade-in'), 10);
    return () => clearTimeout(t);
  }, [question?.id]);

  if (!question) return null;

  const buttons = [
    { label: 'Ya',          value: 'YA',              bg: '#FFF7E0' },
    { label: 'Tidak',       value: 'TIDAK',           bg: '#F3FDD8' },
    { label: 'Tidak Yakin', value: 'SAYA_TIDAK_YAKIN', bg: '#F9DBFF' },
  ];

  return (
    <div className={`flex flex-col items-center w-full ${animClass}`}>

      {/* Teks pertanyaan */}
      <p className="text-center mb-6" style={{
        color: '#181818',
        fontSize: 16,
        fontFamily: 'Plus Jakarta Sans, sans-serif',
        fontWeight: 500, lineHeight: 1.6,
        maxWidth: 700,
      }}>
        {question.text}
      </p>

      {/* Help text */}
      {question.help_text && (
        <div className="flex items-start gap-2 justify-center mb-8" style={{ maxWidth: 600 }}>
          {/* Icon lampu */}
          <span style={{ flexShrink: 0, fontSize: 18, marginTop: 2 }}>💡</span>
          <p style={{
            color: '#75A000', fontSize: 14,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            fontWeight: 400, textDecoration: 'underline',
            textAlign: 'center', lineHeight: 1.6,
          }}>
            {question.help_text}
          </p>
        </div>
      )}

      {/* Tombol Ya / Tidak / Tidak Yakin */}
      <div style={{ display: 'flex', gap: 44, flexWrap: 'wrap', justifyContent: 'center' }}>
        {buttons.map(({ label, value, bg }) => (
          <button
            key={value}
            onClick={() => onAnswer(value)}
            disabled={disabled}
            style={{
              width: 161, height: 50,
              paddingLeft: label === 'Ya' ? 66 : label === 'Tidak Yakin' ? 16 : 48,
              paddingRight: label === 'Ya' ? 66 : label === 'Tidak Yakin' ? 16 : 48,
              paddingTop: 10, paddingBottom: 10,
              background: bg, borderRadius: 8,
              outline: '1px solid black', outlineOffset: '-1px',
              border: 'none',
              cursor: disabled ? 'not-allowed' : 'pointer',
              opacity: disabled ? 0.6 : 1,
              display: 'flex', justifyContent: 'center', alignItems: 'center', gap: 10,
              transition: 'opacity 0.15s',
            }}
          >
            <span style={{
              color: '#000', fontSize: 16,
              fontFamily: 'Plus Jakarta Sans, sans-serif',
              fontWeight: 600,
            }}>
              {label}
            </span>
          </button>
        ))}
      </div>
    </div>
  );
}