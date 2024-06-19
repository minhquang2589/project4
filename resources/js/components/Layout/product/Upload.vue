<template>
    <Account page-title="Upload items">
        <form class="max-w-md mx-auto" @submit.prevent="upload">
            <div class="max-w-lg">
                <label
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    for="image"
                    >Images</label
                >
                <input
                    require
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    aria-describedby="images"
                    for="images"
                    type="file"
                    id="image-input"
                    name="images[]"
                    multiple
                    @change="handleImageUpload"
                />
            </div>
            <div class="relative z-0 mb-2 mt-4 group">
                <label
                    for="Username"
                    class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                >
                    <input
                        type="text"
                        id="product_name"
                        name="product_name"
                        v-model="product_name"
                        class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        placeholder=" Product Name"
                    />
                    <span
                        class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                    >
                        Product Name
                    </span>
                </label>
            </div>
            <div class="relative z-0 w-full mb-2 group">
                <label
                    for="Username"
                    class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                >
                    <input
                        require
                        type="text"
                        name="price"
                        id="price"
                        v-model="price"
                        class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        placeholder=" Price"
                    />
                    <span
                        class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                    >
                        Price
                    </span>
                </label>
            </div>
            <div class="relative z-0 w-full mb-2 group">
                <label
                    for="Username"
                    class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                >
                    <input
                        type="text"
                        id="category"
                        for="category"
                        name="category"
                        v-model="category"
                        class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        placeholder=" category"
                    />
                    <span
                        class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                    >
                        category
                    </span>
                </label>
            </div>
            <div class="relative z-0 w-full mb-2 group">
                <label
                    for="Username"
                    class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                >
                    <input
                        type="text"
                        id="brand"
                        for="brand"
                        name="brand"
                        v-model="brand"
                        class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        placeholder="Product brand"
                    />
                    <span
                        class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                    >
                        Product brand
                    </span>
                </label>
            </div>
            <div class="mt-4">
                <div class="w-full mr-5">
                    <div class="">
                        <select
                            id="gender"
                            for="gender"
                            name="gender"
                            v-model="gender"
                            class="block w-full px-3 sm:px-3 lg:px-5 pt-2 pb-1 text-sm text-grey-darker border border-gray-400 rounded focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option hidden selected disabled>
                                Choose a gender
                            </option>
                            <option for="gender" value="Men">Men</option>
                            <option for="gender" value="Women">Women</option>
                            <option for="gender" value="Unisex">Unisex</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full mr-5">
                    <div class="">
                        <select
                            id="status"
                            for="status"
                            name="status"
                            v-model="status"
                            class="block w-full px-3 sm:px-3 lg:px-5 pt-2 pb-1 text-sm text-grey-darker border border-gray-400 rounded focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option hidden selected disabled>
                                Choose a old or new
                            </option>
                            <option for="status" value="New">New</option>
                            <option for="status" value="Old">Old</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex my-3">
                    <label
                        for="Username"
                        class="relative mr-1 w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                    >
                        <input
                            type="number"
                            name="discountnumber"
                            placeholder="Discount %"
                            min="1"
                            max="100"
                            v-model="discountnumber"
                            oninput="validity.valid||(value='');"
                            class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        />
                        <span
                            class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                        >
                            Discount %
                        </span>
                    </label>
                    <label
                        for="Username"
                        class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                    >
                        <input
                            type="number"
                            name="discountquantity"
                            placeholder="Discount Quantity"
                            min="0"
                            v-model="discountquantity"
                            oninput="validity.valid||(value='');"
                            class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        />
                        <span
                            class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                        >
                            Discount Quantity
                        </span>
                    </label>
                </div>
            </div>

            <div
                class="relative z-0 w-full mb-2 mt-3 group"
                v-for="(detail, index) in details"
                :key="index"
            >
                <label
                    :for="'detail' + index"
                    :name="'detail' + index"
                    class="relative w-full block rounded-md border border-gray-400 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600"
                >
                    <input
                        type="text"
                        :name="'detail' + index"
                        :id="'detail' + index"
                        v-model="details[index]"
                        class="peer border-none w-full py-1 bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0"
                        placeholder=" category"
                    />
                    <span
                        class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs"
                    >
                        Details
                    </span>
                </label>
            </div>
            <button
                class="block mr-2 mt-1 rounded-xl bg-gray-400 px-6 py-1 text-sm text-white transition hover:bg-gray-500"
                type="button"
                @click="addDetailInput"
            >
                Add Detail
            </button>
            <div class="colorInputs mt-3" id="colorInputs">
                <div v-for="(color, colorIndex) in colors" :key="colorIndex">
                    <label>
                        Color:
                        <input
                            class="border mt-1 rounded border-gray-600"
                            type="text"
                            v-model="color.name"
                        />
                    </label>
                    <div
                        v-for="(size, sizeIndex) in color.sizes"
                        :key="sizeIndex"
                    >
                        <label class="flex">
                            Size:
                            <input
                                class="border rounded border-gray-600"
                                type="text"
                                v-model="size.name"
                            />
                        </label>
                        <label class="flex">
                            Quantity:
                            <input
                                type="number"
                                class="border mt-1 rounded border-gray-600"
                                v-model="size.quantity"
                            />
                        </label>
                        <br />
                    </div>
                    <button
                        @click="addSizeInput(colorIndex)"
                        class="block mr-2 mt-1 rounded-xl bg-gray-400 px-7 py-1 text-sm text-white transition hover:bg-gray-500"
                        type="button"
                    >
                        Add size
                    </button>
                </div>
            </div>
            <button
                class="block mr-2 mt-1 rounded-xl bg-gray-400 px-6 py-1 text-sm text-white transition hover:bg-gray-500"
                type="button"
                @click="addColorInput"
            >
                Add Color
            </button>
            <div class="mt-3">
                <textarea
                    class="w-full"
                    name="description"
                    v-model="description"
                    id="editor"
                ></textarea>
            </div>
            <div
                v-if="errorMessages.length"
                class="error-messages mt-1 text-xs text-red-600"
            >
                <ul>
                    <li
                        class="mt-1"
                        v-for="(error, index) in errorMessages"
                        :key="index"
                    >
                        {{ error }}
                    </li>
                </ul>
            </div>

            <div class="mb-5 mt-2 w-full">
                <div class="flex py-3 justify-start lg:flex lg:justify-start">
                    <button
                        type="submit"
                        class="block mr-2 rounded-xl bg-gray-800 px-8 py-2 text-sm text-white transition hover:bg-black"
                    >
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </Account>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import Account from "../account/Account.vue";

import { useRouter } from "vue-router";

export default {
    name: "uploadProduct",
    components: { Account },
    data() {
        return {
            errorMessages: [],
            colorCounter: 0,
            colors: [
                {
                    name: "",
                    sizes: [{ name: "", quantity: null }],
                },
            ],
            product_name: "",
            price: "",
            details: [],
            description: "",
            gender: "",
            start_datetime: "",
            end_datetime: "",
            discountnumber: "",
            discountquantity: "",
            imageUrl: "",
            class: "",
            category: "",
            brand: "",
            status: "",
            images: [],
        };
    },
    computed: {
        ...mapGetters(["isLoggedIn", "userName", "accountId", "userData"]),
    },
    mounted() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const uploadUrl = "/api/upload/product/file?_token=" + csrfToken;

        ClassicEditor.create(document.querySelector("#editor"), {
            ckfinder: {
                uploadUrl: uploadUrl,
            },
        })
            .then((editor) => {
                editor.model.document.on("change", () => {
                    this.description = editor.getData();
                });
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {
        goToAccount(accountId) {
            this.$router.push({
                name: "Account",
                params: { id: accountId },
            });
        },
        handleImageUpload(event) {
            const files = event.target.files;
            this.images = [];
            for (let i = 0; i < files.length; i++) {
                this.images.push(files[i]);
            }
        },
        addDetailInput() {
            this.details.push("");
        },
        showNotification(message) {
            const notification = document.createElement("div");
            notification.classList.add("notificationAddcart");
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.remove();
            }, 2100);
        },
        upload() {
            let colorsData = [];
            this.colors.forEach((color) => {
                let sizesData = [];
                color.sizes.forEach((size) => {
                    sizesData.push({
                        name: size.name,
                        quantity: size.quantity,
                    });
                });
                colorsData.push({ name: color.name, sizes: sizesData });
            });
            let formData = new FormData();
            formData.append("product_name", this.product_name);
            formData.append("price", this.price);
            for (let i = 0; i < this.details.length; i++) {
                formData.append("details[]", this.details[i]);
            }
            formData.append("description", this.description);
            formData.append("gender", this.gender);
            formData.append("discountnumber", this.discountnumber);
            formData.append("discountquantity", this.discountquantity);
            formData.append("status", this.status);
            formData.append("category", this.category);
            formData.append("brand", this.brand);
            formData.append("userId", this.userData.id);
            formData.append("colors", JSON.stringify(colorsData));
            for (let i = 0; i < this.images.length; i++) {
                formData.append("images[]", this.images[i]);
            }
            axios
                .post("/api/upload/product", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    if (response.data.success == true) {
                        this.showNotification(response.data.message);
                    } else {
                        this.errorMessages = response.data.error;
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        addColorInput() {
            this.colors.push({
                name: "",
                sizes: [{ name: "", quantity: null }],
            });
        },
        addSizeInput(colorIndex) {
            this.colors[colorIndex].sizes.push({ name: "", quantity: null });
        },
    },
};
</script>
