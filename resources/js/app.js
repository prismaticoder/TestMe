/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//axios
import axios from 'axios';
Vue.prototype.$http = axios
Vue.prototype.$http.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.prototype.$location = window.location

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

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('Questions', require('./components/Questions.vue').default);
Vue.component('Timer', require('./components/Timer.vue').default);
Vue.component('add-question', require('./components/admin/AddQuestion.vue').default)
Vue.component('subjects', require('./components/admin/Subjects.vue').default)
Vue.component('all-subjects', require('./components/admin/AllSubjects.vue').default)
Vue.component('all-results', require('./components/admin/AllResults.vue').default)
Vue.component('class-students', require('./components/admin/ClassStudents.vue').default)
Vue.component('admin-section', require('./components/admin/AdminSection.vue').default)
Vue.component('teachers', require('./components/admin/Teachers.vue').default)

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
