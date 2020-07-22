<template>
    <div class="card">
        <div class="card-body">
        <h5 class="card-title">Question No <span class="questionNo">{{number}}</span> of <span class="questionCount">{{totalCount}}</span></h5>
        <hr>
            <p v-html="question.question"></p>
        </div>
        <ul class="list-group list-group-flush">
            <label v-for="(option,index) in question.options" :key="option.id">
                <li class="list-group-item radios">
                    <input type="radio" v-model="selected" name="options" class="radioBtn" :value="index">
                    <span v-html="option.body"></span>
                </li>
            </label>
        </ul>
        <div class="card-body">
            <button :disabled="number == 1" class="btn btn-secondary card-link" @click="$emit('storeChoice', 'previous', newSelection, number)">Previous Question</button>
            <button v-if="number !== totalCount" @click="$emit('storeChoice', 'next', newSelection, number)"  class="btn btn-primary card-link">Next Question</button>
            <button v-else @click="$emit('storeChoice', 'submit', newSelection, number)"  class="btn btn-primary card-link">Submit</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "SingleQuestion",
    props: ['question', 'number', 'totalCount', 'selectedOption'],
    data() {
        return {
            newSelection: this.selectedOption || null,
        }
    },
    computed: {
        selected: {
            get() {
                return this.selectedOption
            },
            set(newValue) {
                this.newSelection = newValue
                this.$emit('updateSelection', newValue)
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
