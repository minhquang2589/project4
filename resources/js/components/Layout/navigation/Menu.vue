<template>
    <Account page-title="Menu">
        <div class="flex w-full h-screen flex-col justify-between border-e">
            <div class="px-4 py-6">
                <ul class="space-y-1">
                    <li>
                        <div
                            class="text-xl flex justify-start px-4 py-2 font-bold"
                        >
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
                        <div
                            class="text-xl flex justify-start px-4 py-2 font-bold"
                        >
                            sell
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
                        <a
                            v-if="isLoggedIn"
                            href=""
                            class="block hover:cursor-pointer hover:bg-gray-300 rounded-lg text-gray-800 px-4 py-2 text-sm font-medium"
                        >
                            <LogoutBtn />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </Account>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import Account from "../account/Account.vue";
import LogoutBtn from "../logout/Logout.vue";


import { useRouter } from "vue-router";

export default {
    name: "Menu",
    components: { Account,LogoutBtn },
    computed: {
        ...mapGetters(["isLoggedIn", "userName", "accountId", "userData"]),
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
