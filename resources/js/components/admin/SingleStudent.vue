<template>
    <tr>
        <td>{{number}}</td>
        <td>{{student.code}}</td>
        <td>{{student.fullName}}</td>
        <td>
            <v-btn small text :color="yellow" title="Edit Student Details" @click="editDialog=true">
                <v-icon>
                    mdi-pencil
                </v-icon>
            </v-btn>
        </td>
        <td>
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
        </td>

        <v-dialog v-model="editDialog" max-width="500" persistent>
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
        </v-dialog>

        <v-dialog v-model="disableDialog" :persistent="loading" max-width="350">
            <v-card>
                <v-card-title v-if="student.deleted_at" class="headline">Restore Examination Access?</v-card-title>
                <v-card-title v-else class="headline">Disable Examination Access?</v-card-title>
                <v-card-text v-if="student.deleted_at">
                    Please confirm that you want to restore <strong>{{student.fullName}}'s</strong> access to the next school examinations.
                </v-card-text>
                <v-card-text v-else>
                    Please confirm that you want to disable <strong>{{student.fullName}}'s</strong> access to the next school examinations.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="disableDialog = false">Close</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="student.deleted_at ? restoreStudent() : disableStudent()">Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" :persistent="loading" max-width="350">
            <v-card>
                <v-card-title class="headline">Delete this student?</v-card-title>
                <v-card-text>
                    Please confirm that you want to delete <strong>{{student.fullName}}</strong> from the school student database.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="deleteDialog = false">Close</v-btn>
                    <v-btn :loading="loading" :disabled="loading" color="green darken-1" text @click="deleteStudent()">Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar">
            {{ snackbarText }}
            <v-btn color="pink" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>
    </tr>
</template>

<script>
export default {
    name: "SingleStudent",
    props: ['yellow','student','number','classIndex'],
    data() {
        return {
            editDialog: false,
            loading: false,
            disableDialog: false,
            deleteDialog: false,
            firstname: this.student.firstname,
            lastname: this.student.lastname,
            snackbar: false,
            snackbarText: ''
        }
    },
    methods: {
        backToPrevious() {
            this.firstname = this.student.firstname,
            this.lastname = this.student.lastname
            this.editDialog = false
        },
        updateStudent() {
            this.loading = true;

            this.$http.put(`/students/${this.student.id}`, {
                firstname: this.firstname,
                lastname: this.lastname
            })
            .then(res => {
                this.editDialog = false;
                this.loading = false
                this.$emit('updateStudent', res.data.student, this.number - 1)
                this.snackbar = true;
                this.snackbarText = res.data.message
            })
            .catch(err => {
                this.editDialog = false;
                this.loading = false
                console.log(err.response.data)
                alert("Sorry, there was an error updating this student's details, please try again")
            })
        },
        disableStudent() {
            this.loading = true;

            this.$http.get(`/disableStudent/${this.student.id}`)
            .then(res => {
                this.disableDialog = false;
                this.loading = false
                this.$emit('updateStudent', res.data.student, this.number - 1)
                this.snackbar = true;
                this.snackbarText = res.data.message
            })
            .catch(err => {
                this.disableDialog = false;
                this.loading = false
                console.log(err.response.data)
                alert("Sorry, there was an error disabling this student's examination access, please try again")
            })
        },
        restoreStudent() {
            this.loading = true;

            this.$http.get(`/restoreStudent/${this.student.id}`)
            .then(res => {
                this.disableDialog = false;
                this.loading = false
                console.log(res.data)
                this.$emit('updateStudent', res.data.student, this.number - 1)
                this.snackbar = true;
                this.snackbarText = res.data.message
            })
            .catch(err => {
                this.disableDialog = false;
                this.loading = false
                console.log(err.response.data)
                alert("Sorry, there was an error restoring this student's examination access, please try again")
            })
        },
        deleteStudent() {
            this.loading = true;

            this.$http.delete(`/students/${this.student.id}`)
            .then(res => {
                this.deleteDialog = false;
                this.loading = false
                this.$emit('deleteStudent', this.student)
                this.snackbar = true;
                this.snackbarText = res.data.message
            })
            .catch(err => {
                this.deleteDialog = false;
                this.loading = false
                console.log(err.response.data)
                alert("Sorry, there was an error deleting this student from the database, please try again")
            })
        }
    }
}
</script>

<style>

</style>
