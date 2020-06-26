<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item active">Help center</li>
        </ol> -->
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">Help center</h1>
            </div>
            <div class="media-right">
                <a href="#" class="btn btn-success">
                    Have a Question <i class="material-icons btn__icon--right">help_outline</i>
                </a>
            </div>
        </div>
        <div class="card-columns">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex">
                        <h4 class="card-title">Introduction</h4>
                        <div class="card-subtitle">Getting started</div>
                    </div>
                    <i class="material-icons text-muted-light">info_outline</i>
                </div>
                <ul class="list-group list-group-fit">
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Basic Setup</a>
                    </li>
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Working with
                            the Dev</a>
                    </li>
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Your first
                            deploy</a>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex">
                        <h4 class="card-title">Customization</h4>
                        <div class="card-subtitle">Documentation</div>
                    </div>
                    <i class="material-icons text-muted-light">info_outline</i>
                </div>
                <ul class="list-group list-group-fit">
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Styling with
                            SASS</a>
                    </li>
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Editing the
                            Pages</a>
                    </li>
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Saving for
                            later</a>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex">
                        <h4 class="card-title">API</h4>
                        <div class="card-subtitle">Documentation</div>
                    </div>
                    <i class="material-icons text-muted-light">info_outline</i>
                </div>
                <ul class="list-group list-group-fit">
                    <li class="list-group-item">
                        <a href="">
                            <i class="material-icons float-right text-muted-light">trending_flat</i>
                            Course API Docs
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href=""><i class="material-icons float-right text-muted-light">trending_flat</i> Student API
                            Docs</a>
                    </li>
                    <li class="list-group-item">
                        <a href=""> <i class="material-icons float-right text-muted-light">trending_flat</i>Instructor
                            API Docs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {};
</script>

<style>
</style>
