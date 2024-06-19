<template>
    <div class="text-xl flex justify-center lg:mt-10 mt-4 px-4 py-2 font-bold">
        Post products
    </div>
    <div class="flex justify-center">
        <div class="lg:w-1/2 w-full">
            <ul class="space-y-2 lg:px-8 px-4 mt-3">
                <li
                    v-if="products != null"
                    v-for="item in products"
                    :key="item.id"
                    @click="viewProduct(item.id)"
                    class="flex flex-col w-full px-3 gap-2 hover:cursor-pointer rounded-lg border hover:border-red-500"
                >
                    <div class="flex items-center gap-2">
                        <img
                            :src="`/images/${item.image}`"
                            :alt="item.name"
                            class="lg:w-28 w-20 rounded hover:cursor-pointer object-cover"
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
                                        {{ this.formatCurrency(item.price) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div v-if="hasMore" class="flex mt-2 w-full items-center justify-center">
        <button @click="loadMore" class="text-gray-800 text-xs lg:text-sm">
            Loading...
        </button>
    </div>
    <div v-else class="flex mt-2 w-full items-center justify-center">
        <span class="text-gray-800 text-xs lg:text-sm"> End </span>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";

export default {
    name: "userPosts",
    emits: ["viewProduct"],
    data() {
        return {
            products: [],
            page: 1,
            perPage: 36,
            hasMore: true,
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
        ...mapGetters(["accountId", "userData", "formatCurrency"]),
    },
    methods: {
        viewProduct(productId) {
            this.$emit("viewProduct", productId);
            this.$router.push({
                name: "ViewProduct",
                params: { id: productId },
            });
        },
        async fetchdata() {
            try {
                const response = await axios.get(
                    `/api/user/post/${this.page}/${this.accountId}`
                );
                // console.log(response.data);
                const newProducts = response.data.postProducts;
                if (response.data.hasMore === false) {
                    this.hasMore = false;
                }
                this.products = this.products.concat(newProducts);
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
