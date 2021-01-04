<template>
    <div class="col-md-4 float-right">
        <v-menu offset-y close-on-click>
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="float-right" :color="yellow" v-bind="attrs" v-on="on" small tile outlined :retain-focus-on-click="false">
                    <v-icon color="text-white">mdi-dots-vertical</v-icon> OPTIONS
                </v-btn>
            </template>
            <v-list dense>
                <v-list-item :href="exams.length >= 1 ? `/admin/${subject.slug}/${classId}/results/${selected_exam.id}/download` : ''" :disabled="selected_exam.students.length === 0">
                    <v-list-item-title>Download PDF</v-list-item-title>
                </v-list-item>
                <v-list-item :href="`/admin/${subject.slug}/${classId}/results`" :disabled="iscurrentexam">
                    <v-list-item-title>Latest Exam Results</v-list-item-title>
                </v-list-item>
                <v-list-item @click="showExamList = true" :disabled="exams.length <= 1">
                    <v-list-item-title>View Previous Results</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-overlay :color="black" v-show="showExamList">

            <v-btn fab absolute top right :color="yellow" title="Close" @click="showExamList = false">
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
                            <v-btn :color="yellow" small tile outlined :href="`?exam_id=${exam.id}`">
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
    name: "Results",
    props: ['exams','subject','classId','iscurrentexam','selected_exam'],
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
