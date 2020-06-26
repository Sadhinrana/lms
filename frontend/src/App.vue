<template>
    <div class="">
        <loading :active.sync="$store.getters.loading"
                 :can-cancel="true"
                 :is-full-page="true"
                 :opacity="0.2"
                 :z-index="9999"
                 style="z-index: 9999;width: 100%;height: 100vh;position: fixed;background: #fff;opacity:.4"
                 loader="dots"></loading>

        <!-- Header Layout -->
        <div class="mdk-header-layout js-mdk-header-layout">

            <router-view name="header"></router-view>

            <!-- Header Layout Content -->
            <div class="mdk-header-layout__content" style="padding-top: 64px;">
                <div
                        data-push
                        data-responsive-width="992px"
                        class="mdk-drawer-layout js-mdk-drawer-layout"
                >
                    <div class="mdk-drawer-layout__content page">
                        <keep-alive :include="aliveComponents">
                            <router-view/>
                        </keep-alive>
                    </div>

                    <router-view name="sidebar"></router-view>

                </div>
            </div>

        </div>

        <div id="modelDestination">

        </div>

    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import {eventBus} from "@/main";

    export default {
        name: "app",
        data() {
            return {
                aliveComponents: []
            };
        },

        mounted() {
            if (this.$store.getters.isAuthorized) this.getAuthUser();
        },

        methods: {
            getAuthUser() {
                axios.get(this.routes.common.USER_INFO).then(response => {
                    this.$store.dispatch("setAuthUserData", response.data);
                    eventBus.$emit("authUserFetched");
                });
            }
        },
        components: {
            Loading
        },
    };
</script>
<style lang="scss">
    .mdk-header-layout__content {
        padding-top: 64px !important;
    }

    .vld-icon {
        text-align: center;

        svg {
            transform: translateY(50%);
        }
    }

    .swal-button--confirm {
        background-color: #2db331;
    }
    .swal-button--confirm:focus {
        box-shadow: none !important;
        outline: none !important;
    }
    .swal-text {
      font-size: 18px;
      text-align: center;
      font-weight: bold;
    }
    .swal-footer {
      text-align: center;
    }
    .swal-button{
        font-size: initial!important;
        min-width: 100px!important;
    }
</style>
