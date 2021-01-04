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
                    <v-divider></v-divider>
                    <v-text-field persistent-hint
                        v-model="code"
                        label="Subject Code"
                        max="3"
                        hint="A unique 3-letter code to identify the subject e.g ICT, ENG"
                    >
                    </v-text-field>
                    <v-divider></v-divider>
                    <v-select chips deletable-chips multiple
                        class="mt-3"
                        v-model="classes"
                        :items="items"
                        label="Classes taking this subject"
                    >
                    </v-select>

                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="green darken-1" text @click="backToPrevious">CLOSE</v-btn>
                    <v-btn text
                        :loading="loading"
                        :disabled="loading || emptyInputExists || valuesHaveNotChanged"
                        color="green darken-1"
                        @click="updateSubject()"
                    >
                        SAVE
                    </v-btn>
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
            code: this.subject.code,
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
            this.code = this.subject.code
            this.classes = this.subject.classes.map(single =>  single.id)
            this.editDialog = false
        },
        updateSubject() {
            this.loading = true

            this.$http.put(`subjects/${this.subject.id}`, {
                subject_name: this.name,
                subject_code: this.code,
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
                this.$noty.error(err.response.data.message)
            })
        },
    },
    computed: {
        getClassList() {
            let classArray = this.subject.classes.map(single => single.name)

            return classArray.join()
        },
        emptyInputExists() {
            return !this.name || !this.code || this.classes.length === 0
        },
        valuesHaveNotChanged() {
            return this.name == this.subject.name && this.code == this.subject.code && this.classesHaveNotChanged
        },
        classesHaveNotChanged() {
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
