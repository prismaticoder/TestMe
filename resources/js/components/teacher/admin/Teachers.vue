<template>
  <div class="container-fluid text-center">

    <v-btn tile class="m-3" outlined @click="createDialog=true">
        <v-icon>
            mdi-plus
        </v-icon>
        ADD NEW TEACHER
    </v-btn>

    <table class="table table-md table-bordered w-100 text-center">
        <thead>
            <th>S/N</th>
            <th>NAME</th>
            <th>USERNAME</th>
            <th>SUBJECTS TAUGHT</th>
            <th colspan="2">OPTIONS</th>
        </thead>
        <tbody>
            <SingleTeacher v-for="(teacher,index) in teachers" :key="teacher.id" :teacher="teacher" :subjects="subjects" :classes="allclasses" :number="index+1" :yellow="yellow" :items="subjectList" @updateTeacher="updateTeacher" @deleteTeacher="deleteTeacher"/>
        </tbody>
    </table>

    <v-dialog v-model="createDialog" max-width="650" :persistent="loading">
        <v-card>
            <v-card-title class="headline">Add New Teacher</v-card-title>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="2">
                        <v-select :items="titles" v-model="title" label="Title"></v-select>
                    </v-col>
                    <v-col cols="12" sm="5">
                        <v-text-field v-model="lastname" label="Surname" placeholder="Type surname here..."></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="5">
                        <v-text-field v-model="firstname" label="Firstname" placeholder="Type firstname here..."></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <v-text-field v-model="username" label="Username" placeholder="Type username here..." hint="Usernames can be a combination of the person's names or subjects e.g adekunle.ciroma, oyewo.physics" persistent-hint></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <v-text-field :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'" :type="showPass ? 'text' : 'password'" v-model="password" label="Password" placeholder="Enter preferred user password" hint="Password can be changed later after the user logs in" persistent-hint @click:append="showPass = !showPass"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12">
                        <v-text-field :color="password == confPassword ? '' : '#abb1'" :append-icon="showConf ? 'mdi-eye' : 'mdi-eye-off'" :type="showConf ? 'text' : 'password'" :rules="rule" v-model="confPassword" :disabled="!password" label="Confirm Password" placeholder="Confirm Password" @click:append="showConf = !showConf"></v-text-field>
                    </v-col>

                    <v-divider></v-divider>

                    <v-col>
                        <v-autocomplete v-model="subjectArray" placeholder="Start typing to see list of subjects" :items="subjectList" chips deletable-chips label="Subjects" multiple hint="Subjects this teacher will be teaching" persistent-hint></v-autocomplete>
                    </v-col>
                </v-row>

            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :disabled="loading" color="green darken-1" text @click="clearDialog()">CLOSE</v-btn>
                <v-btn :loading="loading" :disabled="loading || !lastname || !firstname || !username || !password || subjectArray.length === 0 || password !== confPassword" color="green darken-1" text @click="createTeacher()">SAVE</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
  </div>
</template>

<script>
import SingleTeacher from './SingleTeacher'
export default {
    name: "Teachers",
    components: {
        SingleTeacher
    },
    props: ['allsubjects', 'allteachers','allclasses'],
    data() {
        return {
            subjects: this.allsubjects,
            teachers: this.allteachers,
            yellow:  "#e67d23",
            showPass: false,
            showConf: false,
            loading: false,
            title: 'Mrs',
            lastname: null,
            firstname: null,
            username: null,
            password: null,
            titles: ['Mrs','Mr','Miss'],
            confPassword: null,
            subjectArray: new Array(),
            createDialog: false,
            rule: [
                (val) => {
                    return val == this.password || "Error: Passwords must be equal"
                }
            ]
        }
    },
    methods: {
        updateTeacher(teacher, index) {
            this.teachers.splice(index,1,teacher)
        },
        deleteTeacher(id) {
            this.teachers = this.teachers.filter(teacher => teacher.id !== id)
        },
        createTeacher() {
            this.loading = true
            const subjects = this.prepareSubjectsData();

            this.$http.post(`teachers`, {
                title: this.title,
                lastname: this.lastname,
                firstname: this.firstname,
                username: this.username,
                password: this.password,
                subjects
            })
            .then(res => {
                this.loading = false
                this.clearDialog();
                this.$noty.success(res.data.message)
                this.teachers.push(res.data.data)
                this.teachers.sort((a, b) => (a.full_name > b.full_name) ? 1 : -1);
            })
            .catch(err => {
                this.loading = false
                this.$noty.error(err.response.data.message)
            })
        },
        clearDialog() {
            this.lastname=this.firstname=this.username=this.password=this.confPassword=null;
            this.subjectArray = [];
            this.createDialog=false
        },
        prepareSubjectsData() {
            let subjects = new Array();

            this.subjectArray.forEach(value => {
                let item = this.subjectList.find(item => item.value === value)

                if (item) {
                    let subjectIndex = subjects.findIndex(subject => subject.subject_id === item.subject_id)

                    if (subjectIndex !== -1) {
                        subjects[subjectIndex].classes.push(item.class_id)
                    }

                    else {
                        subjects.push({subject_id: item.subject_id, classes: [item.class_id]})
                    }
                }
            })

            return subjects
        }
    },
    computed: {
        subjectList() {
            let subjectList = new Array()
            let iteration = 1;
            this.subjects.forEach(subject => {
                subject.classes.forEach(single => {
                    subjectList.push({text: `${subject.name} (${single.name})`, value: iteration, subject_id: subject.id, class_id: single.id})
                    iteration++
                })
            })

            return subjectList
        }
    }


}
</script>
