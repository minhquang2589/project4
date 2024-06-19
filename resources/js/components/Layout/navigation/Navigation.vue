<template>
    <div class="navigation">
        <router-link to="/">
            <div
                class="shrink-0 hover:cursor-pointer rounded-lg p-2 text-sm font-medium text-black hover:bg-gray-50 hover:text-gray-700"
            >
                <svg
                    width="24"
                    height="24"
                    xmlns="http://www.w3.org/2000/svg"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                >
                    <path
                        d="M22 11.414v12.586h-20v-12.586l-1.293 1.293-.707-.707 12-12 12 12-.707.707-1.293-1.293zm-6 11.586h5v-12.586l-9-9-9 9v12.586h5v-9h8v9zm-1-7.889h-6v7.778h6v-7.778z"
                    />
                </svg>
            </div>
        </router-link>

        <div
            class="shrink-0 hover:cursor-pointer rounded-lg p-2 text-sm font-medium text-black hover:bg-gray-50 hover:text-gray-700"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
            >
                <path
                    d="M23.832 19.641l-6.821-6.821c2.834-5.878-1.45-12.82-8.065-12.82-4.932 0-8.946 4.014-8.946 8.947 0 6.508 6.739 10.798 12.601 8.166l6.879 6.879c1.957.164 4.52-2.326 4.352-4.351zm-14.886-4.721c-3.293 0-5.973-2.68-5.973-5.973s2.68-5.973 5.973-5.973c3.294 0 5.974 2.68 5.974 5.973s-2.68 5.973-5.974 5.973z"
                />
            </svg>
        </div>
        <router-link to="/product/upload">
            <div
                class="shrink-0 hover:cursor-pointer rounded-lg p-2 text-sm font-medium text-black hover:bg-gray-50 hover:text-gray-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"
                    />
                </svg>
            </div>
        </router-link>
        <router-link to="/chat">
            <div class="relative">
                <div
                    class="shrink-0 relative hover:cursor-pointer rounded-lg p-2 text-sm font-medium text-black hover:bg-gray-50 hover:text-gray-700"
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
                    <span v-if="isLoggedIn && chatQuantity >0" class="message-count">{{
                        chatQuantity
                    }}</span>
                </div>
            </div>
        </router-link>

        <div
            v-if="isLoggedIn"
            @click="goToAccount(accountId)"
            class="flex items-center sm:gap-1 hover:cursor-pointer"
        >
            <img
                v-if="userData.image"
                alt=""
                :src="`/images/${userData.image}`"
                class="size-7 border border-gray-400 rounded-full object-cover"
            />
            <img
                v-else
                src="data:image/png;base64,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"
                class="size-7 border border-gray-400 rounded-full object-cover"
            />
        </div>
        <div
            v-if="!isLoggedIn"
            @click="goToAccount(accountId)"
            class="flex items-center sm:gap-1 hover:cursor-pointer"
        >
            <img
                src="data:image/png;base64,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"
                class="size-7 border border-gray-400 rounded-full object-cover"
            />
        </div>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import { useRouter } from "vue-router";

export default {
    name: "Navigation",
    components: {},
    computed: {
        ...mapGetters([
            "isLoggedIn",
            "userName",
            "accountId",
            "userData",
            "chatQuantity",
        ]),
    },

    methods: {
        ...mapActions(["fetchChat"]),

        goToAccount(accountId) {
            if (!this.isLoggedIn) {
                this.$router.push({
                    name: "Login",
                });
            } else {
                this.$router.push({
                    name: "Account",
                    params: { id: accountId },
                });
            }
        },
    },
};
</script>
<style>
.navigation {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    padding: 6px 0;
    width: 100%;
    bottom: 0;
    background: white;
    position: fixed;
    z-index: 6666 !important;
}

.message-count {
    position: absolute;
    top: -2px;
    right: -4px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}
</style>
