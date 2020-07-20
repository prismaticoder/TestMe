<template>
    <div class="card">
        <div class="card-body">
        <h5 class="card-title">Question No <span class="questionNo">{{number}}</span> of <span class="questionCount">{{totalCount}}</span></h5>
            <p v-html="question.question"></p>
        </div>
        <ul class="list-group list-group-flush">
            <label v-for="option in question.options" :key="option.id">
                <li class="list-group-item radios">
                    <input type="radio" id="radio" data-option-id="" name="options" class="radioBtn" value="0">
                    <span class="options"> {{option.body}}</span>
                </li>
            </label>
        </ul>
        <div class="card-body">
            <button data-button-type="next" class="btn btn-secondary card-link">Previous Question</button>
            <button data-button-type="start" data-question="1"  class="btn btn-primary card-link">Next Question</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "SingleQuestion",
    props: ['question', 'number', 'totalCount']
}
</script>

<style>

</style>
