<template>
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">Questions List</h4>
            <div class="list-group">
                <a href="#" v-for="(question, index) in questions" :key="question.id" class="list-group-item list-group-item-action" v-bind:class="{'disabled' : !currentQuestion, 'active': questionNumber == index + 1}" @click.prevent="goToQuestion(index)">
                    Question {{index + 1}}
                </a>
            </div>
        </div>
        <div class="col-md-10 question-body">
            <SingleQuestion v-if="currentQuestion" :question="currentQuestion" :totalCount="questions.length" :number="questionNumber" />

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
                    <button data-button-type="start" class="btn btn-primary card-link" @click="startExam">START EXAM</button>
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
            // questions: null,
            currentQuestion: sessionStorage.getItem('current') || null,
            questionNumber: null,
            // hours: null,
            // minutes: null
        }
    },
    methods: {
        startExam() {
            this.currentQuestion = this.questions[0]
            this.questionNumber = 1
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
