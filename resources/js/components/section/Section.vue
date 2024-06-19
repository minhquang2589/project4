<template>
    <div v-if="$route.meta.showSection">
        <div>
            <div class="mx-auto max-w-screen-2xl px-4 py-2 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:h-screen lg:grid-cols-2">
                    <div class="relative image_section lg:py-16">
                        <div
                            @click="navigateToUrl(section.link_url)"
                            class="relative h-64 sm:h-80 hover:cursor-pointer lg:h-full"
                        >
                            <img
                                alt=""
                                :src="`/images/${section.image}`"
                                class="absolute inset-0 h-full w-full object-cover"
                            />
                        </div>
                    </div>

                    <div class="relative flex items-center bg-gray-100">
                        <span
                            class="hidden lg:absolute lg:inset-y-0 lg:-start-16 lg:block lg:w-16 lg:bg-gray-100"
                        ></span>

                        <div class="p-8 sm:p-16 lg:p-24">
                            <h2 class="text-2xl font-bold sm:text-3xl">
                                {{ section.name }}
                            </h2>

                            <p class="mt-4 text-gray-600">
                                {{ section.description }}
                            </p>

                            <span
                                @click="navigateToUrl(section.link_url)"
                                class="lg:mt-8 mt-5 hover:cursor-pointer inline-block rounded border bg-black px-12 py-3 text-sm font-medium text-white"
                            >
                                Shopping
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    name: "Section",
    data() {
        return {
            section: {},
        };
    },
    mounted() {
        axios
            .get("/api/section")
            .then((response) => {
                //  console.log(response.data);
                this.section = response.data.section;
            })
            .catch((error) => {
                //  console.error("Error fetching section 02 data:", error);
            });
    },
    methods: {
        navigateToUrl(url) {
            if (url) {
                window.location.href = url;
            }
        },
    },
};
</script>

<style scoped>
.image_section {
    z-index: 1;
}
</style>
