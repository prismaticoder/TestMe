<template>
    <div class="border-bottom py-2">
        <div class="text-center">
            <strong>{{single.name}}</strong>
        </div>

        <div class="row justify-content-center d-flex">
            <div class="col-lg-4">
                <v-btn block tile  :href="`/admin/${subject.slug}/${single.id}/questions`" small title="Go to Questions" :color="yellow">
                    Questions
                </v-btn>
            </div>
            <div class="col-lg-4">
                <v-btn block tile  :href="`/admin/${subject.slug}/${single.id}/results`" small title="View results of most recent exam" :color="yellow">
                    Results
                </v-btn>
            </div>
            <div class="col-lg-4">
                <v-btn block tile  v-if="!examStarted" @click="dialog = true" :disabled="!examCanBeStarted" small :color="yellow" :title="examCanBeStarted ? 'Start Exam' : 'This exam cannot be started'">
                    Begin Exam
                </v-btn>
                <v-btn block tile  v-else @click="dialog = true" small title="End Exam" :color="yellow">
                    End Exam
                </v-btn>
            </div>
        </div>

        <!-- <v-container>
            <v-row justify="center">
                <v-col cols="12" lg="4">
                    <v-btn :href="`/admin/${subject.slug}/${single.id}/questions`" small title="Go to Questions" :color="yellow">
                        Questions
                    </v-btn>
                </v-col>
                <v-col cols="12" lg="4">
                    <v-btn :href="`/admin/${subject.slug}/${single.id}/results`" small title="View results of most recent exam" :color="yellow">
                        Results
                    </v-btn>
                </v-col>
                <v-col cols="12" lg="4">
                    <v-btn v-if="!examStarted" @click="dialog = true" :disabled="!examCanBeStarted" small :color="yellow" :title="examCanBeStarted ? 'Start Exam' : 'This exam cannot be started'">
                        Begin Exam
                    </v-btn>
                    <v-btn v-else @click="dialog = true" small title="End Exam" :color="yellow">
                        End Exam
                    </v-btn>
                </v-col>
            </v-row>
        </v-container> -->
        <!-- <div class="col-md-4">
            <v-btn :href="`/admin/${subject.slug}/${single.id}/questions`" small title="Go to Questions" :color="yellow">
                Questions
            </v-btn>
        </div>
        <div class="col-md-4">
            <v-btn :href="`/admin/${subject.slug}/${single.id}/results`" small title="View results of most recent exam" :color="yellow">
                Results
            </v-btn>
        </div>
        <div class="col-md-4">
            <v-btn class="ml-n3" v-if="!examStarted" @click="dialog = true" :disabled="!examCanBeStarted" small :color="yellow" :title="examCanBeStarted ? 'Start Exam' : 'This exam cannot be started'">
                Begin Exam
            </v-btn>
            <v-btn v-else class="ml-n3" @click="dialog = true" small title="End Exam" :color="yellow">
                End Exam
            </v-btn>
        </div> -->

        <v-dialog v-model="dialog" max-width="350">
            <v-card>
                <v-card-title v-if="!examStarted" class="headline">Start Exam?</v-card-title>
                <v-card-title v-else class="headline">End Exam?</v-card-title>
                <v-card-text v-if="!examStarted">
                Please confirm that you want to start this exam: <strong>{{subject.name}} ({{single.name}})</strong>
                </v-card-text>
                <v-card-text v-else>
                Please confirm that you want to put an end to this exam: <strong>{{subject.name}} ({{single.name}})</strong>
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
    props: ['single', 'subject', 'yellow', 'startedExams', 'pendingExamsForToday'],
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

            this.$http.post('started-exams', {
                class_id: this.single.id,
                subject_id: this.subject.subject_id
            })
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('startNewExam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.dialog = false
                this.$noty.error(err.response.data.message)
            })
        },
        endExam() {
            this.loading = true

            let examId = this.startedExams.filter(exam => exam.subject.name == this.subject.name && exam.class.name == this.single.name)[0].id
            this.$http.delete(`started-exams/${examId}`)
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('endExam', res.data.data.id)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        }
    },
    computed: {
        examStarted() {
            let check = this.startedExams.filter(exam => exam.subject.name == this.subject.name && exam.class.name == this.single.name)

            return check.length > 0;
        },
        examCanBeStarted() {
            let check = this.pendingExamsForToday.filter(exam => exam.subject_id == this.subject.subject_id && exam.class_id == this.single.id)

            return check.length > 0;
        }
    }
}
</script>

<style>

</style>
