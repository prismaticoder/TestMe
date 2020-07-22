export default {
    state: {
      examStarted: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).start || false : false,
      examEnded: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).end || false : false,
      endTime: localStorage.getItem('exam_params') ? JSON.parse(localStorage.getItem('exam_params')).time || null : null
    },
    getters: {
      hasStarted: state => !!state.examStarted,
      hasEnded: state => !!state.examEnded
    },
    mutations: {
      START_EXAM(state, time) {
        state.examStarted = true;
        state.endTime = time
      },
      END_EXAM(state) {
        state.examEnded = true;
      },
    },
    actions: {
      startExam({commit}, data) {
        return new Promise((resolve, reject) => {
            let { hours, minutes } = data
            let timeExamEnds = new Date().getTime() + (hours * 60 * 60 * 1000) + (minutes * 60 * 1000)

            localStorage.setItem('exam_params', JSON.stringify({start: true, end: false, time: timeExamEnds}))
            commit('START_EXAM', timeExamEnds)
            resolve()
        })
      },
      endExam({commit}) {
        return new Promise((resolve, reject) => {
          commit('END_EXAM')
          localStorage.removeItem('exam_params')
          localStorage.removeItem('choices')
          window.location.href = '/success'
          resolve()
        })
      },
    },
}
