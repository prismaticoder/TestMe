<template>
    <div class="row border-bottom">
        <div class="col-12 text-center">
            <span>{{single.class}}</span>
        </div>
        <div class="col-1"></div>
        <div class="col-5">
            <v-btn :href="`/admin/subjects/${subject.alias}/${single.id}/questions`" small title="Go to Questions" :color="yellow">
                Questions
            </v-btn>
        </div>
        <div class="col-1"></div>
        <div class="col-5">
            <v-btn v-if="!single.startedExams[subject.subject_name]" :disabled="!single.examsWithParamsSet[subject.subject_name]" small :title="single.examsWithParamsSet[subject.subject_name] ? 'Start Exam' : 'You cannot start this exam because exam duration has not been set'" :color="yellow">
                Begin Exam
            </v-btn>
            <v-btn v-else small tile title="End Exam" :color="yellow">
                End Exam
            </v-btn>
        </div>
        <hr>
    </div>
</template>

<script>
export default {
    name: "SingleClass",
    props: ['single', 'subject', 'yellow']
}
</script>

<style>

</style>
