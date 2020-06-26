<template>
    <div class="page">
        <div class="container">
            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <div class="row">
                        <h1 class="h2 col-md-6">Upcoming lessons</h1>
                        <h1 class="h2 col-md-2">Teacher: </h1>
                        <div class="pt-1 col-md-4">
                            <select class="form-control custom-select" v-model="instructor_id" @change="initializeFullCalendar">
                                <option value="0" disabled>Choose teacher</option>
                                <option :value="instructor.id" v-for="instructor in instructors" :key="instructor.id">{{ instructor.first_name + " " + instructor.last_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container calendar-container">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "@/main";
    import {Calendar} from "@fullcalendar/core";
    import dayGridPlugin from "@fullcalendar/daygrid";

    export default {
        data() {
            return {
                instructors: [],
                events: [],
                instructor_id: '0',
                calendar: null
            };
        },
        computed: {
            company_id:function(){
                return this.$store.getters.authUser.company_id
            }
        },
        created: function () {
            console.log(this.$store.getters.authUser);
        },
        mounted() {
            this.getInstructors();
        },
        methods: {
            getInstructors: function () {
                var self = this;
                this.$store.dispatch("enableLoading");

                // Only run if authUser is fetched
                if (this.company_id) {
                    axios
                        .post(
                            self.routes.company.INSTRUCTOR_LIST, {company_id: self.company_id}
                        )
                        .then(res => {
                            self.instructors = res.data;

                            if (self.instructors.length) {
                                self.instructor_id = self.instructors[0].id;
                                self.initializeFullCalendar();
                            }

                            self.$store.dispatch("disableLoading");
                        })
                        .catch(err => {
                            console.log(err);
                        });
                } else {
                    //If this is the first time user visit page, wait until authUser is fetched
                    eventBus.$on("authUserFetched", function () {
                        axios
                            .post(
                                self.routes.company.INSTRUCTOR_LIST, {company_id: self.company_id}
                            )
                            .then(res => {
                                self.instructors = res.data;

                                if (self.instructors.length) {
                                    self.instructor_id = self.instructors[0].id;
                                    self.initializeFullCalendar();
                                }

                                self.$store.dispatch("disableLoading");
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    });
                }
            },
            liveLessonsByTeacher: function (params) {
                let _this = this;
                this.$store.dispatch("enableLoading");

                axios
                    .get(this.routes.lesson.SESSIONS + '/teacher', {
                        params: params
                    })
                    .then(response => {
                        if (response.data.status === 200) {
                            this.events = response.data.data;

                            this.events.forEach(function (value, index, array) {
                                _this.calendar.addEvent(value);
                            });
                        } else {
                            swal(response.data.msg);
                        }
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    console.log(err.response);
                    swal(err.response);
                    this.$store.dispatch("disableLoading");
                });
            },
            initializeFullCalendar: function () {
                let _this = this;
                let calendarEl = document.getElementById('calendar');
                calendarEl.innerHTML = '';
                _this.calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin],
                    datesRender: function (info) {
                        let monthS = info.view.activeStart.getUTCMonth() + 1;
                        monthS = monthS < 10 ? '0' + monthS : monthS;
                        let dtS = info.view.activeStart.getUTCDate();
                        dtS = dtS < 10 ? '0' + dtS : dtS;
                        let monthE = info.view.activeEnd.getUTCMonth() + 1;
                        monthE = monthE < 10 ? '0' + monthE : monthE;
                        let dtE = info.view.activeEnd.getUTCDate();
                        dtE = dtE < 10 ? '0' + dtE : dtE;

                        let params = {
                            instructor_id: _this.instructor_id,
                            today: moment().format('YYYY-MM-DD HH:mm'),
                            start: info.view.activeStart.getUTCFullYear() + '-' + monthS + '-' + dtS,
                            end: info.view.activeEnd.getUTCFullYear() + '-' + monthE + '-' + dtE,
                        };

                        _this.$store.dispatch("enableLoading");
                        let events = _this.calendar.getEvents();
                        let len = events.length;
                        for (let i = 0; i < len; i++) {
                            events[i].remove();
                        }

                        _this.liveLessonsByTeacher(params);
                    },
                    events: _this.events,
                });

                _this.calendar.render();
            },
        }
    };
</script>

<style lang='scss'>
    @import '~@fullcalendar/core/main.css';
    @import '~@fullcalendar/daygrid/main.css';

    .calendar-container {
        @media screen and (max-width: 768px) {
            padding: 0;
        }
    }
    .fc-scroller{
        overflow: initial!important;
        display: inline-block!important;
        height: inherit!important;
    }
    .fc th, .fc td {
        padding: 5px;
        @media screen and (max-width: 768px) {
            padding: 0;
        }
    }
    .fc-today-button{
        text-transform: capitalize!important;
    }
    .fc-toolbar h2{
        @media screen and (max-width: 500px) {
            font-size: 17px;
            font-weight: bold;
        }
    }
    .fc-past {
        opacity: 0.3;
    }
    .fc-day {
        position: relative;
        &:before {
            display: none!important;
            content: none!important;
        }
    }
    .calendar-container .card .card-body {
        .fc-day {
            position: relative;
            &:before {
                content: 'Check In';
                position: absolute;
                font-size: 12px;
                color: #000;
                z-index: 99;
                bottom: 17px;
                left: calc(50% - 33px);
                padding: 5px 10px;
                background: #e8e8e8;
                border-radius: 4px;
                @media screen and (max-width: 768px) {
                    display: none;
                }
            }
        }
        .fc-event {
            width: auto;
            position: relative;
            font-size: 12px;
            color: #000;
            z-index: 99;
            padding: 5px 10px;
            background: #e8e8e8;
            border-radius: 4px;
            top: 8px;
            text-align: center;
            @media screen and (max-width: 1024px) {
                top: -10px;
            }
            @media screen and (max-width: 991px) {
                top: 8px;
            }
            @media screen and (max-width: 500px) {
                top: 0;
            }
        }
    }
</style>
