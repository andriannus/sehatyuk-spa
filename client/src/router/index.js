import Vue from 'vue'
import Router from 'vue-router'
import Header from '@/components/Header'
import Home from '@/components/Home'
import Puskesmas from '@/components/Puskesmas'
import Rsk from '@/components/Rsk'
import Rsu from '@/components/Rsu'

Vue.use(Router)

export default new Router({
	mode: 'history',
  linkExactActiveClass: 'is-active',
  routes: [
    {
      path: '/',
      component: Header,
      children: [
      	{
      		path: '',
      		name: 'Home',
      		component: Home
      	},
      	{
      		path: 'puskesmas',
      		name: 'Puskesmas',
      		component: Puskesmas
      	},
        {
          path: 'rsk',
          name: 'Rsk',
          component: Rsk
        },
        {
          path: 'rsu',
          name: 'Rsu',
          component: Rsu
        }
      ]
    }
  ]
})
