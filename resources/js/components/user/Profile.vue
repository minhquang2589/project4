<template>
    <div class="mt-3 mx-3">
        <a
            href="javascript:void(0);"
            onclick="window.history.back()"
            class="rounded-xl border border-gray-500 px-1 py-0.5 text-sm"
        >
            <button class="back-button">Back</button>
        </a>
    </div>
    <div class="flex justify-center px-3 mt-3">
        <div class="rounded-xl lg:w-7/12 w-full border border-gray-700 p-4">
            <div class="flex items-center gap-4">
                <img
                    v-if="userImage"
                    alt=""
                    :src="`/images/${userImage}`"
                    class="size-16 border border-gray-400 rounded-full object-cover"
                />
                <img
                    v-else
                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAAAh1BMVEX///8AAAD29vbu7u75+fnj4+P8/Pzn5+fDw8PX19eMjIyNjY3r6+uamppERESmpqa1tbXKysqGhoYiIiLT09Pc3NxAQEAVFRXExMS9vb0bGxsMDAwzMzMoKChPT084ODhfX1+vr69nZ2dra2tXV1efn591dXV+fn4eHh5SUlJxcXEuLi5JSUnfwfXfAAAJNUlEQVR4nO2diZqiOBCAW0DlVvCkvcCzt/X9n29b3ZmdbgnUReL48T8AoTQkddfbW0tLS0tLS0tLS0tLS0tLS0s5PceyrL43Ttwsc5OxF1qW4/RMv5UMXXc4SPfLRecHs80+HQzdrun3Y2DZiZ/Hq5+SfWcV535iW6bfFU+3CNbVov3JNC3+pr/S9qL4HS7dnY9pOrZNvzmIxJ9ihftF7Lum376G3iQYUaW7Mwomjmkp1Aw/edLd2Q5Ny1FO9/BwFZCJnu/I6fs19wGO97RvWqJveCdJ6e4cx6al+k2YzuTl63QWaWhasht21IR0dwLzIvaG5FsPwnRoWCX3/mlSvCv/eAbFc6JGPr7vzAJjN//40rx4V2Iz52kvkrvYa1iY+BO7W13iXcknuuVzdYp3RbOdEeiWr9PxNYrXz/XL92VlaFNP+7EJ+b5OU002xlzb6fmAlg8xMydfZ5E1L9/AnHhXBk8s32h9ioosK6LT5eNpJaSaRnFQfLN8rCygnlTR88k325V5PO3xDu08bVpC0v5895X3V+iTrJHGdilJvm2lu9omORobkjAjvMo5qXtqsiQ8tpHbgnK/HwHRBvuIf+5iLi9fH/8anQD2aMLRtRDXSwn656KAPrzAb45YWkKC/QCW7+1tiH/6VlY+H/8GqKOuwD8fuP9hEOz3FLdCil9B0LTo4lfPsWsQvgEx89DB+5dWaCfRBB+d2kp5vQnHOCGGSThohLTSMeEQp6yDD3IsRDzCDsGuIS08xq8TSziECRt0R/o4ejv8SgKb1CNYNMSdQ/gLZ+zYU48QHxtRFyOkoOy5AhLOts6ButiBsBjTcgoJ8dsP8rbxCD6MKS//i+KE2ZOPNmtPWI51zoSEBbFa6J8QNNJOh5OoQFqQkYlF+eI5PyjlimD9oqQdw7gqCN6SL+jyvb2RFjxSV6O4YRi34BVaMibVfUEw47+YcgTckJYkBn+7tJ9zzREQkef9ByOa6UsMtOQcAYmxcdpdSFuLpx1SM8Moa5HupA7R2P0FNbOPcPdSrLMbZ46AZ+KiBAt0QlzKwD14BZ8JRU/1YdTpWORF8W5gev1DbcRMTUJeFK1e0Jfi2C+M7Gjsz0rTYm4wwiKMDEbkHrUZmdgbcmSrS9PUbsQ4y96jr8Rwk1CC5L/BGU2sdEmyLsPKcMftUV6tANEPZLMWRVkxXUauVYccm+Rl2X5gTIqCtRTRfGH+qpiQOeeSuHGiCMgt8ELsG4ud0kvwA7EO7isXuI7I+9qv5GiF1OLngcPPNoae9gv0OSOQxw/X1rif4BXkbc+64/8D7nuSqBrApSEQUhAeycHLiZQNrBB3RVekABjsLJFZrjMFK919mSLLM/QnlapLmgG/+oSW2vwINMmS6k97YARydmVS8oF9a4KlEZ+1dxMlH1YFNP+PFBVUENdcF5lkHRQwUtijBJLVbCu+jES2yjKHeUcdSpp4Fdt56cI9V7qIdAPLDnDkq68+AvdH3Dd0A9HmFzdmMAHp3tcqlvu0mNg3JkW6l94ld2AqfjMCagEmIC1y/RTA8h/Ylqc5YHY2IenvWYClOQqYu6aAKb/aewDIAas0kDCuDQFzI7y8gC+/RV/+kHn5a+LlL/qXV9VeXtl+eQEdDb3EmgFo8Iq7LLQBdFkIO500AnQ6iboNtQItMDDcE4cO1PEr5rrXDdR1/9dq29Dgi1D4TDvg8JlMAFQ/8GxxI43v+ORgASWSEH6weB+dl/Flvd+vL9PlefTeQHc2eBKCqMm7yYNDkY3737QMpz/OioOfMxJEH4GnkfATga7MlpfjYBJWDZOw7LBbnNZLGe0Xnghk8Xvbxn6RgLOdrKTw+QcbIpWL9xGOLsehha7jdawsvbBGHWCSqwr6MtuCUbvvFYyIPSadkpi6uTzO2c0lnPmRljWDSoglpTTvpEbwhC6lbgpXmInO/csHsj3bC3T8HpffiHQdnhL5RtgJMgMY9+nbiFP7HDQ0bceKEM6TGJmCC78oBg02T+7DbW9sBi5QW3uPGh565URA2w1d8wa6c48aWl/3QVfjCv1cwDmqa4pAAig+x9ei1Je4RtomeVn1dYX4Ete6IuWl1u76dW1WKW2yqn1rGNVdAqt6m5JavFQ90Nc+4cKpvLhIj6zY+I32utb2PupmHUbkq5CQ2KxDqc0wegzxUAVNqLM2FLHstbExQY7ipCGrG+U6hMHReeWdXEnVijfKm1bRn8em1ILi9Dcs3/SNz3xQUW5bcI6E8jZg8l28YSjOBJbGWH4y61Zj7ii8tbw7S9HygdzJjEP5YANm80aVRmrgM1QY9+zBBYr2CxrG53xHkcXKbqCq7G+oee6hws3Hb4Gr1ABXWocCjhWeGQmtWNWG+qxxAGmokE+kDbWykfhU20zAiSKSINNIXG2m0Fv/4FDWMEuZbY4qKWGl5aTxVN3kxJr5q8cxLDXsUqV8kmbNXLUGp6EvDLXvS9Stp3YDN6zTqIMToiNRqjqenRrUvC212154qE3VWCLxCUG/6epctKLYYNHQNh2os6GaMEmrRoOdGlgvrIjwNjEarLoqbSEeqXCr4ncNxUUqw61bUdU0rMxBMDOAcBGJ+UtrxsA3eDNVx+mWmciNYWXVsTKTQ0D3AopNVlO20XBgpC7tYeOynEC2W5dBan4Qb3wgb1RnUJvHqcHb5dYnI58Swt9oAVKb5G+jMiDDQNfq2bTl2BGkYqqR+/0R2LjMVZQAr8YwgWX7NKf0/qQPTAWcfga1Nn/38AnM3dzqDIkg0i3X6dD1wgfvQi/03GGKmC8hbP/VgatvGk3X+e4URIfBcDg4RMFpl6+nuARtTZ/f/0C3qQy5gbByL2qgeKUcQTUXxZhfXgFCV9LfI85BQ8H2zNDfd2fSeEHzXnMY6wHR5oQPTLXHIR+xKWMRgUQaI1gV2L5Yh9A/WaTPId4Vj9vlvISj6Y/vO31ftLL5HWuNaKB7kLv4I4P5cFUMy1NZkOwaj1jR6U0CVo3jl1oeTEze6xCSgHwzxgFjZpNG7EkUoyssP6a+ZyQFjki3CBDG7CUdPumxUoVlJ34e18yjO8e5n1RVoj893flwkOabB7NjtsnTwXD+F/5xZfQcx7JCb5y4WeYmYy+0LMeRrYltaWlpaWlpaWlpaWlpaWl5Jf4FeQSe/KeqeWYAAAAASUVORK5CYII="
                    class="size-16 border border-gray-400 rounded-full object-cover"
                />

                <div class="w-full">
                    <div class="flex justify-between">
                        <div class="text-lg font-medium">{{ userName }}</div>
                        <div>
                            <button
                                @click="follow"
                                class="rounded-xl mr-1 border hover:border-red-500 border-gray-400 px-1 py-0.5 text-sm"
                            >
                                <span class="back-button">{{
                                    isFollowing ? "Unfollow" : "Follow"
                                }}</span>
                            </button>

                            <span
                                @click.prevent="sendMessage"
                                class="rounded-xl border hover:border-red-500 border-gray-400 px-1 py-0.5 text-sm"
                            >
                                <button class="back-button">
                                    Send message
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="flow-root">
                        <ul>
                            <li class="flex">
                                <span
                                    v-for="star in 5"
                                    :key="star"
                                    class="star"
                                    :class="{ filled: star <= rate }"
                                >
                                    ★
                                </span>
                                <span class="ml-2"> {{ totalRates }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <ul class="mt-4 space-y-2">
                <li class="p-1 flex leading-none">
                    <span class="text-xs mr-3 font-medium text-gray-700">
                        Post {{ totalPost }}
                    </span>
                    <span
                        @click="viewUserFollow()"
                        class="text-xs hover:text-blue-700 hover:cursor-pointer mr-3 font-medium text-gray-700"
                    >
                        Follower {{ totalFollow }}
                    </span>
                    <span
                        @click="viewUserFollower()"
                        class="text-xs hover:text-blue-700 hover:cursor-pointer font-medium text-gray-700"
                    >
                        Follow {{ totalFollowing }}
                    </span>
                </li>

                <li>
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
    <h2 class="text-xl flex justify-center mt-4 mb-1 font-bold">Posts</h2>
    <span class="flex items-center">
        <span class="h-px flex-1 bg-gray-300"></span>
    </span>
    <div class="grid grid-cols-1 lg:grid-cols-6 lg:px-2">
        <div class="h-fit rounded-lg lg:col-span-6">
            <div
                class="grid grid-cols-2 lg:mx-6 lg:grid-cols-5 2xl:grid-cols-5 lg:gap-1"
            >
                <div v-for="product in displayedProducts" :key="product.id">
                    <router-link
                        :to="{
                            name: 'ViewProduct',
                            params: { id: product.id },
                        }"
                    >
                        <div class="product-card w-full rounded-lg">
                            <div
                                class="group rounded-xl relative block overflow-hidden"
                            >
                                <div
                                    class="relative h-[175px] xl:h-[300px] lg:h-[240px] 2xl:h-[300px] sm:h-[305px] md:h-[360px]"
                                >
                                    <div
                                        v-if="product.image2 && product.image1"
                                        class="product-container"
                                    >
                                        <img
                                            :src="`/images/${product.image1}`"
                                            :alt="product.name"
                                            class="product-image front"
                                        />

                                        <img
                                            :src="`/images/${product.image2}`"
                                            :alt="product.name"
                                            class="product-image back"
                                        />
                                    </div>
                                    <div v-else>
                                        <img
                                            :src="`/images/${product.image1}`"
                                            :alt="product.name"
                                            class="rounded-t-2xl w-full object-cover transition duration-500 group-hover:scale-105"
                                        />
                                    </div>
                                </div>

                                <div class="relative pt-6 pl-6 pb-3">
                                    <div
                                        class="flex items-baseline lg:items-start"
                                    >
                                        <span
                                            v-if="product.is_new"
                                            class="rounded-full mr-1 text-white bg-red-600 px-1 lg:px-1.5 lg:py-0.5 text-xs font-medium"
                                        >
                                            New
                                        </span>
                                        <span
                                            v-if="product.discount > 0"
                                            class="rounded-full text-white bg-red-600 px-1 lg:px-1.5 lg:py-0.5 text-xs font-medium"
                                        >
                                            - {{ product.discount }} %
                                        </span>
                                    </div>
                                    <h3
                                        class="mt-2 flex text-[11px] lg:text-sm font-medium group-hover:underline group-hover:underline-offset-4"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <p
                                        class="mt-1 text-[11px] lg:text-sm text-gray-600 transition hover:text-gray-800"
                                    >
                                        {{ this.formatCurrency(product.price) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
    <div v-if="!hasMore" class="flex mt-2 w-full items-center justify-center">
        <span class="text-gray-800 text-xs lg:text-sm"> End </span>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions, mapState } from "vuex";
export default {
    name: "userProfile",
    data() {
        return {
            userImage: null,
            userName: null,
            description: null,
            rate: 0,
            totalFollow: null,
            user_id: null,
            totalFollowing: null,
            totalRates: null,
            totalPost: null,
            products: [],
            displayedProducts: [],
            page: 1,
            perPage: 36,
            hasMore: true,
            filters: {},
        };
    },
    mounted() {
        this.fetchIsFollowing();
        this.fetchdata();
        this.fetchdataProducts();
        window.addEventListener("scroll", this.handleScroll);
    },
    computed: {
        ...mapGetters([
            "isLoggedIn",
            "userData",
            "userInformation",
            "formatCurrency",
        ]),
        isFollowing() {
            return this.$store.getters.getIsFollowing;
        },
    },
    methods: {
        ...mapActions(["fetchUser"]),
        sendMessage() {
            this.$router.push({
                name: "ChatWindow",
                params: { id: this.user_id },
            });
        },
        viewUserFollow() {
            this.$router.push({
                name: "userFollow",
                params: { id: this.user_id },
            });
        },
        viewUserFollower() {
            this.$router.push({
                name: "userFollower",
                params: { id: this.user_id },
            });
        },
        fetchdata() {
            axios
                .get(`/api/user-profile/${this.$route.params.id}`)
                .then((response) => {
                    // console.log(response.data);
                    const user = response.data.data;
                    this.user_id = user.id;
                    this.userImage = user.image;
                    this.userName = user.name;
                    this.description = user.descriptions;
                    this.rate = response.data.averageRating || 0;
                    this.totalFollow = response.data.follow;
                    this.totalFollowing = response.data.totalFollowing;
                    this.totalPost = response.data.countProduct;
                    this.totalRates = response.data.totalRates;
                })
                .catch((error) => {
                    console.error("Error 1:", error);
                });
        },
        async fetchIsFollowing() {
            const userId = this.$route.params.id;
            await this.$store.dispatch("fetchIsFollowing", userId);
        },
        async follow() {
            if (!this.isLoggedIn) {
                this.$router.push({ name: "Login" });
                return;
            }
            let formData = new FormData();
            formData.append("user_id", this.user_id);
            axios
                .post("/api/follow", formData)
                .then((response) => {
                    // console.log(response.data);
                    if (response.data) {
                        this.fetchUser();
                        this.fetchdata();
                        this.fetchIsFollowing();
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async fetchdataProducts() {
            try {
                const response = await axios.get(
                    `/api/account/products?page=${this.page}&id=${this.$route.params.id}`
                );
                // console.log(response.data);
                const newProducts = response.data.productViews;
                if (newProducts.length < this.perPage) {
                    this.hasMore = false;
                }
                this.products = this.products.concat(newProducts);
                this.applyFilters();
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        loadMore() {
            if (this.hasMore) {
                this.page += 1;
                this.fetchdataProducts();
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
        updateFilters(filters) {
            console.log(filters);
            this.filters = filters;
            this.applyFilters();
        },
        applyFilters() {
            let filteredProducts = [...this.products];

            if (this.filters.searchKey) {
                filteredProducts = filteredProducts.filter((product) =>
                    product.name
                        .toLowerCase()
                        .includes(this.filters.searchKey.toLowerCase())
                );
            }

            if (this.filters.priceFrom && this.filters.priceTo) {
                filteredProducts = filteredProducts.filter(
                    (product) =>
                        product.price >= this.filters.priceFrom &&
                        product.price <= this.filters.priceTo
                );
            }

            if (this.filters.filternew == true) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.is_new == 1
                );
            }

            if (this.filters.filterDiscount == true) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.discount > 0
                );
            }

            if (this.filters.instock == true) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.total_quantity > 0
                );
            }

            if (this.filters.outofstock == true) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.total_quantity <= 0
                );
            }

            this.displayedProducts = filteredProducts;
        },
    },
};
</script>
<style scoped>
.star {
    font-size: 16px;
    color: #d3d3d3;
}
.star.filled {
    color: #ffd700;
}
</style>
