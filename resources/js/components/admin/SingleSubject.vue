<template>
    <tr>
        <td>{{number}}</td>
        <td>{{subject.name}}</td>
        <td v-for="single in allclasses" :key="single.id">
            <v-icon small v-if="subject.classes.find(one => one.name == single.name)">
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
                    <v-btn :loading="loading" :disabled="(loading || !name || classes.length === 0) || (name == subject.name && isEqual)" color="green darken-1" text @click="updateSubject()">SAVE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </tr>
</template>

<script>
export default {
    name: "SingleSubject",
    props: ['yellow','subject','number','allclasses'],
    data() {
        return {
            name: this.subject.name,
            classes: this.subject.classes.map(single => single.id),
            items: this.allclasses.map((single) => {return {text: single.name, value: single.id}}),
            editDialog: false,
            loading: false,
            disableDialog: false,
            deleteDialog: false,
        }
    },
    methods: {
        backToPrevious() {
            this.name = this.subject.name
            this.alias = this.subject.alias
            this.classes = this.subject.classes.map(single =>  single.id)
            this.editDialog = false
        },
        updateSubject() {
            this.loading = true

            this.$http.put(`subjects/${this.subject.id}`, {
                name: this.name,
                alias: this.alias,
                classes: this.classes
            })
            .then(res => {
                this.loading = false
                this.editDialog = false
                this.$noty.success(res.data.message)
                this.$emit('update-subject', res.data.data, this.number - 1)
            })
            .catch(err => {
                this.loading = false
                this.editDialog = false
                this.$noty.error(err.response.data.message)
            })
        },
    },
    computed: {
        getClassList() {
            let classArray = this.subject.classes.map(single => single.name)

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
