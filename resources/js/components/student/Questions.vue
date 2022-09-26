<template>
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group">
                <a href="#question" v-for="(question, index) in questions" :key="question.id" class="list-group-item list-group-item-action text-dark" v-bind:class="{'disabled' : !currentQuestion, 'active': questionNumber == index + 1}" :title="hasNotBeenAnswered(index + 1) ? 'You have not responded to this question' : ''" @click.prevent="storeChoice('random', currentSelection, questionNumber, index)">
                    Question {{index + 1}}
                    <span v-if="!hasNotBeenAnswered(index+1)" class="badge badge-primary text-light badge-pill float-right">
                        <v-icon small class="text-light">
                            mdi-check
                        </v-icon>
                    </span>
                    <span v-else class="badge badge-danger text-light badge-pill float-right">
                        <v-icon small class="text-light">
                            mdi-exclamation
                        </v-icon>
                    </span>
                </a>
            </div>
        </div>
        <div class="col-md-10 question-body">

            <div>
                <p class="text-center">
                    <strong>{{student.fullName}} ({{student.examination_number}})</strong>
                </p>
            </div>

            <SingleQuestion v-if="currentQuestion" :question="currentQuestion" :totalCount="questions.length" :selectedOption="selectedOption" :number="questionNumber" @store-choice="storeChoice" v-on:updateSelection="updateSelection"/>

            <v-card shaped outlined class="p-3" v-else>
                <v-card-title>
                    {{subject.name.toUpperCase()}} EXAMINATION
                </v-card-title>
                <hr>

                <v-card-text>
                    <h5>INSTRUCTIONS</h5>
                    <hr>
                    <p><v-icon small>mdi-chevron-right</v-icon> This examination will last for <strong>{{examtime}}</strong></p>
                    <hr>
                    <p><v-icon small>mdi-chevron-right</v-icon> Study every question carefully</p>
                    <hr>
                    Best of Luck!

                </v-card-text>

                <v-card-actions>
                    <v-btn tile color="primary" @click="startExam">
                        <span v-if="!hasStarted">Start Exam</span>
                        <span v-else>Continue</span>
                        <v-icon small>
                            mdi-arrow-right
                        </v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </div>
    </div>
</template>

<script>
import SingleQuestion from './SingleQuestion'
import Timer from './Timer'

export default {
    name: "Questions",
    components: {
        SingleQuestion
    },
    props: ['questions', 'hours', 'minutes', 'subject', 'student', 'examId', 'studentId'],
    data() {
        return {
            currentQuestion: null,
            questionNumber: null,
            choices: this.$store.getters.choices || [],
            selectedOption: null
        }
    },
    methods: {
        startExam() {
            if (!this.hasStarted) {
                this.$store.dispatch('startExam', {
                    hours: this.hours,
                    minutes: this.minutes,
                    examId: this.examId,
                    studentId: this.studentId
                })
                .then(() => {
                    this.currentQuestion = this.questions[0]
                    this.questionNumber = 1
                    this.getChosenOption()
                })
                .catch(err => {
                    console.log(err)
                })
            }

            else {
                this.currentQuestion = this.questions[0]
                this.questionNumber = 1
                this.getChosenOption()
            }
        },
        updateSelection(val) {
            this.$store.commit('UPDATE_SELECTION', val)
        },

        getChosenOption() {
            let selectedArray = this.choices.filter(choice => choice.question == this.questionNumber)
            let selectedOption = selectedArray.length > 0 ? selectedArray[0].choice : null

            this.selectedOption = selectedOption;

            this.$store.commit('STORE_CHOICE', {choices: this.choices, currentQuestionNumber: this.questionNumber, currentSelectedOption: this.selectedOption})

        },
        hasNotBeenAnswered(questionNumber) {
            let check = this.choices.filter(choice => choice.question == questionNumber)
            if (check.length > 0) {
                return check[0].choice == null
            }
            else {
                return true
            }
        },
        storeChoice(type, selected, questionNumber, index=undefined) {
            this.choices = this.choices.filter(choice => choice.question !== questionNumber)
            this.choices.push({question: questionNumber, choice: selected})

            if (['next','previous'].includes(type)) {

                 if (type == 'next') {
                    this.questionNumber += 1;
                    this.currentQuestion = this.questions[this.questionNumber - 1]
                }

                else {
                    this.questionNumber -= 1;
                    this.currentQuestion = this.questions[this.questionNumber - 1]
                }

                this.getChosenOption()
            }

            else if (type == 'random') {
                this.questionNumber = index + 1;
                this.currentQuestion = this.questions[this.questionNumber - 1]

                this.getChosenOption()
            }

            else {
                this.dialog = false
                this.submitPage = true
            }
        }
    },
    computed: {
        examtime() {
            let time;
            if (this.hours > 0) {
                if (this.hours == 1) {
                    time = this.minutes === 0 ? "1 hour" : `1 hour and ${this.minutes} minutes`
                }
                else {
                    time = this.minutes === 0 ? `${this.hours} hours` : `${this.hours} hours and ${this.minutes} minutes`
                }
            }

            else {
                time = `${this.minutes} minutes`
            }

            return time
        },
        currentSelection() {
            return this.$store.state.currentSelectedOption
        },
        hasStarted() {
            return this.$store.getters.hasStarted(this.studentId, this.examId);
        },
        hasEnded() {
            return this.$store.getters.hasEnded
        }
    },
}
</script>

<style>
    .question-body {
        padding: 130px 100px 50px;
        height: 100vh;
        overflow-y: scroll;
    }
</style>
