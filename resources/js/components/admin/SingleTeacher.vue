<template>
    <tr>
        <td>{{number}}</td>
        <td>{{teacher.full_name}}</td>
        <td>{{teacher.username}}</td>
        <td>{{getSubjects}}</td>
        <td>
            <v-btn small text :color="yellow" title="Update subjects for this teacher" @click="editDialog=true">
                <v-icon>
                    mdi-pencil
                </v-icon>
            </v-btn>
        </td>
        <td>
            <v-btn small text :color="yellow" title="Revoke User Access" @click="deleteDialog=true">
                <v-icon>
                    mdi-close
                </v-icon>
            </v-btn>
        </td>

        <v-dialog v-model="editDialog" max-width="600" persistent>
            <v-card>
                <v-card-title class="headline">Add/Remove Subjects from User &lt;{{teacher.username}}&gt; </v-card-title>
                <v-container>
                    <v-autocomplete placeholder="Start typing to search subject" v-model="subjectArray" :items="items" chips deletable-chips label="Subjects" multiple></v-autocomplete>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious()">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || subjectArray.length == 0" color="green darken-1" text @click="updateTeacher()">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" max-width="350" :persistent="loading">
            <v-card>
                <v-card-title class="headline">Revoke User Access?</v-card-title>
                <v-card-text>
                Please confirm that you want to permanently revoke <strong>{{teacher.username}}</strong>'s access to the administrative section of this platform.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="deleteDialog = false">No</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="deleteTeacher()">Yes, Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </tr>
</template>

<script>
export default {
    name: "SingleTeacher",
    props: ['yellow','teacher','number','subjects','classes','items'],
    data() {
        return {
            editDialog: false,
            loading: false,
            deleteDialog: false,
            subjectArray: [],
        }
    },
    methods: {
        backToPrevious() {
            this.createSubjectArray()
            this.editDialog = false
        },
        createSubjectArray() {
            let subjectArray = new Array()

            this.items.forEach(item => {
                if (this.teacher.subjects.find(subject => subject.subject_id === item.subject_id && subject.classes.find(single => single.id == item.class_id))) subjectArray.push(item.value)
            })

            this.subjectArray = subjectArray
        },
        updateTeacher() {
            this.loading = true

            //create the proper subject array
            let subjects = new Array();

            this.subjectArray.forEach(value => {
                let item = this.items.find(item => item.value === value)

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

            this.$http.put(`teachers/${this.teacher.id}`, {
                subjects
            })
            .then(res => {
                this.loading = false
                this.editDialog = false
                this.$emit('updateTeacher', res.data.teacher, this.number - 1)
            })
            .catch(err => {
                this.loading = false
                this.editDialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error updating this subject, please try again later")
            })
        },
        deleteTeacher() {
            this.loading = true

            this.$http.delete(`teachers/${this.teacher.id}`)
            .then(res => {
                this.loading = false
                this.deleteDialog = false
                this.$emit('deleteTeacher', this.teacher.id)
            })
            .catch(err => {
                this.loading = false
                this.deleteDialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error revoking this user's access, please try again later")
            })
        }
    },
    computed: {
        getSubjects() {
            let subjects = this.teacher.subjects.map((subject) => {
                let subjectClasses = subject.classes.map(single => single.class)
                return `${subject.subject.name} (${subjectClasses.length == this.classes.length ? 'All Classes' : subjectClasses.join()})`
            })

            return subjects.join()
        }
    },
    mounted() {
        this.createSubjectArray()
    }
}
</script>

<style>

</style>
