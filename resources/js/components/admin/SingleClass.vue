<template>
    <div class="row border-bottom">
        <div class="col-md-12 text-center">
            <span>{{single.class}}</span>
        </div>
        <div class="col-md-4">
            <v-btn :href="`/admin/${subject.alias}/${single.id}/questions`" small title="Go to Questions" :color="yellow">
                Questions
            </v-btn>
        </div>
        <div class="col-md-4">
            <v-btn :href="`/admin/${subject.alias}/${single.id}/results`" small title="View results of most recent exam" :color="yellow">
                Results
            </v-btn>
        </div>
        <div class="col-md-4">
            <v-btn class="ml-n3" v-if="!examStarted" @click="dialog = true" :disabled="!single.hasPendingExamToday" small :color="yellow" :title="single.hasPendingExamToday ? 'Start Exam' : 'You cannot start this exam because exam duration has not been set'">
                Begin Exam
            </v-btn>
            <v-btn v-else class="ml-n3" @click="dialog = true" small title="End Exam" :color="yellow">
                End Exam
            </v-btn>
        </div>

        <v-dialog v-model="dialog" max-width="350">
            <v-card>
                <v-card-title v-if="!examStarted" class="headline">Start Exam?</v-card-title>
                <v-card-title v-else class="headline">End Exam?</v-card-title>
                <v-card-text v-if="!examStarted">
                Please confirm that you want to start this exam: <strong>{{subject.subject_name}} ({{single.class}})</strong>
                </v-card-text>
                <v-card-text v-else>
                Please confirm that you want to put an end to this exam: <strong>{{subject.subject_name}} ({{single.class}})</strong>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="dialog = false">No</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="examStarted ? endExam() : startExam()">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "SingleClass",
    props: ['single', 'subject', 'yellow', 'exams'],
    data() {
        return {
            loading: false,
            dialog: false,
            today: new Date().toISOString().substr(0, 10),
        }
    },
    methods: {
        startExam() {
            this.loading = true

            this.$http.patch('start-exam', {
                class_id: this.single.id,
                subject_id: this.subject.subject_id
            })
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('startNewExam', res.data.exam)
            })
            .catch(err => {
                this.loading = false
                this.dialog = false
                console.error(err.response.data)
                alert("There was an error starting this exam, please try again")
            })
        },
        endExam() {
            this.loading = true

            let examId = this.exams.filter(exam => exam.subject.subject_name == this.subject.subject_name && exam.class.class == this.single.class)[0].id
            this.$http.patch(`end-exam/${examId}`, {
                class_id: this.single.id,
                subject_id: this.subject.subject_id
            })
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('endExam', res.data.exam.id)
            })
            .catch(err => {
                this.loading = false
                this.dialog = false
                console.error(err.response.data)
                alert("There was an error ending this exam, please try again")
            })
        }
    },
    computed: {
        examStarted() {
            let check = this.exams.filter(exam => exam.subject.subject_name == this.subject.subject_name && exam.class.class == this.single.class)

            return check.length > 0 ? true : false;
        }
    }
}
</script>

<style>

</style>
