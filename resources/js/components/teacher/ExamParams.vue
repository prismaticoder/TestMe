<template>
    <div class="container">
        <div class="row">
            <div class="text-center col-md-6">
            Examination Date / Duration / Aggregate Score: <strong>{{refinedDate}} / {{examtime}} / {{totalMarks}} marks</strong> <span>(<a href="#change" @click.prevent="updateDialog = true">Change</a>)</span>
            </div>
            <!-- <div class="col-md-1"></div> -->
            <div class="col-md-6">
                <div class="float-right">
                    <v-btn v-show="!exam.hasBeenWritten && examCount > 1 && !exam.duplicated_from" :color="yellow" @click="$emit('alterPQList', 'open')" small tile title="Import some of the previous examination questions into the current examination">
                        EXAM PQ TEMPLATES
                    </v-btn>
                    <v-btn v-show="exam.date == today && questionCount > 0 && !exam.has_started" :color="yellow" @click="examDialog = true" small tile title="Start this Examination">
                        START EXAM
                    </v-btn>
                    <v-btn v-show="exam.has_started" :color="yellow" @click="examDialog = true" small tile title="End examination">
                        END EXAM
                    </v-btn>
                    <v-btn v-show="exam.hasBeenWritten && !exam.has_started" @click="createDialog=true" class="ml-2" :color="yellow" small tile title="Create New Examination">
                        CREATE NEW EXAM
                    </v-btn>
                </div>
            </div>
        </div>

        <v-dialog v-model="examDialog" max-width="350">
            <v-card>
                <v-card-title v-if="!exam.has_started" class="headline">Start Exam?</v-card-title>
                <v-card-title v-else class="headline">End Exam?</v-card-title>
                <v-card-text v-if="!exam.has_started">
                Please confirm that you want to start this exam: <strong>{{exam.subject.name}} ({{exam.class.name}})</strong>
                </v-card-text>
                <v-card-text v-else>
                Please confirm that you want to put an end to this exam: <strong>{{exam.subject.name}} ({{exam.class.name}})</strong>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="examDialog = false">No</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="exam.has_started ? endExam() : startExam()">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="updateDialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="headline">Change Exam Parameters</v-card-title>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="hourArray" v-model="hours" :menu-props="{ maxHeight: '400' }" label="Hours"></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="minuteArray" v-model="minutes" :menu-props="{ maxHeight: '400' }" label="Minutes"></v-select>
                        </v-col>
                    </v-row>

                    <v-menu ref="dateMenu" v-model="updateDateMenu" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                      <template v-slot:activator="{ on }">
                          <v-text-field readonly v-model="date" label="Date" v-on="on" persistent-hint :hint="date < today ? 'Date selected must be after or equal to today\'s date' : ''"></v-text-field>
                      </template>
                      <v-date-picker :color="yellow" :min="today"  v-model="date" no-title @input="updateDateMenu = false"></v-date-picker>
                    </v-menu>

                    <v-text-field type="number" v-model="totalMarks" persistent-hint label="Aggregate Score" hint="Note: student scores will be calculated using this as a base value">

                    </v-text-field>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious(); updateDialog=false">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || ((hours == 0 && minutes == 0) || totalMarks < 5)" color="green darken-1" text @click="updateExam()">CHANGE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="createDialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="headline">Create New Exam</v-card-title>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="hourArray" v-model="hours" :menu-props="{ maxHeight: '400' }" label="Hours"></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                            <v-select :items="minuteArray" v-model="minutes" :menu-props="{ maxHeight: '400' }" label="Minutes"></v-select>
                        </v-col>
                    </v-row>

                    <v-menu ref="dateMenu" v-model="createDateMenu" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                      <template v-slot:activator="{ on }">
                          <v-text-field readonly v-model="newExamDate" label="Date" v-on="on" persistent-hint :hint="newExamDate < today ? 'Date selected must be after or equal to today\'s date' : ''"></v-text-field>
                      </template>
                      <v-date-picker :color="yellow" :min="today"  v-model="newExamDate" no-title @input="createDateMenu = false"></v-date-picker>
                    </v-menu>

                    <v-text-field type="number" v-model="totalMarks" persistent-hint label="Aggregate Score" hint="Note: student scores will be calculated using this as a base value">

                    </v-text-field>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious(); createDialog=false">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || ((hours == 0 && minutes == 0) || totalMarks < 5)" color="green darken-1" text @click="createExam()">CREATE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "ExamParams",
    props: ['exam','yellow','examCount','questionCount','subjectId','classId'],
    data() {
        return {
            hours: this.exam.hours,
            minutes: this.exam.minutes,
            totalMarks: this.exam.aggregate_score,
            date: this.exam.date,
            newExamDate: new Date().toISOString().substr(0, 10),
            today: new Date().toISOString().substr(0, 10),
            hourArray: [0,1,2,3,4,5,6],
            minuteArray: [0,5,10,15,20,25,30,35,40,45,50,55],
            createDialog: false,
            updateDialog: false,
            examDialog: false,
            loading: false,
            createDateMenu: false,
            updateDateMenu: false,
        }
    },
    methods: {
        createExam() {
            this.loading = true
            let { hours, minutes, totalMarks, newExamDate } = this;

            this.$http.post('exams', {
                hours,
                minutes,
                date: newExamDate,
                aggregate_score: totalMarks,
                class_id: this.classId,
                subject_id: this.subjectId
            })
            .then(res => {
                this.loading = false
                this.createDialog = false
                this.$noty.success(res.data.message)
                this.$emit('create-exam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        updateExam() {
            this.loading = true
            let { hours, minutes, totalMarks, date } = this;

            if (hours == this.exam.hours
                && minutes == this.exam.minutes
                && totalMarks == this.exam.aggregate_score
                && date == this.exam.date)
            {
                this.loading = false;
                this.updateDialog = false
                return;
            }

            this.$http.put(`exams/${this.exam.id}`, {
                hours,
                minutes,
                date,
                aggregate_score: totalMarks
            })
            .then(res => {
                this.loading = false
                this.updateDialog = false
                this.$noty.success(res.data.message)
                this.$emit('update-exam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        backToPrevious() {
            this.hours = this.exam.hours
            this.minutes = this.exam.minutes
            this.totalMarks = this.exam.aggregate_score
            this.newExamDate = new Date().toISOString().substr(0, 10)
        },
        startExam() {
            this.loading = true

            this.$http.post('started-exams', {
                class_id: this.classId,
                subject_id: this.subjectId
            })
            .then(res => {
                this.loading = false
                this.examDialog = false
                this.$emit('update-exam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        endExam() {
            this.loading = true

            this.$http.delete(`started-exams/${this.exam.id}`, {
                class_id: this.classId,
                subject_id: this.subjectId
            })
            .then(res => {
                this.loading = false
                this.examDialog = false
                this.$emit('update-exam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
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
