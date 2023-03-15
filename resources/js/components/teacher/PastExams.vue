<template>
    <v-overlay :color="black" v-show="showPQList">

        <v-btn fab absolute top right :color="yellow" title="Close" @click="$emit('alterPQList', 'close')">
            <v-icon style="color: #343a40">
                mdi-close
            </v-icon>
        </v-btn>

        <v-simple-table light class="p-4" fixed-header>
            <thead>
                <tr>
                    <th colspan="2">
                        <h4>EXAM PAST QUESTION TEMPLATES</h4>
                    </th>
                </tr>
                <tr>
                    <th class="text-center">S/N</th>
                    <th class="text-center">Date Held</th>
                    <th class="text-center">Options</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(exam,index) in pastExams" :key="exam.id" class="text-center">
                    <th>{{index+1}}</th>
                    <th>{{formatDate(exam.date)}}</th>
                    <th>
                        <v-btn :color="yellow" small tile outlined @click="dialog = true; selectedExam = exam">
                            IMPORT QUESTIONS
                        </v-btn>
                    </th>
                </tr>
            </tbody>
        </v-simple-table>


        <v-dialog v-model="dialog" max-width="500">
            <v-card>
                <v-card-title class="headline">Import Questions From This Examination?</v-card-title>
                <v-card-text>
                Doing this will randomly import a specified number of questions from this examination. It is important to note that question imports can only be done ONCE, and a maximum of <strong>sixty</strong> is allowed for imports. <br>
                Kindly enter the number of questions you wish to import from this examination (<strong>Exam Date: {{formatDate(selectedExam.date)}}</strong>) into the current examination in the input field below:
                </v-card-text>

                <v-col cols="5" sm="6" md="6">
                    <v-text-field solo type="number" min="1" :max="selectedExam.questions.length > 60 ? 60 : selectedExam.questions.length" v-model="maxImport" label="Number of questions to import" persistent-hint :hint="`No of Questions in selected examination: ${selectedExam.questions.length}`">
                    </v-text-field>
                </v-col>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="dialog = false">Close</v-btn>
                    <v-btn :loading="loading" :disabled="loading || maxImport == 0 || maxImport > selectedExam.questions.length" color="green darken-1" text @click="setTemplate()">Import</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-overlay>
</template>

<script>
export default {
    name: "PastExams",
    props: ['showPQList','pastExams','yellow','black'],
    data() {
        return {
            dialog: false,
            loading: false,
            selectedExam: {questions: [], date: null},
            maxImport: 1
        }
    },
    methods: {
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric"}
            return new Date(dateString).toLocaleDateString(undefined, options)
        },
        setTemplate() {
            this.loading = true

            this.$http.post('duplicated-exams', {
                from_exam_id: this.selectedExam.id,
                number: this.maxImport
            })
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$emit('usePQTemplate', res.data.data)
            })
            .catch(err => {
                this.$noty.error(err.response.data.message || "Sorry, an error was encountered in creating this template, please try again.")
                this.loading = false
                this.dialog = false
            })
        }
    }
}
</script>

<style>

</style>
