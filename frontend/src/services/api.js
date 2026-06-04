export const API_URL = '/api';

export const startSession = async () => {
  const response = await fetch(`${API_URL}/session/start`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  });
  
  if (!response.ok) {
    throw new Error('Gagal memulai sesi');
  }
  
  const data = await response.json();
  return data.data;
};

export const answerQuestion = async (sessionToken, questionId, answer) => {
  const response = await fetch(`${API_URL}/session/answer`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      session_token: sessionToken,
      question_id: questionId,
      answer: answer,
    }),
  });
  
  if (!response.ok) {
    throw new Error('Gagal mengirim jawaban');
  }
  
  const data = await response.json();
  return data.data;
};

export const getConclusion = async (sessionToken) => {
  const response = await fetch(`${API_URL}/session/conclude?session_token=${sessionToken}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
    },
  });
  
  if (!response.ok) {
    throw new Error('Gagal mengambil kesimpulan');
  }
  
  const data = await response.json();
  return data.data.conclusion;
};
