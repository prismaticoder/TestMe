<template>
    <div>
        <h4>Exams in progress...</h4>
        <hr>
        <div v-if="allStartedExams.length > 0" class="container">
            <div class="row">
                <StartedExam v-for="exam in allStartedExams" :key="exam.id" :exam="exam" :yellow="yellow" @endExam="endExam"/>
            </div>
        </div>
        <p v-else>There are currently no examinations in progress</p>
        <hr>
        <h4>Subjects</h4>
        <hr>
        <div class="container-fluid">
            <v-expansion-panels accordion hover focusable>
                <v-expansion-panel class="col-md-4 border m-1" v-for="subject in subjects" :key="subject.id">
                        <v-expansion-panel-header><h6>{{subject.name.toUpperCase()}}</h6></v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <SingleClass :single="single" :subject="subject" :startedExams="allStartedExams" :pendingExamsForToday="allPendingExamsForToday" :yellow="yellow" @startNewExam="startNewExam" @endExam="endExam" v-for="single in subject.classes" :key="single.id" />
                            </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </div>
</template>

<script>
import SingleClass from './SingleClass';
import StartedExam from './StartedExam';

export default {
    name: "Dashboard",
    props: ['subjects', 'classes', 'startedExams', 'pendingExamsForToday'],
    data() {
        return {
            yellow: "#e67d23",
            dark: "#343a40",
            allStartedExams: this.startedExams,
            allPendingExamsForToday: this.pendingExamsForToday
        }
    },
    components: {
        SingleClass,
        StartedExam
    },
    methods: {
        startNewExam(exam) {
            let newExam = {id: exam.id, subject: exam.subject, class: exam.class}
            this.allStartedExams.push(newExam)
        },

        endExam(id) {
            this.allStartedExams = this.allStartedExams.filter(exam => exam.id !== id)
        },
    }
}
</script>

<style scoped>
.active {
    background-color: #343a40;
    color: #e67d23;
    border: solid #343a40 1px;
}
</style>
