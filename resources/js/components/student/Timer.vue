<template>
  <div class="row mx-1">
    <div class="col-lg-10 border-right border-left">
      <h4
        class="text-center text-white"
        v-bind:class="{ 'text-danger': examIsAlmostEnding }"
      >
        TIME LEFT:
        <span id="hours">{{ currentHour }}h</span>
        <span id="minutes">{{ currentMinute }}m</span>
        <span id="seconds">{{ currentSecond }}s</span>
      </h4>
    </div>

    <div class="col-lg-2">
      <v-btn
        tile
        block
        class="nav-link"
        color="bg-warning"
        :disabled="!hasStarted || !studentCanNowSubmit"
        @click="dialog = true"
      >
        SUBMIT
      </v-btn>

      <v-dialog v-model="dialog" persistent max-width="350">
        <v-card>
          <v-card-title class="headline">Submit Examination?</v-card-title>
          <v-card-text>
            Are you sure you are ready to submit your examination? Doing so
            would mean you will not be able to return to check your answers.
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              :disabled="btnLoading"
              color="green darken-1"
              text
              @click="dialog = false"
              >No</v-btn
            >
            <v-btn
              :loading="btnLoading"
              :disabled="btnLoading"
              color="green darken-1"
              text
              @click="submitExam"
              >Yes, Submit</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="pauseDialog" persistent max-width="350">
        <v-card>
          <v-card-title class="headline">Network Disconnected</v-card-title>
          <v-card-text>
            This system is no longer connected to the network. Reach out to the
            invigilator to help sort this out. This screen will disappear once
            connection is restored.
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
export default {
  name: "Timer",
  props: ["hours", "minutes", 'examId', 'studentId'],
  data() {
    return {
      currentHour: this.hasStarted ? "-" : this.hours,
      currentMinute: this.hasStarted ? "-" : this.minutes,
      currentSecond: this.hasStarted ? "-" : 0,
      interval: null,
      duration: null,
      x: null,
      dialog: false,
      pauseDialog: false,
      btnLoading: false,
    };
  },
  computed: {
    hasStarted() {
      return this.$store.getters.hasStarted(this.studentId, this.examId);
    },
    examIsAlmostEnding() {
      return this.interval <= (0.1 * this.duration);
    },
    studentCanNowSubmit() {
      return this.interval <= (0.5 * this.duration);
    }
  },
  watch: {
    interval(newValue) {
      if (newValue < 0) {
        clearInterval(this.x);
        this.currentHour = 0;
        this.currentMinute = 0;
        this.currentSecond = 0;
        this.submitExam();
      }
    },
    hasStarted(newValue) {
      this.setInterval();
    },
  },
  methods: {
    triggerOfflineStatus() {
      if (this.x) {
        clearInterval(this.x);
        this.x = null;
        this.pauseDialog = true;
      }
    },
    networkReactivationCheck() {
      if (this.hasStarted && this.x === null && this.pauseDialog) {
        //make a call to the network status to be sure the server is reachable not just that the system is online
        this.$http.get("network-status")
          .then((res) => {
              this.pauseDialog = false;
              this.setInterval();
          })
      }
    },
    setInterval() {
      const timeExamEnds = this.$store.getters.timeExamEnds;
      const timeExamStarted = this.$store.getters.timeExamStarted;
      const examDurationInMilliseconds = timeExamEnds - timeExamStarted;
      const examTimeLeft =
        localStorage.getItem("tabClosed") || !localStorage.getItem("timeLeft")
          ? timeExamEnds - new Date().getTime()
          : localStorage.getItem("timeLeft");

      this.duration = examDurationInMilliseconds;
      this.interval = examTimeLeft;

      this.x = this.interval ? setInterval(this.changeInterval, 1000) : null;
    },
    changeInterval() {
      this.interval = this.interval - 1000;
      this.currentHour = Math.floor(this.interval / (1000 * 60 * 60));
      this.currentMinute = Math.floor(
        ((this.interval % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60)) /
          (1000 * 60)
      );
      this.currentSecond = Math.floor(
        (((this.interval % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60)) %
          (1000 * 60)) /
          1000
      );

      localStorage.setItem("timeLeft", this.interval);
    },
    submitExam() {
      this.btnLoading = true;
      this.$store
        .dispatch("endExam")
        .then((res) => {
          this.btnLoading = false;
          this.dialog = false;
          localStorage.removeItem("timeLeft");
          window.location.href = "/success";
        })
        .catch((err) => {
          this.btnLoading = false;
          console.log(err.response.data)
          const errorMessage = err.response.status === 403
            ? err.response.data.message
            : "Sorry, there was an error submitting your examination. Kindly contact the invigilator for assistance."
          this.$noty.error(errorMessage);
        });
    },
  },
  mounted() {
    if (this.hasStarted) {
      this.setInterval();
    }
    window.addEventListener('offline', this.triggerOfflineStatus);
    window.addEventListener('online', this.networkReactivationCheck);
  },
  destroyed() {
    window.removeEventListener('offline', this.triggerOfflineStatus);
    window.removeEventListener('online', this.networkReactivationCheck);
  },
};
</script>

<style>
</style>
