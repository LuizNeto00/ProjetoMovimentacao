import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/'
})

export const createAMovement = (movementData) => {
  return api.post('movements', movementData, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })
}

export const getMovementData = () => {
  return api.get('movements', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })
}

export const updateMovement = (id, movementData) => {
  return api.put(`movements/${id}`, movementData, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })
}

export const excludeMovement = (id) => {
  return api.delete(`movements/${id}`, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })
}
