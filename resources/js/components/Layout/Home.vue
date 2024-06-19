<template>
    <Product />
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import { useRouter } from "vue-router";
import Product from "../Layout/product/Product.vue";

export default {
    name: "Home",
    components: { Product},
    computed: {
        ...mapGetters(["isLoggedIn", "userName", "accountId", "userData"]),
    },
    methods: {
        goToAccount(accountId) {
            this.$router.push({
                name: "Account",
                params: { id: accountId },
            });
        },
    },
};
</script>
