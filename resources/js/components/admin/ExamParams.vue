<template>
    <div class="container">
        <div class="row">
            <div class="text-center col-md-7">
            Examination Date / Duration / Aggregate Score: <strong>{{refinedDate}} / {{examtime}} / {{totalMarks}} marks</strong> (<a href="#change" @click.prevent="dialog = true">Change</a>)
            </div>
            <!-- <div class="col-md-1"></div> -->
            <div class="col-md-5">
                <div class="float-right">
                    <v-btn class="" :color="yellow" small tile title="Use Previous Examination Questions as a template for this examination">
                    EXAM PQ TEMPLATES
                    </v-btn>
                    <v-btn class="ml-2 " :color="yellow" small tile title="Create New Examination">
                        CREATE NEW EXAM
                    </v-btn>
                </div>
            </div>
        </div>

        <v-snackbar v-model="snackbar">
            {{ snackbarText }}
            <v-btn color="pink" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>

        <v-dialog v-model="dialog" max-width="500" persistent>
            <v-card>
                <v-card-title v-if="exam" class="headline">Change Exam Parameters</v-card-title>
                <v-card-title v-else class="headline">Set Exam Parameters</v-card-title>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="hourArray" v-model="hours" :menu-props="{ maxHeight: '400' }" label="Hours"></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="minuteArray" v-model="minutes" :menu-props="{ maxHeight: '400' }" label="Minutes"></v-select>
                        </v-col>
                    </v-row>

                    <v-menu ref="dateMenu" v-model="dateMenu" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                      <template v-slot:activator="{ on }">
                          <v-text-field readonly v-model="date" label="Date" v-on="on"></v-text-field>
                      </template>
                      <v-date-picker :color="yellow" :min="today"  v-model="date" no-title @input="dateMenu = false"></v-date-picker>
                    </v-menu>

                    <v-text-field type="number" v-model="totalMarks" persistent-hint label="Aggregate Score" hint="Note: student scores will be calculated using this as a base value">

                    </v-text-field>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || ((hours == 0 && minutes == 0) || totalMarks < 5)" color="green darken-1" text @click="setExam()">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "ExamParams",
    props: ['exam','yellow'],
    data() {
        return {
            hours: this.exam.hours,
            minutes: this.exam.minutes,
            totalMarks: this.exam.base_score,
            date: this.exam.date,
            today: new Date().toISOString().substr(0, 10),
            hourArray: [0,1,2,3,4,5,6],
            minuteArray: [0,5,10,15,20,25,30,35,40,45,50,55],
            dialog: false,
            loading: false,
            dateMenu: false,
            snackbar: false,
            snackbarText: ''
        }
    },
    methods: {
        setExam() {
            this.loading = true
            let { hours, minutes, totalMarks, date } = this;

            if (this.hours == this.exam.hours && this.minutes == this.exam.minutes && this.totalMarks == this.exam.base_score && this.date == this.exam.date) {
                this.loading = false;
                this.dialog = false
            }
            else {
                this.$http.put(`exams/${this.exam.id}`, {
                    hours,
                    minutes,
                    date,
                    base_score: totalMarks
                })
                .then(res => {
                    this.loading = false
                    this.dialog = false
                    this.$emit('setExam', 'update', res.data.exam)
                    this.snackbar = true
                    this.snackbarText = res.data.message

                })
                .catch(err => {
                    this.loading = false
                    this.dialog = false
                    console.log(err.response.data)
                    alert("There was an error updating this exam, please try again")
                })
            }
        },
        backToPrevious() {
            this.hours = this.exam.hours
            this.minutes = this.exam.minutes
            this.totalMarks = this.exam.base_score
            this.dialog = false
        }
    },
    computed: {
        examtime() {
            let time;
            if (this.exam.hours > 0) {
                if (this.exam.hours == 1) {
                    this.exam.minutes == 0 ? time = "1 hour" : time = `1 hour and ${this.exam.minutes} minutes`
                }
                else {
                    this.exam.minutes == 0 ? time = `${this.exam.hours} hours` : time = `${this.exam.hours} hours and ${this.exam.minutes} minutes`
                }
            }

            else {
                time = `${this.exam.minutes} minutes`
            }

            return time
        },
        refinedDate() {
            const options = { year: "numeric", month: "long", day: "numeric" }
            return new Date(this.exam.date).toLocaleDateString(undefined, options)
        },
    }
}
</script>

<style scoped>

</style>
