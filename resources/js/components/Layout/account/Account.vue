<template>
    <div class="grid lg:px-10 px-2 grid-cols-1 gap-1 lg:grid-cols-9">
        <div class="h-fit hidden lg:block standing rounded-lg lg:col-span-2">
            <Siderbar />
        </div>
        <div class="h-full rounded-lg lg:col-span-7">
            <div>
                <div class="mt-3 mx-3 block lg:hidden">
                    <a
                        href="javascript:void(0);"
                        onclick="window.history.back()"
                        class="rounded-xl border border-gray-500 px-1 py-0.5 text-sm"
                    >
                        <button class="back-button">Back</button>
                    </a>
                </div>
                <h2
                    class="text-xl flex justify-center mt-0 lg:mt-6 lg:mb-5 font-bold"
                >
                    {{ pageTitle }}
                </h2>
            </div>
            <div v-if="$slots.default">
                <slot></slot>
            </div>
            <div v-else>
                <div class="flex justify-center px-3 mt-3">
                    <div
                        class="rounded-xl lg:w-10/12 w-full border border-gray-700 p-4"
                    >
                        <div class="flex items-center gap-4">
                            <img
                                v-if="userImage"
                                alt=""
                                :src="`/images/${userImage}`"
                                class="size-16 border border-gray-400 rounded-full object-cover"
                            />
                            <img
                                v-else
                                src="data:image/png;base64,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"
                                class="size-16 border border-gray-400 rounded-full object-cover"
                            />

                            <div class="w-full">
                                <div class="flex justify-between">
                                    <div class="text-lg font-medium">
                                        {{ userName }}
                                    </div>
                                    <router-link to="/account/settings">
                                        <button
                                            class="block lg:hidden rounded-xl border border-gray-500 px-4 py-1.5 text-sm"
                                        >
                                            Menu
                                        </button>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <ul class="mt-4 space-y-2">
                            <li class="p-1 flex leading-none">
                                <router-link to="/account/posts">
                                    <span
                                        class="text-xs mr-3 hover:text-blue-700 font-medium text-gray-700"
                                    >
                                        Post {{ userInfor.countProduct }}
                                    </span>
                                </router-link>
                                <router-link to="/account/follower">
                                    <span
                                        class="text-xs mr-3 hover:text-blue-700 font-medium text-gray-700"
                                    >
                                        Follower {{ userInfor.follow }}
                                    </span>
                                </router-link>

                                <router-link to="/account/follow">
                                    <span
                                        class="text-xs hover:text-blue-700 font-medium text-gray-700"
                                    >
                                        Follow {{ userInfor.totalFollowing }}
                                    </span>
                                </router-link>
                            </li>

                            <li v-if="description">
                                <div href="#" class="block h-full rounded-lg">
                                    <p
                                        v-html="description"
                                        class="mt-1 text-xs font-medium"
                                    ></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-xl flex justify-start px-4 py-2 font-bold">
                    Posts
                </div>
                <ul
                    v-if="postProducts != null && postProducts.length > 0"
                    class="space-y-2 lg:px-8 px-4 mt-3"
                >
                    <li
                        v-for="item in postProducts"
                        :key="item.id"
                        @click="viewProduct(item.id)"
                        class="flex flex-col gap-2 px-2 hover:cursor-pointer rounded-lg border hover:border-red-500"
                    >
                        <div class="flex items-center gap-2">
                            <img
                                :src="`/images/${item.image}`"
                                :alt="item.name"
                                class="lg:size-28 size-20 rounded object-cover"
                            />
                            <div>
                                <h3 class="lg:text-sm text-xs text-gray-900">
                                    {{ item.name }}
                                </h3>
                                <dl
                                    class="mt-0.5 space-y-px text-[10px] text-gray-600"
                                >
                                    <div>
                                        <dt class="inline mr-1">Price:</dt>
                                        <dd class="inline">
                                            {{
                                                this.formatCurrency(item.price)
                                            }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </li>
                    <li class="flex justify-center">
                        <div class="">
                            <router-link to="/account/posts">
                                <h4
                                    class="text-sm font-bold hover:cursor-pointer hover:text-blue-700"
                                >
                                    see all
                                </h4>
                            </router-link>
                        </div>
                    </li>
                </ul>
                <ul v-else>
                    <li class="flex justify-center">
                        <router-link to="/product/upload">
                            <a
                                class="inline-block rounded-xl border border-current px-5 py-1.5 lg:py-2 text-sm font-medium transition hover:scale-110 hover:shadow-xl focus:outline-none focus:ring active:text-indigo-500"
                                href="#"
                            >
                                Upload items
                            </a>
                        </router-link>
                    </li>
                </ul>
                <div class="text-xl flex justify-start px-4 py-2 font-bold">
                    Liked product
                </div>
                <ul class="space-y-2 lg:px-8 px-4 mt-3">
                    <li
                        v-if="likedProducts != null"
                        v-for="item in likedProducts"
                        :key="item.id"
                        @click="viewProduct(item.id)"
                        class="flex flex-col gap-2 px-2 hover:cursor-pointer rounded-lg border hover:border-red-500"
                    >
                        <div class="flex items-center gap-2">
                            <img
                                :src="`/images/${item.image}`"
                                :alt="item.name"
                                class="lg:size-28 size-20 rounded object-cover"
                            />
                            <div>
                                <h3 class="lg:text-sm text-xs text-gray-900">
                                    {{ item.name }}
                                </h3>
                                <dl
                                    class="mt-0.5 space-y-px text-[10px] text-gray-600"
                                >
                                    <div>
                                        <dt class="inline mr-1">Price:</dt>
                                        <dd class="inline">
                                            {{
                                                this.formatCurrency(item.price)
                                            }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </li>
                    <li class="flex justify-center">
                        <div class="">
                            <router-link to="/account/like">
                                <h4
                                    class="text-sm font-bold hover:cursor-pointer hover:text-blue-700"
                                >
                                    see all
                                </h4>
                            </router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import Siderbar from "../siderbar/Siderbar.vue";
import LogoutBtn from "../logout/Logout.vue";

export default {
    name: "Account",
    components: {
        Siderbar,
        LogoutBtn,
    },
    props: {
        pageTitle: {
            type: String,
            default: "Account",
        },
    },

    data() {
        return {
            errorMessages: [],
            name: null,
            address: null,
            userName: null,
            gender: null,
            email: null,
            userImage: null,
            description: "",
            likedProducts: [],
            postProducts: [],
        };
    },
    mounted() {
        this.getData();
        this.getPost();
    },
    computed: {
        ...mapGetters([
            "isLoggedIn",
            "userData",
            "userInfor",
            "formatCurrency",
        ]),
    },
    methods: {
        viewProduct(productId) {
            this.$emit("viewProduct", productId);
            this.$router.push({
                name: "ViewProduct",
                params: { id: productId },
            });
        },
        async getData() {
            try {
                const response = await axios.get(
                    `/api/account/${this.userData.id}`
                );
                // console.log(response.data);
                if (response.data.success == true) {
                    const user = response.data.user;
                    this.address = user.address;
                    this.userName = user.name;
                    this.gender = user.gender;
                    this.email = user.email;
                    this.userImage = user.image;
                    this.description = user.descriptions;
                    this.likedProducts = response.data.likedProducts;
                } else {
                    console.log("error.");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        },
        async getPost() {
            try {
                const response = await axios.get(
                    `/api/account/post/${this.userData.id}`
                );
                // console.log(response.data);
                if (response.data.success == true) {
                    this.postProducts = response.data.postProducts;
                } else {
                    console.log("error.");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        },
    },
};
</script>
