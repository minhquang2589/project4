<template>
    <div class="text-xl flex justify-center lg:mt-10 mt-4 px-4 py-2 font-bold">
        Follower
    </div>
    <div class="flex justify-center">
        <div class="lg:w-1/2 w-full">
            <ul class="space-y-2 lg:px-8 px-4 mt-3">
                <li
                    v-if="dataFollowUser != null"
                    v-for="item in dataFollowUser"
                    :key="item.id"
                    @click="viewUser(item.id)"
                    class="flex flex-col w-full px-3 gap-2 hover:cursor-pointer rounded-lg border hover:border-red-500"
                >
                    <div class="flex items-center gap-2">
                        <img
                            :src="`/images/${item.image}`"
                            :alt="item.name"
                            class="lg:size-24 size-20 border my-2 border-gray-400 rounded-full object-cover"
                        />
                        <div class="w-full">
                            <div class="text-lg font-medium">
                                {{ item.name }}
                            </div>
                            <div>
                                <ul class="flex">
                                    <li class="p-1 leading-none">
                                        <span
                                            class="text-xs font-medium text-gray-700"
                                        >
                                            Post {{ item.total_products }}
                                        </span>
                                    </li>
                                    <li class="p-1 leading-none">
                                        <span
                                            class="text-xs font-medium text-gray-700"
                                        >
                                            Follower {{ item.follower }}
                                        </span>
                                    </li>
                                    <li class="p-1 leading-none">
                                        <span
                                            class="text-xs font-medium text-gray-700"
                                        >
                                            Follow {{ item.follow }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div v-if="!hasMore" class="flex mt-2 w-full items-center justify-center">
        <span class="text-gray-800"> End </span>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";

export default {
    name: "userFollow",
    emits: ["viewProduct"],
    data() {
        return {
            dataFollowUser: [],
            page: 1,
            perPage: 36,
            hasMore: true,
            userId: null,
        };
    },
    mounted() {
        this.fetchdata();
        window.addEventListener("scroll", this.handleScroll);
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    computed: {
        ...mapGetters([
            "accountId",
            "isLoggedIn",
            "userData",
            "formatCurrency",
        ]),
    },
    methods: {
        viewUser(userId) {
            if (this.isLoggedIn && this.userData.id === userId) {
                this.$router.push({
                    name: "Account",
                    params: { id: userId },
                });
            } else {
                this.$router.push({
                    name: "userProfile",
                    params: { id: userId },
                });
            }
        },
        async fetchdata() {
            try {
                const response = await axios.get(
                    `/api/user/follow/${this.page}/${this.$route.params.id}`
                );
                // console.log(response.data);
                const newdataFollowUser = response.data.data;
                if (response.data.hasMore === false) {
                    this.hasMore = false;
                }
                this.dataFollowUser =
                    this.dataFollowUser.concat(newdataFollowUser);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        loadMore() {
            if (this.hasMore) {
                this.page += 1;
                this.fetchdata();
            }
        },
        handleScroll() {
            const bottomOfWindow =
                window.innerHeight + window.scrollY >=
                document.documentElement.offsetHeight - 40;
            if (bottomOfWindow && this.hasMore) {
                this.loadMore();
            }
        },
    },
};
</script>
