<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
              <li class="breadcrumb-item active">Edit Category</li>
            </ol> -->
            <h1 class="h2">Edit Category</h1>

            <div class="card">
                <ul class="nav nav-tabs nav-tabs-card">
                    <li class="nav-item">
                        <a
                                class="nav-link active"
                                href="#first"
                                data-toggle="tab"
                        >Category Information</a>
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
                                >Category name</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input
                                                    id="name"
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Category name"
                                                    v-model="category_data.txt_name"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                        for="description"
                                        class="col-sm-3 col-form-label form-label"
                                >Category Description</label>
                                <div class="col-sm-6 col-md-6">
                                    <div class="input-group">
                    <textarea
                            id="description"
                            class="form-control"
                            placeholder="Write something to describe this company"
                            v-model="category_data.txt_description"
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
                        <div class="alert alert-dismissible bg-success text-white border-0 fade"
                             :class="added_success?'show':''" role="alert">
                            Added new company <strong>successfully !</strong>
                        </div>
                        <div class="alert alert-dismissible bg-danger text-white border-0 fade"
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
                category_data: {
                    txt_name: '',
                    txt_description: '',
                },
                instructor_list: [],
                added_success: false,
                added_error: false,
                added_error_messages: [],
            };
        },
        mounted() {
            this.fetchListInstructor()
            this.fetchCompany()
        },
        methods: {
            updateCompany() {
                axios.put(this.routes.category.EDIT_CATEGORY + this.$route.params.id, this.category_data).then(res => {
                    this.added_success = true
                    this.added_error = false
                    this.$router.push({name: 'admin_category_course'})
                }).catch(err => {
                    this.added_error_messages = err.response.data
                    this.added_error = true
                    this.added_success = false
                })
            },
            fetchListInstructor() {
                axios.get(this.routes.common.SHOW_USER_BY_ROLE + "content_manager").then(res => {
                    this.instructor_list = res.data
                }).catch(err => {
                    console.log(err.response.data)
                })
            },
            fetchCompany() {
                axios.get(this.routes.category.SHOW_CATEGORY + '/' + this.$route.params.id).then(res => {
                    this.category_data.txt_name = res.data.name
                    this.category_data.txt_description = res.data.description
                }).catch(err => {
                    console.log(err.response.data)
                })
            },
        }
    };
</script>

<style>
</style>
