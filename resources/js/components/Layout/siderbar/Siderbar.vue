<template>
    <div class="flex w-full h-screen flex-col justify-between border-e">
        <div class="px-4 py-6">
            <ul class="space-y-1">
                <li>
                    <div class="text-xl flex justify-start px-4 py-2 font-bold">
                        Page
                    </div>
                </li>
                <li>
                    <p
                        @click="goToAccount(accountId)"
                        class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                    >
                        Account
                    </p>
                </li>
                <li>
                    <router-link to="/profile">
                        <p
                            class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                        >
                            Profile
                        </p>
                    </router-link>
                </li>
                <li>
                    <div class="text-xl flex justify-start px-4 py-2 font-bold">
                        Bill
                    </div>
                </li>
                <li>
                    <router-link to="/order">
                        <p
                            class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                        >
                            Orders
                        </p>
                    </router-link>
                </li>
                <li>
                    <div class="text-xl flex justify-start px-4 py-2 font-bold">
                        Sell
                    </div>
                </li>
                <li>
                    <router-link to="/product/upload">
                        <p
                            class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                        >
                            Upload items
                        </p>
                    </router-link>
                </li>
                <li>
                    <p
                        v-if="isLoggedIn"
                        class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                    >
                        <LogoutBtn />
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import LogoutBtn from "../logout/Logout.vue";
import { useRouter } from "vue-router";
export default {
    name: "Siderbar",
    components: {
        LogoutBtn,
    },
    computed: {
        ...mapGetters(["isLoggedIn", "userName", "accountId"]),
    },
    methods: {
        goToAccount(accountId) {
            this.$router.push({
                name: "Account",
                params: { id: accountId },
            });
        },
    },
};
</script>
