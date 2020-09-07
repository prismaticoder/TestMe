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
            <th>USERNAME</th>
            <th>SUBJECTS TAUGHT</th>
            <th colspan="2">OPTIONS</th>
        </thead>
        <tbody>
            <SingleTeacher v-for="(teacher,index) in teachers" :key="teacher.id" :teacher="teacher" :subjects="subjects" :number="index+1" :yellow="yellow" :items="subjectList" @updateTeacher="updateTeacher" @deleteTeacher="deleteTeacher"/>
        </tbody>
    </table>

    <v-dialog v-model="createDialog" max-width="600" :persistent="loading">
        <v-card>
            <v-card-title class="headline">Add New Teacher</v-card-title>
            <v-container>
                <v-text-field class="mt-3" v-model="username" label="Username" placeholder="Type username here..." hint="Usernames can be a combination of the person's names or subjects e.g adekunle.ciroma, oyewo.physics" persistent-hint></v-text-field>
                <v-text-field class="mt-3" :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'" :type="showPass ? 'text' : 'password'" v-model="password" label="Password" placeholder="Enter preferred user password" hint="Password can be changed later after the user logs in" persistent-hint @click:append="showPass = !showPass"></v-text-field>
                <v-text-field class="mt-3" :color="password == confPassword ? '' : '#abb1'" :append-icon="showConf ? 'mdi-eye' : 'mdi-eye-off'" :type="showConf ? 'text' : 'password'" :rules="rule" v-model="confPassword" :disabled="!password" label="Confirm Password" placeholder="Confirm Password" @click:append="showConf = !showConf"></v-text-field>
                <v-autocomplete class="mt-3" v-model="subjectArray" placeholder="Start typing to see list of subjects" :items="subjectList" chips deletable-chips label="Subjects" multiple hint="Subjects this teacher will be teaching" persistent-hint></v-autocomplete>

            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :disabled="loading" color="green darken-1" text @click="username=password=confPassword=null; subjectArray = []; createDialog=false">CLOSE</v-btn>
                <v-btn :loading="loading" :disabled="loading || !username || !password || subjectArray.length === 0 || password !== confPassword" color="green darken-1" text @click="createTeacher()">SAVE</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

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
            username: null,
            password: null,
            confPassword: null,
            subjectArray: new Array(),
            createDialog: false,
            snackbar: false,
            snackbarText: null,
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
            this.snackbarText = "Subjects updated successfully"
            this.snackbar = true
        },
        deleteTeacher(id) {
            this.teachers = this.teachers.filter(teacher => teacher.id !== id)

            this.snackbarText = "Access successfully revoked"
            this.snackbar = true
        },
        createTeacher() {
            this.loading = true

            //create the proper subject array
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

            this.$http.post(`admins/teachers`, {
                username: this.username,
                password: this.password,
                subjects
            })
            .then(res => {
                this.loading = false
                this.createDialog = false
                this.teachers.push(res.data.teacher)
                this.teachers.sort((a, b) => (a.username > b.username) ? 1 : -1);
                this.snackbarText = res.data.message
                this.snackbar = true
            })
            .catch(err => {
                this.loading = false
                this.createDialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error creating this teacher, please try again later")
            })
        },
    },
    computed: {
        subjectList() {
            let subjectList = new Array()
            let iteration = 1;
            this.subjects.forEach(subject => {
                subject.classes.forEach(single => {
                    subjectList.push({text: `${subject.subject_name} (${single.class})`, value: iteration, subject_id: subject.id, class_id: single.id})
                    iteration++
                })
            })

            return subjectList
        }
    }


}
</script>

<style>

</style>
