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
                <span
                    v-for="(question, index) in questions" :key="question.id"
                    class="list-group-item list-group-item-action questionBtn"
                    v-bind:class="{'active': currentQuestion ? currentQuestion.id == questions[index].id : false}"
                    @click.prevent="editorDisabled = true; currentQuestion = questions[index];"
                >
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

        <div class="col-md-10 bg-white">

            <ExamParams
                :exam="examArray[0]"
                :examCount="examArray.length"
                :questionCount="questions.length"
                :yellow="yellow"
                :subjectId="subjectId"
                :classId="classId"
                @create-exam="createExam"
                @update-exam="updateExam"
                @alterPQList="alterPQList"
            />

            <div class="container">
                <h3 class="text-center">Question</h3>
                <quill-editor
                    class="questions container"
                    v-model="question"
                    :options="editorOption"
                />
                <hr>
            </div>

            <h3 class="text-center">Options</h3>
            <p class="text-center" style="color: #ff5500">
                <small>(Note: Click the checkbox beside an option to mark it as the correct option)</small>
            </p>
            <hr>

            <div class="row" v-for="(element, index) in options.length" :key="index">
                <div class="col-md-1"></div>
                <div class="col-md-1">
                    <v-checkbox
                        title="Mark this option as correct"
                        v-model="correct"
                        :color="yellow"
                        :value="getAlphabetEquivalent(index)"
                        class="d-flex align-items-center h-100"
                    >
                    </v-checkbox>
                </div>
                <div class="col-md-8">
                    <h4>Option {{getAlphabetEquivalent(index).toUpperCase()}}</h4>
                    <quill-editor
                        v-model="options[index]"
                        class="option"
                        :disabled="editorDisabled"
                        :options="editorOption"
                        @focus="onEditorFocus($event)"
                    />
                    <hr>
                </div>
            </div>

            <div class="col-md-4 mx-auto mt-5">
                <v-btn
                    :loading="loading"
                    :color="yellow"
                    :disabled="emptyTextInputExists || correct === null || loading"
                    @click="currentQuestion ? updateQuestion() : createQuestion()"
                    tile block
                    class="col-4 mx-auto"
                >
                    <span v-if="!currentQuestion">Add Question</span>
                    <span v-else> Update Question</span>
                </v-btn>
            </div>
        </div>

        <PastExams
            :yellow="yellow"
            :black="black"
            :pastExams="pastExams"
            :showPQList="showPQList"
            @usePQTemplate="usePQTemplate"
            @alterPQList="alterPQList"
        />
  </div>
  <div class="container mt-5 mx-auto" v-else>
        <CreateExam
            :black="black"
            :yellow="yellow"
            :subjectId="subjectId"
            :classId="classId"
            @create-exam="createExam"
        />
  </div>

</template>

<script>
import ExamParams from './ExamParams'
import CreateExam from './CreateExam'
import PastExams from './PastExams'
import katex from 'katex';
import 'katex/dist/katex.min.css';

export default {
    name: "Questions",
    props: ['subjectId', 'classId', 'exams', 'defaultNumberOfOptions'],
    components: {
        ExamParams,
        CreateExam,
        PastExams
    },
    data() {
        return {
            questions: this.exams.length > 0 ? this.exams[0].questions : [],
            currentQuestion: null,
            examArray: this.exams,
            question: null,
            options: new Array(this.defaultNumberOfOptions).fill(""),
            correct: null,
            btnLoading: false,
            dialog: false,
            loading: false,
            showPQList: false,
            editorDisabled: true,
            yellow: "#e67d23",
            black: "#343a40",
            editorOption: {
                modules: {
                    toolbar:
                    [
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
                placeholder: "Type text here",
                theme: 'snow',
                readonly: true
            },
        }
    },
    methods: {
        onEditorFocus(quill) {
            this.editorDisabled = false
        },
        clearQuestionForm() {
            this.question = this.correct = null
            this.options = new Array(this.defaultNumberOfOptions).fill("");
            this.editorDisabled = true
        },
        deleteQuestion() {
            this.btnLoading = true;
            this.$http.delete(`questions/${this.currentQuestion.id}`)
            .then(res => {
                this.btnLoading = false
                this.dialog = false
                this.$noty.success(res.data.message)
                this.questions = this.questions.filter(question => question.id !== this.currentQuestion.id)
                this.currentQuestion = null
            })
            .catch(err => {
                this.btnLoading = false
                this.$noty.error(err.response.data.message)
            })
        },
        createQuestion() {
            this.loading = true
            let { question, options, correct } = this

            this.$http.post('questions', {
                question,
                options,
                correct_option: correct,
            })
            .then(res => {
                this.loading = false
                this.$noty.success(res.data.message)
                this.questions.push(res.data.data)

                this.clearQuestionForm()
                window.scrollTo(0,0)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        updateQuestion() {
            this.loading = true
            let { question, options, correct } = this

            this.$http.put(`questions/${this.currentQuestion.id}`, {
                question,
                options,
                correct_option: correct,
            })
            .then(res => {
                this.loading = false
                this.$noty.success(res.data.message)

                let index = this.questions.findIndex(question => question.id == res.data.data.id)
                this.questions.splice(index, 1, res.data.data)

                window.scrollTo(0,0)
                this.editorDisabled = true
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        createExam(exam) {
            this.examArray.unshift(exam)
            this.questions = exam.questions
        },
        updateExam(exam) {
            this.examArray.splice(0,1,exam)
        },
        setExam(type, exam) {
            if (type == 'create') {
                this.examArray.unshift(exam)
                this.questions = exam.questions
            }
            else {
                this.examArray.splice(0,1,exam)
            }
        },
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric"}
            return new Date(dateString).toLocaleDateString(undefined, options)
        },
        getAlphabetEquivalent(index) {
            return String.fromCharCode(97 + index);
        },
        alterPQList(type) {
            this.showPQList = (type === 'open');
        },
        usePQTemplate(exam) {
            this.showPQList = false
            this.examArray.splice(0,1,exam)
            this.questions = exam.questions
        }
    },
    watch: {
        'currentQuestion'(newValue) {
            if (newValue) {
                this.question = newValue.body;
                newValue.options.forEach((option,index) => {
                    this.options[index] = option.body
                })
                this.correct = this.getAlphabetEquivalent((newValue.options.findIndex(option => option.is_correct)))
            }

            else {
                this.question = this.correct = null;
                this.options = new Array(this.defaultNumberOfOptions).fill("")
            }
        }
    },
    mounted() {
        window.katex = katex;

        // Uncomment this line if you ever plan on implementing pasting with plain text
        // let editors = document.querySelectorAll(".ql-editor");

        // editors.forEach(function(editor) {
        //     editor.addEventListener("paste", function(e) {
        //         // cancel paste
        //         e.preventDefault();

        //         // get text representation of clipboard
        //         var text = (e.originalEvent || e).clipboardData.getData('text/plain');

        //         // insert text manually
        //         document.execCommand("insertHTML", false, text);
        //     });
        // });

    },
    computed: {
        pastExams() {
            return this.examArray.filter((exam, index) => index !== 0);
        },
        emptyTextInputExists() {
            return !this.question || this.options.includes("");
        }
    }
}
</script>

<style scoped>

.questions {
    height: 250px;
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
