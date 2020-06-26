<template>
  <!-- Header -->

  <div
    id="header"
    data-fixed
    class="mdk-header js-mdk-header mb-0"
    v-show="!['login', 'register'].includes($route.name)"
  >
    <div class="mdk-header__content">

      <!-- Navbar -->
      <nav
        id="default-navbar"
        class="navbar navbar-expand navbar-dark bg-primary m-0"
      >
        <div class="container-fluid">
          <!-- Toggle sidebar -->
          <button
            class="navbar-toggler d-block"
            data-toggle="sidebar"
            type="button"
          >
            <span class="material-icons">menu</span>
          </button>

          <!-- Brand -->
          <router-link
            :to="{ name: 'home'}"
            class="navbar-brand"
          >
            <img
              src="assets/images/logo/white.svg"
              class="mr-2"
              alt="LearnPlus"
            />
            <span class="d-none d-xs-md-block">CELT English</span>
          </router-link>

          <!-- Search -->
          <form class="search-form d-none d-md-flex">
            <input
              type="text"
              class="form-control"
              placeholder="Search"
            >
            <button
              class="btn"
              type="button"
            ><i class="material-icons font-size-24pt">search</i></button>
          </form>
          <!-- // END Search -->

          <div class="flex"></div>

          <ul class="nav navbar-nav flex-nowrap d-none d-lg-flex">
            <li class="nav-item">
              <!-- <router-link
                :to="{ name: 'forum'}"
                class="nav-link"
              >
                Forum
              </router-link>
            </li> -->
            <li class="nav-item">
              <router-link
                :to="{ name: 'help'}"
                class="nav-link"
              >
                Get Help
              </router-link>
            </li>
          </ul>

          <!-- Menu -->
          <ul class="nav navbar-nav flex-nowrap">

            <!-- User dropdown -->
            <li class="nav-item dropdown ml-1 ml-md-3">
              <a
                class="nav-link active dropdown-toggle"
                data-toggle="dropdown"
                href="#"
                role="button"
              >
                <!-- <img
                  src="@/assets/images/people/50/guy-6.jpg"
                  alt="Avatar"
                  class="rounded-circle"
                  width="40"
                > -->
                <img
                  :src="routes.server_api + user.avatar_url"
                  alt="Avatar"
                  class="rounded-circle"
                  v-if="user.avatar_url"
                  width="40"
                >
                <img
                  :src="routes.server_api+ '/img/default_avatar.jpg'"
                  alt=""
                  class="rounded-circle"
                  v-else
                  width="40"
                >
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <router-link
                  class="dropdown-item"
                  :to="{ name: 'instructor_edit_profile'}"
                >
                  <i class="material-icons">edit</i> Edit Account
                </router-link>
                <!-- <a
                  class="dropdown-item"
                  href="instructor-profile.html"
                >
                  <i class="material-icons">person</i> Public Profile
                </a> -->
                <router-link
                  :to="{name:'logout'}"
                  class="dropdown-item"
                >
                  <i class="material-icons">lock</i> Logout
                </router-link>
              </div>
            </li>
            <!-- // END User dropdown -->
          </ul>
        </div>
      </nav>
      <!-- // END Navbar -->

    </div>
  </div>

  <!-- // END Header -->
</template>

<script>
export default {
  computed: {
    user() {
      return this.$store.getters.authUser;
    }
  },
  mounted() {    
  }
};
</script>
