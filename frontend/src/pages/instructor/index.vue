<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'home'}">Home</router-link>
          </li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        <h1 class="h2">Dashboard</h1>
        <div class="row">
            <div class="col">

                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="flex">
                            <h4 class="card-title">Total Exam Stats</h4>
                            <!-- <p class="card-subtitle">by course</p> -->
                        </div>
                        <!-- <a
                          class="btn btn-sm btn-primary"
                          href="instructor-earnings.html"
                        >Earnings</a> -->
                    </div>
                    <ul class="list-group list-group-fit mb-0">
                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>Total Exams taken</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span :class="['badge badge-pill',data.todayQuizzesTakenCount > data.yesterdayQuizzesTakenCount  ? 'badge-success' : 'badge-warning', 'btn_size']">{{ data.todayQuizzesTakenCount + ' / ' + data.todayQuizzesTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>Yesterday</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span :class="['badge badge-pill btn_size',data.todayQuizzesTakenCount < data.yesterdayQuizzesTakenCount  ? 'badge-success' : 'badge-warning', 'btn_size']">{{ data.yesterdayQuizzesTakenCount + ' / ' + data.yesterdayQuizzesTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>This week</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span class="badge badge-pill  badge-primary btn_size">{{ data.weeklyQuizzesTakenCount + ' / ' + data.weeklyQuizzesTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>This month</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span :class="['badge badge-pill',data.lastMonthQuizzesTakenCount < data.thisMonthQuizzessTakenCount  ? 'badge-success' : 'badge-warning', 'btn_size']">{{ data.thisMonthQuizzessTakenCount + ' / ' + data.thisMonthQuizzessTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>Last month</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span :class="['badge badge-pill',data.lastMonthQuizzesTakenCount > data.thisMonthQuizzessTakenCount  ? 'badge-success' : 'badge-warning', 'btn_size']">{{ data.lastMonthQuizzesTakenCount + ' / ' + data.lastMonthQuizzesTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>This year</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span class="badge badge-pill  badge-primary btn_size">{{ data.thisYearQuizzessTakenCount + ' / ' + data.thisYearQuizzessTakenPercentage+ '%' }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body"><strong>Last year</strong></a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span :class="['badge badge-pill','badge-warning', 'btn_size']">0 / 0 %</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- <li class="list-group-item">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <a class="text-body"><strong>Most taken quiz</strong></a>
                            </div>
                            <div class="media-right">
                              <div class="text-center">
                                <span class="badge badge-pill">{{ data.mostPopularQuiz.quiz.title + ', times taken: '+ data.mostPopularQuizTakenCount }}</span>

                              </div>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <a class="text-body"><strong>Quiz taken recently</strong></a>
                            </div>
                            <div class="media-right">
                              <div class="text-center">
                                <span class="badge badge-pill">{{ data.latestQuizAttempt.quiz.title + ', taken at: '+ data.latestQuizAttempt.created_at }}</span>
                              </div>
                            </div>
                          </div>
                        </li> -->
                    </ul>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="flex">
                            <h4 class="card-title">Company Stats</h4>
                            <!-- <p class="card-subtitle">Last 7 Days</p> -->
                        </div>
                        <!-- <a
                          href="instructor-earnings.html"
                          class="btn btn-sm btn-primary"
                        ><i class="material-icons">trending_up</i></a> -->
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="font-size: 15px;" scope="col">Companies</th>
                                <th style="font-size: 15px;" scope="col">Total Students</th>
                                <th style="font-size: 15px;" scope="col">Total Exams Today</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                    :key="company.id"
                                    v-for="company in data.companies"
                            >
                                <td class="btn_size">{{ company.name }}</td>
                                <td class="btn_size">{{ company.students_count }}</td>
                                <td class="btn_size">{{ company.daily_test_taken_count }}</td>
                            </tr>
                            </tbody>
                        </table>
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
                data: {
                    companies: [],
                    lastMonthQuizzesTakenCount: 0,
                    lastMonthQuizzesTakenPercentage: 0,
                    latestQuizAttempt: {
                        quiz: {id: 0},
                        created_at: "",
                    },
                    mostPopularQuiz: {
                        quiz: {
                            title: ""
                        }
                    },
                    mostPopularQuizTakenCount: 0,
                    thisMonthQuizzessTakenCount: 0,
                    thisMonthQuizzessTakenPercentage: 0,
                    thisYearQuizzessTakenCount: 0,
                    thisYearQuizzessTakenPercentage: 0,
                    todayQuizzesTakenCount: 0,
                    todayQuizzesTakenPercentage: 0,
                    totalQuizzesCount: 0,
                    totalQuizzesTaken: 0,
                    weeklyQuizzesTakenCount: 0,
                    weeklyQuizzesTakenPercentage: 0,
                    yesterdayQuizzesTakenCount: 0,
                    yesterdayQuizzesTakenPercentage: 0,

                }
            };
        },

        mounted() {
            // (function() {
            //   "use strict";
            //
            //   //Charts.init();
            //
            //   var earnings = [];
            //
            //   // Create a date range for the last 7 days
            //   var start = moment()
            //     .subtract(7, "days")
            //     .format("YYYY-MM-DD"); // 7 days ago
            //   var end = moment().format("YYYY-MM-DD"); // today
            //   var range = moment.range(start, end);
            //
            //   // Create the earnings graph data
            //   // Iterate the date range and assign a random ($) earnings value for each day
            //   range.by("days", function(moment) {
            //     earnings.push({
            //       y: Math.floor(Math.random() * 300) + 10,
            //       x: moment.toDate()
            //     });
            //   });
            //
            //   var Earnings = function(id, type = "roundedBar", options = {}) {
            //     options = Chart.helpers.merge(
            //       {
            //         barRoundness: 1.2,
            //         scales: {
            //           yAxes: [
            //             {
            //               ticks: {
            //                 callback: function(a) {
            //                   if (!(a % 10)) return "$" + a;
            //                 }
            //               }
            //             }
            //           ],
            //           xAxes: [
            //             {
            //               offset: true,
            //               ticks: {
            //                 padding: 10
            //               },
            //               maxBarThickness: 20,
            //               gridLines: {
            //                 display: false
            //               },
            //               type: "time",
            //               time: {
            //                 unit: "day"
            //               }
            //             }
            //           ]
            //         },
            //         tooltips: {
            //           callbacks: {
            //             label: function(a, e) {
            //               var t = e.datasets[a.datasetIndex].label || "",
            //                 o = a.yLabel,
            //                 r = "";
            //               return (
            //                 1 < e.datasets.length &&
            //                   (r +=
            //                     '<span class="popover-body-label mr-auto">' +
            //                     t +
            //                     "</span>"),
            //                 (r += '<span class="popover-body-value">$' + o + "</span>")
            //               );
            //             }
            //           }
            //         }
            //       },
            //       options
            //     );
            //
            //     var data = {
            //       datasets: [
            //         {
            //           label: "Earnings",
            //           data: earnings
            //         }
            //       ]
            //     };
            //
            //     Charts.create(id, type, options, data);
            //   };
            //
            //   // Create Chart
            //   Earnings("#earningsChart");
            // })();
            this.getDashboard();
        },

        methods: {
            getDashboard() {
                axios.get(this.routes.admin.GET_DASHBOARD).then(response => {
                    this.data = response.data;
                });
            }
        }
    };
</script>

<style>
    .btn_size {
        font-size: large !important;
    }

    strong {
        font-size: large !important;
    }
</style>
