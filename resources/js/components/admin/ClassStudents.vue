<template>
  <div>
        <v-tabs v-model="tab" :background-color="yellow" centered>
            <v-tab v-for="section in classes" :key="section.id" :href="`#${section.class.toLowerCase()}`">
                {{ section.class }}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="(section, indexNum) in classes" :key="section.id" :value="section.class.toLowerCase()">
                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>EXAMINATION NUMBER</th>
                        <th>NAME</th>
                        <th colspan="3">OPTIONS</th>
                    </thead>
                    <tbody>
                        <SingleStudent v-for="(student,index) in section.students" :classIndex="indexNum" :key="student.id" :student="student" :number="index+1" :yellow="yellow" @updateStudent="updateStudent" @deleteStudent="deleteStudent"/>
                    </tbody>
                </table>
            </v-tab-item>
        </v-tabs-items>
  </div>
</template>

<script>
import SingleStudent from './SingleStudent'

export default {
    name: "ClassStudents",
    props: ['allclasses'],
    components: {
        SingleStudent
    },
    data() {
        return {
            tab: null,
            yellow:  "#e67d23",
            classes: this.allclasses
        }
    },
    methods: {
        updateStudent(student,index) {
            this.classes.find(single => single.id == student.class_id).students.splice(index,1,student);
        },
        deleteStudent(student) {
            this.classes.find(single => single.id == student.class_id).students = this.classes.find(single => single.id == student.class_id).students.filter(theStudent => theStudent.id !== student.id)
        }
    }
}
</script>

<style>

</style>
