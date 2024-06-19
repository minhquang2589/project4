<template>
    <Account page-title="Profile">
        <form @submit.prevent="accountEdit">
            <div class="rounded-2xl lg:px-6 px-0">
                <div class="flex mb-5 items-center gap-4">
                    <img
                        v-if="imagePreviewUrl"
                        :src="imagePreviewUrl"
                        class="size-20 border border-gray-300 rounded-full object-cover"
                    />
                    <img
                        v-else
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAAAh1BMVEX///8AAAD29vbu7u75+fnj4+P8/Pzn5+fDw8PX19eMjIyNjY3r6+uamppERESmpqa1tbXKysqGhoYiIiLT09Pc3NxAQEAVFRXExMS9vb0bGxsMDAwzMzMoKChPT084ODhfX1+vr69nZ2dra2tXV1efn591dXV+fn4eHh5SUlJxcXEuLi5JSUnfwfXfAAAJNUlEQVR4nO2diZqiOBCAW0DlVvCkvcCzt/X9n29b3ZmdbgnUReL48T8AoTQkddfbW0tLS0tLS0tLS0tLS0tLS0s5PceyrL43Ttwsc5OxF1qW4/RMv5UMXXc4SPfLRecHs80+HQzdrun3Y2DZiZ/Hq5+SfWcV535iW6bfFU+3CNbVov3JNC3+pr/S9qL4HS7dnY9pOrZNvzmIxJ9ihftF7Lum376G3iQYUaW7Mwomjmkp1Aw/edLd2Q5Ny1FO9/BwFZCJnu/I6fs19wGO97RvWqJveCdJ6e4cx6al+k2YzuTl63QWaWhasht21IR0dwLzIvaG5FsPwnRoWCX3/mlSvCv/eAbFc6JGPr7vzAJjN//40rx4V2Iz52kvkrvYa1iY+BO7W13iXcknuuVzdYp3RbOdEeiWr9PxNYrXz/XL92VlaFNP+7EJ+b5OU002xlzb6fmAlg8xMydfZ5E1L9/AnHhXBk8s32h9ioosK6LT5eNpJaSaRnFQfLN8rCygnlTR88k325V5PO3xDu08bVpC0v5895X3V+iTrJHGdilJvm2lu9omORobkjAjvMo5qXtqsiQ8tpHbgnK/HwHRBvuIf+5iLi9fH/8anQD2aMLRtRDXSwn656KAPrzAb45YWkKC/QCW7+1tiH/6VlY+H/8GqKOuwD8fuP9hEOz3FLdCil9B0LTo4lfPsWsQvgEx89DB+5dWaCfRBB+d2kp5vQnHOCGGSThohLTSMeEQp6yDD3IsRDzCDsGuIS08xq8TSziECRt0R/o4ejv8SgKb1CNYNMSdQ/gLZ+zYU48QHxtRFyOkoOy5AhLOts6ButiBsBjTcgoJ8dsP8rbxCD6MKS//i+KE2ZOPNmtPWI51zoSEBbFa6J8QNNJOh5OoQFqQkYlF+eI5PyjlimD9oqQdw7gqCN6SL+jyvb2RFjxSV6O4YRi34BVaMibVfUEw47+YcgTckJYkBn+7tJ9zzREQkef9ByOa6UsMtOQcAYmxcdpdSFuLpx1SM8Moa5HupA7R2P0FNbOPcPdSrLMbZ46AZ+KiBAt0QlzKwD14BZ8JRU/1YdTpWORF8W5gev1DbcRMTUJeFK1e0Jfi2C+M7Gjsz0rTYm4wwiKMDEbkHrUZmdgbcmSrS9PUbsQ4y96jr8Rwk1CC5L/BGU2sdEmyLsPKcMftUV6tANEPZLMWRVkxXUauVYccm+Rl2X5gTIqCtRTRfGH+qpiQOeeSuHGiCMgt8ELsG4ud0kvwA7EO7isXuI7I+9qv5GiF1OLngcPPNoae9gv0OSOQxw/X1rif4BXkbc+64/8D7nuSqBrApSEQUhAeycHLiZQNrBB3RVekABjsLJFZrjMFK919mSLLM/QnlapLmgG/+oSW2vwINMmS6k97YARydmVS8oF9a4KlEZ+1dxMlH1YFNP+PFBVUENdcF5lkHRQwUtijBJLVbCu+jES2yjKHeUcdSpp4Fdt56cI9V7qIdAPLDnDkq68+AvdH3Dd0A9HmFzdmMAHp3tcqlvu0mNg3JkW6l94ld2AqfjMCagEmIC1y/RTA8h/Ylqc5YHY2IenvWYClOQqYu6aAKb/aewDIAas0kDCuDQFzI7y8gC+/RV/+kHn5a+LlL/qXV9VeXtl+eQEdDb3EmgFo8Iq7LLQBdFkIO500AnQ6iboNtQItMDDcE4cO1PEr5rrXDdR1/9dq29Dgi1D4TDvg8JlMAFQ/8GxxI43v+ORgASWSEH6weB+dl/Flvd+vL9PlefTeQHc2eBKCqMm7yYNDkY3737QMpz/OioOfMxJEH4GnkfATga7MlpfjYBJWDZOw7LBbnNZLGe0Xnghk8Xvbxn6RgLOdrKTw+QcbIpWL9xGOLsehha7jdawsvbBGHWCSqwr6MtuCUbvvFYyIPSadkpi6uTzO2c0lnPmRljWDSoglpTTvpEbwhC6lbgpXmInO/csHsj3bC3T8HpffiHQdnhL5RtgJMgMY9+nbiFP7HDQ0bceKEM6TGJmCC78oBg02T+7DbW9sBi5QW3uPGh565URA2w1d8wa6c48aWl/3QVfjCv1cwDmqa4pAAig+x9ei1Je4RtomeVn1dYX4Ete6IuWl1u76dW1WKW2yqn1rGNVdAqt6m5JavFQ90Nc+4cKpvLhIj6zY+I32utb2PupmHUbkq5CQ2KxDqc0wegzxUAVNqLM2FLHstbExQY7ipCGrG+U6hMHReeWdXEnVijfKm1bRn8em1ILi9Dcs3/SNz3xQUW5bcI6E8jZg8l28YSjOBJbGWH4y61Zj7ii8tbw7S9HygdzJjEP5YANm80aVRmrgM1QY9+zBBYr2CxrG53xHkcXKbqCq7G+oee6hws3Hb4Gr1ABXWocCjhWeGQmtWNWG+qxxAGmokE+kDbWykfhU20zAiSKSINNIXG2m0Fv/4FDWMEuZbY4qKWGl5aTxVN3kxJr5q8cxLDXsUqV8kmbNXLUGp6EvDLXvS9Stp3YDN6zTqIMToiNRqjqenRrUvC212154qE3VWCLxCUG/6epctKLYYNHQNh2os6GaMEmrRoOdGlgvrIjwNjEarLoqbSEeqXCr4ncNxUUqw61bUdU0rMxBMDOAcBGJ+UtrxsA3eDNVx+mWmciNYWXVsTKTQ0D3AopNVlO20XBgpC7tYeOynEC2W5dBan4Qb3wgb1RnUJvHqcHb5dYnI58Swt9oAVKb5G+jMiDDQNfq2bTl2BGkYqqR+/0R2LjMVZQAr8YwgWX7NKf0/qQPTAWcfga1Nn/38AnM3dzqDIkg0i3X6dD1wgfvQi/03GGKmC8hbP/VgatvGk3X+e4URIfBcDg4RMFpl6+nuARtTZ/f/0C3qQy5gbByL2qgeKUcQTUXxZhfXgFCV9LfI85BQ8H2zNDfd2fSeEHzXnMY6wHR5oQPTLXHIR+xKWMRgUQaI1gV2L5Yh9A/WaTPId4Vj9vlvISj6Y/vO31ftLL5HWuNaKB7kLv4I4P5cFUMy1NZkOwaj1jR6U0CVo3jl1oeTEze6xCSgHwzxgFjZpNG7EkUoyssP6a+ZyQFjki3CBDG7CUdPumxUoVlJ34e18yjO8e5n1RVoj893flwkOabB7NjtsnTwXD+F/5xZfQcx7JCb5y4WeYmYy+0LMeRrYltaWlpaWlpaWlpaWlpaWl5Jf4FeQSe/KeqeWYAAAAASUVORK5CYII="
                        class="size-20 rounded-full border border-gray-300 object-cover"
                    />
                    <button
                        type="button"
                        @click="triggerFileInput"
                        class="rounded hover:underline"
                    >
                        Change
                    </button>
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileUpload"
                        class="hidden"
                    />
                </div>
                <div class="md:w-full">
                    <div>
                        <input
                            v-model="fullname"
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                            type="text"
                            placeholder="Enter Your Name"
                        />

                        <input
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                            v-model="email"
                            name="email"
                            type="text"
                            placeholder="Enter Your Email"
                        />

                        <input
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                            id="phone"
                            name="phone"
                            type="tphoneext"
                            placeholder="Enter Your Phone Number"
                            v-model="phone"
                        />

                        <input
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                            id="birthday"
                            name="birthday"
                            type="date"
                            placeholder="Enter Your Phone Number"
                            v-model="birthday"
                        />

                        <select
                            id="gender"
                            for="gender"
                            name="gender"
                            v-model="gender"
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                        >
                            <option hidden selected disabled>
                                Choose a gender
                            </option>
                            <option for="gender" value="Male">Male</option>
                            <option for="gender" value="Female">Female</option>
                        </select>

                        <input
                            class="text-sm border border-gray-300 appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-2 px-4 mb-2"
                            id="address"
                            name="address"
                            type="text"
                            placeholder="Enter Your Address"
                            v-model="address"
                        />
                        <textarea
                            class="w-full"
                            name="description"
                            v-model="description"
                            id="editor"
                        ></textarea>
                    </div>
                </div>
                <div
                    v-if="errorMessages.length"
                    class="error-messages mt-1 text-xs text-red-600"
                >
                    <ul>
                        <li
                            class="mt-2"
                            v-for="(error, index) in errorMessages"
                            :key="index"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <button
                    type="submit"
                    class="text-white flex justify-center mt-6 bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm w-1/2 sm:w-auto px-20 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
            </div>
        </form>
    </Account>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import Account from "../account/Account.vue";
export default {
    name: "editUser",
    components: {
        Account,
    },
    data() {
        return {
            fullname: "",
            email: "",
            phone: "",
            birthday: "",
            gender: "",
            avatar: "",
            address: "",
            description: "",
            errorMessages: [],
            image: null,
            fileName: "",
            imagePreviewUrl: null,
        };
    },
    mounted() {
        this.getData();
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
                this.editorInstance = editor;
                editor.model.document.on("change", () => {
                    this.description = editor.getData();
                });
                if (this.description) {
                    editor.setData(this.description);
                }
            })
            .catch((error) => {
                console.error(error);
            });
    },
    computed: {
        ...mapGetters(["userData"]),
    },
    methods: {
        ...mapActions(["showNotification"]),
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    this.imagePreviewUrl = reader.result;
                };
                reader.readAsDataURL(file);
            }
            this.fileName = [file];
        },
        async getData() {
            try {
                const response = await axios.get(
                    `/api/account/user/edit/${this.userData.id}`
                );
                // console.log(response.data);
                if (response.data.success == true) {
                    const userData = response.data.user;
                    this.fullname = userData.name;
                    this.email = userData.email;
                    this.phone = userData.phone;
                    this.gender = userData.gender;
                    this.address = userData.address;
                    this.birthday = userData.birthday;
                    this.description = userData.descriptions;
                    this.avatar = userData.image;
                    this.imagePreviewUrl = `/images/${userData.image}`;
                    if (this.editorInstance) {
                        this.editorInstance.setData(this.description);
                    }
                } else {
                    console.log(" error.");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        },
        async accountEdit() {
            try {
                const formData = new FormData();
                formData.append("avatar", this.fileName[0]);
                formData.append("fullname", this.fullname);
                formData.append("email", this.email);
                formData.append("phone", this.phone);
                formData.append("birthday", this.birthday);
                formData.append("gender", this.gender);
                formData.append("description", this.description);
                formData.append("address", this.address);
                formData.append("userId", this.userData.id);
                const response = await axios.post(
                    "/api/account/update",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                // console.log(response.data);
                if (response.data.success == true) {
                    this.showNotification(response.data.message);
                    this.getData();
                } else {
                    this.errorMessages = response.data.error;
                }
            } catch (error) {
                console.error("Error:", error);
            }
        },
    },
};
</script>
