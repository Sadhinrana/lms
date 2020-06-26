<template>
    <div class="d-flex align-items-center" style="min-height: 70vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
                <div class="avatar avatar-lg" style="width:30%; height:30%;">
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
                    <h4 class="card-title">Forget password</h4>
                    <p class="card-subtitle">Reset your password</p>
                </div>
                <div class="card-body">
                    <form @submit.prevent="sendForgetPasswordMail">
                        <div class="form-group">
                            <label class="form-label" for="email">Your email address:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        type="email"
                                        class="form-control form-control-prepended"
                                        placeholder="Your email address"
                                        id="exampleInputEmail1"
                                        v-model="email"
                                        aria-describedby="emailHelp"
                                        required
                                >
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Send</button>
                        </div>
                        <div class="text-center">
                            <router-link :to="{ name: 'login' }" class="text-black-70"
                                         style="text-decoration: underline;">Login
                            </router-link>
                        </div>
                    </form>
                </div>
                <div
                        class="alert alert-light border-1 border-left-3 border-left-danger d-flex"
                        style="margin:0 15px;"
                        v-if="GUI.SHOW_ERROR"
                        role="alert"
                >
                    <i class="material-icons text-danger mr-3">error</i>
                    <div class="text-body">The email {{ this.email }} cannot be found!</div>
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
                email: '',
                error_messages: {},
                GUI: {
                    SHOW_ERROR: false
                }
            };
        },

        methods: {
            sendForgetPasswordMail() {
                this.$store.dispatch("enableLoading");
                axios.post(this.routes.auth.SEND_FORGET_PASSWORD_MAIL, {
                    email: this.email
                }).then(response => {
                    alert('We\'ve sent an email to ' + this.email + ' Please check your email.');
                    this.GUI.SHOW_ERROR = false;
                    this.$store.dispatch("disableLoading");
                }).catch(e => {
                    console.error(e);
                    this.GUI.SHOW_ERROR = true;
                    this.$store.dispatch("disableLoading");
                });
            }
        }
    };
</script>

<style></style>
