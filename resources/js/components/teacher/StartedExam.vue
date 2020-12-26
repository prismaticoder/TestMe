<template>
    <span class="col-md-4 border-bottom border-right">
        {{exam.subject.name}} ({{exam.class.name}})
        <v-btn tile small outlined class="float-right" :color="yellow" @click="dialog = true" title="End Exam">
            End Exam
        </v-btn>

        <v-dialog v-model="dialog" max-width="350">
            <v-card>
                <v-card-title class="headline">End Exam?</v-card-title>
                <v-card-text>
                Please confirm that you want to put an end to this exam: <strong>{{exam.subject.name}} ({{exam.class.name}})</strong>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="dialog = false">No</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="endExam()">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </span>
</template>

<script>
export default {
    name: "StartedExam",
    props: ['exam', 'yellow'],
    data() {
        return {
            loading: false,
            dialog: false
        }
    },
    methods: {
        endExam() {
            this.loading = true

            this.$http.delete(`started-exams/${this.exam.id}`)
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('endExam', res.data.data.id)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message);
            })
        }
    }
}
</script>

<style>

</style>
