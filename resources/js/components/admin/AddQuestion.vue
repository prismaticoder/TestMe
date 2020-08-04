<template>
  <div class="row" v-if="examArray.length > 0">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">
                Questions
                <v-btn fab absolute right small class="bg-primary text-white" title="Add New Question" @click="currentQuestion = null">
                    <v-icon>
                        mdi-plus
                    </v-icon>
                </v-btn>
            </h4>
            <div class="list-group">
                <span v-for="(question, index) in questions" :key="question.id" class="list-group-item list-group-item-action questionBtn" v-bind:class="{'active': currentQuestion ? currentQuestion.id == questions[index].id : false}"  @click.prevent="currentQuestion = questions[index]">
                    Question {{index + 1}}
                    <v-btn text small dark :color="yellow" @click="dialog = true" class="float-right" title="Remove Question">
                        <v-icon>
                            mdi-close
                        </v-icon>
                    </v-btn>
                </span>
            </div>
        </div>
        <v-dialog v-model="dialog" max-width="350">
            <v-card>
                <v-card-title class="headline">Delete Question?</v-card-title>
                <v-card-text>
                Please confirm that you want to delete this question.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="btnLoading" color="green darken-1" text @click="dialog = false">No</v-btn>
                    <v-btn :loading="btnLoading" :disabled="btnLoading" color="green darken-1" text @click="deleteQuestion()">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar">
            {{ snackbarText }}
            <v-btn color="pink" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>

        <div class="col-md-10 bg-white">

            <ExamParams :exam="examArray[0]" :examCount="examArray.length" :yellow="yellow" :subject="subject" :classId="classId" @setExam="setExam"/>

            <div class="container">
                <h3 class="text-center">Question</h3>
                <quill-editor class="questions container" :options="editorOption" v-model="question" />
                <hr>
            </div>

            <h3 class="text-center">Options</h3>
            <p class="text-center" style="color: #ff5500"><small>(Note: Click the checkbox beside an option to mark it as the correct option)</small></p>
            <hr>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <v-checkbox title="Mark this option as correct" v-model="correct" :color="yellow" value="0" class="d-flex align-items-center h-100"></v-checkbox>
                </div>
                <div class="col-md-8">
                    <h4>Option A</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionA" />
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <v-checkbox title="Mark this option as correct" v-model="correct" :color="yellow" value="1" class="d-flex align-items-center h-100"></v-checkbox>
                </div>
                <div class="col-md-8">
                    <h4>Option B</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionB" />
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <v-checkbox title="Mark this option as correct" v-model="correct" :color="yellow" value="2" class="d-flex align-items-center h-100"></v-checkbox>
                </div>
                <div class="col-md-8">
                    <h4>Option C</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionC" />
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <v-checkbox title="Mark this option as correct" v-model="correct" :color="yellow" value="3" class="d-flex align-items-center h-100"></v-checkbox>
                </div>
                <div class="col-md-8">
                    <h4>Option D</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionD" />
                </div>
            </div>

            <div class="col-md-4 mx-auto mt-5">
                <v-btn :loading="loading" :color="yellow" :disabled="!question || !optionA || !optionB || !optionC || !optionD || correct == null || loading" v-if="!currentQuestion" @click="addQuestion" tile block class="col-4 mx-auto">
                    Add Question
                </v-btn>
                <v-btn :loading="loading" :color="yellow" :disabled="!question || !optionA || !optionB || !optionC || !optionD || correct == null || loading" v-else @click="updateQuestion" tile block class="col-4 mx-auto">
                    Update Question
                </v-btn>
            </div>
        </div>
  </div>
  <div class="container mt-5 mx-auto" v-else>
        <CreateExam @setExam="setExam" :black="black" :yellow="yellow" :subject="subject" :classId="classId"/>
  </div>

</template>

<script>
import ExamParams from './ExamParams'
import CreateExam from './CreateExam'
import katex from 'katex';
import 'katex/dist/katex.min.css';

export default {
    name: "AddQuestion",
    props: ['subject', 'classId', 'exams'],
    components: {
        ExamParams,
        CreateExam
    },
    data() {
        return {
            questions: this.exams.length > 0 ? this.exams[0].questions : [],
            currentQuestion: null,
            options: ['A','B','C','D'],
            examArray: this.exams,
            question: null,
            optionA: null,
            optionB: null,
            optionC: null,
            optionD: null,
            correct: null,
            btnLoading: false,
            dialog: false,
            loading: false,
            snackbar: false,
            snackbarText: '',
            editorDisabled: true,
            editorOption: {
                modules: {
                    toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['clean'],
                    ['link', 'image', 'video'],
                    ["formula"]
                    ],
                 },
                placeholder: "Enter value here",
                theme: 'snow',
                readonly: true
            },
            yellow: "#e67d23",
            black: "#343a40"
        }
    },
    methods: {
        onEditorBlur(quill) {
            this.editorDisabled = true
        },
        onEditorFocus(quill) {
            this.editorDisabled = false
        },
        clearQuestionForm() {
            this.question = this.optionA = this.optionB = this.optionC = this.optionD = this.correct = null
        },
        deleteQuestion() {
            this.btnLoading = true;
            this.$http.post(`deleteQuestion/${this.currentQuestion.id}`)
            .then(res => {
                this.btnLoading = false
                this.dialog = false
                this.questions = this.questions.filter(question => question.id !== this.currentQuestion.id)
                this.snackbar = true;
                this.snackbarText = res.data.message
                this.currentQuestion = null
            })
            .catch(err => {
                this.btnLoading = false
                this.dialog = false;
                console.log(err.response.data);
                alert(err.response.status == 403 ? err.response.data.message : "There was an error deleting this question, please try again.")
            })
        },
        addQuestion() {
            this.loading = true
            let { question, optionA, optionB, optionC, optionD, correct } = this
            const options = [optionA, optionB, optionC, optionD]

            this.$http.post('addQuestion', {
                question,
                options,
                correct,
            })
            .then(res => {
                this.loading = false
                this.questions.push(res.data.question)
                this.snackbar = true;
                this.snackbarText = res.data.message
                this.clearQuestionForm()
                window.scrollTo(0,0)
            })
            .catch(err => {
                this.loading = false
                console.log(err.response.data);
                alert(err.response.status == 403 ? err.response.data.message : "There was an error submitting this question, please try again.")
            })
        },
        updateQuestion() {
            this.loading = true
            let { question, optionA, optionB, optionC, optionD, correct } = this
            const options = [{id: this.currentQuestion.options[0].id, value: optionA}, {id: this.currentQuestion.options[1].id, value: optionB}, {id: this.currentQuestion.options[2].id, value: optionC}, {id: this.currentQuestion.options[3].id, value: optionD}]

            this.$http.post(`updateQuestion/${this.currentQuestion.id}`, {
                question,
                options,
                correct,
            })
            .then(res => {
                this.loading = false
                let index = this.questions.findIndex(question => question.id == res.data.question.id)
                this.questions.splice(index, 1, res.data.question)
                this.snackbar = true;
                this.snackbarText = res.data.message
                window.scrollTo(0,0)
            })
            .catch(err => {
                this.loading = false
                console.log(err.response.data);
                alert("There was an error updating this question, please try again.")
            })
        },
        setExam(type, exam) {
            if (type == 'create') {
                this.examArray.unshift(exam)
                this.questions = exam.questions
            }
            else {
                this.examArray.splice(0,1,exam)
            }
        }
    },
    watch: {
        'currentQuestion'(newValue) {
            if (newValue) {
                this.question = newValue.question;
                this.optionA = newValue.options[0].body
                this.optionB = newValue.options[1].body
                this.optionC = newValue.options[2].body
                this.optionD = newValue.options[3].body
                this.correct = (newValue.options.findIndex(option => option.isCorrect)).toString()
            }

            else {
                this.question = this.optionA = this.optionB = this.optionC = this.optionD = this.correct = null
            }
        }
    },
    mounted() {
        window.katex = katex
    }
}
</script>

<style scoped>

.questions {
    height: 250px;
}

.option {
    /* height: 300px; */
    /* width: 700px; */
}

.active {
    background-color: #343a40;
    color: #e67d23;
    border: solid #343a40 1px;
}

.yellow {
    background-color: #e67d23
}
</style>
