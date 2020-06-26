<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
              <li class="breadcrumb-item active">Edit Company</li>
            </ol> -->
            <h1 class="h2">Edit Company</h1>

            <div class="card">
                <ul class="nav nav-tabs nav-tabs-card">
                    <li class="nav-item">
                        <a
                                class="nav-link active"
                                href="#first"
                                data-toggle="tab"
                        >Company Information</a>
                    </li>
                </ul>
                <div class="tab-content card-body">
                    <div
                            class="tab-pane active"
                            id="first"
                    >
                        <form
                                action="#"
                                class="form-horizontal"
                        >

                            <div class="form-group row">
                                <label
                                        for="name"
                                        class="col-sm-3 col-form-label form-label"
                                >Company name</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input
                                                    id="name"
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Company name"
                                                    v-model="company_data.name"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="avatar"
                                        class="col-sm-3 col-form-label form-label"
                                >Company's profile picture</label>
                                <div class="col-sm-9">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <div class="icon-block rounded">
                                                <!-- <i class="material-icons text-muted-light md-36">photo</i> -->
                                                <img
                                                        v-if="company_data.company_avatar"
                                                        :src="company_data.company_avatar"
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
                                        for="address"
                                        class="col-sm-3 col-form-label form-label"
                                >Country</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="material-icons md-18 text-muted">add_location</i>
                                            </div>
                                        </div>
                                        <input
                                                type="text"
                                                id="address"
                                                class="form-control"
                                                placeholder="Company Country"
                                                v-model="company_data.country"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="address"
                                        class="col-sm-3 col-form-label form-label"
                                >City</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="material-icons md-18 text-muted">add_location</i>
                                            </div>
                                        </div>
                                        <input
                                                type="text"
                                                id="address"
                                                class="form-control"
                                                placeholder="Company city"
                                                v-model="company_data.city"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="company_head"
                                        class="col-sm-3 col-form-label form-label"
                                >Company Head</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                                        <select
                                                id="company_head"
                                                class="form-control custom-select"
                                                v-model="company_data.instructor"
                                        >
                                            <option
                                                    selected
                                                    disabled
                                            >Select Company Head
                                            </option>
                                            <option
                                                    v-for="instructor in instructor_list"
                                                    :value="instructor.id"
                                            >{{ instructor.first_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="description"
                                        class="col-sm-3 col-form-label form-label"
                                >Company Introduction</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                    <textarea
                            id="description"
                            class="form-control"
                            placeholder="Write something to describe this company"
                            v-model="company_data.description"
                    ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-sm-3">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <a
                                                    href="#"
                                                    class="btn btn-info"
                                                    @click.prevent="updateCompany"
                                            >Save changes</a>
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
                            Update company <strong>successfully !</strong>
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
                company_data: {
                    name: "",
                    description: "",
                    address: "",
                    instructor: "Select Company Head",
                    company_avatar: "",
                    image: ""
                },
                instructor_list: [],
                added_success: false,
                added_error: false,
                added_error_messages: []
            };
        },
        mounted() {
            this.fetchListInstructor();
            this.fetchCompany();
        },
        methods: {
            onImageInputChanged(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                    this.company_data.company_avatar = "";
                    this.company_data.image =
                        "data:image/jpeg;base64," + btoa(reader.result);
                    this.company_data.company_avatar =
                        "data:image/jpeg;base64," + btoa(reader.result);
                };
                reader.onerror = () => {
                    console.log("there are some problems");
                };
            },

            updateCompany() {
                this.$store.dispatch("enableLoading");
                axios
                    .put(
                        this.routes.company.EDIT_COMPANY + this.$route.params.id,
                        this.company_data
                    )
                    .then(res => {
                        this.added_success = true;
                        this.added_error = false;
                        this.$router.push({name: "admin_company_manager"});
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        this.$store.dispatch("disableLoading");
                    });
            },
            fetchListInstructor() {
                axios
                    .get(this.routes.common.SHOW_USER_BY_ROLE + "company_head")
                    .then(res => {
                        this.instructor_list = res.data;
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    });
            },
            fetchCompany() {
                axios
                    .get(this.routes.company.SHOW_COMPANY + "/" + this.$route.params.id)
                    .then(res => {
                        this.company_data = res.data;
                        if (this.company_data.company_avatar) {
                            this.company_data.company_avatar =
                                this.routes.server_api + this.company_data.company_avatar;
                        }
                        this.company_data.instructor = res.data.company_head_id;
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    });
            }
        }
    };
</script>

<style>
</style>
