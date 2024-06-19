import axios from 'axios';

const state = {
   isViewCartOpen: false
};

const mutations = {
   toggleViewCart(state) {
      state.isViewCartOpen = !state.isViewCartOpen;
   }
};

const actions = {
   toggleViewCart({ commit }) {
      commit('toggleViewCart');
   }
};

const getters = {
   isViewCartOpen: state => state.isViewCartOpen
};

export default {
   state,
   actions,
   mutations,
   getters
};
