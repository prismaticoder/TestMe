<template>
    <tr>
        <td>{{number}}</td>
        <td>{{teacher.username}}</td>
        <td>{{getSubjects}}</td>
        <td>
            <v-btn small text :color="yellow" title="Update subjects for this teacher" @click="editDialog=true">
                <v-icon>
                    mdi-pencil
                </v-icon>
            </v-btn>
        </td>
        <!-- <td>
            <v-btn v-if="!student.deleted_at" small text :color="yellow" title="Disable this student's exam access" @click="disableDialog = true">
                <v-icon>
                    mdi-close
                </v-icon>
            </v-btn>
            <v-btn v-else small text :color="yellow" title="Restore examination access for this student" @click="disableDialog = true">
                <v-icon>
                    mdi-check
                </v-icon>
            </v-btn>
        </td>
        <td>
            <v-btn small text :color="yellow" title="Delete this student" @click="deleteDialog = true">
                <v-icon>
                    mdi-delete
                </v-icon>
            </v-btn>
        </td> -->

        <!-- <v-dialog v-model="editDialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="headline">Edit Student Details</v-card-title>
                <v-container>
                    <v-text-field v-model="lastname" label="Surname"></v-text-field>
                    <v-text-field v-model="firstname" label="Firstname"></v-text-field>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || !lastname || !firstname || (lastname === student.lastname && firstname === student.firstname)" color="green darken-1" text @click="updateStudent()">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog> -->

    </tr>
</template>

<script>
export default {
    name: "SingleTeacher",
    props: ['yellow','teacher','number','subjects'],
    data() {
        return {
            editDialog: false,
            loading: false,
            disableDialog: false,
            deleteDialog: false,
            snackbar: false,
            snackbarText: '',
            items: null,
            subjectArray: null
        }
    },
    computed: {
        getSubjects() {
            let subjects = this.teacher.subjects.map((subject) => {
                return `${subject.subject.subject_name} (${subject.classes.map(single => single.class).join()})`
            })

            return subjects.join()
        }
    },
    mounted() {
        let items = new Array()
        let subjectArray = new Array()
        let iteration = 1;
        this.subjects.forEach(subject => {
            subject.classes.forEach(single => {
                items.push({text: `${subject.subject_name} (${single.class})`, value: iteration, subject_id: subject.id, class_id: single.id})

                if (this.teacher.subjects.find(one => one.subject_id == subject.id && one.classes.find(oneClass => oneClass.id == single.id))) {
                    subjectArray.push(iteration)
                }

                iteration++
            })
        })
        this.items = items,
        this.subjectArray = subjectArray

    }
}
</script>

<style>

</style>
