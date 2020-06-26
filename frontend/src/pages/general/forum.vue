<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'home'}">Home</router-link>
          </li>
          <li class="breadcrumb-item active">Forum</li>
        </ol> -->
        <h1 class="h2">Forum</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h4 class="card-title">General</h4>
                                <p class="card-subtitle">All non-course topics.</p>
                            </div>
                            <div class="media-right">
                                <a
                                        href="#"
                                        class="btn btn-white btn-sm"
                                ><i class="material-icons">add</i></a>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-fit">
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-7.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <router-link :to="{ name: 'forum_thread', params: {id:'1'} }">
                                        Am I learning the right way?
                                    </router-link>
                                    <br>
                                    <small>replied by <a href="#">Brian S.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-4.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="instructor-forum-thread.html">Can someone help me with the basic Setup?</a><br>
                                    <small>posted by <a href="instructor-profile.html">Adrian D.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-2.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="instructor-forum-thread.html">I think this is the right way?</a><br>
                                    <small>replied by <a href="#">Michael D.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h4 class="card-title">Lessons</h4>
                                <p class="card-subtitle">Course related topics.</p>
                            </div>
                            <div class="media-right">
                                <a
                                        href="#"
                                        class="btn btn-white btn-sm"
                                ><i class="material-icons">add</i></a>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-fit">
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-7.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="instructor-forum-thread.html">Getting around AngularJS</a><br>
                                    <small>replied by <a href="instructor-profile.html">Adrian D.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-8.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="instructor-forum-thread.html">Responsive Bootstrap Question</a><br>
                                    <small>posted by <a href="instructor-profile.html">Adrian D.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item forum-thread">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <div class="forum-icon-wrapper">
                                        <a
                                                href="instructor-forum-thread.html"
                                                class="forum-thread-icon"
                                        >
                                            <i class="material-icons">description</i>
                                        </a>
                                        <a
                                                href="#public-profile"
                                                class="forum-thread-user"
                                        >
                                            <img
                                                    src="assets/images/people/50/guy-5.jpg"
                                                    alt=""
                                                    width="20"
                                                    class="rounded-circle"
                                            >
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="instructor-forum-thread.html">Can someone help me with the basic Setup?</a><br>
                                    <small>replied by <a href="instructor-profile.html">Adrian D.</a></small>
                                </div>
                                <div class="media-right">
                                    <div
                                            style="width:80px;"
                                            class="text-right"
                                    >
                                        <small class="text-muted-light">5 min ago</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="form-group">
                            <input
                                    id="forumSearch"
                                    type="text"
                                    class="form-control"
                                    placeholder="Type to search"
                            >
                        </div>
                        <a
                                href="#"
                                class="btn btn-primary"
                        >Search <i class="material-icons btn__icon--right">search</i></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Categories</h4>
                    </div>
                    <ul class="list-group list-group-fit">
                        <li class="list-group-item active">
                            <a
                                    href="#"
                                    class="text-white"
                            >All Topics</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#">General</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#">Lessons</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#">Support</a>
                        </li>
                    </ul>
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
