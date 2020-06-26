<template>
    <div class="d-flex align-items-center" style="min-height: 70vh; margin-top:-100px;">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mb-5 mb-1">
                <div class="avatar avatar-lg" style="width:50%;">
                    <img :src="routes.server_api+'/img/celt_english.png'"  style="width:100%;" alt="CELT English"/>
                </div>
            </div>
            <!--<div class="d-flex justify-content-center mb-5 navbar-light">
                &lt;!&ndash; Brand &ndash;&gt;
                <a href="#" class="navbar-brand m-0">
                    <span class="btn btn-lg btn-primary mr-2" style="font-weight: bold;">CELT</span> <span
                        class="btn btn-lg btn-warning ml-2" style="font-weight: bold;">English</span>
                </a>
            </div>-->
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Login</h4>
                    <p class="card-subtitle">Access your account</p>
                </div>
                <div class="card-body">

                    <form @submit.prevent="login">
                        <div class="form-group">
                            <label class="form-label" for="email">Your email address:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="login_data.email"
                                        @click="resetField('email')"
                                        type="email"
                                        class="form-control form-control-prepended"
                                        placeholder="Your email address"
                                        id="exampleInputEmail1"
                                        aria-describedby="emailHelp"
                                        required
                                />
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Your password:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="login_data.password"
                                        type="password"
                                        @click="resetField('password')"
                                        class="form-control form-control-prepended"
                                        name="password"
                                        id="password"
                                        placeholder="Password"
                                        required
                                />
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary btn-block">
                                Login
                            </button>
                        </div>
                        <!--<div class="text-center">
                            <router-link
                                    :to="{ name: 'forget_password'}"
                                    class="text-black-70"
                                    style="text-decoration: underline;"
                            >Forgot Password?
                            </router-link
                            >
                        </div>-->
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
                    <div class="text-body">
                        {{ error[0] }}
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        data() {
            return {
                login_data: {
                    email: "",
                    password: ""
                },
                error_messages: {}
            };
        },

        methods: {
            resetField(field_name) {
            },

            login() {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.auth.LOGIN, this.login_data)
                    .then(response => {
                        this.$store.dispatch("setAuthToken", response.data.access_token);
                        axios.defaults.headers.common["Authorization"] =
                            "Bearer " + response.data.access_token;
                        this.getAuthUserInfomation();
                    })
                    .catch(e => {
                        this.$store.dispatch("disableLoading");
                        if (e.response.status == 500) {
                            this.error_messages = {errors: {incorrect: []}};
                            this.error_messages.errors.incorrect[0] =
                                "Your credentials is not correct";
                        } else {
                            this.error_messages = e.response.data;
                        }
                    });
            },

            getAuthUserInfomation() {
                axios.get(this.routes.common.USER_INFO).then(response => {
                    if (response.data.is_deleted) {
                        swal("You have to re-register!");
                    } else {
                        this.$store.dispatch("setAuthUserData", response.data);
                        this.$router.push({name: "home"});
                        this.$store.dispatch("disableLoading");
                    }
                });
            }
        }
    };
</script>

<style></style>
