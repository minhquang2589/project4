import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Layout/Home.vue';
import Header from './components/Layout/Header.vue';
import Layout from './components/Layout/Layout.vue';
import Login from './components/Layout/Login.vue';
import Register from './components/Layout/Register.vue';
import Dashboard from './components/Layout/Dashboard.vue';
import Account from './components/Layout/account/Account.vue';
import Menu from './components/Layout/navigation/Menu.vue';
import Profile from './components/Layout/account/Profile.vue';
import uploadProduct from './components/Layout/product/Upload.vue';
import ViewProduct from "./components/view/ViewProduct.vue";
import Cart from "./components/cart/Cart.vue";
import Checkout from "./components/Layout/Checkout.vue";
import Payment from "./components/Layout/Payment.vue";
import userProfile from "./components/user/Profile.vue";
import LikedProduct from "./components/user/LikeProduct.vue";
import SliderUpload from "./components/slider/Upload.vue";
import SliderUpdate from "./components/slider/Update.vue";
import EditSlider from "./components/slider/Edit.vue";
import Section from "./components/section/Section.vue";
import sectionUpload from "./components/section/Upload.vue";
import sectionUpdate from "./components/section/Update.vue";
import editSection from "./components/section/Edit.vue";
import userPosts from "./components/user/Post.vue";
import Follow from "./components/Layout/account/Follow.vue";
import Follower from "./components/Layout/account/Follower.vue";
import userFollow from "./components/user/Follow.vue";
import userFollower from "./components/user/Follower.vue";
import ChatList from "./components/mesage/ChatList.vue";
import ChatWindow from "./components/mesage/ChatWindow.vue";
import Error from "./components/Layout/Error.vue";
import RecentlyProduct from "./components/Layout/product/RecentlyProduct.vue";
import DiscountProduct from "./components/Layout/product/Discount.vue";
import NotificationList from "./components/notification/NotificationList.vue";
import Confirm from "../js/components/Layout/Confirm.vue";
import Order from "../js/components/order/Order.vue";


const router = createRouter({
   history: createWebHistory(),
   routes: [
      {
         path: '/',
         component: Layout,
         children: [
            {
               path: '',
               name: 'Home',
               component: Home,
               meta: { title: 'Home', showSlider: true, showSection: true, showSliderSale: true, showSliderRecently: true }
            },
            {
               path: 'login',
               name: 'Login',
               component: Login,
               meta: { title: 'Login', hideFooter: true, hideHeader: true, hideNavigatiion: true }
            },
            {
               path: 'error',
               name: 'Error',
               component: Error,
               meta: { title: 'Error' }
            },
            {
               path: 'register',
               name: 'register',
               component: Register,
               meta: { title: 'Register', hideFooter: true, hideHeader: true, hideNavigatiion: true }
            },
            {
               path: 'dashboard',
               name: 'Dashboard',
               component: Dashboard,
               meta: { title: 'Dashboard', requiresAuth: true, requiresAdmin: true, hideFooter: true }
            },
            {
               path: 'account/:id',
               name: 'Account',
               component: Account,
               meta: { title: 'Account', requiresAuth: true }
            },
            {
               path: 'profile',
               name: 'Profile',
               component: Profile,
               meta: { title: 'Profile', requiresAuth: true }
            },
            {
               path: 'account/settings',
               name: 'Menu',
               component: Menu,
               meta: { title: 'Menu', requiresAuth: true }
            },
            {
               path: 'product/upload',
               name: 'uploadProduct',
               component: uploadProduct,
               meta: { title: 'Product - Upload', requiresAuth: true }
            },
            {
               path: 'products/:id',
               name: 'ViewProduct',
               component: ViewProduct,
               props: true,
               meta: { title: 'Product - View', }
            },
            {
               path: 'cart',
               name: 'Cart',
               component: Cart,
               props: true,
               meta: { title: 'Cart - View', hideFooter: true }
            },
            {
               path: '/checkout',
               name: 'Checkout',
               component: Checkout,
               meta: { title: 'Checkout', requiresAuth: true, hideFooter: true }
            },
            {
               path: '/payment',
               name: 'Payment',
               component: Payment,
               meta: { title: 'Payment', requiresAuth: true, hideFooter: true }
            },
            {
               path: 'user/profile/:id',
               name: 'userProfile',
               component: userProfile,
               meta: { title: 'User - Profile', }
            },
            {
               path: 'account/like',
               name: 'LikedProduct',
               component: LikedProduct,
               meta: { title: 'Account - Liked', requiresAuth: true }
            },
            {
               path: 'slider/upload',
               name: 'SliderUpload',
               component: SliderUpload,
               meta: { title: 'Slider - Upload', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'slider/update',
               name: 'SliderUpdate',
               component: SliderUpdate,
               meta: { title: 'Slider - Update', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'slider/edit/:id',
               name: 'EditSlider',
               component: EditSlider,
               props: true,
               meta: { title: 'Slider - Edit', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'confirm',
               name: 'Confirm',
               component: Confirm,
               meta: { title: 'Thank you', requiresAuth: true }
            },
            {
               path: 'section/upload',
               name: 'sectionUpload',
               component: sectionUpload,
               meta: { title: 'Section - Upload', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'section/update',
               name: 'sectionUpdate',
               component: sectionUpdate,
               meta: { title: 'Section - Update', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'section/edit/:id',
               name: 'editSection',
               component: editSection,
               props: true,
               meta: { title: 'Section - Edit', requiresAuth: true, requiresAdmin: true }
            },
            {
               path: 'account/posts',
               name: 'userPosts',
               component: userPosts,
               meta: { title: 'Account - Posts', requiresAuth: true }
            },
            {
               path: 'account/follow',
               name: 'Follow',
               component: Follow,
               meta: { title: 'Account - Follow', requiresAuth: true }
            },
            {
               path: 'account/follower',
               name: 'Follower',
               component: Follower,
               meta: { title: 'Account - Follower', requiresAuth: true }
            },
          
            {
               path: 'user/:id/follow',
               name: 'userFollower',
               component: userFollower,
               meta: { title: 'User - Follow' }
            },
            {
               path: 'user/:id/follower',
               name: 'userFollow',
               component: userFollow,
               meta: { title: 'User - Follower' }
            },
            {
               path: 'chat',
               name: 'ChatList',
               component: ChatList,
               meta: { title: 'Chat', requiresAuth: true, hideFooter: true }
            },
            {
               path: 'notification',
               name: 'Notification',
               component: NotificationList,
               meta: { title: 'Notification', requiresAuth: true, hideFooter: true }
            },
            {
               path: 'chat/user/:id',
               name: 'ChatWindow',
               component: ChatWindow,
               meta: { title: 'Chat', requiresAuth: true, hideFooter: true }
            },
            {
               path: 'product/recently',
               name: 'RecentlyProduct',
               component: RecentlyProduct,
               meta: { title: 'Product - Recently', showSlider: true, }
            },
            {
               path: 'product/discount',
               name: 'DiscountProduct',
               component: DiscountProduct,
               meta: { title: 'Product - Discount', showSlider: true, }
            },
            {
               path: 'order',
               name: 'Order',
               component: Order,
               meta: { title: 'Account - Order', requiresAuth: true }
            },

         ]
      },
   ],
});



router.beforeEach(async (to, from, next) => {
   document.title = to.meta.title || 'Web title';
   window.scrollTo(0, 0);

   const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
   const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);

   if (requiresAuth || requiresAdmin) {
      try {
         const [authResponse, dashboardResponse] = await Promise.all([
            axios.get('/api/check-auth'),
            requiresAdmin ? axios.get('/api/dashboard') : Promise.resolve()
         ]);
         if (!authResponse.data.authenticated) {
            next({ name: 'Login' });
         } else if (requiresAdmin && authResponse.data.role !== 'admin') {
            next({ name: 'Home' });
         } else {
            next();
         }
      } catch (error) {
         console.error('An error occurred during authentication check:', error);
         next({ name: 'Login' });
      }
   } else {
      next();
   }
});


export default router;
