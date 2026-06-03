import { create } from 'zustand';

const useSessionStore = create((set) => ({
  sessionToken: null,
  currentQuestion: null,
  answers: {},
  conclusion: null,
  isLoading: false,
  error: null,

  setSessionToken: (token) => set({ sessionToken: token }),
  setCurrentQuestion: (question) => set({ currentQuestion: question }),
  addAnswer: (questionId, answer) =>
    set((state) => ({
      answers: { ...state.answers, [questionId]: answer },
    })),
  setConclusion: (conclusion) => set({ conclusion }),
  setLoading: (isLoading) => set({ isLoading }),
  setError: (error) => set({ error }),

  resetSession: () =>
    set({
      sessionToken: null,
      currentQuestion: null,
      answers: {},
      conclusion: null,
      isLoading: false,
      error: null,
    }),
}));

export default useSessionStore;
