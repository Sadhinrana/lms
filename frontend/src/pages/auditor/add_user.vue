<template>
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__container">
            <h1 class="h2">Add User</h1>

            <div class="card">
                <ul class="nav nav-tabs nav-tabs-card">
                    <li class="nav-item">
                        <a
                                class="nav-link active"
                                href="#first"
                                data-toggle="tab"
                        >Account</a>
                    </li>
                </ul>
                <div class="tab-content card-body">
                    <div
                            class="tab-pane active"
                            id="first"
                    >
                        <form
                                enctype="multipart/form-data"
                                @submit.prevent="addNewUser()"
                                class="form-horizontal"
                        >
                            <div class="form-group row">
                                <label
                                        for="avatar"
                                        class="col-sm-3 col-form-label form-label"
                                >Avatar</label>
                                <div class="col-sm-9">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <div class="icon-block rounded">
                                                <!-- <i class="material-icons text-muted-light md-36">photo</i> -->
                                                <img
                                                        v-if="userData.avatar_url"
                                                        :src="userData.avatar_url"
                                                />
                                                <img
                                                        v-else
                                                        :src="routes.server_api+ '/img/default_avatar.jpg'"
                                                />
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div
                                                    class="custom-file"
                                                    style="width: auto;"
                                            >
                                                <input
                                                        type="file"
                                                        id="avatar"
                                                        accept="image/*"
                                                        @change="onImageInputChanged"
                                                        class="custom-file-input"
                                                >
                                                <label
                                                        for="avatar"
                                                        class="custom-file-label"
                                                >Choose file</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="name"
                                        class="col-sm-3 col-form-label form-label"
                                >Full Name</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input
                                                    id="name"
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="First Name"
                                                    v-model="userData.first_name"
                                            >

                                        </div>
                                        <div class="col-md-6">
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Last Name"
                                                    v-model="userData.last_name"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="email"
                                        class="col-sm-3 col-form-label form-label"
                                >Email</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="material-icons md-18 text-muted">mail</i>
                                            </div>
                                        </div>
                                        <input
                                                type="text"
                                                id="email"
                                                class="form-control"
                                                placeholder="Email Address"
                                                v-model="userData.email"
                                        >

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="user-role"
                                        class="col-sm-3 col-form-label form-label"
                                >User Role</label>
                                <div class="col-sm-6 col-md-6">
                                    <select
                                            id="user-role"
                                            class="form-control custom-select"
                                            v-model="userData.role"
                                    >
                                        <option
                                                value="0"
                                                selected
                                                disabled
                                        >Select Roles
                                        </option>
                                        <option value="1">Headmaster</option>
                                        <option value="2">Content Manager</option>
                                        <option value="3">Company Head</option>
                                        <option value="4">Instructor</option>
                                        <option value="5">Student</option>
                                        <option value="6">Chief Auditor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="user-role"
                                        class="col-sm-3 col-form-label form-label"
                                >Company</label>
                                <div class="col-sm-6 col-md-6">
                                    <select id="company_head" class="form-control custom-select"
                                            v-model="userData.company">
                                        <option value="0"
                                                selected
                                                disabled>Select Company
                                        </option>
                                        <option v-for="company in company_list" :value="company.id">{{ company.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="password"
                                        class="col-sm-3 col-form-label form-label"
                                >Change Password</label>
                                <div class="col-sm-6 col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="material-icons md-18 text-muted">lock</i>
                                            </div>
                                        </div>
                                        <input
                                                type="password"
                                                id="password"
                                                class="form-control"
                                                placeholder="Enter new password"
                                                v-model="userData.password"
                                        >

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="password"
                                        class="col-sm-3 col-form-label form-label"
                                >Password confirmation</label>
                                <div class="col-sm-6 col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="material-icons md-18 text-muted">lock</i>
                                            </div>
                                        </div>
                                        <input
                                                type="password"
                                                id="password_confirmation"
                                                class="form-control"
                                                placeholder="Retype new password"
                                                v-model="userData.password_confirmation"
                                        >

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-sm-3">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <button
                                                    href="#"
                                                    class="btn btn-success"
                                            >Save Changes
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <div
                                class="alert alert-dismissible bg-success text-white border-0 fade"
                                :class="added_success?'show':''"
                                role="alert"
                        >
                            Added new user <strong>successfully !</strong>
                        </div>
                        <div
                                class="alert alert-dismissible bg-danger text-white border-0 fade"
                                :class="added_error?'show':''"
                                v-for="error in added_error_messages.errors"
                                role="alert"
                        >
                            <strong>Error ! </strong> {{error[0]}}
                        </div>
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
                userData: {
                    avatar_url: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                    first_name: "",
                    last_name: "",
                    role: "0"
                },
                company_list: [],
                added_success: false,
                added_error: false,
                added_error_messages: []
            };
        },

        mounted() {
            this.fetchListCompany()
        },

        methods: {
            addNewUser() {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.admin.ADD_NEW_USER, this.userData)
                    .then(response => {
                        this.added_error = false;
                        this.added_success = true;
                        this.$router.push({name: 'chief_auditor_manange_user'})
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        this.$store.dispatch("disableLoading");
                    });
            },

            onImageInputChanged(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                    this.userData.avatar_url =
                        "data:image/jpeg;base64," + btoa(reader.result);
                    this.userData.image = "data:image/jpeg;base64," + btoa(reader.result);
                };
                reader.onerror = () => {
                    console.log("there are some problems");
                };
            },

            updateUserInfo() {
                this.$store.dispatch("enableLoading");
                axios
                    .patch(this.routes.admin.UPDATE_USER, this.userData)
                    .then(response => {
                        this.userData.avatar_url =
                            this.routes.server_api + response.data.avatar_url;
                        this.added_success = true;
                        this.added_error = false;
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        this.$store.dispatch("disableLoading");
                    });
            },
            fetchListCompany() {
                axios.get(this.routes.company.SHOW_COMPANY).then(res => {
                    this.company_list = res.data
                }).catch(err => {
                    console.log(err.data)
                })
            }
        }
    };
</script>

<style scoped lang="scss">
    .media-left {
        img {
            max-width: 100%;
        }
    }
</style>
