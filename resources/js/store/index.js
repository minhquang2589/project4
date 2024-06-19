import { createStore } from 'vuex';
import user from './modules/user';
import recentlyViewedProducts from './modules/RecentlyViewedProducts';
import notification from './modules/showNotification';
import cart from './modules/cart/cart';
import currencyFormatter from './modules/formatCurrency';

import isFollowing from './modules/follow';
import isViewCartOpen from './modules/viewCart';
import chat from './modules/mesage/Mesage.js';


const store = createStore({
   modules: {
      user,
      cart,
      notification,
      recentlyViewedProducts,
      currencyFormatter,
      isFollowing,
      isViewCartOpen,
      chat,
   },
});

export default store;
