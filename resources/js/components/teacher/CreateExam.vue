<template>
    <v-overlay :color="black" absolute>

        <h4>You haven't created any examination...</h4>

        <v-btn class="mt-4 col-6 mx-auto" tile :color="yellow" @click="dialog=true">
            <v-icon>
                mdi-arrow-right
            </v-icon>
            CREATE NEW EXAM
        </v-btn>

        <v-dialog v-model="dialog" max-width="500" persistent>
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

    </v-overlay>
</template>

<script>
export default {
    name: "CreateExam",
    props: ['black','yellow','classId','subjectId'],
    data() {
        return {
            hours: 0,
            minutes: 0,
            totalMarks: 50,
            date: new Date().toISOString().substr(0, 10),
            today: new Date().toISOString().substr(0, 10),
            hourArray: [0,1,2,3,4,5,6],
            minuteArray: [0,5,10,15,20,25,30,35,40,45,50,55],
            dialog: false,
            loading: false,
            dateMenu: false
        }
    },
    methods: {
        backToPrevious() {
            [this.hours, this.minutes, this.totalMarks] = [0, 0, 50];
            this.date = new Date().toISOString().substr(0, 10)
            this.dialog = false
        },
        setExam() {
            this.loading = true
            let { hours, minutes, totalMarks, date } = this;

            this.$http.post('exams', {
                hours,
                minutes,
                date,
                aggregate_score: totalMarks,
                class_id: this.classId,
                subject_id: this.subjectId
            })
            .then(res => {
                this.loading = false
                this.dialog = false
                this.$noty.success(res.data.message)
                this.$emit('create-exam', res.data.data)
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        }
    }
}
</script>
