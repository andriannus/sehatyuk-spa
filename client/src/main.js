// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Meta from 'vue-meta'
import Buefy from 'buefy'
import BackToTop from 'vue-backtotop'
import App from './App'
import router from './router'

import 'vue2-animate/dist/vue2-animate.min.css'
import 'buefy/lib/buefy.css'

Vue.use(VueAxios, axios)
Vue.use(Buefy)
Vue.use(Meta)
Vue.use(BackToTop)

axios.defaults.baseURL = 'http://localhost/sehatyuk-spa/api'
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
