import { defineStore } from 'pinia';

export const useMovementStore = defineStore('movement', {
    state: () => ({
        movements:[]
    }),

    actions: {
        setMovementData(movements) {
            this.movements = movements;
      
            localStorage.setItem('movements', JSON.stringify(movements));
          },

          clearMovementData() {
            this.movements = [];
            localStorage.removeItem('movements');
          }
    }
})