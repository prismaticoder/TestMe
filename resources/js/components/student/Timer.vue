<template>
    <div>
        <span class="navbar-text center text-white" v-bind:class="{'text-danger': examIsAlmostEnding}" style="margin-right:40px;">

            <h4>
                    TIME LEFT:
                <span id="hours">
                    {{currentHour}}h
                </span>
                <span id="minutes">
                    {{currentMinute}}m
                </span>
                <span id="seconds">
                    {{currentSecond}}s
                </span>
            </h4>

        </span>

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

        <span class="navbar-text">
            <v-btn tile class="nav-link" color="bg-warning" :disabled="!hasStarted" @click="dialog = true">
                SUBMIT
            </v-btn>
        </span>

    </div>
</template>

<script>
export default {
    name: "Timer",
    props: ['hours', 'minutes'],
    data() {
        return {
            currentHour: this.hasStarted ? '-' : this.hours,
            currentMinute: this.hasStarted ? '-' :this.minutes,
            currentSecond: this.hasStarted ? '-' : 0,
            interval: null,
            duration: null,
            examIsAlmostEnding: false,
            x: null,
            dialog: false,
            btnLoading: false
        }
    },
    computed: {
        hasStarted() {
            return this.$store.getters.hasStarted
        }
    },
    watch: {
        interval(newValue) {
            if (newValue < 0) {
                clearInterval(this.x)
                this.currentHour = 0;
                this.currentMinute = 0;
                this.currentSecond = 0;
                this.submitExam()
                .then(() => {
                    console.log('submission successful!')
                })
            }

            else if (newValue <= 0.1 * this.duration) {
                this.examIsAlmostEnding = true;
            }
        },
        hasStarted(newValue) {
            this.setInterval()
        }
    },
    methods: {
        setInterval() {
            let { hasStarted } = this
            let endTime = this.$store.getters.timeExamEnds

            if (hasStarted) {

                const examDurationInMilliseconds = parseInt(endTime) - new Date().getTime();
                this.duration = examDurationInMilliseconds;
                this.interval = examDurationInMilliseconds;

                this.x = this.interval ? setInterval(this.changeInterval, 1000) : null;
            }
        },
        changeInterval() {
            this.interval = this.interval - 1000;
            this.currentHour = Math.floor(this.interval / (1000*60*60));
            this.currentMinute =  Math.floor((this.interval % (1000*60*60*24)) % (1000*60*60) / (1000*60))
            this.currentSecond = Math.floor((this.interval % (1000*60*60*24)) % (1000*60*60) % (1000*60) / 1000)
        },
        submitExam() {
            this.btnLoading = true;
            this.$store.dispatch('endExam')
            .then(() => {
                this.btnLoading = false;
                this.dialog = false
                window.location.href = '/success'
            })
            .catch(err => {
                this.btnLoading = false;
                this.dialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error submitting your examination. Kindly contact the invigilator for assistance.")

            })
        }
    },
    mounted() {
        this.setInterval()
    }

}
</script>

<style>

</style>
