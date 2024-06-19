<template>
    <div class="my-5 mb-3 mt-2 mx-3 flex justify-center">
        <div>
            <h1 class="text-xl font-bold mt-7 text-gray-900 sm:text-3xl">
                Related product
            </h1>
        </div>
    </div>
    <span class="flex items-center">
        <span class="h-px flex-1 bg-gray-300"></span>
    </span>
    <div class="h-fit moving rounded-lg lg:col-span-5">
        <div
            class="grid grid-cols-2 lg:mx-6 lg:grid-cols-5 2xl:grid-cols-5 gap-1 lg:gap-4"
        >
            <div
                class="hover:cursor-pointer"
                v-for="product in products"
                :key="product.id"
            >
                <div
                    class="product-card w-full rounded-lg"
                    @click="viewProduct(product.id)"
                >
                    <div class="product-card w-full rounded-lg">
                        <div
                            class="group rounded-xl relative block overflow-hidden"
                        >
                            <div
                                class="relative h-[175px] lg:h-[215px] xl:h-[310px] 2xl:h-[250px] sm:h-[305px] md:h-[360px]"
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
                                <div class="flex items-baseline lg:items-start">
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
                                    {{ formatCurrency(product.price) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
import axios from "axios";
import { mapActions, mapState } from "vuex";
export default {
    name: "RelatedProduct",
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
        this.fetchData();
        window.addEventListener("scroll", this.handleScroll);
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    methods: {
        viewProduct(productId) {
            this.$emit("viewProduct", productId);
            this.$router.push({
                name: "ViewProduct",
                params: { id: productId },
            });
        },
        async fetchData() {
            try {
                const response = await axios.get(
                    `/api/products/related?page=${this.page}`
                );
                //  console.log(response.data)
                const newProducts = response.data.productsMore;
                if (response.data.hasMore == false) {
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
                this.fetchData();
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
        formatCurrency(value) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND",
            }).format(value);
        },
    },
};
</script>
