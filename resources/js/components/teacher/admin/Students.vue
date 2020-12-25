<template>
  <div>
        <v-tabs v-model="tab" :background-color="yellow" centered grow center-active>
            <v-tab v-for="section in classes" :key="section.id" :href="`#${trimString(section.name)}`" @click="$location.hash = `#${trimString(section.name)}`">
                {{ section.name }}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="section in classes" :key="section.id" :value="trimString(section.name)" class="text-center">
                <v-btn tile class="m-3" outlined @click="openClassDialog(section)">
                    <v-icon>
                        mdi-plus
                    </v-icon>
                    ADD STUDENTS
                </v-btn>
                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>EXAMINATION NUMBER</th>
                        <th>NAME</th>
                        <th colspan="3">OPTIONS</th>
                    </thead>
                    <tbody>
                        <SingleStudent v-for="(student,index) in section.students" :isadmin="isadmin" :key="student.id" :student="student" :number="index+1" :yellow="yellow" @update-student="updateStudent" @delete-student="deleteStudent"/>
                    </tbody>
                </table>
            </v-tab-item>
        </v-tabs-items>

        <v-dialog v-model="dialog" max-width="500">
            <v-card class="mx-auto" max-width="500">
                <v-card-title class="title font-weight-regular justify-space-between">
                    <span>{{currentClass.name}} | {{ addStudentModalTitle }}</span>
                </v-card-title>
                <v-window v-model="step">
                    <v-window-item :value="1">
                        <v-card-text class="justify-content-center p-4">
                                <v-btn outlined :color="yellow" text @click="step = 2">ADD SINGLE STUDENT</v-btn>
                                <v-divider></v-divider>
                            <v-btn outlined :color="yellow" text @click="step = 3">UPLOAD STUDENT LIST</v-btn>
                        </v-card-text>
                        <v-card-actions class="justify-center">
                            <v-spacer></v-spacer>
                            <v-btn :disabled="loading" color="green darken-1" text @click="dialog = false">CLOSE</v-btn>
                        </v-card-actions>
                    </v-window-item>

                    <v-window-item :value="2">
                        <v-container>
                            <v-text-field v-model="lastname" label="Surname"></v-text-field>
                            <v-text-field v-model="firstname" label="Firstname"></v-text-field>
                        </v-container>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="loading" color="green darken-1" text @click="resetWindowAndClose()">CLOSE</v-btn>
                            <v-btn :loading="loading" :disabled="loading || !lastname || !firstname" color="green darken-1" text @click="addStudent(currentClass.id)">ADD STUDENT</v-btn>
                        </v-card-actions>
                    </v-window-item>

                    <v-window-item :value="3">
                        <v-container>
                            <v-card-text>
                                <strong>NOTE</strong>: The file to be uploaded must be formatted in accordance with the following rules:

                                <ul class="list-group-flush mt-3">
                                    <li class="list-group-item">The file must be an excel file</li>
                                    <li class="list-group-item">The first row of the file should contain two columns: "Surname" and "First Name", every other row is to enter student details in that format</li>
                                    <li class="list-group-item">You can <a href="/download-sample-excel-file">download this sample file</a> as a reference</li>
                                </ul>
                            </v-card-text>
                            <v-file-input accept=".xlsx,.xls,.csv" v-model="studentList" show-size truncate-length="24" label="Choose File"></v-file-input>
                        </v-container>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="loading" color="green darken-1" text @click="resetWindowAndClose()">CLOSE</v-btn>
                            <v-btn :loading="loading" :disabled="!studentList" color="green darken-1" text @click="addMultipleStudents(currentClass.id)">UPLOAD</v-btn>
                        </v-card-actions>
                    </v-window-item>
                </v-window>
            </v-card>
        </v-dialog>
  </div>
</template>

<script>
import SingleStudent from './SingleStudent'

export default {
    name: "Students",
    props: ['allclasses','isadmin'],
    components: {
        SingleStudent
    },
    data() {
        return {
            tab: null,
            yellow:  "#e67d23",
            classes: this.allclasses,
            currentClass: this.allclasses[0],
            dialog: false,
            step: 1,
            studentList: null,
            lastname: null,
            firstname: null,
            loading: false,
        }
    },
    methods: {
        openClassDialog(currentClass) {
            this.currentClass = currentClass;
            this.dialog = true
        },
        addStudent(classId) {
            this.loading = true;

            this.$http.post('students', {
                firstname: this.firstname,
                lastname: this.lastname,
                class_id: classId
            })
            .then(res => {
                this.loading = false;
                this.resetWindowAndClose()
                this.lastname = null;
                this.firstname = null
                this.$noty.success(res.data.message)
                this.classes.find(single => single.id == classId).students.push(res.data.data);
                this.classes.find(single => single.id == classId).students.sort((a, b) => (a.fullName > b.fullName) ? 1 : -1);
            })
            .catch(err => {
                this.loading = false;
                this.dialog = false;
                this.$noty.error(err.response.data.message)
            })
        },
        addMultipleStudents(classId) {
            this.loading = true;

            let formData = new FormData();
            formData.append('students', this.studentList);
            formData.append('class_id', classId)

            this.$http.post('students/multiple', formData)
            .then(res => {
                this.loading = false;
                this.resetWindowAndClose();
                this.studentList = null
                this.$noty.success(res.data.message)
                this.classes.find(single => single.id == classId).students = [...res.data.data];
                this.classes.find(single => single.id == classId).students.sort((a, b) => (a.fullName > b.fullName) ? 1 : -1);
            })
            .catch(err => {
                this.loading = false;
                this.dialog = false;
                this.$noty.error(`Upload error: ${err.response.data.message}`)
            })
        },
        updateStudent(student,index) {
            this.classes.find(single => single.id == student.class_id).students.splice(index,1,student);
        },
        deleteStudent(student) {
            this.classes.find(single => single.id == student.class_id).students = this.classes.find(single => single.id == student.class_id).students.filter(theStudent => theStudent.id !== student.id)
            this.$noty.success("Student deleted successfully!")
        },
        resetWindowAndClose() {
            this.step = 1;
            this.dialog = false
        },
        trimString(string) {
            string = string.trim();
            string = string.toLowerCase().split(' ').join('');
            return string;
        }
    },

    computed: {
      addStudentModalTitle () {
        switch (this.step) {
          case 1: return 'Add Students'
          case 2: return 'Add Single Student'
          case 3: return 'Upload Student List'
        }
      },
    },

    mounted() {
        this.tab = this.$location.hash.slice(1);
    }
}
</script>
