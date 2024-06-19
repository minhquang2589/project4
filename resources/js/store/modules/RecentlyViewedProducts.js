import axios from 'axios';

const state = {
   recentlyViewedProducts: [],
};

const mutations = {
   setRecentlyProducts(state, products) {
      state.recentlyViewedProducts = products;
   },
   AddRecentlyProducts(state, product) {
      state.recentlyViewedProducts.unshift(product);
      if (state.recentlyViewedProducts.length > 10) {
         state.recentlyViewedProducts.pop();
      }
   }
};

const actions = {
   fetchRecentlyViewedProducts({ commit }) {
      axios.get('/api/recently-viewed')
         .then(response => {
            console.log(response.data);
            commit('setRecentlyProducts', response.data);
         })
         .catch(error => {
            console.error('Error fetching recently viewed products:', error);
         });
   },
   addRecentlyViewedProduct({ commit }, productId) {
      axios.post('/api/recently-viewed', { product_id: productId })
         .then(response => {
            console.log(response.data);

            commit('AddRecentlyProducts', response.data);
         })
         .catch(error => {
            console.error('Error adding product to recently viewed:', error);
         });
   }
};

export default {
   state,
   mutations,
   actions,
};
