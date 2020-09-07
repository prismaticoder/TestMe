<template>
    <tr>
        <td>{{number}}</td>
        <td>{{subject.subject_name}}</td>
        <td v-for="single in allclasses" :key="single.id">
            <v-icon small v-if="subject.classes.find(one => one.class == single.class)">
                mdi-check
            </v-icon>
        </td>
        <td>
            <v-btn small text :color="yellow" title="Edit Subject information" @click="editDialog=true">
                <v-icon>
                    mdi-pencil
                </v-icon>
            </v-btn>
        </td>

        <v-dialog v-model="editDialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="headline">Update Subject Details</v-card-title>
                <v-container>
                    <v-text-field v-model="name" label="Subject Name"></v-text-field>

                    <v-select class="mt-3" v-model="classes" :items="items" chips deletable-chips label="Classes taking this subject" multiple></v-select>

                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn :loading="loading" :disabled="(loading || !name || classes.length === 0) || (name == subject.subject_name && isEqual)" color="green darken-1" text @click="updateSubject()">SAVE</v-btn>
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
    name: "SingleSubject",
    props: ['yellow','subject','number','allclasses'],
    data() {
        return {
            name: this.subject.subject_name,
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
        },
    },
    computed: {
        getClassList() {
            let classArray = this.subject.classes.map(single => single.class)

            return classArray.join()
        },
        isEqual() {
            let originalClasses = this.subject.classes.map(single => single.id);
            let modifiedClasses = this.classes;

            var union = [...new Set([...originalClasses, ...modifiedClasses])];

            return union.length == originalClasses.length && union.length == modifiedClasses.length
        }
    }
}
</script>

<style>

</style>
