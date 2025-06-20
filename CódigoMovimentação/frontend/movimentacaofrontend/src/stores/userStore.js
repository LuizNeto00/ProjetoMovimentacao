import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    nome: '',
    email: '',
    token: '',
    profileImage:''
  }),
  actions: {
    setUserData({ nome, email, token, image }) {
      this.nome = nome;
      this.email = email;
      this.token = token;
      this.profileImage = image;

      localStorage.setItem('Username', nome);
      localStorage.setItem('Email', email);
      localStorage.setItem('auth_token', token);
      localStorage.setItem('Photo', image);
    },
    clearUserData() {
      this.nome = '';
      this.email = '';
      this.token = '';
      this.image = '';
      localStorage.clear();
    }
  }
});
