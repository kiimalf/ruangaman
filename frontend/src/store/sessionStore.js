import { create } from 'zustand';

const useSessionStore = create((set) => ({
  sessionToken: sessionStorage.getItem('ruangaman_session_token') || null,
  currentQuestion: null,
  answers: {},
  conclusion: null,
  isLoading: false,
  error: null,

  setSessionToken: (token) => {
    if (token) {
      sessionStorage.setItem('ruangaman_session_token', token);
    } else {
      sessionStorage.removeItem('ruangaman_session_token');
    }
    set({ sessionToken: token });
  },
  setCurrentQuestion: (question) => set({ currentQuestion: question }),
  addAnswer: (questionId, answer) =>
    set((state) => ({
      answers: { ...state.answers, [questionId]: answer },
    })),
  setConclusion: (conclusion) => set({ conclusion }),
  setLoading: (isLoading) => set({ isLoading }),
  setError: (error) => set({ error }),

  resetSession: () => {
    sessionStorage.removeItem('ruangaman_session_token');
    set({
      sessionToken: null,
      currentQuestion: null,
      answers: {},
      conclusion: null,
      isLoading: false,
      error: null,
    });
  },
}));

export default useSessionStore;
