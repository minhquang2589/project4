<template>
    <button @click="logout">Logout</button>
</template>

<script>
export default {
    name: "LogoutBtn",
    methods: {
        logout() {
            axios
                .post("/api/logout")
                .then((response) => {
                    // console.log(response.data);
                    if (response.data.success) {
                        this.$router.push({ name: "Login" });
                        window.location.reload();
                    } else {
                        console.error("Failed to log out");
                    }
                })
                .catch((error) => {
                    console.error("An error occurred during logout:", error);
                });
        },
    },
};
</script>
