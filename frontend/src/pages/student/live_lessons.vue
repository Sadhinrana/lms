<template>
    <div class="page">
        <div class="container">
            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <h1 class="h2">My Lessons</h1>
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
    import {Calendar} from "@fullcalendar/core";
    import dayGridPlugin from "@fullcalendar/daygrid";

    export default {
        data() {
            return {
                events: [],
                calendar: null
            };
        },
        mounted() {
            this.initializeFullCalendar();
        },
        methods: {
            liveLessonsByStudent: function (params) {
                let _this = this;
                this.$store.dispatch("enableLoading");

                axios
                    .get(this.routes.lesson.SESSIONS + '/student', {
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
                            start: info.view.activeStart.getUTCFullYear() + '-' + monthS + '-' + dtS,
                            end: info.view.activeEnd.getUTCFullYear() + '-' + monthE + '-' + dtE,
                        };

                        _this.$store.dispatch("enableLoading");
                        let events = _this.calendar.getEvents();
                        let len = events.length;
                        for (let i = 0; i < len; i++) {
                            events[i].remove();
                        }

                        _this.liveLessonsByStudent(params);
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
