<template>
    <v-card shaped outlined class="p-3">
        <v-card-title>
            Question No {{number}} of {{totalCount}}
        </v-card-title>
        <hr>

        <v-card-text>
            <p v-html="question.body"></p>

            <v-list two-line :key="question.id">
                <v-list-item-group v-model="selected">
                    <template v-for="(option,index) in question.options">
                        <v-list-item :key="index">
                            <template v-slot:default="{ active }">
                                <v-list-item-action>
                                <v-checkbox
                                    :input-value="active"
                                    color="primary"
                                ></v-checkbox>
                                </v-list-item-action>

                                <v-list-item-content v-html="option.body">
                                </v-list-item-content>
                            </template>
                        </v-list-item>
                    </template>
                </v-list-item-group>
            </v-list>

        </v-card-text>

        <v-card-actions>
            <v-btn tile color="danger" :disabled="number == 1" @click="$emit('store-choice', 'previous', newSelection, number)">
                <v-icon small>
                    mdi-arrow-left
                </v-icon>
                Previous Question
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn tile color="primary" outlined v-if="number !== totalCount" @click="$emit('store-choice', 'next', newSelection, number)">
                Next Question
                <v-icon small>
                    mdi-arrow-right
                </v-icon>
            </v-btn>
            <v-btn tile v-else @click="dialog = true" color="success">
                Submit
                <v-icon small>
                    mdi-check
                </v-icon>
            </v-btn>
        </v-card-actions>

        <v-dialog v-model="dialog" :persistent="btnLoading" max-width="350">
            <v-card>
                <v-card-title class="headline">Submit Examination?</v-card-title>
                <v-card-text>
                Are you sure you are ready to submit your examination?
                Doing so would mean you will not be able to return to check your answers.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="btnLoading" color="green darken-1" text @click="dialog = false">No</v-btn>
                    <v-btn :loading="btnLoading" :disabled="btnLoading" color="green darken-1" text @click="submitExam">Yes, Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
export default {
    name: "SingleQuestion",
    props: ['question', 'number', 'totalCount', 'selectedOption'],
    data() {
        return {
            newSelection: this.selectedOption !== undefined ? this.selectedOption : null,
            dialog: false,
            yellow:  "#e67d23",
            btnLoading: false
        }
    },
    methods: {
        submitExam() {
            this.btnLoading = true;
            this.$store.dispatch('endExam')
            .then(() => {
                this.btnLoading = false;
                this.dialog = false
                window.location.href = '/success'
            })
            .catch(err => {
                this.btnLoading = false;
                this.$noty.error(`Error submitting examination: ${err.response.data.message}`);
            })
        }
    },
    computed: {
        selected: {
            get() {
                return this.selectedOption
            },
            set(newValue) {
                this.newSelection = newValue
                this.$store.commit('UPDATE_SELECTION', newValue)
            }

        }
    },
    watch: {
        'question.id'() {
            this.newSelection = this.selectedOption
        }
    }
}
</script>

<style>

</style>
