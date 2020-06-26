<template>
    <div class="d-flex align-items-center" style="min-height: 70vh">
        <div class="col-sm-8 col-md-6 col-lg-4 mx-auto" style="min-width: 300px;">
            <div class="text-center mt-5 mb-1">
                <div class="avatar avatar-lg">
                    <img
                            src="assets/images/logo/primary.svg"
                            class="avatar-img rounded-circle"
                            alt="LearnPlus"
                    />
                </div>
            </div>
            <div class="d-flex justify-content-center mb-5 navbar-light">
                <!-- Brand -->
                <a href="#" class="navbar-brand m-0">
                    CELT English
                </a>
            </div>
            <div class="card navbar-shadow">
                <div class="card-header text-center">
                    <h4 class="card-title">Sign Up</h4>
                    <p class="card-subtitle">Create a new account</p>
                </div>
                <div class="card-body">

                    <form @submit.prevent="register">
                        <div class="form-group">
                            <label class="form-label" for="name">First Name:</label>
                            <div class="input-group input-group-merge">
                                <!-- <input id="name" type="text" required="" class="form-control form-control-prepended" placeholder="Your first and last name"> -->
                                <input
                                        v-model="register_data.first_name"
                                        type="text"
                                        class="form-control form-control-prepended"
                                        placeholder="First name"
                                        required
                                />
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="name">Name:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="register_data.last_name"
                                        type="text"
                                        class="form-control form-control-prepended"
                                        placeholder="Last name"
                                        required
                                />
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="far fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email address:</label>
                            <div class="input-group input-group-merge">
                                <!-- <input id="email" type="email" required="" class="form-control form-control-prepended" placeholder="Your email address"> -->
                                <input
                                        v-model="register_data.email"
                                        type="email"
                                        class="form-control form-control-prepended"
                                        placeholder="Enter email"
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
                            <label class="form-label" for="password">Password:</label>
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="register_data.password"
                                        type="password"
                                        class="form-control form-control-prepended"
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
                        <div class="form-group">
                            <label class="form-label" for="password2"
                            >Password Confirmation:</label
                            >
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="register_data.password_confirmation"
                                        type="password"
                                        class="form-control form-control-prepended"
                                        placeholder="Password confirmation"
                                        required
                                />
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="company">Company:</label>
                            <div class="input-group input-group-merge">
                                <select
                                        id="company"
                                        class="form-control custom-select"
                                        v-model="register_data.company"
                                        required
                                >
                                    <option selected disabled>Select Company</option>
                                    <option v-for="company in company_list" :value="company.id">{{
                                        company.name
                                        }}
                                    </option>
                                </select>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fas fa-building"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="company"
                            >Captcha: <img :src="captcha.img" alt=""/>
                            </label>
                            <div class="input-group input-group-merge">
                                <input
                                        v-model="register_data.captcha"
                                        type="text"
                                        class="form-control form-control-prepended"
                                        placeholder="Enter captcha"
                                        required
                                />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-3">
                            Sign Up
                        </button>
                        <div class="form-group text-center mb-0">
                            <div class="custom-control custom-checkbox">
                                <input
                                        id="terms"
                                        type="checkbox"
                                        class="custom-control-input"
                                        checked
                                        required=""
                                />
                                <label for="terms" class="custom-control-label text-black-70"
                                >I agree to the
                                    <a
                                            href="#"
                                            class="text-black-70"
                                            style="text-decoration: underline;"
                                    >Terms of Use</a
                                    ></label
                                >
                            </div>
                        </div>
                    </form>
                </div>
                <div
                        class="alert alert-light border-1 border-left-3 border-left-danger d-flex"
                        v-if="error_messages"
                        v-for="error in error_messages.errors"
                        style="margin:5px 15px;"
                        role="alert"
                >
                    <i class="material-icons text-danger mr-3">error</i>
                    <div class="text-body">
                        {{ error[0] }}
                    </div>
                </div>
                <div class="card-footer text-center text-black-50">
                    Already signed up?
                    <router-link :to="{ name: 'login' }">Login</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                register_data: {
                    email: "",
                    password: "",
                    password_confirmation: "",
                    first_name: "",
                    last_name: "",
                    website: "",
                    address: "",
                    company: "Select Company",
                    captcha: "",
                    captcha_key: ""
                },
                company_list: [],
                error_messages: {},
                captcha: ""
            };
        },
        mounted() {
            this.fetchListCompany();
            this.getCaptcha();
        },
        methods: {
            register() {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.auth.REGISTER, this.register_data)
                    .then(response => {
                        this.$store.dispatch("disableLoading");
                        this.$store.dispatch(
                            "setAuthToken",
                            response.data.token_data.access_token
                        );
                        axios.defaults.headers.common["Authorization"] =
                            "Bearer " + response.data.token_data.access_token;
                        this.$store.dispatch("setAuthUserData", response.data.user);
                        this.$router.push({name: "home"});
                    })
                    .catch(e => {
                        this.error_messages = e.response.data;
                        this.getCaptcha();
                        this.$store.dispatch("disableLoading");
                    });
            },
            getCaptcha() {
                axios
                    .get(this.routes.auth.GET_CAPTCHA)
                    .then(res => {
                        this.captcha = res.data;
                        this.register_data.captcha_key = res.data.key;
                    })
                    .catch(err => {
                    });
            },
            fetchListCompany() {
                axios
                    .get(this.routes.company.SHOW_COMPANY)
                    .then(res => {
                        this.company_list = res.data;
                    })
                    .catch(err => {
                        console.log(err.data);
                    });
            }
        }
    };
</script>

<style></style>
