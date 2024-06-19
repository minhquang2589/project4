import axios from 'axios';

const state = {
   user: {
      isAdmin: false,
      isAuthenticated: false,
      name: null,
      phone: null,
      email: null,
      address: null,
      gender: null,
      id: null,
      image: null,
      role: null,
      description: null,
   },
   userInformation: [],
   userInfor: null,
};

const mutations = {
   setUser(state, user) {
      state.user = {
         name: user.name,
         phone: user.phone,
         email: user.email,
         address: user.address,
         gender: user.gender,
         id: user.id,
         image: user.image,
         description: user.description,
         role: user.role,
         isAdmin: user.role === 'admin',
         isAuthenticated: true,
      };
   },
   setUserInformation(state, userInformation) {
      state.userInformation = userInformation;
   },
   setUserInfor(state, userInfor) {
      state.userInfor = userInfor;
   },
   clearUser(state) {
      state.user = {
         name: null,
         phone: null,
         email: null,
         address: null,
         gender: null,
         role: null,
         id: null,
         image: null,
         description: null,
         isAdmin: false,
         isAuthenticated: false,
      };
      state.userInformation = [];
      state.userInfor = null;

   },
};

const actions = {
   fetchUser({ commit }) {
      axios
         .get('/api/user')
         .then((response) => {
            // console.log(response.data);
            commit('setUser', response.data.user);
            commit('setUserInformation', response.data.likeProducts);
            commit('setUserInfor', response.data.userInfor);
         })
         .catch((error) => {
            // console.error('Error fetching user:', error);
            commit('clearUser');
         });
   },
};

const getters = {
   isAdmin: (state) => state.user.isAdmin,
   isAuthenticated: (state) => state.user.isAuthenticated,
   isLoggedIn: (state) => state.user.isAuthenticated,
   userName: (state) => state.user.name,
   accountId: (state) => state.user.id,
   userData: (state) => state.user,
   userInfor: (state) => state.userInfor,
   userInformation: (state) => state.userInformation,
};

export default {
   state,
   mutations,
   actions,
   getters,
};
