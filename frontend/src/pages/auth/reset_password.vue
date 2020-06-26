<template>
    <div class="d-flex align-items-center" style="min-height: 70vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
                <div class="avatar avatar-lg">
                    <!-- <img -->
                    <!-- src="assets/images/logo/primary.svg" -->
                    <!-- class="avatar-img rounded-circle" -->
                    <!-- alt="LearnPlus" -->
                    <!-- > -->
                </div>
            </div>
            <div class="d-flex justify-content-center mb-5 navbar-light">
                <!-- Brand -->
                <a href="#" class="navbar-brand m-0">

                    <span class="btn btn-lg btn-primary mr-2" style="font-weight: bold;">CELT</span> <span
                        class="btn btn-lg btn-warning ml-2" style="font-weight: bold;">English</span>

                </a>
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Reset password</h4>
                    <p class="card-subtitle">Reset your password</p>
                </div>
                <div class="card-body">
                    <form @submit.prevent="resetAccountWithNewPassword">
                        <div class="form-group">
                            <label class="form-label" for="email">Your new password:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        type="password"
                                        class="form-control form-control-prepended"
                                        placeholder="New password"
                                        id="exampleInputEmail1"
                                        v-model="password"
                                        aria-describedby="emailHelp"
                                        required
                                >
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Confirm your new password:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        type="password"
                                        class="form-control form-control-prepended"
                                        placeholder="Confirm new password"
                                        id="exampleInputEmail1"
                                        v-model="retype_password"
                                        aria-describedby="emailHelp"
                                        required
                                >
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Change password</button>
                        </div>
                        <div class="text-center">
                            <router-link
                                    :to="{ name: 'login' }"
                                    class="text-black-70"
                                    style="text-decoration: underline;"
                            >Login
                            </router-link>
                        </div>
                    </form>
                </div>
                <div
                        class="alert alert-light border-1 border-left-3 border-left-danger d-flex"
                        v-if="error_messages"
                        v-for="error in error_messages.errors"
                        style="margin:0 15px;"
                        role="alert"
                >
                    <i class="material-icons text-danger mr-3">error</i>
                    <div class="text-body">{{ error[0] }}</div>
                </div>
                <!-- <div class="card-footer text-center text-black-50"> -->
                <!-- Not yet a student? -->
                <!-- <router-link :to="{ name: 'register' }">Register</router-link> -->
                <!-- </div> -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                password: "",
                retype_password: "",
                error_messages: {}
            };
        },

        mounted() {
            this.checkIfTokenValid();
        },

        methods: {
            resetAccountWithNewPassword() {
                axios
                    .put(this.routes.auth.RESET_PASSWORD, {
                        password: this.password,
                        password_confirmation: this.retype_password,
                        token: this.$route.params.token
                    })
                    .then(response => {
                        alert('Changed password success, now you can login');
                        this.$router.push({name: 'login'});
                    })
                    .catch(e => {
                        this.error_messages = e.response.data;
                    });
            },

            checkIfTokenValid() {
                axios
                    .post(
                        this.routes.auth.CHECK_IF_TOKEN_VALID +
                        "/" +
                        this.$route.params.token,
                        {
                            email: this.$route.params.token
                        }
                    )
                    .then(response => {
                        // TODO
                    })
                    .catch(e => {
                        console.error(e);
                        this.$router.push({name: "login"});
                    });
            }
        }
    };
</script>

<style></style>
