import axios from 'axios';

const state = {
   chat: [],
   chatQuantity: 0,

};

const mutations = {
   setChat(state, chat) {
      state.chat = chat;
   },
   setChatQuantity(state, chatQuantity) {
      state.chatQuantity = chatQuantity;
   },
};

const actions = {
   async fetchChat({ commit }) {
      try {
         const response = await axios.get('/api/chat');
         // console.log(response.data);
         if (response.data.success) {
            commit('setChat', response.data.chat);
            commit('setChatQuantity', response.data.chatQuantity);

         } else {
            commit('setChat', []);
         }
      } catch (error) {
         console.error('Error:', error);
         commit('setChat', []);
      }
   },
};

const getters = {
   chat: (state) => state.chat,
   chatQuantity: (state) => state.chatQuantity,

};

export default {
   state,
   mutations,
   actions,
   getters,
};
