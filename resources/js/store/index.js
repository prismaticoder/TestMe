import axios from 'axios';

export default {
    state: {
      examStarted: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).start || false : false,
      examEnded: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).end || false : false,
      endTime: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).time || null : null,
      subjectId: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).subject_id || null : null,
      classId: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).class_id || null : null,
      choices: localStorage.getItem('choices') ? JSON.parse(localStorage.getItem('choices')) : [],
      currentQuestionNumber: null,
      currentSelectedOption: null
    },
    getters: {
      hasStarted: state => !!state.examStarted,
      hasEnded: state => !!state.examEnded
    },
    mutations: {
      START_EXAM(state, data) {
        state.examStarted = true;
        state.endTime = data.time
        state.subjectId = data.subjectId
        state.classId = data.classId
      },
      END_EXAM(state) {
        state.examEnded = true;
      },
      UPDATE_SELECTION(state, newValue) {
          state.currentSelectedOption = newValue
      },
      STORE_CHOICE(state, data) {
          localStorage.setItem('choices', JSON.stringify(data.choices))
          state.choices = data.choices
          state.currentQuestionNumber = data.currentQuestionNumber
          state.currentSelectedOption = data.currentSelectedOption
      },
      STORE_LAST_CHOICE(state) {
        state.choices = state.choices.filter(choice => choice.question !== state.currentQuestionNumber)
        state.choices.push({question: state.currentQuestionNumber, choice: state.currentSelectedOption})
      }
    },
    actions: {
      startExam({commit}, data) {
        return new Promise((resolve, reject) => {
            let { hours, minutes, subjectId, classId } = data
            let timeExamEnds = new Date().getTime() + (hours * 60 * 60 * 1000) + (minutes * 60 * 1000)

            localStorage.setItem('exam_params', JSON.stringify({start: true, end: false, time: timeExamEnds, subject_id: subjectId, class_id: classId}))
            commit('START_EXAM', {time: timeExamEnds, subjectId, classId})
            resolve()
        })
      },
      endExam(context) {
        return new Promise((resolve, reject) => {
        //   commit('END_EXAM')
        //first store the last question in the choices
            context.commit('STORE_LAST_CHOICE');

            axios.post('submitExam', {choices: JSON.stringify(context.state.choices), subject_id: context.state.subjectId, class_id: context.state.classId})
            .then(() => {
                localStorage.removeItem('exam_params')
                localStorage.removeItem('choices')
                window.formSubmitting = true;
                resolve()
            })
            .catch(err => {
                reject(err)
            })
        //   window.location.href = '/success'
        })
      },
      storeChoice({commit}, data) {
        return new Promise((resolve, reject) => {
          commit('STORE_CHOICE')
          localStorage.removeItem('exam_params')
          localStorage.removeItem('choices')
          window.location.href = '/success'
          resolve()
        })
      },
    },
}
