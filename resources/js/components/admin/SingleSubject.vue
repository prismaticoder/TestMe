<template>
    <tr>
        <td>{{number}}</td>
        <td>{{subject.subject_name}}</td>
        <td>{{getClassList}}</td>
        <td>
            <v-btn small text :color="yellow" title="Edit Subject information" @click="editDialog=true">
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

        <v-dialog v-model="editDialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="headline">Update Subject Details</v-card-title>
                <v-container>
                    <v-text-field v-model="name" @keyup="changeSlug" label="Subject Name"></v-text-field>
                    <v-text-field readonly v-model="alias" label="Slug" hint="This is the url rendering of the subject"></v-text-field>

                    <v-select
                        v-model="classes"
                        :items="items"
                        chips
                        item-color=""
                        label="Classes taking this subject"
                        multiple
                    ></v-select>

                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="loading || !name || !alias || classes.length === 0" color="green darken-1" text @click="updateSubject()">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- <v-dialog v-model="deleteDialog" :persistent="loading" max-width="350">
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
        </v-dialog> -->

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
    name: "SingleSubject",
    props: ['yellow','subject','number','allclasses'],
    data() {
        return {
            name: this.subject.subject_name,
            alias: this.subject.alias,
            classes: this.subject.classes.map(single => single.id),
            items: this.allclasses.map((single) => {return {text: single.class, value: single.id}}),
            editDialog: false,
            loading: false,
            disableDialog: false,
            deleteDialog: false,
            snackbar: false,
            snackbarText: ''
        }
    },
    methods: {
        backToPrevious() {
            this.name = this.subject.subject_name
            this.alias = this.subject.alias
            this.classes = this.subject.classes.map(single =>  single.id)
            this.editDialog = false
        },
        changeSlug() {
            this.alias = this.name.toLowerCase().split(' ').join('-')
        },
        updateSubject() {
            this.loading = true

            this.$http.put(`admins/subjects/${this.subject.id}`, {
                name: this.name,
                alias: this.alias,
                classes: this.classes
            })
            .then(res => {
                this.loading = false
                this.editDialog = false
                this.$emit('updateSubject', res.data.subject, this.number - 1)
            })
            .catch(err => {
                this.loading = false
                this.editDialog = false
                console.log(err.response.data)
                alert("Sorry, there was an error updating this subject, please try again later")
            })
        }
    },
    computed: {
        getClassList() {
            let classArray = this.subject.classes.map(single => single.class)

            return classArray.join()
        }
    }
}
</script>

<style>

</style>
