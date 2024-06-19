<template>
    <div class="text-center lg:py-10 pt-8 pb-5">
        <h1 class="text-xl font-bold text-gray-900 sm:text-3xl">Your notifications</h1>
    </div>
    <div class="grid mb-10 grid-cols-1 lg:px-10 px-2 gap-2 lg:grid-cols-4">
        <div class="h-screen  lg:border lg:border-gray-300 rounded-lg">
            <ul>
                <template v-for="(user, index) in chat">
                    <li>
                        <div
                            class="hover:cursor-pointer"
                            @click="selectUser(user.id)"
                        >
                            <div class="rounded-lg hover:bg-gray-200 p-4">
                                <div class="flex items-center gap-4">
                                    <img
                                        :src="`/images/${user.image}`"
                                        :alt="user.name"
                                        class="size-12 rounded-full object-cover"
                                    />

                                    <div>
                                        <h3 class="text-lg font-medium">
                                            {{ user.name }}
                                        </h3>

                                        <ul class="flex flex-wrap">
                                            <li class="p-1 leading-none">
                                                <div
                                                    class="text-[11px] flex text-gray-400 font-medium"
                                                >
                                                    <span class="lg:mr-8 mr-6">
                                                        ...{{
                                                            user.last_message.slice(
                                                                -20
                                                            )
                                                        }}
                                                    </span>
                                                    <span>
                                                        {{
                                                            formatDateTime(
                                                                user.last_time
                                                            )
                                                        }}.
                                                    </span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
        <div
            class=" rounded-lg border border-gray-300  lg:col-span-3"
            v-if="isDesktop"
        >
            <div v-if="selectedUser">
                <ChatWindow :UserId="selectedUser" />
            </div>
            <div
                v-else
                class="flex items-center border justify-center h-full text-gray-500"
            >
                No message
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions, mapState } from "vuex";
import ChatWindow from "../mesage/ChatWindow.vue";

export default {
    name: "NotificationList",
    components: {
        ChatWindow,
    },
    data() {
        return {
            selectedUser: null,
            isDesktop: window.innerWidth >= 1024,
        };
    },
    created() {
        this.$store.dispatch("fetchChat");
    },
    computed: {
        ...mapGetters(["isLoggedIn", "userData", "chat"]),
    },
    mounted() {
        window.addEventListener("resize", this.checkIfDesktop);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.checkIfDesktop);
    },

    methods: {
        ...mapActions(["fetchChat"]),
        checkIfDesktop() {
            this.isDesktop = window.innerWidth >= 1024;
        },
        selectUser(UserId) {
            // console.log(UserId);
            if (this.isDesktop) {
                this.selectedUser = UserId;
            } else {
                this.$router.push(`/chat/user/${UserId}`);
            }
        },
        formatDateTime(dateTimeString) {
            const postDate = new Date(dateTimeString);
            const now = new Date();
            const diffMilliseconds = now - postDate;
            const diffSeconds = Math.floor(diffMilliseconds / 1000);
            const diffMinutes = Math.floor(diffSeconds / 60);
            const diffHours = Math.floor(diffMinutes / 60);
            const diffDays = Math.floor(diffHours / 24);
            const diffWeeks = Math.floor(diffDays / 7);
            if (diffWeeks >= 1) {
                return `${diffWeeks} last week`;
            } else if (diffDays >= 1) {
                return `${diffDays} yesterday`;
            } else if (diffHours >= 1) {
                return `${diffHours} hours ago`;
            } else if (diffMinutes >= 1) {
                return `${diffMinutes} minute ago`;
            } else {
                return `${diffSeconds} seconds ago`;
            }
        },
    },
};
</script>

