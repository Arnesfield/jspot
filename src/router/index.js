import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/components/Home'
import Login from '@/components/Login'
import Dashboard from '@/components/Dashboard'
import NotFound from '@/components/NotFound'

import ManageUsers from '@/components/ManageUsers'

Vue.use(Router)

export default new Router({
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        selector: to.hash
        // , offset: { x: 0, y: 10 }
      }
    }
    return savedPosition ? savedPosition : { x: 0, y: 0 }
  },
  
  routes: [
    // auth 0
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        auth: 0
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        auth: 0
      }
    },

    // auth+

    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      meta: {
        auth: [3, 4],
        icon: 'dashboard'
      }
    },
    
    // manage

    {
      path: '/manage/users',
      name: 'ManageUsers',
      component: ManageUsers,
      meta: {
        auth: 2,
        title: 'Manage Users',
        icon: 'account_circle'
      }
    },
    
    // end of manage

    // last resort
    {
      path: '*',
      name: 'NotFound',
      component: NotFound,
      meta: {
        title: 'Error 404',
        auth: 10
      }
    }
  ]
})
