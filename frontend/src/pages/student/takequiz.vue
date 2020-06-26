<template>
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__container">
            <div class="card-group" v-if="is_init_timer">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-0"><strong>Part {{ current_question_index }}</strong></h4>
                        <small class="text-muted-light">out of {{ total_question_count }}</small>
                    </div>
                </div>
            </div>

            <div class="card stopCopyTarget stopCopy">
                <div :key="question.id" :id="'rows_'+question.id" v-for="(question, index) in questions">
                    <div class="card-header">
                        <div class="media align-items-center">
                            <div class="media-left">
                                <h4 class="mb-0"><strong></strong></h4>
                            </div>
                            <div class="media-body">

                                <h4 class="card-title">
                                    {{ question.title }}
                                </h4>
                                <center v-if="question.question_image">
                                    <p>
                                        <img :src="question.question_image" alt="something wrong in image" width="300"
                                             class="rounded">
                                    </p>
                                </center>
                                <div v-html="question.description"
                                     v-if="!['fill_in_blank','drag_drop','dropdown','single_set'].includes(question.type)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-if="['fill_in_blank','dropdown'].includes(question.type)"
                         v-html="question.description"></div>
                    <div class="card-body"
                         v-if="question.type == 'matching' || question.type == 'matching_as_image' || question.type == 'matching_text_image'"
                         style="padding:0px !important;">
                        <div class="row" id="dragDropQuestion">

                            <div class="col-12">

                                <ul class="matching-questions" v-if="question.type == 'matching'">
                                    <li
                                            v-for="question in questions[0].child_questions"
                                    >
                                        <span class="question-item">{{question.title}}</span>
                                        <span
                                                class='droppableAnswer BlockWrapper BlockWrapper--isDropzone'
                                                data-dropzone='1'
                                                :question-id="question.id"
                                        >
					  </span>
                                    </li>
                                </ul>

                                <ul class="matching-questions" v-if="question.type == 'matching_text_image'">
                                    <li
                                            v-for="question in questions[0].child_questions"
                                    >
                                        <!-- :style="'margin-top:'+questions[0].max_length+'px'" -->
                                        <span class="question-item">{{question.title}}</span>
                                        <span
                                                class='droppableAnswer BlockWrapper BlockWrapper--isDropzone'
                                                data-dropzone='1'
                                                :question-id="question.id"
                                        >
					  </span>
                                    </li>
                                </ul>

                                <ul class="matching-questions" v-if="question.type == 'matching_as_image'">
                                    <li
                                            v-for="question in questions[0].child_questions"
                                    >
                                        <span class="question-item"><img v-if="question.title" :src="question.title"
                                                                         alt="something wrong in image" width="50"
                                                                         class="rounded"></span>
                                        <span
                                                class='droppableAnswer BlockWrapper BlockWrapper--isDropzone'
                                                data-dropzone='1'
                                                :question-id="question.id"
                                        >
					  </span>
                                    </li>
                                </ul>

                            </div>


                            <div class="col-11">
                                <ol class="matching-answers" v-if="question.type == 'matching'">
                                    <li
                                            v-for="answer in questions[0].child_questions.slice().reverse()"
                                            class="answer-item BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                            data-dropzone="1" style="background-color:#c7e6ff !important;"
                                    >
                                        <a

                                                class="Block Block--item1 Block--isDraggable"
                                                :data-id="answer.answers[0].id"
                                                title="Click to drag"
                                        >
                                            {{answer.answers[0].title}}
                                        </a>
                                    </li>
                                </ol>

                                <ol class="matching-answers" v-if="question.type == 'matching_as_image'">
                                    <li
                                            v-for="answer in questions[0].child_questions.slice().reverse()"
                                            class="answer-item BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                            data-dropzone="1" style="background-color:#c7e6ff !important;"
                                    >
                                        <a

                                                class="Block Block--item1 Block--isDraggable"
                                                :data-id="answer.answers[0].id"
                                                title="Click to drag"
                                        >
                                            <img v-if="answer.answers[0].title" :src="answer.answers[0].title"
                                                 alt="something wrong in image" width="50" class="rounded">
                                        </a>
                                    </li>
                                </ol>
                                <ol class="matching-answers" v-if="question.type == 'matching_text_image'">
                                    <li
                                            v-for="answer in questions[0].child_questions.slice().reverse()"
                                            class="answer-item BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                            data-dropzone="1" style="background-color:#c7e6ff !important;"
                                    >
                                        <a

                                                class="Block Block--item1 Block--isDraggable matching_as_image"
                                                :data-id="answer.answers[0].id"
                                                title="Click to drag"
                                        >
                                            <img v-if="answer.answers[0].title" :src="answer.answers[0].title"
                                                 alt="something wrong in image" width="50" class="rounded">
                                        </a>
                                    </li>
                                </ol>


                            </div>

                        </div>
                    </div>
                    <div class="card-body" v-if="question.type == 'drag_drop'">
                        <div class="row" id="dragDropQuestion">
                            <div class="col-12"
                                 v-html="question.description">
                            </div>

                            <div class="col-11 sorting-horizontal">
                                <ul class="drag-drop-horizontal-list">
                                    <li class="BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                        v-for="(answer, index) in question.answers"
                                        :data-order="index"
                                        data-dropzone="1">
                                        <a
                                                href="#"
                                                class="Block Block--item1 Block--isDraggable"
                                                title="Click to drag"
                                                :answer-id="answer.id"
                                        >{{answer.title}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-else>
                        <div class="container-full" v-html="question.description"
                             v-if="question.type == 'open_end'"></div>
                        <div
                                style="display:flex; justify-content:center;align-items:center;padding-bottom:50px;"
                                v-if="question.audio_file">
                            <div :id="'outer_'+question.id" class="outer" style="display:none;"></div>
                            <audio id="my_audio" @play="checkAudio(question.id)"
                                   controls controlsList="nodownload">
                                <source
                                        :src="routes.server_api + question.audio_file"
                                        type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>

                        </div>
                        <div
                                class="card"
                                v-if="question.video_link"
                                style="display:flex; justify-content:center;align-items:center">
                            <div
                                    class="embed-responsive embed-responsive-16by9"
                                    style="width:560px;height:315px">
                                <iframe
                                        class="embed-responsive-item"
                                        width="560"
                                        height="315"
                                        :src="question.video_link"
                                        allowfullscreen=""
                                ></iframe>
                            </div>
                        </div>

                        <template
                                v-if="['single_choice','yes_no','true_false','single_choice_as_image'].indexOf(question.type) !== -1">
                            <div
                                    :key="answer.id"
                                    v-for="answer in question.answers"
                                    class="form-group">
                                <div class="custom-control custom-radio">
                                    <input
                                            :id="'answer-'+answer.id"
                                            type="radio"
                                            :name="'question'+question.id"
                                            :value="answer.id"
                                            v-model="question.selected_answer_id"
                                            class="custom-control-input"
                                    >
                                    <label v-if="question.type != 'single_choice_as_image'"
                                           :for="'answer-'+answer.id"
                                           class="custom-control-label"
                                    >{{ answer.title }}</label>

                                    <label v-if="question.type == 'single_choice_as_image'"
                                           :for="'answer-'+answer.id"
                                           class="custom-control-label"
                                    ><img :src="answer.title" alt="something wrong in image" width="150"
                                          class="rounded"></label>

                                </div>
                            </div>
                        </template>
                        <template v-else-if="question.type == 'math_set'">
                            <center class="katex_question" v-if="question.formula">
                                <katex-element v-bind:expression="question.formula"/>
                            </center>
                            <hr v-if="question.formula">

                            <div
                                    :key="answer.id"
                                    v-for="answer in question.answers"
                                    class="form-group"
                            >
                                <div class="custom-control custom-radio">
                                    <input
                                            :id="'answer-'+answer.id"
                                            type="radio"
                                            :name="'question'+question.id"
                                            :value="answer.id"
                                            v-model="question.selected_answer_id"
                                            class="custom-control-input"
                                    >
                                    <label
                                            :for="'answer-'+answer.id"
                                            class="custom-control-label"
                                    >
                                        <katex-element v-bind:expression="answer.title"/>
                                    </label>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="['single_set'].indexOf(question.type) !== -1">
                            <div class="row">
                                <div class="col-sm-8 ">
                                    <div class="singleset" v-html="question.description"
                                         v-if="['single_set'].includes(question.type)"></div>
                                </div>
                                <div class="col-sm-4 ">
                                    <div class="singleset">
                                        <div v-for="(child_question, index) in question.child_questions"
                                             class="form-group">

                                            <div>
                                                <!--<small v-if="child_question.title">Question {{index+1}}</small>-->
                                                <p style="margin-top: 1rem !important;">{{child_question.title}}</p>
                                            </div>

                                            <div v-for="answer in child_question.answers">
                                                <div class="inputGroup">
                                                    <input
                                                            :id="'answer-'+answer.id"
                                                            type="radio"
                                                            :name="'question'+child_question.id"
                                                            :value="answer.id"
                                                            v-model="child_question.selected_answer_id"

                                                    >
                                                    <label :for="'answer-'+answer.id">{{ answer.title }}</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="question.type == 'multiple_choice'">
                            <div
                                    :key="answer.id"
                                    v-for="answer in question.answers"
                                    class="form-group"
                            >
                                <div class="custom-control custom-checkbox">
                                    <input
                                            :id="'answer-'+answer.id"
                                            type="checkbox"
                                            :name="'question'+question.id"
                                            :value="answer.id"
                                            v-model="question.selected_answers_id"
                                            class="custom-control-input"
                                    >
                                    <label
                                            :for="'answer-'+answer.id"
                                            class="custom-control-label"
                                    >{{ answer.title }}</label>
                                </div>
                            </div>
                        </template>

                        <template v-else-if="question.type == 'open_end'">
                            <div class="form-group col">
                                <label for="quiz_title" class="col-sm-3 col-form-label form-label">Your Answer:</label>
                                <div class="container-full">
                                    <textarea id="quiz_title" type="text" class="form-control"
                                              placeholder="Type your answer here" v-model="answer"></textarea>
                                </div>
                            </div>
                        </template>

                        <template v-else-if="question.type == 'sorting_horizontal'">
                            <div class="sorting-horizontal">
                                <ul class="sorting-horizontal-list">
                                    <li class="sorting-horizontal-item text-warning"
                                        v-for="(answer, index) in question.answers" :data-order="index"
                                        :data-id="answer.id" :data-question_id="question.id"
                                        @mousedown="dragStart(question.id)"><span
                                            class="summary_span">{{index+1}}</span>
                                        {{ answer.title }}
                                    </li>
                                </ul>
                            </div>
                        </template>

                        <template v-else-if="question.type == 'summary'">
                            <div class="summary">
                                <ul class="summary-list">
                                    <li class="summary-item text-primary" v-for="(answer, index) in question.answers"
                                        :data-order="index" :data-id="answer.id"
                                        :data-question_id="question.id">
                                        <span class="summary_span">{{index+1}}</span>
                                        {{ answer.title }}
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </div>
                    <!-- ======================Child questions=================== -->
                    <div v-if="!['matching_as_image','matching_text_image','single_set'].includes(question.type)">
                        <div
                                class="child-question" :id="'row_'+child_question.id"
                                v-if="question.child_questions.length !== 0 && question.type != 'matching'"
                                v-for="child_question in question.child_questions"
                                style="padding:35px 15px 0px 15px; border-top:solid 1px #ccc;">

                            <strong>{{child_question.title}}</strong>

                            <center v-if="child_question.question_image">
                                <p>
                                    <img :src="child_question.question_image" alt="something wrong in image" width="300"
                                         class="rounded">
                                </p>
                            </center>

                            <div v-html="child_question.description"
                                 v-if="!['fill_in_blank','drag_drop','dropdown','single_set'].includes(question.type)"></div>

                            <div style="padding-bottom:20px;padding-top: 20px;" v-if="child_question.audio_file">
                                <center>
                                    <div :id="'outer-child_'+child_question.id" class="outer-child"
                                         style="display:none;"></div>
                                    <audio id="my_audio" @play="checkAudio(child_question.id)" controls
                                           controlsList="nodownload">
                                        <source :src="routes.server_api + child_question.audio_file" type="audio/mpeg">
                                        Your browser does not support the audio tag.
                                    </audio>
                                </center>
                            </div>

                            <div class="card-body" v-if="['fill_in_blank','dropdown'].includes(question.type)"
                                 v-html="child_question.description">
                            </div>
                            <div class="card-body" v-if="child_question.type == 'drag_drop'">
                                <div class="row" id="dragDropQuestion">
                                    <div class="col-12" v-html="child_question.description"></div>
                                    <div class="col-11 sorting-horizontal">
                                        <ul class="drag-drop-horizontal-list">
                                            <li class="BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                                v-for="(child_answer, index) in child_question.answers"
                                                :data-order="index"
                                                data-dropzone="1">
                                                <a href="#" class="Block Block--item1 Block--isDraggable"
                                                   title="Click to drag">
                                                    {{child_answer.title}}
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- <ol> -->
                                        <!-- <li -->
                                        <!-- v-for="child_answer in child_question.answers" -->
                                        <!-- class="BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied" -->
                                        <!-- data-dropzone="1" style="background-color:#c7e6ff !important;" -->
                                        <!-- > -->
                                        <!-- <a -->
                                        <!-- href="#" -->
                                        <!-- class="Block Block--item1 Block--isDraggable" -->
                                        <!-- title="Click to drag" -->
                                        <!-- > -->
                                        <!-- {{child_answer.title}} -->
                                        <!-- </a> -->
                                        <!-- </li> -->
                                        <!-- </ol> -->
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" v-if="child_question.type == 'matching'"
                                 style="padding:0px !important;">
                                <div class="row" id="dragDropQuestion">


                                    <div class="col-12">
                                        <ul class="matching-questions">
                                            <li
                                                    v-for="question in child_question.child_questions"
                                                    :style="'margin-top:'+child_question.max_length+'px'"
                                            >
                                                <span class="question-item">{{question.title}}</span>
                                                <span
                                                        class='droppableAnswer BlockWrapper BlockWrapper--isDropzone '
                                                        data-dropzone='1'
                                                        :question-id="question.id"
                                                >
                      </span>
                                            </li>
                                        </ul>
                                    </div>


                                    <div class="col-11">
                                        <ol class="matching-answers">
                                            <li
                                                    v-for="answer in child_question.child_questions"
                                                    class="answer-item BlockWrapper BlockWrapper--isDropzone draggable-dropzone--occupied"
                                                    data-dropzone="1" style="background-color:#c7e6ff !important;"
                                            >
                                                <a
                                                        href="#"
                                                        class="Block Block--item1 Block--isDraggable"
                                                        :data-id="answer.answers[0].id"
                                                        title="Click to drag"
                                                >
                                                    {{answer.answers[0].title}}
                                                </a>
                                            </li>

                                        </ol>
                                    </div>


                                </div>
                            </div>

                            <div class="card-body" v-else>
                                <div
                                        class="card"
                                        v-if="child_question.video_link"
                                        style="display:flex; justify-content:center;align-items:center"
                                >
                                    <div
                                            class="embed-responsive embed-responsive-16by9"
                                            style="width:560px;height:315px"
                                    >
                                        <iframe
                                                class="embed-responsive-item"
                                                width="560"
                                                height="315"
                                                :src="child_question.video_link"
                                                allowfullscreen=""
                                        ></iframe>
                                    </div>
                                </div>

                                <template
                                        v-if="['single_choice','yes_no','true_false','single_choice_as_image'].indexOf(child_question.type) !== -1">
                                    <div
                                            :key="child_answer.id"
                                            v-for="child_answer in child_question.answers"
                                            class="form-group"
                                    >
                                        <div class="custom-control custom-radio">
                                            <input
                                                    :id="'answer-'+child_answer.id"
                                                    type="radio"
                                                    :name="'question'+child_question.id"
                                                    :value="child_answer.id"
                                                    v-model="child_question.selected_answer_id"
                                                    class="custom-control-input"
                                            >
                                            <label v-if="child_question.type != 'single_choice_as_image'"
                                                   :for="'answer-'+child_answer.id"
                                                   class="custom-control-label"
                                            >{{ child_answer.title }}</label>

                                            <label v-if="child_question.type == 'single_choice_as_image'"
                                                   :for="'answer-'+child_answer.id"
                                                   class="custom-control-label"
                                            ><img :src="child_answer.title" alt="something wrong in image" width="150"
                                                  class="rounded"></label>

                                        </div>
                                    </div>
                                </template>

                                <template v-else-if="child_question.type == 'multiple_choice'">
                                    <div
                                            :key="child_answer.id"
                                            v-for="child_answer in child_question.answers"
                                            class="form-group"
                                    >
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                    :id="'answer-'+child_answer.id"
                                                    type="checkbox"
                                                    :name="'question'+child_question.id"
                                                    :value="child_answer.id"
                                                    v-model="child_question.selected_answers_id"
                                                    class="custom-control-input"
                                            >
                                            <label
                                                    :for="'answer-'+child_answer.id"
                                                    class="custom-control-label"
                                            >{{ child_answer.title }}</label>
                                        </div>
                                    </div>
                                </template>

                                <template v-else-if="child_question.type == 'sorting_horizontal'">
                                    <div class="sorting-horizontal">
                                        <ul class="sorting-horizontal-list">
                                            <li class="sorting-horizontal-item text-warning"
                                                v-for="(answer, index) in child_question.answers"
                                                :data-order="index"
                                                :data-id="answer.id"
                                                :data-question_id="child_question.id"
                                                @mousedown="dragStart(child_question.id)">
                                                <span class="summary_span">{{index+1}}</span>
                                                {{ answer.title }}
                                            </li>
                                        </ul>
                                    </div>
                                </template>

                                <template v-else-if="child_question.type == 'summary'">
                                    <div class="summary">
                                        <ul class="summary-list">
                                            <li
                                                    class="summary-item text-primary"
                                                    v-for="(answer, index) in child_question.answers.slice().reverse()"
                                                    :data-order="index"
                                                    :data-id="answer.id"
                                                    :data-question_id="child_question.id"
                                            ><span class="summary_span">{{index+1}}</span>
                                                {{ answer.title }}
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer" v-if="questions.length>0">
                <div class="container">
                    <div class="row" style="padding-bottom: 10px !important; padding-top: 10px; !important">
                        <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                            <a href="#"
                               id=""
                               class="btn btn-warning"
                               @click.prevent="getPreviousQuestion()"><i class="material-icons btn__icon--left">keyboard_arrow_left</i>Back</a>
                        </div>
                        <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                            <center><a class="btn btn-secondary" style="color: #fff; font-size: 17px; padding: 7px;">{{
                                this.hours }}:{{ this.minutes }}:{{ this.seconds }}</a></center>
                        </div>
                        <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                            <a href="#"
                               @click.prevent="submitAnswer(true)"
                               class="btn btn-success float-right ">Next<i class="material-icons btn__icon--right">keyboard_arrow_right</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // let route_name= this.$route.name;
    // alert(route_name);
    import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";
    import {Sortable, Droppable, Swappable} from "@shopify/draggable";

    export default {
        data() {
            return {
                isCheckDragdrop: 0,
                isCheckDragdropValue: [],
                isChecksortHorizontal: 0,
                questions: [],
                is_check_questions: [],
                answer: "",
                child_questions: [],
                attempt_id: 0,
                quiz_duration: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                intervalTimeTick: null,
                is_init_timer: false,
                current_question_index: 0,
                child_index: 0,
                total_question_count: 0,
                timer_time: null,
                count_play: 0,
                child_index_ans: [],
                isPrivateMode: false,
                isNext: true,
            };
        },

        mounted() {
            // this.checkIsPrivate();
            this.checkSession();
            this.rightClickDisable();
            Array.prototype.swap = function (x, y) {
                var b = this[x];
                this[x] = this[y];
                this[y] = b;
                return this;
            };
            $("body").on('mousemove', function (event) {
                if ($('.card.stopCopyTarget').length > 0) {
                    if (!$('.card.stopCopyTarget').hasClass('stopCopy')) {
                        $('.card.stopCopyTarget').addClass('stopCopy');
                    }
                }
            });
        },

        methods: {
            checkSession() {
                if ((this.$session.get("quiz_id") == this.$route.params.quiz_id) && (this.$route.params.question_id)) {
                    this.$session.start();
                    this.attempt_id = this.$session.get("attempt_id");
                    if (this.$session.get("startTime")) {
                            this.initQuizClock(this.$session.get("startTime"), this.$session.get("timezone"));
                            this.getSpecificQuestionByID();
                    }
                    console.log('session_alive');
                } else {
                    console.log('destroy_session');
                    //this.$session.destroy();
                    this.$session.start();
                        this.takeOrContinueQuiz();
                }
            },

            dragStart(getId) {
                this.isCheckDragdrop = 1;
                if (this.isCheckDragdropValue.indexOf(getId) === -1) {
                    this.isCheckDragdropValue.push(getId);
                }
            },

            getSpecificQuestionByID() {
                console.log(this.attempt_id);
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.question.GET_PREVIOUS_QUESTION, {
                        params: {
                            quiz_id: this.$route.params.quiz_id,
                            attempt_id: this.attempt_id,
                            question_id: this.$route.params.question_id
                        }
                    })
                    .then(response => {

                        this.isChecksortHorizontal = 1;
                        this.is_check_questions = response.data.questions;
                        var new_queue = this.is_check_questions[0];


                        new_queue.child_questions.forEach(key => {
                            key.student_answer.forEach(keys => {
                                setTimeout(function () {
                                    $("#answer-" + keys.answer_id).trigger("click");
                                }, 1000);
                            });
                        });


                        this.handleGetSingleQuestionResponse(response);
                        this.$store.dispatch("disableLoading");
                    });
            },

            handleGetSingleQuestionResponse(response) {
                response.data.questions.forEach(q => {
                    this.convertQuestion(q);
                    if (q.child_questions.length > 0 && q.type != "matching") {
                        _.forEach(q.child_questions, child_question => {
                            this.convertQuestion(child_question);
                        });
                    }
                });
                if (response.data.questions.length > 0) {
                    this.questions = response.data.questions;
                    this.current_question_index = response.data.questionIndex;
                    this.total_question_count = response.data.totalQuestionsInQuizCount


                    if (0 < this.questions[0].child_questions.length && this.questions[0].type == "parent") {
                        this.questions[0].child_questions.forEach(child_question => {

                            this.renderAnsweredQuestion(child_question);
                        })
                    } else {
                        this.renderAnsweredQuestion(this.questions[0]);
                    }
                }

                //Set timeout so it will render dragNdrop, still dont know another way to do it
                var self = this;
                setTimeout(function () {
                    self.initDragDrop();
                }, 1000);
            },

            getPreviousQuestion() {
                this.isCheckDragdropValue = [];
                this.$store.dispatch("enableLoading");

                this.submitAnswer(false);

                axios
                    .get(this.routes.question.GET_PREVIOUS_QUESTION, {
                        params: {
                            quiz_id: this.$route.params.quiz_id,
                            attempt_id: this.attempt_id,
                            current_question_id: this.questions[0].id
                        }
                    })
                    .then(response => {
                        this.isChecksortHorizontal = 1;
                        this.is_check_questions = response.data.questions;
                        var new_queue = this.is_check_questions[0];

                        new_queue.child_questions.forEach(key => {
                            key.student_answer.forEach(keys => {
                                setTimeout(function () {
                                    $("#answer-" + keys.answer_id).trigger("click");
                                }, 1000);
                            });
                        });

                        this.$store.dispatch("disableLoading");
                        this.handleGetSingleQuestionResponse(response);
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });

                this.scrollToTop();
            },

            renderAnsweredQuestion(q) {
                switch (q.type) {
                    case "single_choice":
                    case "math_set":
                    case "single_choice_as_image":
                    case "yes_no":
                    case "true_false":
                        q.answers.forEach(a => {
                            q.student_answer.forEach(sa => {
                                if (sa.answer_id === a.id) {
                                    q.selected_answer_id = sa.answer_id
                                }
                            })
                        })
                        break;
                    case "multiple_choice":
                        q.selected_answers_id = []
                        q.answers.forEach(a => {
                            q.student_answer.forEach(sa => {
                                if (sa.answer_id === a.id) {
                                    q.selected_answers_id.push(sa.answer_id);
                                }
                            })
                        })
                        break;

                    case "fill_in_blank":
                        let inputElements = document.getElementsByClassName("inputAnswer");
                        this.$nextTick(() => {
                            for (var i = 0; i < inputElements.length; i++) {
                                if (0 < q.student_answer.length) {
                                    let answer = q.student_answer.find(answer => {
                                        return answer.answer_id == inputElements[i].id
                                    });
                                    inputElements[i].value = answer.answer_content
                                }
                            }
                        })
                        break;
                    case "dropdown":
                        let selectElements = document.getElementsByClassName("selectAnswer");
                        this.$nextTick(() => {
                            for (var i = 0; i < selectElements.length; i++) {
                                if (0 < q.student_answer.length) {
                                    let answer = q.student_answer.find(answer => {
                                        return answer.answer_id == selectElements[i].id
                                    })
                                    //selectElements[i].value = answer.answer_content
                                }
                            }
                        })
                        break;
                    case "matching":
                    case "matching_as_image":
                    case "matching_text_image":

                        let droppableElements = document.getElementsByClassName("droppableAnswer");
                        let draggableElements = document.getElementsByClassName("Block--isDraggable");
                        this.$nextTick(() => {
                            for (var i = 0; i < droppableElements.length; i++) {
                                let droppableID = droppableElements[i].getAttribute("question-id")
                                let child_question = q.child_questions.find(question => {
                                    return question.id == droppableID
                                })
                                if (0 < child_question.student_answer.length) {
                                    let student_answer = $("[data-id=" + child_question.student_answer[0].answer_id + "]");
                                    student_answer.parent().removeClass("draggable-dropzone--occupied");
                                    student_answer.appendTo($("[question-id=" + child_question.id + "]"));
                                    student_answer.parent().addClass("draggable-dropzone--occupied");
                                }
                            }

                        })
                        break;

                    case "drag_drop":
                        let dropElements = document.getElementsByClassName("droppableAnswer");
                        let dragElements = document.getElementsByClassName("Block--isDraggable");
                        this.$nextTick(() => {
                            let dropContent = []
                            for (let dragEl of dragElements) {
                                dropContent.push(dragEl.innerHTML)
                            }

                            for (var i = 0; i < dropContent.length; i++) {
                                if (0 < q.student_answer.length) {
                                    var student_answer = q.student_answer.find(answer => {
                                        return answer.answer_content == dropContent[i]
                                    })
                                    var dragAnswer = $(".Block--isDraggable:contains('" + student_answer.answer_content + "')");
                                    var dropAnswer = $("[data-id=" + student_answer.answer_id + "]");
                                    dragAnswer.parent().removeClass("draggable-dropzone--occupied");
                                    dragAnswer.appendTo(dropAnswer)
                                    dragAnswer.parent().addClass("draggable-dropzone--occupied");

                                }
                            }

                        })
                        break;

                    case "sorting_horizontal":
                        console.log(q.type);
                        if (q.type == 'sorting_horizontal') {
                            q.description = '';
                        }
                        if (q.student_answer[0] !== undefined) {
                            let studentAnswer = q.student_answer[0].answer_content.split(',');
                            let answers = [];
                            studentAnswer.forEach(function (v, i) {
                                if (v !== '0' && v !== '') {
                                    const index = q.answers.map(e => e.id.toString()).indexOf(v.toString());
                                    answers.push(q.answers[index]);
                                }
                            });
                            console.log(answers);
                            if (answers.length > 0) {
                                q.answers = answers;
                            }
                        }
                        break;


                }
            },

            initQuizClock(time, timezone) {
                let quiz_duration = this.$session.get("quiz_duration");

                this.intervalTimeTick = setInterval(() => {
                    let startTime = moment(time);
                    let endTime = startTime
                        .add(quiz_duration, "minutes")
                        .format("DD/MM/YYYY HH:mm:ss");
                    let currentTime = new Date().toLocaleString("en-US", {timeZone: timezone});

                    currentTime = new Date(currentTime);
                    let result = moment
                        .utc(
                            moment(endTime, "DD/MM/YYYY HH:mm:ss").diff(
                                moment(currentTime, "DD/MM/YYYY HH:mm:ss")
                            )
                        )
                        .format("HH:mm:ss")
                        .split(":");
                    this.hours = result[0];
                    this.minutes = result[1];
                    this.seconds = result[2];

                    if (
                        parseInt(this.hours) == 0 &&
                        parseInt(this.minutes) == 0 &&
                        parseInt(this.seconds) == 0
                    ) {
                        clearInterval(this.intervalTimeTick);
                        swal("Exam time finished. Your test will be automatically submitted. Click OK");
                        this.markQuizAttemptAsDone();
                    }
                }, 1000);
            },

            markQuizAttemptAsDone() {
                axios
                    .post(this.routes.quiz.MARK_QUIZ_ATTEMPT_AS_DONE, {
                        quiz_id: this.$route.params.quiz_id,
                        attempt_id: this.attempt_id
                    })
                    .then(response => {
                        this.$session.destroy();
                        this.$router.push({
                            name: "quiz_result",
                            params: {quiz_id: this.$route.params.quiz_id, attempt_id: this.attempt_id}
                        });
                    });
            },

            takeOrContinueQuiz() {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.quiz.TAKE_OR_CONTINUEING_QUIZ, {
                        id: this.$route.params.quiz_id,
                        timer: this.timer_time
                    })
                    .then(response => {
                        if (response.data.studentQuizBlock) {
                            let message = "You have been locked to take quizzes";
                            if (response.data.studentQuizBlock.company_id) {
                                message += ", contact company head for more details";
                            } else {
                                message += ", contact headmaster for more details";
                            }
                            if (response.data.studentQuizBlock.description) {
                                message += ", reason: " + response.data.studentQuizBlock.description
                            }
                            swal(message);
                            this.$router.push("/");
                            return;
                        }
                        this.attempt_id = response.data.quizAttempt.id;
                        this.quiz_duration = response.data.quizAttempt.quizDuration;
                        if (this.quiz_duration) {
                            /*if(this.$session.get("startTime")){
                                this.initQuizClock(this.$session.get("startTime"), response.data.timeZone);
                            }else{*/
                            this.$session.set("startTime", response.data.quizAttempt.start_time);
                            this.$session.set("timezone", response.data.timeZone);
                            this.$session.set("quiz_duration", this.quiz_duration);
                            this.$session.set("quiz_id", this.$route.params.quiz_id);
                            this.$session.set("attempt_id", this.attempt_id);
                            this.initQuizClock(response.data.quizAttempt.start_time, response.data.timeZone);
                            //}
                            this.is_init_timer = true;
                        }
                        if (this.$route.name == 'take_quiz') {
                            this.getQuestionFromQuiz(null);
                        } else {
                            this.getSpecificQuestionByID();
                        }
                        if (this.isPrivateMode==true){
                            swal({
                                title: 'WARNING!',
                                icon: 'warning',
                                text: 'Candidates are not permitted to use:\n' +
                                    'Mobile Phones\n' +
                                    'Translations\n' +
                                    'Students’ Books\n' +
                                    'Breaking rules will be fined by\n\n' +
                                    '           CELT Colleges Manager\n',
                            });
                            setTimeout(() => {
                                $('.sweet-alert').find('.lead.text-muted').removeClass('text-muted');
                                $('.sweet-alert').find('.lead').css({
                                    fontWeight: 700
                                });
                            }, 400);
                            this.$store.dispatch("disableLoading");
                        }


                    })
                    .catch(e => {
                        this.$session.destroy();
                        this.$store.dispatch("disableLoading");
                        if (e.response && e.response.data && e.response.data.error) {
                            swal(e.response.data.error.message);
                            this.$router.push("/");
                        }
                    });
            },

            getChildQuestionsAndAnswer(parent_id) {
                axios
                    .get(this.routes.question.GET_CHILD_QUESTIONS + parent_id)
                    .then(res => {
                        this.child_questions = res.data;
                    })
                    .catch(err => {
                    });
            },

            initDragDrop() {
                if ($(".sorting-horizontal").length > 0) {
                    const swappable = new Swappable(
                        document.querySelectorAll(".sorting-horizontal"),
                        {
                            draggable: "li",
                            mirror: {
                                constrainDimensions: true,
                                cursorOffsetX: 250,
                                cursorOffsetY: 90
                            }
                        }
                    );
                }
                if ($(".summary-list").length > 0) {
                    const swappable = new Swappable(
                        document.querySelectorAll(".summary-list"),
                        {
                            draggable: "li",
                            mirror: {
                                constrainDimensions: true,
                                cursorOffsetX: 250,
                                cursorOffsetY: 90
                            }
                        }
                    );
                }

                const containers = document.querySelectorAll("#dragDropQuestion");
                const droppable = new Droppable(containers, {
                    draggable: ".Block--isDraggable",
                    dropzone: ".BlockWrapper--isDropzone",
                    mirror: {
                        constrainDimensions: true,
                        cursorOffsetX: 250,
                        cursorOffsetY: 90
                    }
                });
                let droppableOrigin;
                droppable.on("drag:start", evt => {
                    droppableOrigin = evt.originalSource.parentNode.dataset.dropzone;
                });
                droppable.on("droppable:dropped", evt => {
                    if (droppableOrigin !== evt.dropzone.dataset.dropzone) {
                        evt.cancel();
                    }
                });
            },

            getQuestionFromQuiz(except_question_id) {

                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.question.GET_QUESTION_BY_QUIZ_ID, {
                        params: {
                            quiz_id: this.$route.params.quiz_id,
                            except_question_id: except_question_id,
                            attempt_id: this.attempt_id
                        }
                    })
                    .then(response => {
                        if (!response.data.questions || response.data.questions.length == 0) {
                            this.goToPageReviewQuiz();
                            throw new Error("This quiz attempt has been completed");
                            return;
                        }
                        response.data.questions.forEach(q => {
                            this.convertQuestion(q);
                            if (q.child_questions.length > 0 && q.type != "matching") {
                                _.forEach(q.child_questions, child_question => {
                                    this.convertQuestion(child_question);
                                });
                            }
                        });

                        this.questions = response.data.questions;
                        /*if(this.questions[0].type == 'single_set'){
                          this.child_index = response.data.child_index;
                            var i;
                            var ans = [];
                            for(i=1; i<= this.child_index; i++){
                              var anss = [];
                              this.questions[0].answers.forEach(arr => {
                                  if(arr.child_index == i){
                                     anss.push(arr);
                                     ans[i] = anss;
                                  }
                              });
                            }
                            ans = ans.filter(function () { return true });
                            this.questions[0].answers = ans;
                        }*/
                        this.current_question_index = response.data.questionIndex;
                        this.total_question_count = response.data.totalQuestionsInQuizCount

                        var self = this;
                        this.$nextTick(() => {
                            self.initDragDrop();
                        });
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },

            goToPageReviewQuiz() {
                this.$router.push({
                    name: 'review_quiz_question_and_answer',
                    params: {
                        quiz_id: this.$route.params.quiz_id,
                        attempt_id: this.attempt_id
                    }
                })
            },

            shuffle(array) {
                var currentIndex = array.length, temporaryValue, randomIndex;
                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            },

            sorting_answers(getArr, answerArr) {
                let newArr = {};
                let finalArr = [];

                getArr.forEach(itemz => {
                    newArr[itemz.id] = itemz;
                })
                let tmpArr = [];
                answerArr.forEach(items => {
                    items.answer_content = items.answer_content == null ? '' : items.answer_content;
                    items.answer_content = items.answer_content.replace(/,\s*$/, "");
                    var tmpobj = {
                        answer_id: items.answer_content.split(',')
                    };
                    tmpArr.push(tmpobj);
                })

                tmpArr.forEach(check => {
                    check.answer_id.forEach(get_value => {

                        $.each(newArr, function (key, valuei) {
                            if (get_value == key) {
                                finalArr.push(valuei);
                            }
                        });
                    })
                })

                console.log('check1');
                console.log(finalArr);
                console.log('check2');
                setTimeout(function () {
                    return finalArr;
                }, 3000);


            },

            jack(array) {
                var currentIndex = array.length, temporaryValue, randomIndex;
                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            },

            convertQuestion(q) {
                q.selected_answer_id = null;
                q.selected_answers_id = [];
                q.answer_content = null;
                q.description.replace('[{"insert":"\\n"}]', "");

                if (['fill_in_blank', 'drag_drop', 'dropdown'].includes(q.type)) {
                    if (this.IsJsonString(q.description)) {
                        var converter = new QuillDeltaToHtmlConverter(
                            JSON.parse(q.description)
                        );
                        q.description = converter.convert();
                        //--------This method helping replace from index to index-------//
                        String.prototype.replaceBetween = function (start, end, what) {
                            return this.substring(0, start) + what + this.substring(end);
                        };

                        const qtemp = Array.from(q.answers);
                        let longestAnswer = qtemp.sort(function (a, b) {
                            return b.title.length - a.title.length;
                        })[0];
                        q.max_length = longestAnswer.title.length;


                        if (q.type == "dropdown") {

                            var maketag = q.description;
                            var slpitedDesc = maketag.split(/[{}]/);
                            var newDesc;
                            slpitedDesc.forEach(desc => {
                                if (desc.includes("<s>answer-") && desc) {
                                    var temp = desc.split("|");

                                    var currentIndex = temp.length, temporaryValue, randomIndex;
                                    while (0 !== currentIndex) {
                                        randomIndex = Math.floor(Math.random() * currentIndex);
                                        currentIndex -= 1;
                                        temporaryValue = temp[currentIndex];
                                        temp[currentIndex] = temp[randomIndex];
                                        temp[randomIndex] = temporaryValue;
                                    }

                                    newDesc += "{";
                                    temp.forEach(t1 => {
                                        newDesc += t1;
                                    });
                                    newDesc += "}";

                                } else if (desc) {
                                    newDesc += desc;
                                }
                            });

                            maketag = newDesc;
                            console.log('prefix' + maketag);
                            var index = 0;
                            q.answers.forEach(arr => {
                                index++;
                                arr.title = arr.title.replace("*", "");


                                if (q.description) {
                                    var select_start = maketag.replace("{", "<select id='select_" + index + "' class='selectAnswer'>");
                                    maketag = select_start;
                                    var select_end = maketag.replace("}", "</select>");
                                    maketag = select_end;

                                    var option_start = maketag.replace("<s>answer-" + arr.id + "-" + arr.title.length + "", "<option class='option_" + index + "' value='" + arr.id + "'>" + arr.title + "");
                                    maketag = option_start;

                                    var option_end = maketag.replace("</s>", "</option>");

                                    maketag = option_end;

                                }

                            });
                            console.log('sufix' + maketag);
                            maketag = maketag.replace("undefined", "");
                            q.description = maketag;
                        }


                        let countLoop = 0;
                        while (q.description.indexOf("answer-") !== -1) {
                            var index_of_answer = q.description.indexOf("answer-");
                            var index_of_space = q.description.indexOf("<", index_of_answer);
                            var answer_string = q.description.substring(
                                index_of_answer,
                                index_of_space
                            );
                            var index_of_length = q.description.indexOf(
                                "-",
                                index_of_answer + 7
                            );
                            var length = q.description.substring(
                                index_of_length + 1,
                                index_of_space
                            );

                            var answer_id = q.description.substring(
                                index_of_answer + 7,
                                index_of_length
                            );
                            if (q.type === "fill_in_blank") {
                                q.description = q.description.replace(
                                    answer_string,
                                    "<input type='text' class='inputAnswer' id='" +
                                    answer_id +
                                    "' value='' style='width:" +
                                    length * 10 +
                                    "px'>"
                                );
                            } else {
                                q.description = q.description.replace(
                                    answer_string,
                                    "<span class='droppableAnswer BlockWrapper BlockWrapper--isDropzone' data-dropzone='1' data-id='" +
                                    answer_id +
                                    "' style='width:" +
                                    (q.max_length * 8 + 10) +
                                    "px'></span>"
                                );

                            }
                            countLoop++;
                        }

                    }
                } else if ("matching" === q.type) {
                    let qtemp = Array.from(q.child_questions);
                    let longestAnswer = qtemp.sort(function (a, b) {
                        return b.answers[0].title.length - a.answers[0].title.length;
                    })[0];


                    q.max_length = longestAnswer.answers[0].title.length;
                }
                if (!['fill_in_blank', 'drag_drop'].includes(q.type)) {
                    if (this.IsJsonString(q.description)) {
                        var converter = new QuillDeltaToHtmlConverter(
                            JSON.parse(q.description)
                        );
                        q.description = converter.convert();
                        console.log(q.description);
                    }
                }

                if ("sorting_horizontal" == q.type) {
                    if (this.isChecksortHorizontal == 1) {
                        this.sorting_answers(q.answers, q.student_answer);
                        return q = this.shuffle(q.answers);
                    } else {
                        return q = this.shuffle(q.answers);
                    }

                    /*q.answers.forEach(item=> {
                        console.log(item);
                    })*/
                }


            },

            /*
             ** This function helping send answer request,
             ** It's works as a general method using for all types of question
             */
            sendPostAnswer(question = null) {
                this.count_play = 0;

                axios
                    .post(this.routes.quiz.SUBMIT_ANSWER, {
                        questions: question,
                        attempt_id: this.attempt_id
                    })
                    .then(response => {
                        this.child_index_ans = [];
                        if (response.data.questions.length == 0) {
                            this.checkIfQuizAttemptHasCompleted();
                            this.$store.dispatch("disableLoading");
                        } else {
                            if (this.isNext) {
                                this.questions = response.data.questions;
                                this.current_question_index = response.data.questionIndex;
                                this.total_question_count = response.data.totalQuestionsInQuizCount;

                                var self = this;
                                setTimeout(function () {
                                    self.initDragDrop();
                                }, 2000);

                                //Set timeout so it will render dragNdrop, still dont know another way to do it
                                response.data.questions.forEach(q => {
                                    this.convertQuestion(q);
                                    if (q.child_questions.length > 0 && q.type != "matching") {
                                        _.forEach(q.child_questions, child_question => {
                                            if (child_question.type == 'sorting_horizontal') {
                                                child_question.description = '';
                                            }
                                            this.convertQuestion(child_question);
                                        });
                                    }
                                });
                            }
                            this.$store.dispatch("disableLoading");
                        }
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },

            checkIfQuizAttemptHasCompleted() {
                axios
                    .post(this.routes.quiz.CHECK_IF_QUIZ_ATTEMPT_HAS_COMPLETED, {
                        attempt_id: this.attempt_id,
                        quiz_id: this.$route.params.quiz_id
                    })
                    .then(response => {
                        if (response.data.completed) {
                            clearInterval(this.intervalTimeTick);
                            // this.$router.push({
                            //   name: "quiz_result",
                            //   params: { quiz_id: this.$route.params.quiz_id, attempt_id: this.attempt_id}
                            // });
                            console.log('Get data');
                            this.goToPageReviewQuiz();
                        } else {
                            this.getQuestionFromQuiz(this.questions[0].id);
                        }
                    });
            },

            sendAnswer(q) {
                if (q.type == "fill_in_blank") {
                    let inputElements = document.getElementsByClassName("inputAnswer");
                    let questions = [];
                    for (var i = 0; i < inputElements.length; i++) {
                        var question = {};
                        question.selected_answer_id = inputElements[i].id;
                        question.answer_content = inputElements[i].value;
                        question.id = q.id;
                        question.type = "fill_in_blank";
                        question.quiz_id = this.$route.params.quiz_id;
                        questions.push(question);
                    }
                    return questions;
                } else if (q.type == "dropdown") {
                    let selectElements = document.getElementsByClassName("selectAnswer");
                    let questions = [];
                    for (var i = 0; i < selectElements.length; i++) {
                        var question = {};
                        //question.selected_answer_id = selectElements[i].id;
                        question.selected_answer_id = selectElements[i].options[selectElements[i].selectedIndex].value;
                        question.answer_content = selectElements[i].options[selectElements[i].selectedIndex].value;
                        question.id = q.id;
                        question.type = "dropdown";
                        question.quiz_id = this.$route.params.quiz_id;
                        questions.push(question);
                    }
                    return questions;
                } else if (q.type == "drag_drop") {
                    var inputElements = document.getElementsByClassName("droppableAnswer");
                    var questions = [];
                    for (var i = 0; i < inputElements.length; i++) {
                        var question = {};
                        question.selected_answer_id =
                            inputElements[i].attributes["data-id"].value;
                        question.answer_content = "";
                        if (inputElements[i].children.length > 0) {
                            question.answer_content = inputElements[i].children[0].innerText;
                        }
                        question.id = q.id;
                        question.type = "drag_drop";
                        question.quiz_id = this.$route.params.quiz_id;
                        questions.push(question);
                        //this.sendPostAnswer(question);
                    }
                    return questions;
                } else if (q.type == "matching") {
                    var inputElements = document.getElementsByClassName("droppableAnswer");
                    var questions = [
                        {
                            id: q.id,
                            quiz_id: this.$route.params.quiz_id,
                            type: "matching",
                            selected_answer_id: null
                        }
                    ];

                    for (var i = 0; i < inputElements.length; i++) {
                        if (inputElements[i].childNodes[0]) {
                            var question = {};
                            question.selected_answer_id =
                                inputElements[i].childNodes[0].attributes["data-id"].value;
                            question.id = inputElements[i].attributes["question-id"].value;
                            question.type = "matching";
                            question.quiz_id = this.$route.params.quiz_id;
                            questions.push(question);
                        }
                    }
                    return questions;
                } else if (q.type == "matching_as_image") {
                    var inputElements = document.getElementsByClassName("droppableAnswer");
                    var questions = [
                        {
                            id: q.id,
                            quiz_id: this.$route.params.quiz_id,
                            type: "matching_as_image",
                            selected_answer_id: null
                        }
                    ];

                    for (var i = 0; i < inputElements.length; i++) {
                        if (inputElements[i].childNodes[0]) {
                            var question = {};
                            question.selected_answer_id =
                                inputElements[i].childNodes[0].attributes["data-id"].value;
                            question.id = inputElements[i].attributes["question-id"].value;
                            question.type = "matching_as_image";
                            question.quiz_id = this.$route.params.quiz_id;
                            questions.push(question);
                        }
                    }
                    return questions;
                } else if (q.type == "matching_text_image") {
                    var inputElements = document.getElementsByClassName("droppableAnswer");
                    var questions = [
                        {
                            id: q.id,
                            quiz_id: this.$route.params.quiz_id,
                            type: "matching_text_image",
                            selected_answer_id: null
                        }
                    ];

                    for (var i = 0; i < inputElements.length; i++) {
                        if (inputElements[i].childNodes[0]) {
                            var question = {};
                            question.selected_answer_id =
                                inputElements[i].childNodes[0].attributes["data-id"].value;
                            question.id = inputElements[i].attributes["question-id"].value;
                            question.type = "matching_text_image";
                            question.quiz_id = this.$route.params.quiz_id;
                            questions.push(question);
                        }
                    }
                    return questions;
                } else if (["sorting_horizontal", "summary"].includes(q.type)) {
                    var q_id = q.id;
                    var sorting_item =
                        q.type == "summary"
                            ? document.getElementsByClassName("summary-item")
                            : document.getElementsByClassName("sorting-horizontal-item");
                    q.answer_content = "";

                    if (this.isCheckDragdropValue.indexOf(q_id) !== -1) {
                        _.forEach(sorting_item, item => {
                            if (item.dataset.question_id == q_id) {
                                q.answer_content += (item.dataset.id + ",");
                            }
                        });
                    } else {
                        if (q.student_answer !== undefined) {
                            if (q.student_answer.length > 0) {
                                q.answer_content = q.student_answer[0].answer_content;
                            }
                        } else {
                            _.forEach(sorting_item, item => {
                                if (item.dataset.question_id == q_id) {
                                    q.answer_content += (0 + ",");
                                }
                            });
                        }
                    }

                    return [q];
                } else {
                    return [q];
                }
            },

            scrollToTop() {
                window.scrollTo(0, 0);
            },

            submitAnswer(isNext) {
                this.isNext = isNext;

                switch (this.questions[0].type) {
                    case "single_choice":
                    case "math_set":
                    case "single_choice_as_image":
                        if (this.questions[0].selected_answer_id == null)
                            //return;
                            break;
                    case "multiple_choice":
                        if (this.questions[0].selected_answers_id.length == 0)
                            //return;
                            break;
                }
                if (
                    "child_questions" in this.questions[0] &&
                    this.questions[0].child_questions.length > 0 &&
                    this.questions[0].type != "matching" && this.questions[0].type != "matching_as_image" && this.questions[0].type != "matching_text_image"
                ) {
                    this.$store.dispatch("enableLoading");
                    var questionArr = [];
                    //Push all child questions into new Array :
                    _.forEach(this.questions[0].child_questions, q => {
                        _.forEach(this.sendAnswer(q), qel => {

                            questionArr.push(qel);
                        });
                    });
                    //Push parent question to that array too:
                    questionArr.push(this.questions[0]);
                    this.sendPostAnswer(questionArr);
                } else {
                    this.$store.dispatch("enableLoading");
                    if (["open_end"].indexOf(this.questions[0].type) !== -1) {
                        this.questions[0].answer_content = this.answer;
                    }
                    this.sendPostAnswer(this.sendAnswer(this.questions[0]));
                }

                this.isCheckDragdropValue = [];
                this.scrollToTop();
            },

            IsJsonString(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            },

            checkAudio(id) {
                var parentDOM = document.getElementById("row_" + id);
                if (parentDOM == null) {
                    var parentDOM = document.getElementById("rows_" + id);
                }
                const audio = parentDOM.querySelector("#my_audio");
                var cnt = 0

                if (audio.duration != audio.currentTime) {
                    $('#outer_' + id).css('display', 'block');
                    $('#outer-child_' + id).css('display', 'block');
                }

                if (this.$session.get("count_play_" + id + "") >= 3) {
                    audio.pause();
                    audio.currentTime = 0;
                    cnt = this.$session.get("count_play_" + id + "");
                    this.$session.set("count_play_" + id + "", cnt + 1);
                } else {
                    if (this.$session.get("count_play_" + id + "")) {
                        cnt = this.$session.get("count_play_" + id + "");
                    }
                    this.$session.set("count_play_" + id + "", cnt + 1);
                    //$(audio).css('pointer-events', 'none');

                }
                console.log(this.$session.get("count_play_" + id + ""));
                this.updatePaused(id);
            },

            updatePaused(id) {
                /*console.log(this.$session.get("privious_id") +'='+ id)
                if(this.$session.get("privious_id")){
                    var privious_id = this.$session.get("privious_id");
                    if(privious_id != id){
                        var parent = document.getElementById("row_"+privious_id);
                        if(parent == null){
                            var parent = document.getElementById("rows_"+privious_id);
                        }
                        //const privious_audio = parent.querySelector("#my_audio");
                        //privious_audio.stop();
                        $('#outer_'+privious_id).css('display','none');
                        $('#outer-child_'+privious_id).css('display','none');
                        this.$session.set("privious_id",id);
                    }else{
                        this.$session.set("privious_id",id);
                    }
                }else{
                    this.$session.set("privious_id",id);
                }*/

                var parentDOM = document.getElementById("row_" + id);
                if (parentDOM == null) {
                    var parentDOM = document.getElementById("rows_" + id);
                }
                const audio = parentDOM.querySelector("#my_audio");
                var session_id = this.$session.get("count_play_" + id + "");
                var cnt = 0
                var interval = null;

                interval = setInterval(() => {
                    var currentTime = audio.currentTime;
                    var duration = audio.duration;
                    if (currentTime == duration) {
                        $('#outer_' + id).css('display', 'none');
                        $('#outer-child_' + id).css('display', 'none');
                        clearInterval(interval);
                    } else if (session_id > 3) {
                        $('#outer_' + id).css('display', 'none');
                        clearInterval(interval);
                    }
                    //console.log(id+'--'+audio.currentTime);
                }, 1000);

            },

            checkIsPrivate() {
                (async () => {
                    var is_private;

                    if ("storage" in navigator && "estimate" in navigator.storage) {
                        const {usage, quota} = await navigator.storage.estimate();
                        // console.log(`Using ${usage} out of ${quota} bytes.`);
                        if (quota < 120000000) {
                            this.isPrivateMode = true;
                            // Incognito;
                            is_private = true;
                        } else {
                            this.isPrivateMode = false;
                            // Not Incognito
                            is_private = false;
                        }
                    } else {
                        // Can not detect
                        is_private = null;
                    }
                    if (is_private != true) {
                        this.isPrivateMode = false;
                        swal("The exam is ONLY allowed in the New Incognito Window in Chrome. Please open New Incognito Window in Chrome and then log in to the site. Ask your teacher if you need help.\n" +
                            "\n" +
                            "İmtahan yalnız New İncognito Window-da (Yeni Gizli Pəncərə) götürülə bilər. Zəhmət olmasa Yeni Gizli Pəncərə açıb sonra imtahan saytına daxil olun. İngiliscə və rusca adlarına baxa bilərsiniz. Kömək üçün müəlliminizdən soruşun.\n" +
                            "\n" +
                            "Экзамен разрешен ТОЛЬКО в во кладке инкогнито в Chrome. Пожалуйста, войдите в Chrome перейдите в «новая вкладка инкогнито», а затем перейдите  на сайт.");
                    }
                })();
            },

            rightClickDisable(){
                //disable right click
                    document.addEventListener("contextmenu", function(e){
                        e.preventDefault();
                    }, false);
                    document.addEventListener("keydown", function(e) {
                        //document.onkeydown = function(e) {
                        // "I" key
                        if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
                            disabledEvent(e);
                        }
                        // "J" key
                        if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                            disabledEvent(e);
                        }
                        // "S" key + macOS
                        if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
                            disabledEvent(e);
                        }
                        // "U" key
                        if (e.ctrlKey && e.keyCode == 85) {
                            disabledEvent(e);
                        }
                        // "F12" key
                        if (event.keyCode == 123) {
                            disabledEvent(e);
                        }
                    }, false);
                    function disabledEvent(e){
                        if (e.stopPropagation){
                            e.stopPropagation();
                        } else if (window.event){
                            window.event.cancelBubble = true;
                        }
                        e.preventDefault();
                        return false;
                    }
                document.addEventListener("keyup", function (e) {
                    var keyCode = e.keyCode ? e.keyCode : e.which;
                    if (keyCode == 44) {
                        stopPrntScr();
                    }
                });
                function stopPrntScr() {

                    var inpFld = document.createElement("input");
                    inpFld.setAttribute("value", ".");
                    inpFld.setAttribute("width", "0");
                    inpFld.style.height = "0px";
                    inpFld.style.width = "0px";
                    inpFld.style.border = "0px";
                    document.body.appendChild(inpFld);
                    inpFld.select();
                    document.execCommand("copy");
                    inpFld.remove(inpFld);
                }
                function AccessClipboardData() {
                    try {
                        window.clipboardData.setData('text', "Access   Restricted");
                    } catch (err) {
                    }
                }
                setInterval("AccessClipboardData()", 300);
            }
        },

        beforeDestroy() {
            clearInterval(this.intervalTimeTick);
        }
    };
</script>

<style lang="scss">

    .sorting-horizontal-list li.sorting-horizontal-item.text-warning.draggable-mirror {
        display: none;
    }

    .sortable-animation > * {
        transform: translate3d(0, 0, 0);
    }

    .sortableCards-move {
        -webkit-transition: transform cubic-bezier(0.22, 0.61, 0.36, 1) 500ms;
        -moz-transition: transform cubic-bezier(0.22, 0.61, 0.36, 1) 500ms;
        -o-transition: transform cubic-bezier(0.22, 0.61, 0.36, 1) 500ms;
        transition: transform cubic-bezier(0.22, 0.61, 0.36, 1) 500ms;
        background-color: #f00;
    }

    .sortableCards-enter,
    .sortableCards-leave-to {
        transition: 5s;
        opacity: 0;
    }

    .draggable-source--is-dragging {
        opacity: 0.3;
    }

    .draggable-container--over {
        animation: draggablePulseBg cubic-bezier(0.22, 0.61, 0.36, 1) 1500ms infinite;
    }

    .draggable-source--placed {
        animation: placedItem cubic-bezier(0.22, 0.61, 0.36, 1) 0.5s;
    }

    .sortableCards-enter-active,
    .sortableCards-leave-to {
        opacity: 0;
    }

    .sortableCards-leave-active {
        position: absolute;
    }

    @-moz-keyframes draggablePulseBg {
        0% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
        35% {
            background-color: rgba(0, 0, 0, 0.05);
            outline: solid 0.1rem rgba(0, 0, 0, 0.1);
        }
        100% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
    }

    @-webkit-keyframes draggablePulseBg {
        0% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
        35% {
            background-color: rgba(0, 0, 0, 0.05);
            outline: solid 0.1rem rgba(0, 0, 0, 0.1);
        }
        100% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
    }

    @-o-keyframes draggablePulseBg {
        0% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
        35% {
            background-color: rgba(0, 0, 0, 0.05);
            outline: solid 0.1rem rgba(0, 0, 0, 0.1);
        }
        100% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
    }

    @keyframes draggablePulseBg {
        0% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
        35% {
            background-color: rgba(0, 0, 0, 0.05);
            outline: solid 0.1rem rgba(0, 0, 0, 0.1);
        }
        100% {
            background-color: transparent;
            outline: solid 0.1rem transparent;
        }
    }

    @-moz-keyframes placedItem {
        from {
            transform: scale(1.065);
            box-shadow: 0 0.25rem 2rem rgba(0, 0, 0, 0.25), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        to {
            transform: scale(1);
            box-shadow: 0 0.4rem 3rem rgba(0, 0, 0, 0.35), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.2);
        }
    }

    @-webkit-keyframes placedItem {
        from {
            transform: scale(1.065);
            box-shadow: 0 0.25rem 2rem rgba(0, 0, 0, 0.25), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        to {
            transform: scale(1);
            box-shadow: 0 0.4rem 3rem rgba(0, 0, 0, 0.35), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.2);
        }
    }

    @-o-keyframes placedItem {
        from {
            transform: scale(1.065);
            box-shadow: 0 0.25rem 2rem rgba(0, 0, 0, 0.25), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        to {
            transform: scale(1);
            box-shadow: 0 0.4rem 3rem rgba(0, 0, 0, 0.35), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.2);
        }
    }

    @keyframes placedItem {
        from {
            transform: scale(1.065);
            box-shadow: 0 0.25rem 2rem rgba(0, 0, 0, 0.25), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        to {
            transform: scale(1);
            box-shadow: 0 0.4rem 3rem rgba(0, 0, 0, 0.35), 0 0.1rem 0.5rem rgba(0, 0, 0, 0.2);
        }
    }


    .disabled {
        background-color: gray;
    }

    .stopCopy {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .inputAnswer {
        width: 80px;
        border-radius: 3px;
        height: 22px;
        border: 1px solid #bbb;
        margin: 5px 0 !important;
    }

    .droppableAnswer {
        width: 100px;
        height: 20px;
        border-radius: 3px;
        background: #98ccf6;
        display: inline-block;
        margin: 0 5px;
        margin-top: 5px;
    }

    #dragDropQuestion {
        .matching-questions {
            //width:300px;
            li {
                list-style: none;
                margin: 10px 0;
                position: relative;

                .question-item {
                    padding: 5px;
                    border: 1px solid #ccc;
                    background: #eee;
                    width: 50%;
                    display: inline-block;
                }

                .droppableAnswer {
                    border-bottom: 1px solid #eee;
                    width: 45%;
                    height: 35px;
                    background: none;
                    position: absolute;
                    bottom: 0;

                    a {
                        background: #a7ff9b;
                        color: #555;
                        padding: 7px;
                        display: block;

                        &:hover {
                            text-decoration: none;
                        }
                    }
                }
            }
        }

        .matching-answers {
            li {
                //list-style: none;
                a {
                    background: #98ccf6;
                    border: 1px solid #98ccf6;
                    margin: 10px 0;
                    padding: 5px;
                    display: block;

                    &:hover {
                        text-decoration: none;
                    }
                }
            }
        }
    }

    .sorting-horizontal-list {
        li {
            padding-right: 12px !important;
            display: inline-block;
            list-style: none;
            margin: 10px;
            padding: 4px 0px;
            cursor: pointer;
            border: 1px dashed #2196f3;
        }
    }

    .drag-drop-horizontal-list {
        li {

            display: inline-block;
            list-style: none;
            margin: 10px !important;
            padding: 2px 8px !important;
            cursor: pointer;
            //border-bottom: 2px solid #eee !important;
            border: 1px dashed #2196f3;
            height: 28px
        }
    }

    .summary-list {
        li {
            list-style: none;
            margin: 20px auto;
            padding: 5px 0px;
            cursor: pointer;
            color: #2196f3;
            border: solid 1px #2196f3;
            max-width: 500px;
        }
    }

    .summary_span {
        background-color: #2196f3 !important;
        color: white !important;
        padding: 7px;
    }

    .selectAnswer {
        margin: 3px 0 !important;
    }

    #fixed {
        position: sticky;
        right: 0;
        top: 65%;
        width: 7em;
        margin: -5.5em 0 0 0;
        margin-left: auto;
        z-index: 5;
        background: #2196f3 !important;
        color: white;
        text-align: left;
        border: solid hsla(80, 90%, 40%, 0.5);
        border-right-color: rgba(133, 194, 10, 0.5);
        border-right-style: solid;
        border-right-width: medium;
        border-right: none;
        padding-left: 15px;
        box-shadow: 0 1px 3px black;
        border-radius: 3em 0.5em 0.5em 3em;
    }

    .card-footer {
        padding: 0px !important;
    }

    #timing {
        font-size: 20px;
    }

    .singleset {
        width: 100%;
        max-height: 500px;
        display: inline-block;
        overflow-x: hidden;
        overflow-y: auto;
        line-height: 35px;
        text-align: justify;
        padding: 0 15px;
    }

    .inputGroup {
        background-color: #fff;
        display: block;
        margin: 10px 0;
        position: relative;

        label {
            padding: 16px 32px;
            width: 100%;
            display: block;
            text-align: left;
            color: #3C454C;
            cursor: pointer;
            position: relative;
            z-index: 2;
            transition: color 200ms ease-in;
            overflow: hidden;

            &:before {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                content: '';
                background-color: #5562eb;
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%) scale3d(1, 1, 1);
                transition: all 300ms cubic-bezier(0.4, 0.0, 0.2, 1);
                opacity: 0;
                z-index: -1;
            }

            &:after {
                width: 32px;
                height: 32px;
                content: '';
                border: 2px solid #D1D7DC;
                background-color: #fff;
                background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E ");
                background-repeat: no-repeat;
                background-position: 2px 3px;
                border-radius: 50%;
                z-index: 2;
                position: absolute;

                right: 8px;
                top: 53%;
                transform: translateY(-50%);
                cursor: pointer;
                transition: all 200ms ease-in;
            }
        }

        input:checked ~ label {
            color: #fff;

            &:before {
                transform: translate(-50%, -50%) scale3d(56, 56, 1);
                opacity: 1;
            }

            &:after {
                background-color: #54E0C7;
                border-color: #54E0C7;
            }
        }

        input {
            width: 32px;
            height: 32px;
            order: 1;
            z-index: 2;
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            visibility: hidden;
        }
    }


    *,
    *::before,
    *::after {
        box-sizing: inherit;
    }

    #dragDropQuestion .matching-questions li .droppableAnswer {
        position: initial !important;
    }

    .matching_as_image {
        background-color: #ffff !important;
        padding: 1px !important;
    }

    #my_audio::-webkit-media-controls-timeline {
        display: none;
    }

    .embed-responsive {
        width: 100% !important;
        height: 100% !important;
    }

    .outer {
        position: absolute;
        width: 177%;
        background: #909ca200;
        height: 52px;
        z-index: 10;
        margin-left: -100%;
    }

    .outer-child {
        position: absolute;
        width: 137%;
        background: #909ca200;
        height: 52px;
        z-index: 10;
        margin-left: -100%;
    }

    .col-sm-4 .singleset {
        padding: 0 20px;
        background-color: #e0eaf2;
    }

    .inputGroup label:after {
        content: none;
    }

    @media print {
        html, body {
            display: none; /* hide whole page */
        }
    }
</style>
