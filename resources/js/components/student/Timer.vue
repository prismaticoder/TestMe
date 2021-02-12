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
        :disabled="!hasStarted"
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
  props: ["hours", "minutes"],
  data() {
    return {
      currentHour: this.hasStarted ? "-" : this.hours,
      currentMinute: this.hasStarted ? "-" : this.minutes,
      currentSecond: this.hasStarted ? "-" : 0,
      interval: null,
      duration: null,
      examIsAlmostEnding: false,
      x: null,
      dialog: false,
      pauseDialog: false,
      btnLoading: false,
    };
  },
  computed: {
    hasStarted() {
      return this.$store.getters.hasStarted;
    },
  },
  watch: {
    interval(newValue) {
      if (newValue < 0) {
        clearInterval(this.x);
        this.currentHour = 0;
        this.currentMinute = 0;
        this.currentSecond = 0;
        this.submitExam();
      } else if (newValue <= 0.1 * this.duration) {
        this.examIsAlmostEnding = true;
      }
    },
    hasStarted(newValue) {
      this.setInterval();
    },
  },
  methods: {
    checkState() {
      setInterval(() => {
        this.$http
          .get("network-status")
          .then((res) => {
            if (this.hasStarted && this.x === null) {
              this.pauseDialog = false;
              this.setInterval();
            }
          })
          .catch((err) => {
            if (this.x) {
              clearInterval(this.x);
              this.x = null;
              this.pauseDialog = true;
            }
          });
      }, 1000);
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
          window.location.href = "/success";
        })
        .catch((err) => {
          this.btnLoading = false;
          this.$noty.error(
            `Error submitting examination: ${err.response.data.message}`
          );
        });
    },
  },
  mounted() {
    this.checkState();
  },
};
</script>

<style>
</style>
