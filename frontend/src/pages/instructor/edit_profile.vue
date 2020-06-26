<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/">Home</router-link>
              </li>
              <li class="breadcrumb-item active">Edit Account</li>
            </ol> -->
            <h1 class="h2">Edit Account</h1>

            <div class="card">
                <ul class="nav nav-tabs nav-tabs-card">
                    <li class="nav-item">
                        <a
                                class="nav-link active"
                                href="#first"
                                data-toggle="tab"
                        >Account</a>
                    </li>
                    <li class="nav-item">
                        <a
                                class="nav-link"
                                href="#second"
                                data-toggle="tab"
                        >Billing</a>
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
                                        for="avatar"
                                        class="col-sm-3 col-form-label form-label"
                                >Avatar</label>
                                <div class="col-sm-9">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <div class="icon-block rounded">
                                                <i class="material-icons text-muted-light md-36">photo</i>
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
                                                    value="Laza"
                                            >
                                        </div>
                                        <div class="col-md-6">
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Last Name"
                                                    value="Bogdan"
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
                                                value="contact@mosaicpro.biz"
                                                disabled
                                        >
                                    </div>
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
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-sm-3">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <a
                                                    href="#"
                                                    class="btn btn-success"
                                            >Save Changes</a>
                                        </div>
                                        <div class="media-body pl-1">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                        id="subscribe"
                                                        type="checkbox"
                                                        class="custom-control-input"
                                                        checked
                                                >
                                                <label
                                                        for="subscribe"
                                                        class="custom-control-label"
                                                >Subscribe to Newsletter</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div
                            class="tab-pane"
                            id="second"
                    >
                        <form
                                action="#"
                                class="form-horizontal"
                        >
                            <div class="form-group row">
                                <label
                                        for="name_on_invoice"
                                        class="col-sm-3 col-form-label form-label"
                                >Name on Invoice</label>
                                <div class="col-sm-6 col-md-4">
                                    <input
                                            id="name_on_invoice"
                                            type="text"
                                            class="form-control"
                                            value="Adrian Demian"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="billing_address"
                                        class="col-sm-3 col-form-label form-label"
                                >Address</label>
                                <div class="col-sm-6 col-md-4">
                                    <input
                                            id="billing_address"
                                            type="text"
                                            class="form-control"
                                            value="Sunny Street, 21, Miami, Florida"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                        for="billing_country"
                                        class="col-sm-3 col-form-label form-label"
                                >Country</label>
                                <div class="col-sm-6 col-md-4">
                                    <select
                                            id="billing_country"
                                            class="custom-control custom-select form-control"
                                    >
                                        <option
                                                value="1"
                                                selected
                                        >USA
                                        </option>
                                        <option value="2">India</option>
                                        <option value="3">United Kingdom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 col-md-4 offset-sm-3">
                                    <a
                                            href="#"
                                            class="btn btn-success"
                                    > Update Billing</a>
                                </div>
                            </div>
                        </form>
                        <div class="card mt-2">
                            <div class="card-header">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <h4 class="card-title">Payment Info</h4>
                                    </div>
                                    <div class="media-right">
                                        <a
                                                href="#"
                                                class="btn btn-sm btn-outline-primary"
                                        ><i class="material-icons">add</i></a>
                                    </div>
                                </div>
                            </div>
                            <ul class="card-footer p-0 list-group list-group-fit">
                                <li class="list-group-item active">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <span class="btn btn-primary btn-circle"><i class="material-icons">credit_card</i></span>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0">**** **** **** 2422</p>
                                            <small>Updated on 12/02/2016</small>
                                        </div>
                                        <div class="media-right">
                                            <a
                                                    href="#"
                                                    class="btn btn-primary btn-sm"
                                            >
                                                <i class="material-icons btn__icon--left">edit</i> EDIT
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <span class="btn btn-white btn-circle"><i
                                                    class="material-icons">credit_card</i></span>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0">**** **** **** 6321</p>
                                            <small class="text-muted">Updated on 11/01/2016</small>
                                        </div>
                                        <div class="media-right">
                                            <a
                                                    href="#"
                                                    class="btn btn-white btn-sm"
                                            >
                                                <i class="material-icons btn__icon--left">edit</i> EDIT
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {};
</script>

<style>
</style>
