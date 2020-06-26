<template>

</template>

<script>
    export default {
        mounted() {
            this.logout();
        },
        methods: {
            logout() {
                this.$store.dispatch("destroyToken");
                this.$store.dispatch("destroyAuthUserData");
                this.$router.push({name: "login"});
            }
        }
    };
</script>

<style>
</style>
