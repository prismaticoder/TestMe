<template>
    <div class="container">
        <div class="text-center" v-if="params.length > 0">
            Exam Duration: <strong>{{examtime}}</strong> (<a href="#change" @click.prevent="dialog = true">Change</a>)
        </div>

        <div class="text-center" v-else>
            Exam Parameters not set yet (<a href="#set" @click.prevent="dialog = true">Set</a>)
        </div>

        <v-snackbar v-model="snackbar">
            {{ snackbarText }}
            <v-btn color="pink" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>

        <v-dialog v-model="dialog" max-width="500" persistent>
            <v-card>
                <v-card-title v-if="params.length > 0" class="headline">Change Exam Parameters</v-card-title>
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

                    <v-text-field type="number" v-model="totalMarks" persistent-hint label="Aggregate Score" hint="Note: student scores will be calculated using this as a base value">

                    </v-text-field>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || ((hours == 0 && minutes == 0) || totalMarks < 5)" color="green darken-1" text @click="params.length > 0 ? setParams('update') : setParams('create')">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "Params",
    props: ['params', 'subject', 'classId'],
    data() {
        return {
            hours: this.params.length > 0 ? this.params[0].hours : 0,
            minutes: this.params.length > 0 ? this.params[0].minutes : 0,
            totalMarks: this.params.length > 0 ? this.params[0].mark : 50,
            hourArray: [0,1,2,3,4,5,6],
            minuteArray: [0,5,10,15,20,25,30,35,40,45,50,55],
            dialog: false,
            loading: false,
            snackbar: false,
            snackbarText: ''
        }
    },
    methods: {
        setParams(type) {
            this.loading = true
            let { hours, minutes, totalMarks } = this;

            if (type == 'create') {
                this.$http.post('params', {
                    hours,
                    minutes,
                    mark: totalMarks,
                    class_id: this.classId,
                    subject_id: this.subject
                })
                .then(res => {
                    this.loading = false
                    this.dialog = false
                    this.$emit('setParams', 'create', res.data.params)
                    this.snackbar = true
                    this.snackbarText = res.data.message

                })
                .catch(err => {
                    this.loading = false
                    this.dialog = false
                    console.log(err.response.data)
                    alert("There was an error creating exam details, please try again")
                })
            }

            else if (type == 'update') {
                if (this.hours == this.params[0].hours && this.minutes == this.params[0].minutes && this.totalMarks == this.params[0].mark) {
                    this.loading = false;
                    this.dialog = false
                }
                else {
                    this.$http.put(`params/${this.params[0].id}`, {
                        hours,
                        minutes,
                        mark: totalMarks
                    })
                    .then(res => {
                        this.loading = false
                        this.dialog = false
                        this.$emit('setParams', 'update', res.data.params)
                        this.snackbar = true
                        this.snackbarText = res.data.message

                    })
                    .catch(err => {
                        this.loading = false
                        this.dialog = false
                        console.log(err.response.data)
                        alert("There was an error updating exam details, please try again")
                    })
                }

            }

            else {
                console.log("Invalid type")
                alert("Invalid Set type")
            }
        },
        backToPrevious() {
            if (this.params.length > 0) {
                this.hours = this.params[0].hours
                this.minutes = this.params[0].minutes
                this.totalMarks = this.params[0].mark
            }
            else {
                this.hours = 0;
                this.minutes = 0;
                this.totalMarks = 50
            }

            this.dialog = false
        }
    },
    computed: {
        examtime() {
            let time;
            if (this.params[0].hours > 0) {
                if (this.params[0].hours == 1) {
                    this.params[0].minutes == 0 ? time = "1 hour" : time = `1 hour and ${this.params[0].minutes} minutes`
                }
                else {
                    this.params[0].minutes == 0 ? time = `${this.params[0].hours} hours` : time = `${this.params[0].hours} hours and ${this.params[0].minutes} minutes`
                }
            }

            else {
                time = `${this.params[0].minutes} minutes`
            }

            return time
        },
    }
}
</script>

<style>

</style>
