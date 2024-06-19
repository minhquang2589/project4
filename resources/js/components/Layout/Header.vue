<template>
    <header class="bg-white">
        <div v-if="userData.role == `admin`">
            <HeaderAdmin />
        </div>
        <div
            v-show="isViewCartOpen"
            class="overlay"
            @click="toggleViewCart"
        ></div>
        <div :class="{ 'view-cart-open': isViewCartOpen }" class="view-cart">
            <ViewCart :toggleViewCart="toggleViewCart" />
        </div>

        <div
            v-show="isViewNotificationOpen"
            class="overlay"
            @click="toggleViewNotification"
        ></div>
        <div
            :class="{ 'view-cart-open': isViewNotificationOpen }"
            class="view-cart"
        >
            <viewNotification
                :toggleViewNotification="toggleViewNotification"
            />
        </div>

        <div
            class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8"
        >
            <router-link to="/">
                <a class="block">
                    <img src="" alt="Logo" />
                </a>
            </router-link>
            <div
                class="flex flex-1 items-center justify-end md:justify-between"
            >
                <nav aria-label="Global" class="hidden md:block">
                    <ul class="flex items-center gap-6 text-sm">
                        <li>
                            <a
                                class="text-gray-500 transition hover:text-gray-500/75"
                                href="#"
                            >
                                About
                            </a>
                        </li>

                        <li>
                            <a
                                class="text-gray-500 transition hover:text-gray-500/75"
                                href="#"
                            >
                                Careers
                            </a>
                        </li>

                        <li>
                            <a
                                class="text-gray-500 transition hover:text-gray-500/75"
                                href="#"
                            >
                                History
                            </a>
                        </li>

                        <li>
                            <a
                                class="text-gray-500 transition hover:text-gray-500/75"
                                href="#"
                            >
                                Services
                            </a>
                        </li>

                        <li>
                            <a
                                class="text-gray-500 transition hover:text-gray-500/75"
                                href="#"
                            >
                                Projects
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="flex">
                        <div class="flex mr-5 items-center relative">
                            <button
                                @click="toggleViewNotification"
                                class="relative"
                            >
                                <svg
                                    clip-rule="evenodd"
                                    fill-rule="evenodd"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="2"
                                    width="38"
                                    height="30"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="m2 4v16.002c0 .385.22.735.567.902.346.166.758.119 1.058-.121l4.725-3.781h12.65c.552 0 1-.448 1-1v-12.002c0-.552-.448-1-1-1h-18c-.552 0-1 .448-1 1zm18.5 11.502h-12.677l-4.323 3.46v-14.462h17zm-8.502-6.5c.414 0 .75.336.75.75v3.5c0 .414-.336.75-.75.75s-.75-.336-.75-.75v-3.5c0-.414.336-.75.75-.75zm.002-3c.552 0 1 .448 1 1s-.448 1-1 1-1-.448-1-1 .448-1 1-1z"
                                        fill-rule="nonzero"
                                    />
                                </svg>
                                <span
                                    v-if="isLoggedIn"
                                    class="notification-count"
                                    >10</span
                                >
                            </button>
                        </div>
                        <div class="mr-5 hidden lg:block items-center relative">
                            <router-link to="/chat">
                                <button
                                    class="relative"
                                >
                                    <svg
                                        width="24"
                                        height="24"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                    >
                                        <path
                                            d="M12 0c-6.627 0-12 4.975-12 11.111 0 3.497 1.745 6.616 4.472 8.652v4.237l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111 0-6.136-5.373-11.111-12-11.111zm1.193 14.963l-3.056-3.259-5.963 3.259 6.559-6.963 3.13 3.259 5.889-3.259-6.559 6.963z"
                                        />
                                    </svg>
                                    <span v-if="isLoggedIn && chatQuantity >0" class="cart-count"
                                        >{{chatQuantity}}</span
                                    >
                                </button>
                            </router-link>
                        </div>

                        <div class="relative flex items-center">
                            <button @click="toggleViewCart">
                                <svg
                                    width="24"
                                    height="24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                >
                                    <path
                                        d="M13.5 21c-.276 0-.5-.224-.5-.5s.224-.5.5-.5.5.224.5.5-.224.5-.5.5m0-2c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5m-6 2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5.5.224.5.5-.224.5-.5.5m0-2c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5m16.5-16h-2.964l-3.642 15h-13.321l-4.073-13.003h19.522l.728-2.997h3.75v1zm-22.581 2.997l3.393 11.003h11.794l2.674-11.003h-17.861z"
                                    />
                                </svg>
                                <span v-if="cartQuantity" class="cart-count">{{
                                    cartQuantity
                                }}</span>
                            </button>
                        </div>
                    </div>
                    <div v-if="!isLoggedIn" class="sm:flex sm:gap-1">
                        <router-link to="/login">
                            <a
                                class="block rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-700"
                                href="#"
                            >
                                Login
                            </a>
                        </router-link>
                        <router-link to="/register">
                            <a
                                class="hidden rounded-xl bg-gray-400 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-900 sm:block"
                                href="#"
                            >
                                Register
                            </a>
                        </router-link>
                    </div>
                    <div class="hidden lg:block">
                        <div
                            v-if="isLoggedIn"
                            @click="goToAccount(accountId)"
                            class="flex items-center sm:gap-1 hover:cursor-pointer"
                        >
                            <span class="text-black">{{
                                userData.name.slice(-6)
                            }}</span>
                            <img
                                v-if="userData.image"
                                alt=""
                                :src="`/images/${userData.image}`"
                                class="size-9 border border-gray-400 rounded-full object-cover"
                            />
                            <img
                                v-else
                                src="data:image/png;base64,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"
                                class="size-9 border border-gray-400 rounded-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="flex items-center">
            <span class="h-px flex-1 bg-gray-300"></span>
        </span>
    </header>
</template>
<script>
import { mapState, mapGetters, mapMutations, mapActions } from "vuex";
import LogoutBtn from "./logout/Logout.vue";
import HeaderAdmin from "../admin/HeaderAdmin.vue";
import { useRouter } from "vue-router";
import ViewCart from "../cart/ViewCart.vue";
import viewNotification from "../notification/View.vue";

export default {
    name: "Header",
    components: {
        LogoutBtn,
        HeaderAdmin,
        ViewCart,
        viewNotification,
    },
    data() {
        return {
            isViewCartOpen: false,
            isViewNotificationOpen: false,
        };
    },
    created() {
        this.fetchCartQuantity();
        this.fetchChat();
    },
    computed: {
        ...mapGetters([
            "isLoggedIn",
            "userName",
            "accountId",
            "userData",
            "cartQuantity",
            "chatQuantity"
        ]),
    },
    methods: {
        ...mapActions(["fetchCart", "fetchCartData", "fetchCartQuantity","fetchChat"]),
        toggleViewCart() {
            this.isViewCartOpen = !this.isViewCartOpen;
            this.fetchCart();
            this.fetchCartData();
        },
        toggleViewNotification() {
            this.isViewNotificationOpen = !this.isViewNotificationOpen;
        },
        goToAccount(accountId) {
            this.$router.push({
                name: "Account",
                params: { id: accountId },
            });
        },
    },
};
</script>
<style scoped>
header {
    top: 0;
    left: 0;
    transition: top 1s;
    background: white;
    background-color: rgba(255, 255, 255, 0.8);
    transition: background-color 0.6s ease;
    position: sticky;
    z-index: 10;
}

.notification-count {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}

.cart-count {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}
</style>
