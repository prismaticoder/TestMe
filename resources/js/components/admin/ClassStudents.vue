<template>
  <div>
        <v-tabs v-model="tab" :background-color="yellow" centered grow center-active>
            <v-tab v-for="section in classes" :key="section.id" :href="`#${section.class.toLowerCase()}`" @click="$location.hash = `#${section.class.toLowerCase()}`">
                {{ section.class }}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="section in classes" :key="section.id" :value="section.class.toLowerCase()" class="text-center">
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
            </v-tab-item>
        </v-tabs-items>
  </div>
</template>

<script>
import SingleStudent from './SingleStudent'

export default {
    name: "ClassStudents",
    props: ['allclasses','isadmin'],
    components: {
        SingleStudent
    },
    data() {
        return {
            tab: null,
            yellow:  "#e67d23",
            classes: this.allclasses,
            snackbar: false,
            snackbarText: '',
            dialog: false,
            lastname: null,
            firstname: null,
            loading: false,
        }
    },
    methods: {
        addStudent(classId) {
            this.loading = true;

            this.$http.post('students', {
                firstname: this.firstname,
                lastname: this.lastname,
                class_id: classId
            })
            .then(res => {
                this.loading = false;
                this.dialog = false;
                this.classes.find(single => single.id == classId).students.push(res.data.student);
                this.classes.find(single => single.id == classId).students.sort((a, b) => (a.fullName > b.fullName) ? 1 : -1);
                this.snackbar = true;
                this.snackbarText = res.data.message
            })
            .catch(err => {
                this.loading = false;
                this.dialog = false;
                console.log(err.response.data)
                alert("Sorry, there was an error adding this student")
            })
        },
        updateStudent(student,index) {
            this.classes.find(single => single.id == student.class_id).students.splice(index,1,student);
        },
        deleteStudent(student) {
            this.classes.find(single => single.id == student.class_id).students = this.classes.find(single => single.id == student.class_id).students.filter(theStudent => theStudent.id !== student.id)
            this.snackbar = true;
            this.snackbarText = "Student deleted successfully"
        }
    },

    mounted() {
        this.tab = this.$location.hash.slice(1);
    }
}
</script>

<style>

</style>
