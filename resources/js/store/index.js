import axios from 'axios';

export default {
    state: {
      examParams: localStorage.getItem('exam_params') || null,
      choices: localStorage.getItem('choices') || null,
      currentQuestionNumber: null,
      currentSelectedOption: null
    },
    getters: {
      hasStarted: (state) => (studentId, examId) => {
        if (!state.examParams) {
          return false;
        }

        let examParams = JSON.parse(state.examParams);
        
        return examParams.studentId === studentId && examParams.examId === examId;
      },
      choices: state => JSON.parse(state.choices),
      timeExamStarted: (state) => {
        return state.examParams ? JSON.parse(state.examParams).startedAt : null
      },
      timeExamEnds: (state) => {
        return state.examParams ? JSON.parse(state.examParams).endsAt : null
      },
      studentId: (state) => {
        return state.examParams ? JSON.parse(state.examParams).studentId : null
      },
      examId: (state) => {
        return state.examParams ? JSON.parse(state.examParams).examId : null
      },
    },
    mutations: {
      START_EXAM(state, data) {
        state.examParams = data
      },
      UPDATE_SELECTION(state, newValue) {
          state.currentSelectedOption = newValue
      },
      STORE_CHOICE(state, data) {
          const choices = JSON.stringify(data.choices);

          localStorage.setItem('choices', choices)
          state.choices = choices
          state.currentQuestionNumber = data.currentQuestionNumber
          state.currentSelectedOption = data.currentSelectedOption
      },
      STORE_LAST_CHOICE(state) {
        let choices = JSON.parse(state.choices);

        choices = choices.filter(choice => choice.question !== state.currentQuestionNumber)
        choices.push({
            question: state.currentQuestionNumber,
            choice: state.currentSelectedOption
        })

        state.choices = JSON.stringify(choices);
      }
    },
    actions: {
      startExam({commit}, data) {
        return new Promise((resolve, reject) => {
            const { hours, minutes, examId, studentId} = data
            const timeExamStarted = new Date().getTime();
            const timeExamEnds = timeExamStarted + (hours * 60 * 60 * 1000) + (minutes * 60 * 1000)

            const examParams = JSON.stringify({
              startedAt: timeExamStarted,
              endsAt: timeExamEnds,
              studentId,
              examId,
            })

            localStorage.setItem('exam_params', examParams);

            // remove anything formerly in local storage
            const keysToRemove = ['choices', 'tabClosed', 'timeLeft'];
            keysToRemove.forEach(key => localStorage.removeItem(key));

            commit('START_EXAM', examParams)

            resolve()
        })
      },
      endExam(context) {
        return new Promise((resolve, reject) => {
            //first store the last question in the choices
            if (context.state.currentQuestionNumber) {
                context.commit('STORE_LAST_CHOICE');
            }

            axios.post('submissions', {
                choices: context.getters.choices
            })
            .then(() => {
                const keysToRemove = ['exam_params','choices','tabClosed','timeLeft'];
                keysToRemove.forEach(key => localStorage.removeItem(key));

                window.formSubmitting = true;

                resolve()
            })
            .catch(err => {
                reject(err)
            })
        })
      },
    },
}
