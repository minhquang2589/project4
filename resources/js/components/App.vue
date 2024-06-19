<template>
    <router-view></router-view>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import { ref, provide } from "vue";
export default {
    name: "App",
    computed: {
        ...mapGetters(["isAdmin"]),
    },
    created() {
        this.$store.dispatch("fetchUser");
    },
};
</script>
