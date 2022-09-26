/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueNoty from 'vuejs-noty'
import 'vuejs-noty/dist/vuejs-noty.css'
Vue.use(VueNoty, {
    layout: 'bottomLeft'
})

//axios
import axios from 'axios';
Vue.prototype.$http = axios
Vue.prototype.$http.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.prototype.$location = window.location

Vue.prototype.$http.interceptors.response.use(undefined, function (err) {
    let originalRequest = err.config

    if (err.response) {
        if (err.response.status === 419 && !originalRequest._retry) {
            window.location.reload()
        }

        else if (err.response.status === 422 && err.response.data.errors) {
            const firstError = Object.values(err.response.data.errors)[0];
            err.response.data.message = firstError[0];
            throw err
        }

        else {
            throw err
        }
    }

    else if (err.request) {
        Vue.prototype.$noty.error("Oops! There was an error sending this request, please confirm that you are connected to the network and try again.")
    }

    else {
        console.log(err.message)
        Vue.prototype.$noty.error("An error seems to have been encountered sending this request, please refresh the page and try again.")
    }
})

//vuetify
import Vuetify from 'vuetify';
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css';

//support vuex
import Vuex from 'vuex'
Vue.use(Vuex)
import storeData from "./store/index"


const store = new Vuex.Store(
    storeData
 )


//quill editor
import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme
import Vue from 'vue';

Vue.use(VueQuillEditor)


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('questions', require('./components/student/Questions.vue').default);
Vue.component('timer', require('./components/student/Timer.vue').default);

Vue.component('teacher-questions', require('./components/teacher/Questions.vue').default)
Vue.component('dashboard', require('./components/teacher/Dashboard.vue').default)
Vue.component('result-options', require('./components/teacher/ResultOptions.vue').default)
Vue.component('manage-account', require('./components/teacher/ManageAccount.vue').default)

Vue.component('subjects', require('./components/teacher/admin/Subjects.vue').default)
Vue.component('students', require('./components/teacher/admin/Students.vue').default)
Vue.component('teachers', require('./components/teacher/admin/Teachers.vue').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const vuetifyOptions={};

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(vuetifyOptions),
    store
});
