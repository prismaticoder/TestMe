<template>
    <div>
        <span class="navbar-text center" style="margin-right:40px;">

            <h3>
                Timer:
                <span id="hours">
                    {{currentHour}}h
                </span>
                <span id="minutes">
                    {{currentMinute}}m
                </span>
                <span id="seconds">
                    {{currentSecond}}s
                </span>
            </h3>

        </span>

        <span class="navbar-text">
            <button class="nav-link newButton btn btn-primary submitBtn" disabled data-button-type="submit">SUBMIT EXAMINATION</button>
        </span>

    </div>
</template>

<script>
export default {
    name: "Timer",
    props: ['hours', 'minutes'],
    data() {
        return {
            currentHour: this.hours,
            currentMinute: this.minutes,
            currentSecond: 0,
            interval: null,
            x: null
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
                this.$store.dispatch('endExam')
                .then(() => {
                    console.log('done')
                })
                .catch(() => console.log('ended'))
            }
        }
    },
    methods: {
        setInterval() {
            let { hasStarted } = this
            let endTime = this.$store.state.endTime

            if (hasStarted) {

                this.interval = parseInt(endTime) - new Date().getTime();

                this.x = this.interval ? setInterval(this.changeInterval, 1000) : null;
            }
        },
        changeInterval() {
            this.interval = this.interval - 1000;
            this.currentHour = Math.floor(this.interval / (1000*60*60));
            this.currentMinute =  Math.floor((this.interval % (1000*60*60*24)) % (1000*60*60) / (1000*60))
            this.currentSecond = Math.floor((this.interval % (1000*60*60*24)) % (1000*60*60) % (1000*60) / 1000)
        },
    },
    mounted() {
        this.setInterval()
    }

}
</script>

<style>

</style>
