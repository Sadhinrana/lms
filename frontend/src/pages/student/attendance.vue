<template>
    <div class="page">
        <div class="container page__container">
            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <div class="row">
                        <h1 class="h2 col-md-2">Teacher: </h1>
                        <div class="pt-1 col-md-4">
                            <select class="form-control custom-select" v-model="instructor_id" @change="initializeFullCalendar">
                                <option value="0" disabled>Choose teacher</option>
                                <option :value="instructor.id" v-for="instructor in instructors" :key="instructor.id">{{ instructor.first_name + " " + instructor.last_name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 text-right">
                            <h1 class="h2">Total Attendance: {{ totalAttendancesCurrentMonth }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container page__container full">
            <div class="card">
                <div class="card-body studentCalendar">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "@/main";
    import {Calendar} from '@fullcalendar/core';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from '@fullcalendar/interaction';

    export default {
        data() {
            return {
                added_success: false,
                added_error: false,
                added_error_messages: [],
                totalAttendancesCurrentMonth: 0,
                instructor_id: 0,
                instructors: [],
                events: [],
                calendar: null
            };
        },
        mounted() {
            this.getInstructors();
        },
        computed: {
            user: function () {
                return this.$store.getters.authUser
            }
        },
        methods: {
            getInstructors: function () {
                let _this = this;
                _this.$store.dispatch("enableLoading");

                if (_this.user.id) {
                    axios.get(_this.routes.user.GET_INSTRUCTORS)
                        .then(response => {
                            if (response.data.status === 200) {
                                _this.added_error = false;
                                _this.added_success = true;
                                _this.instructors = response.data.data;
                                if (_this.instructors.length) {
                                    _this.instructor_id = _this.instructors[0].id;
                                    this.initializeFullCalendar();
                                }
                                _this.$store.dispatch("disableLoading");
                            } else {
                                swal(response.data.msg);
                            }
                        })
                        .catch(err => {
                            _this.added_error_messages = err.response.data;
                            _this.added_error = true;
                            _this.added_success = false;
                            _this.$store.dispatch("disableLoading");
                        });
                } else {
                    eventBus.$on("authUserFetched", e => {
                        axios.get(_this.routes.user.GET_INSTRUCTORS)
                            .then(response => {
                                if (response.data.status === 200) {
                                    _this.added_error = false;
                                    _this.added_success = true;
                                    _this.instructors = response.data.data;
                                    if (_this.instructors.length) {
                                        _this.instructor_id = _this.instructors[0].id;
                                        this.initializeFullCalendar();
                                    }
                                    _this.$store.dispatch("disableLoading");
                                } else {
                                    swal(response.data.msg);
                                }
                            })
                            .catch(err => {
                                _this.added_error_messages = err.response.data;
                                _this.added_error = true;
                                _this.added_success = false;
                                _this.$store.dispatch("disableLoading");
                            });
                    });
                }
            },
            getEvents: function (params) {
                let _this = this;

                if (_this.user.id) {
                    axios.get(_this.routes.user.ATTENDANCES, {params: params})
                        .then(response => {
                            if (response.data.status === 200) {
                                _this.added_error = false;
                                _this.added_success = true;
                                _this.events = response.data.data;
                                _this.totalAttendancesCurrentMonth = response.data.attendancesRequestedMonth;
                                response.data.data.forEach(function (value, index, array) {
                                    let evt = JSON.parse(value);
                                    if(window.innerWidth <= 768){ evt.title = 'V'; }
                                    _this.calendar.addEvent(evt);
                                });
                                _this.$store.dispatch("disableLoading");
                            } else {
                                swal(response.data.msg);
                            }
                        })
                        .catch(err => {
                            _this.added_error_messages = err.response.data;
                            _this.added_error = true;
                            _this.added_success = false;
                            _this.$store.dispatch("disableLoading");
                        });
                } else {
                    eventBus.$on("authUserFetched", e => {
                        axios.get(_this.routes.user.ATTENDANCES, {params: params})
                            .then(response => {
                                if (response.data.status === 200) {
                                    _this.added_error = false;
                                    _this.added_success = true;
                                    _this.events = response.data.data;
                                    _this.totalAttendancesCurrentMonth = response.data.attendancesRequestedMonth;
                                    response.data.data.forEach(function (value, index, array) {
                                        let evt = JSON.parse(value);
                                        if(window.innerWidth <= 768){ evt.title = 'V'; }
                                        _this.calendar.addEvent(evt);
                                    });
                                    _this.$store.dispatch("disableLoading");
                                } else {
                                    swal(response.data.msg);
                                }
                            })
                            .catch(err => {
                                _this.added_error_messages = err.response.data;
                                _this.added_error = true;
                                _this.added_success = false;
                                _this.$store.dispatch("disableLoading");
                            });
                    });
                }
            },
            initializeFullCalendar: function () {
                let _this = this;
                let calendarEl = document.getElementById('calendar');
                calendarEl.innerHTML = '';
                _this.calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, interactionPlugin],
                    datesRender: function (info) {
                        let monthS = info.view.activeStart.getUTCMonth() + 1;
                        monthS = monthS < 10 ? '0' + monthS : monthS;
                        let dtS = info.view.activeStart.getUTCDate();
                        dtS = dtS < 10 ? '0' + dtS : dtS;
                        let monthE = info.view.activeEnd.getUTCMonth() + 1;
                        monthE = monthE < 10 ? '0' + monthE : monthE;
                        let dtE = info.view.activeEnd.getUTCDate();
                        dtE = dtE < 10 ? '0' + dtE : dtE;
                        let param = {
                            start: info.view.activeStart.getUTCFullYear() + '-' + monthS + '-' + dtS,
                            end: info.view.activeEnd.getUTCFullYear() + '-' + monthE + '-' + dtE,
                            month: new Date(info.view.title).getMonth() + 1,
                            year: new Date(info.view.title).getFullYear(),
                            instructor_id: _this.instructor_id,
                        };
                        _this.$store.dispatch("enableLoading");
                        let events = _this.calendar.getEvents();
                        let len = events.length;
                        for (let i = 0; i < len; i++) {
                            events[i].remove();
                        }
                        _this.getEvents(param);
                    },
                    dateClick: function (info) {
                        if (new Date(info.dateStr).getTime() !== new Date(moment().format('YYYY-MM-DD')).getTime() || _this.calendar.getEvents().some(element => (new Date(element.start)).getTime() === (new Date(info.date)).getTime())) {
                            return false;
                        }

                        let event = {
                            id: Date.now(),
                            title: moment().format('hh:mm A'),
                            start: moment(info.date).format('YYYY-MM-DD'),
                            color: '#23906a',
                            textColor: '#ffffff',
                            allDay: true
                        };

                        swal({
                            title: "Attendance",
                            text: "Are you sure to check in your Class?\n\nYou won't be able to undo this once you click OK.",
                            icon: "info",
                            buttons: true,
                        }).then((ok) => {
                            if (ok) {
                                _this.$store.dispatch("enableLoading");
                                axios.post(_this.routes.user.ATTENDANCES, {
                                    user_id: _this.user.id,
                                    instructor_id: _this.instructor_id,
                                    date: event.start,
                                    event: JSON.stringify(event)
                                })
                                    .then(response => {
                                        if (response.data.status === 200) {
                                            _this.added_error = false;
                                            _this.added_success = true;
                                            let evt = event;
                                            if(window.innerWidth <= 768){ evt.title = 'V'; }
                                            _this.calendar.addEvent(evt);
                                            _this.events.push(evt);
                                            _this.totalAttendancesCurrentMonth += 1;
                                        } else {
                                            swal(response.data.msg);
                                        }
                                        _this.$store.dispatch("disableLoading");
                                    }).catch(err => {
                                    _this.added_error_messages = err.response.data;
                                    _this.added_error = true;
                                    _this.added_success = false;
                                    _this.$store.dispatch("disableLoading");
                                });
                            }
                        });
                    }
                });
                _this.calendar.render();
            }
        }
    };
</script>

<style lang='scss'>
    @import '~@fullcalendar/core/main.css';
    @import '~@fullcalendar/daygrid/main.css';

    .page__container.full {
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
    .studentCalendar{
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
    .fc-past {
        opacity: 0.3;
    }
</style>
