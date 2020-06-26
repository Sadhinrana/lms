<template>
  <div class="mdk-drawer-layout__content page">

    <div class="container-fluid page__container">
      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link :to="{ name: 'home'}">Home</router-link>
        </li>
        <li class="breadcrumb-item"><a>Company head</a></li>
        <li class="breadcrumb-item active">Edit Company information</li>
      </ol> -->
      <h1 class="h2">Edit Company information</h1>

      <div class="card">
        <ul class="nav nav-tabs nav-tabs-card">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="#first"
              data-toggle="tab"
            >Company information</a>
          </li>
          <!-- <li class="nav-item">
            <a
              class="nav-link"
              href="#second"
              data-toggle="tab"
            >Courses</a>
          </li> -->
        </ul>
        <div class="tab-content card-body">
          <div
            class="tab-pane active"
            id="first"
          >
            <form
              enctype="multipart/form-data"
              @submit.prevent="updateCompanyInfo"
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
                          v-if="company.company_avatar"
                          :src="company.company_avatar"
                        />
                        <img
                          v-else
                          src="
                          https://scontent.fsgn2-4.fna.fbcdn.net/v/t1.0-1/c47.0.160.160a/p160x160/10354686_10150004552801856_220367501106153455_n.jpg?_nc_cat=1&_nc_ht=scontent.fsgn2-4.fna&oh=d91da171bb693f7721ef7cd261bbcf77&oe=5CC35C1E"
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
                >Company name</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-md-6">
                      <input
                        id="name"
                        type="text"
                        class="form-control"
                        placeholder="Full name"
                        v-model="company.name"
                      >

                    </div>

                  </div>
                </div>
              </div>
              <!-- <div class="form-group row">
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
                      v-model="company.email"
                    >
                  </div>
                </div>
              </div> -->
              <div class="form-group row">
                <label
                  for="email"
                  class="col-sm-3 col-form-label form-label"
                >Address</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="material-icons md-18 text-muted">edit_location
                        </i>
                      </div>
                    </div>
                    <input
                      type="text"
                      id="email"
                      class="form-control"
                      placeholder="Address"
                      v-model="company.address"
                    >

                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label
                  for="email"
                  class="col-sm-3 col-form-label form-label"
                >Description</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="material-icons md-18 text-muted">info
                        </i>
                      </div>
                    </div>
                    <input
                      type="text"
                      id="email"
                      class="form-control"
                      placeholder="Company description"
                      v-model="company.description"
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
                      >Save Changes</button>
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
              Information updated <strong>successfully !</strong>
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

          <div
            class="tab-pane"
            id="second"
          >
            <div class="form-group row">
              <label
                for="name"
                class="col-sm-3 col-form-label form-label"
              >Assign course to this user</label>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-md-6">
                    <model-select
                      :options="options"
                      v-model="item"
                      placeholder="Select course"
                    >
                    </model-select>
                  </div>
                  <div class="col-md-6">
                    <button
                      href="#"
                      class="btn btn-success"
                    >Assign</button>
                  </div>
                </div>
              </div>
            </div>

            <table class="table mb-0">
              <thead>
                <tr>

                  <th>Course name</th>
                  <th>Created at</th>
                  <th style="width: 24px;"></th>
                </tr>
              </thead>
              <tbody
                class="list"
                id="search"
              >
                <tr>
                  <td>
                    <span class="js-lists-values-employee-name">Course ABC</span>
                  </td>
                  <td>2019-01-27 02:32:22</td>
                  <td class="text-right">
                    <a
                      href="#"
                      class="text-muted"
                      data-toggle="dropdown"
                    >
                      <i class="material-icons">more_vert</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a
                        href="#"
                        @click.prevent=""
                        class="dropdown-item"
                      >Remove</a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="js-lists-values-employee-name">Course XYZ</span>
                  </td>
                  <td>2019-01-27 02:32:22</td>
                  <td class="text-right">
                    <a
                      href="#"
                      class="text-muted"
                      data-toggle="dropdown"
                    >
                      <i class="material-icons">more_vert</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a
                        href="#"
                        @click.prevent=""
                        class="dropdown-item"
                      >Remove</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              class="alert alert-dismissible bg-success text-white border-0 fade"
              :class="added_success?'show':''"
              role="alert"
            >
              Updated <strong>successfully !</strong>
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
import { ModelSelect } from "vue-search-select";
export default {
  data() {
    return {
      //Just for testing, see company_head/assign_course_student.vue for example :
      options: [{ value: "1", text: "abc" }, { value: "2", text: "xyz" }],
      item: {
        value: "",
        text: ""
      },

      company: {},
      added_success: false,
      added_error: false,
      added_error_messages: []
    };
  },

  mounted() {
    this.getCompanyInfo();
  },

  methods: {
    getCompanyInfo() {
      axios.get(this.routes.company.GET_COMPANY_INFORMATION).then(response => {
        this.company = response.data;
        this.company.company_avatar = this.company.company_avatar
          ? this.routes.server_api + this.company.company_avatar
          : this.company.company_avatar;
      });
    },

    updateCompanyInfo() {
      axios
        .put(this.routes.company.UPDATE_COMPANY_INFORMATION, this.company)
        .then(response => {
          console.log(response.data);
          this.added_success = true;
          this.added_error = false;
        })
        .catch(err => {
          this.added_error_messages = err.response.data;
          this.added_error = true;
          this.added_success = false;
        });
    },

    onImageInputChanged(event) {
      var file = event.target.files[0];
      var reader = new FileReader();
      reader.readAsBinaryString(file);

      reader.onload = () => {
        this.company.company_avatar =
          "data:image/jpeg;base64," + btoa(reader.result);
        this.company.image = "data:image/jpeg;base64," + btoa(reader.result);
      };

      reader.onerror = () => {
        console.log("there are some problems");
      };
    }
  },
  components: {
    ModelSelect
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
