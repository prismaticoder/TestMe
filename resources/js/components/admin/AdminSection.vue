<template>

    <div>
        <v-tabs v-model="tab" :background-color="yellow" centered grow center-active>
            <v-tab>Subjects</v-tab>
            <v-tab>Teachers</v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <v-btn tile class="m-3" small dark @click="dialog = true">
                    ADD NEW SUBJECT
                </v-btn>

                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>SUBJECT NAME</th>
                        <th>CLASSES UNDER THIS SUBJECT</th>
                        <th colspan="3">OPTIONS</th>
                    </thead>
                    <tbody>
                        <SingleSubject v-for="(subject,index) in subjects" :key="subject.id" :subject="subject" :number="index+1" :yellow="yellow"/>
                    </tbody>
                </table>
            </v-tab-item>
            <v-tab-item>
                <v-btn tile class="m-3" small dark @click="dialog = true">
                    ADD NEW TEACHER
                </v-btn>

                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>USERNAME</th>
                        <th>SUBJECTS TAUGHT</th>
                        <th colspan="3">OPTIONS</th>
                    </thead>
                    <tbody>
                        <SingleTeacher v-for="(teacher,index) in teachers" :key="teacher.id" :teacher="teacher" :number="index+1" :yellow="yellow"/>
                    </tbody>
                </table>

            </v-tab-item>
            <!-- <v-tab-item v-for="section in classes" :key="section.id" :value="section.class.toLowerCase()" class="text-center">
                <v-btn tile class="m-3" small dark @click="dialog = true">
                    ADD NEW STUDENT
                </v-btn>
                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>EXAMINATION NUMBER</th>
                        <th>NAME</th>
                        <th colspan="3">OPTIONS</th>
                    </thead>
                    <tbody>
                        <SingleStudent v-for="(student,index) in section.students" :isadmin="isadmin" :key="student.id" :student="student" :number="index+1" :yellow="yellow" @updateStudent="updateStudent" @deleteStudent="deleteStudent"/>
                    </tbody>
                </table>

                <v-dialog v-model="dialog" max-width="500" persistent>
                    <v-card>
                        <v-card-title class="headline">{{section.class}}: Add New Student</v-card-title>
                        <v-container>
                            <v-text-field v-model="lastname" label="Surname"></v-text-field>
                            <v-text-field v-model="firstname" label="Firstname"></v-text-field>
                        </v-container>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="loading" color="green darken-1" text @click="lastname = firstname = null; dialog = false">CLOSE</v-btn>
                            <v-btn :loading="loading" :disabled="loading || !lastname || !firstname" color="green darken-1" text @click="addStudent(section.id)">ADD STUDENT</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-snackbar v-model="snackbar">
                    {{ snackbarText }}
                    <v-btn color="pink" text @click="snackbar = false">
                        Close
                    </v-btn>
                </v-snackbar>
            </v-tab-item> -->
        </v-tabs-items>
    </div>

</template>

<script>
import SingleTeacher from './SingleTeacher'
import SingleSubject from './SingleSubject'

export default {
    name: "AdminSection",
    props: ['allsubjects', 'allteachers'],
    components: {
        SingleTeacher,
        SingleSubject
    },
    data() {
        return {
            subjects: this.allsubjects,
            teachers: this.allteachers,
            tab: null,
            dialog: false,
            loading: false,
            yellow:  "#e67d23",
        }
    }

}
</script>
