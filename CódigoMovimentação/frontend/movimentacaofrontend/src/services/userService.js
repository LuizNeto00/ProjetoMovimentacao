import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/'
})

export const register = (userData) => {
    return api.post('usuarios', userData)
}

export const login = (userData) => {
    return api.post('usuarios/login', userData)
}

export const getAuthenticatedUser = async (token) => {
    return await api.get('usuario/autenticado', {
        headers:{
            Authorization:`Bearer ${token}`
        }
    })
}

export const verifyPasswordBackend = (password) => {
    return api.post('verifypassword', { password }, {
        headers:{
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
        }
    })
  }

  export const updatePasswordBackend = (newpassword) => {
    return api.post('updatepassword', { new_password: newpassword }, {
        headers:{
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
        }
    })
  }
  