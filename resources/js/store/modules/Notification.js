import axios from 'axios';

const state = {
   notification: [],
   notificationQuantity: 0,

};

const mutations = {
   setNotofication(state, notification) {
      state.notification = notification;
   },
   setNotificationQuantity(state, notificationQuantity) {
      state.notificationQuantity = notificationQuantity;
   },
};

const actions = {
   async fetchChat({ commit }) {
      try {
         const response = await axios.get('/api/notification');
         // console.log(response.data);
         if (response.data.success) {
            commit('setNotofication', response.data.notification);
            commit('setNotificationQuantity', response.data.notificationQuantity);

         } else {
            commit('setNotofication', []);
         }
      } catch (error) {
         console.error('Error:', error);
         commit('setNotofication', []);
      }
   },
};

const getters = {
   notification: (state) => state.notification,
   notificationQuantity: (state) => state.notificationQuantity,

};

export default {
   state,
   mutations,
   actions,
   getters,
};
