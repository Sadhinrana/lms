<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <h1 class="h2">Edit Profile</h1>

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
                                @submit.prevent="updateUserInfo()"
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
                                                <div v-if="checkImage === 1">
                                                    <img v-if="userData.avatar_url" :src="userData.avatar_url"/>
                                                    <img v-else :src="routes.server_api+ '/img/default_avatar.jpg'"/>
                                                </div>
                                                <div v-else>
                                                    <img v-if="userData.avatar_url"
                                                         :src="routes.server_api + userData.avatar_url"/>
                                                    <img v-else :src="routes.server_api+ '/img/default_avatar.jpg'"/>
                                                </div>
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
                                                        @click="resetField('image')"
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
                                                    @click="resetField('first_name')"
                                            >
                                        </div>
                                        <div class="col-md-6">
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Last Name"
                                                    v-model="userData.last_name"
                                                    @click="resetField('last_name')"
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
                                                @click="resetField('email')"
                                                disabled
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="password"
                                        class="col-sm-3 col-form-label form-label"
                                >New Password</label>
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
                                                autocomplete="off"
                                                class="form-control"
                                                placeholder="Enter new password"
                                                v-model="userData.password"
                                                @click="resetField('password')"
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
                            Profile updated <strong>successfully !</strong>
                        </div>
                        <div
                                class="alert alert-dismissible bg-danger text-white border-0 fade"
                                :class="added_error?'show':''"
                                :key="error"
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
                added_success: false,
                added_error: false,
                added_error_messages: [],
                userAvatar: "",
                checkImage: 0,
            };
        },

        computed: {
            userData() {
                var userData = this.$store.getters.authUser;
                return userData;
            }
        },

        created() {
        },

        methods: {
            resetField(field_name) {
            },

            onImageInputChanged(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                    this.userData.avatar_url = "";
                    this.userData.image = "data:image/jpeg;base64," + btoa(reader.result);
                    this.userData.avatar_url = "data:image/jpeg;base64," + btoa(reader.result);
                    this.checkImage = 1;
                };
                reader.onerror = () => {
                    console.log("there are some problems");
                };

                /*var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                  this.quiz.quiz_image = "";
                  this.quiz.image = "data:image/jpeg;base64," + btoa(reader.result);
                  this.quiz.quiz_image = "data:image/jpeg;base64," + btoa(reader.result);
                };
                reader.onerror = () => {
                  console.log("there are some problems");
                };*/

            },

            updateUserInfo() {
                this.$store.dispatch("enableLoading");
                axios
                    .patch(this.routes.user.UPDATE, this.userData)
                    .then(response => {
                        this.userData.avatar_url = response.data.avatar_url;
                        this.added_success = true;
                        this.added_error = false;
                        this.checkImage = 0;
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        this.$store.dispatch("disableLoading");
                    });
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
