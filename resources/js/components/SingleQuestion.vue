<template>
    <div class="card">
        <div class="card-body">
        <h5 class="card-title">Question No <span class="questionNo">{{number}}</span> of <span class="questionCount">{{totalCount}}</span></h5>
        <hr>
            <p v-html="question.question"></p>
        </div>
        <ul class="list-group list-group-flush">
            <label v-for="(option,index) in question.options" :key="option.id">
                <li class="list-group-item radios">
                    <input type="radio" v-model="selected" name="options" class="radioBtn" :value="index">
                    <span v-html="option.body"></span>
                </li>
            </label>
        </ul>
        <div class="card-body">
            <button :disabled="number == 1" class="btn btn-secondary card-link" @click="$emit('storeChoice', 'previous', newSelection, number)">Previous Question</button>
            <button v-if="number !== totalCount" @click="$emit('storeChoice', 'next', newSelection, number)"  class="btn btn-primary card-link">Next Question</button>
            <button v-else @click="dialog = true"  class="btn btn-primary card-link">Submit</button>
        </div>

        <v-dialog v-model="dialog" persistent max-width="350">
            <v-card>
                <v-card-title class="headline">Submit Examination?</v-card-title>
                <v-card-text>
                Are you sure you are ready to submit your examination?
                Doing so would mean you will not be able to return to check your answers.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="btnLoading" color="green darken-1" text @click="dialog = false">No</v-btn>
                    <v-btn :loading="btnLoading" :disabled="btnLoading" color="green darken-1" text @click="submitExam">Yes, Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "SingleQuestion",
    props: ['question', 'number', 'totalCount', 'selectedOption'],
    data() {
        return {
            newSelection: this.selectedOption || null,
            dialog: false,
            btnLoading: false
        }
    },
    methods: {
        submitExam() {
            this.btnLoading = true;
            this.$store.dispatch('endExam')
            .then(() => {
                window.location.href = '/success'
            })
            .catch(err => {
                console.log(err)
            })
        }
    },
    computed: {
        selected: {
            get() {
                return this.selectedOption
            },
            set(newValue) {
                this.newSelection = newValue
                this.$emit('updateSelection', newValue)
            }

        }
    },
    watch: {
        'question.id'() {
            this.newSelection = this.selectedOption
        }
    }
}
</script>

<style>

</style>
