<template>
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group">
                <a href="#" v-for="(question, index) in questions" :key="question.id" class="list-group-item list-group-item-action" v-bind:class="{'disabled' : !currentQuestion, 'active': questionNumber == index + 1, 'text-danger': hasNotBeenAnswered(index+1) && questionNumber !== index + 1}" v-bind:title="{'You': hasNotBeenAnswered(index+1)}" @click.prevent="storeChoice('random', currentSelection, questionNumber, index)">
                    Question {{index + 1}}
                </a>
            </div>
        </div>
        <div class="col-md-10 question-body">
            <SingleQuestion v-if="currentQuestion" :question="currentQuestion" :totalCount="questions.length" :selectedOption="selectedOption" :number="questionNumber" @storeChoice="storeChoice" v-on:updateSelection="updateSelection"/>

            <div class="card" v-else>
                <div class="card-body">
                <h5 class="card-title">Question No <span class="questionNo">0</span> of <span class="questionCount">{{questions.length}}</span></h5>
                    <p class="card-text question">
                        <h5>INSTRUCTIONS</h5>
                        <ul>
                            <li>This exam will last for <strong>{{examtime}}</strong></li>
                            <li>Read every question carefully</li>
                            <li>Manage your time well</li>
                            <li>Good luck!</li>
                        </ul>
                    </p>
                </div>
                <ul class="list-group list-group-flush">
                        <label for="radio1"><li class="list-group-item radios"><input type="radio" name="options" class="radioBtn" value="0"><span class="options"> -</span></li></label>
                        <label for="radio2"><li class="list-group-item radios"><input type="radio" name="options" class="radioBtn" value="1"><span class="options"> -</span></li></label>
                        <label for="radio3"><li class="list-group-item radios"><input type="radio" name="options" class="radioBtn" value="2"><span class="options"> -</span></li></label>
                        <label for="radio4"><li class="list-group-item radios"><input type="radio" name="options" class="radioBtn" value="3"><span class="options"> -</span></li></label>
                </ul>
                <div class="card-body">
                    <button v-if="!hasStarted" class="btn btn-primary card-link" @click="startExam">START EXAM</button>
                    <button v-else class="btn btn-primary card-link" @click="startExam">CONTINUE</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SingleQuestion from './SingleQuestion'

export default {
    name: "Questions",
    components: {
        SingleQuestion
    },
    props: ['questions', 'hours', 'minutes'],
    data() {
        return {
            currentQuestion: sessionStorage.getItem('current') || null,
            questionNumber: null,
            currentSelection: null,
            hasStarted: sessionStorage.getItem('hasStarted') || false,
            choices: JSON.parse(sessionStorage.getItem('choices')) || [],
            selectedOption: null
        }
    },
    methods: {
        startExam() {
            this.currentQuestion = this.questions[0]
            this.questionNumber = 1
            sessionStorage.setItem('hasStarted', true)
            this.getChosenOption()
        },
        updateSelection(val) {
            this.currentSelection = val;
        },

        getChosenOption() {
            let selectedArray = this.choices.filter(choice => choice.question == this.questionNumber)
            let selectedOption = selectedArray.length > 0 ? selectedArray[0].choice : null

            this.selectedOption = selectedOption;
        },
        hasNotBeenAnswered(questionNumber) {
            let check = this.choices.filter(choice => choice.question == questionNumber)
            if (check.length > 0) {
                if (check[0].choice == null) {
                    return true
                }
                else {
                    return false
                }
            }
            else {
                return false
            }
        },
        storeChoice(type, selected, questionNumber, index=undefined) {
            this.choices = this.choices.filter(choice => choice.question !== questionNumber)
            this.choices.push({question: questionNumber, choice: selected})
            sessionStorage.setItem('choices', JSON.stringify(this.choices))

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
                if (this.hours = 1) {
                    this.minutes == 0 ? time = "1 hour" : time = `1 hour and ${this.minutes} minutes`
                }
                else {
                    this.minutes == 0 ? time = `${this.hours} hours` : time = `${this.hours} hours and ${this.minutes} minutes`
                }
            }

            else {
                time = `${this.minutes} minutes`
            }

            return time
        },
    },
    watch: {
        questionNumber() {
            this.currentSelection = this.selectedOption
        }
    }
}
</script>

<style>
    .question-body {
        padding: 130px 100px 50px;
        height: 100vh;
        overflow-y: scroll;
    }
    .question-body input {
        margin-right: 15px;
    }

    .radioBtn {
        width: 15px;
        height: 15px;
    }

    .radios:hover {
        border:solid #204d74 1px;
        cursor: pointer;
    }
</style>
