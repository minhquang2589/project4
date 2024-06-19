import axios from 'axios';

const state = {
   isFollowing: false,
};

const mutations = {
   setIsFollowing(state, value) {
      state.isFollowing = value;
   },
};

const actions = {
   async fetchIsFollowing({ commit }, userId) {
      try {
         const response = await axios.get(`/api/check-follow/${userId}`);
         const isFollowing = response.data.isFollowing;
         commit('setIsFollowing', isFollowing);
      } catch (error) {
         console.error("Error fetching follow status:", error);
      }
   },
};


const getters = {
   getIsFollowing(state) {
      return state.isFollowing;
   },
};

export default {
   state,
   mutations,
   actions,
   getters,
};
