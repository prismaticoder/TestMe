<template>

    <div>
        <v-tabs v-model="tab" :background-color="yellow" centered grow center-active>
            <v-tab>Subjects</v-tab>
            <v-tab>Teachers</v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item class="text-center">
                <v-btn tile class="m-3" small dark @click="subjectDialog = true">
                    ADD NEW SUBJECT
                </v-btn>

                <table class="table table-bordered table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">S/N</th>
                            <th rowspan="2">SUBJECT NAME</th>
                            <th :colspan="allclasses.length">
                                SUBJECT CLASSES
                            </th>
                            <th rowspan="2">OPTIONS</th>
                        </tr>
                        <tr>
                            <th v-for="single in allclasses" :key="single.id">
                                {{single.class}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <SingleSubject v-for="(subject,index) in subjects" :key="subject.id" :allclasses="allclasses" :subject="subject" :number="index+1" :yellow="yellow" @updateSubject="updateSubject"/>
                    </tbody>
                </table>

                <v-dialog v-model="subjectDialog" max-width="500" persistent>
                    <v-card>
                        <v-card-title class="headline">Add New Subject</v-card-title>
                        <v-container>
                            <v-text-field v-model="name" @keyup="changeSlug" label="Subject Name"></v-text-field>
                            <v-text-field readonly v-model="alias" label="Slug" persistent-hint hint="This is the url rendering of the subject"></v-text-field>

                            <v-select v-model="classes" :items="items" chips deletable-chips label="Classes taking this subject" multiple></v-select>

                        </v-container>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="loading" color="green darken-1" text @click="name=alias=null; classes.length = 0; subjectDialog=false">CLOSE</v-btn>
                            <v-btn :loading="loading" :disabled="loading || !name || !alias || classes.length === 0" color="green darken-1" text @click="createSubject()">SAVE</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
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
                        <SingleTeacher v-for="(teacher,index) in teachers" :key="teacher.id" :teacher="teacher" :subjects="subjects" :number="index+1" :yellow="yellow" @updateTeacher="updateTeacher"/>
                    </tbody>
                </table>

            </v-tab-item>
        </v-tabs-items>

        <v-snackbar v-model="snackbar">
            {{ snackbarText }}
            <v-btn color="pink" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>
    </div>

</template>

<script>
import SingleTeacher from './SingleTeacher'
import SingleSubject from './SingleSubject'

export default {
    name: "AdminSection",
    props: ['allsubjects', 'allteachers','allclasses'],
    components: {
        SingleTeacher,
        SingleSubject
    },
    data() {
        return {
            subjects: this.allsubjects,
            teachers: this.allteachers,
            name: null,
            alias: null,
            items: this.allclasses.map((single) => {return {text: single.class, value: single.id}}),
            classes: [],
            tab: null,
            dialog: false,
            loading: false,
            subjectDialog: false,
            teacherDialog: false,
            yellow:  "#e67d23",
            snackbar: false,
            snackbarText: ''
        }
    },
    methods: {
        updateSubject(subject, index) {
            this.subjects.splice(index,1,subject)
        },
        updateTeacher(teacher, index) {
            this.teachers.splice(index,1,teacher)
        },
        changeSlug() {
            this.alias = this.name.toLowerCase().split(' ').join('-')
        },
        createSubject() {
            this.loading = true

            this.$http.post(`admins/subjects`, {
                name: this.name,
                alias: this.alias,
                classes: this.classes
            })
            .then(res => {
                this.loading = false
                this.subjectDialog = false
                this.subjects.push(res.data.subject)
                this.snackbarText = res.data.message
                this.snackbar = true
            })
            .catch(err => {
                this.loading = false
                this.subjectDialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error updating this subject, please try again later")
            })
        },
    }

}
</script>
