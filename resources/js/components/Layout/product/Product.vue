<template>
    <div class="mt-5 mb-2 flex justify-center">
        <div>
            <h1 class="lg:text-xl text-sm font-bold text-gray-900 sm:text-3xl">
                All products
            </h1>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-6 lg:px-2">
        <div class="h-fit moving rounded-lg lg:col-span-6">
            <div
                class="grid grid-cols-2 lg:mx-6 lg:grid-cols-4 2xl:grid-cols-5 lg:gap-1"
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
                                        {{ formatCurrency(product.price) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </router-link>
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
export default {
    name: "Products",
    components: {},
    data() {
        return {
            category: "clothes",
            products: [],
            displayedProducts: [],
            page: 1,
            perPage: 36,
            hasMore: true,
            filters: {},
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
        async fetchData() {
            try {
                const response = await axios.get(
                    `/api/products/home?page=${this.page}`
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
        formatCurrency(value) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND",
            }).format(value);
        },
    },
};
</script>
