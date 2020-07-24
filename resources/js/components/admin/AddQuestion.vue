<template>
  <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="mt-3 mb-3 ml-3">
                Questions
                <v-btn fab absolute right small class="bg-primary text-white" title="Add New Question" @click="currentQuestion = null">
                    <v-icon>
                        mdi-plus
                    </v-icon>
                </v-btn>
            </h4>
            <div class="list-group">
                <span v-for="(question, index) in questionArray" :key="question.id" class="list-group-item list-group-item-action questionBtn" v-bind:class="{'active': currentQuestion ? currentQuestion.id == questions[index].id : false}"  @click.prevent="currentQuestion = questionArray[index]">
                    Question {{index + 1}}
                    <v-btn text small dark :color="yellow" @click="deleteQuestion(question.id)" class="float-right" title="Remove Question">
                        <v-icon>
                            mdi-close
                        </v-icon>
                    </v-btn>
                </span>
            </div>
        </div>

        <div class="col-md-10 bg-white">
            <div class="container">
                <h3 class="text-center">Question</h3>
                <quill-editor class="questions container" :options="editorOption" v-model="question" />
                <hr>
            </div>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h4>Option A</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionA" />
                    <hr>
                </div>
                <div class="col-md-8 mx-auto">
                    <h4>Option B</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionB" />
                    <hr>
                </div>
                <hr>
                <div class="col-md-8 mx-auto">
                    <h4>Option C</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionC" />
                    <hr>
                </div>
                <div class="col-md-8 mx-auto">
                    <h4>Option D</h4>
                    <quill-editor :disabled="editorDisabled" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" :options="editorOption" class="option" v-model="optionD" />
                </div>
            </div>
        </div>
  </div>

</template>

<script>
export default {
    name: "AddQuestion",
    props: ['questions'],
    data() {
        return {
            questionArray: this.questions,
            currentQuestion: null,
            options: ['A','B','C','D'],
            question: null,
            optionA: null,
            optionB: null,
            optionC: null,
            optionD: null,
            isCorrect: null,
            editorDisabled: true,
            editorOption: {
                modules: {
                    toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['clean'],
                    ['link', 'image', 'video']
                    ],
                 },
                placeholder: "Enter value here",
                theme: 'snow',
                readonly: true
            },
            yellow: "#e67d23"
        }
    },
    methods: {
        onEditorBlur(quill) {
            this.editorDisabled = true
        },
        onEditorFocus(quill) {
            this.editorDisabled = false
        }
    },
    watch: {
        'currentQuestion'(newValue) {
            if (newValue) {
                this.question = newValue.question;
                this.optionA = newValue.options[0].body
                this.optionB = newValue.options[1].body
                this.optionC = newValue.options[2].body
                this.optionD = newValue.options[3].body
                this.isCorrect = newValue.options.findIndex(option => option.isCorrect)
            }

            else {
                this.question = this.optionA = this.optionB = this.optionC = this.optionD = this.isCorrect = null
            }
        }
    }
}
</script>

<style scoped>

.questions {
    height: 250px;
}

.option {
    /* height: 300px; */
    /* width: 700px; */
}

.active {
    background-color: #343a40;
    color: #e67d23;
    border: solid #343a40 1px;
}

.yellow {
    background-color: #e67d23
}
</style>
