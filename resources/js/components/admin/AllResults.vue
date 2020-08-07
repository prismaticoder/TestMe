<template>
    <div v-show="exams.length > 1" class="col-md-2 mx-auto">
        <v-btn outlined small :color="yellow" @click="showExamList = true">
            SEE PREVIOUS EXAMINATION RESULTS
        </v-btn>

        <v-overlay :color="black" v-show="showExamList">

            <v-btn fab absolute top right :color="yellow" title="Close" @click="showExamList">
                <v-icon style="color: #343a40">
                    mdi-close
                </v-icon>
            </v-btn>

            <v-simple-table light class="p-4" fixed-header>
                <thead>
                    <tr>
                        <th colspan="2">
                            <h4>VIEW PAST EXAMINATION RESULTS</h4>
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
                            <v-btn :color="yellow" small tile outlined :href="`?exam_date=${exam.date}`">
                                VIEW RESULTS
                            </v-btn>
                        </th>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-overlay>
    </div>
</template>

<script>
export default {
    name: "AllResults",
    props: ['exams','subject','classId'],
    data() {
        return {
            yellow:  "#e67d23",
            black: "#343a40",
            showExamList: false,
        }
    },
    computed: {
        pastExams() {
           return this.exams.filter((exam, index) => index !== 0);
        }
    },
    methods: {
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric"}
            return new Date(dateString).toLocaleDateString(undefined, options)
        },
    }
}
</script>

<style>

</style>
