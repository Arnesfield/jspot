import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/components/Home'
import Login from '@/components/Login'
import SignUp from '@/components/SignUp'
import Dashboard from '@/components/Dashboard'
import NotFound from '@/components/NotFound'
import Profile from '@/components/Profile'
import Boosts from '@/components/Boosts'

// employer
import MyJobOpenings from '@/components/employer/MyJobOpenings'
import MyApplicants from '@/components/employer/MyApplicants'

// employer
import MyJobApplications from '@/components/employee/MyJobApplications'

// admin
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
      path: '/login/:verified?',
      name: 'Login',
      component: Login,
      props: true,
      meta: {
        auth: 0
      }
    },
    {
      path: '/signup',
      name: 'Sign Up',
      component: SignUp,
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
        auth: [10, 3, 4],
        icon: 'dashboard'
      }
    },
    {
      path: '/profile/:id?',
      name: 'Profile',
      component: Profile,
      props: true,
      meta: {
        auth: [10, 3, 4],
        icon: 'account_circle'
      }
    },
    {
      path: '/boosts',
      name: 'Boosts',
      component: Boosts,
      props: true,
      meta: {
        auth: [3, 4],
        icon: 'trending_up'
      }
    },
    
    // employer
    {
      path: '/my/jobs',
      name: 'MyJobOpenings',
      component: MyJobOpenings,
      meta: {
        auth: 3,
        icon: 'work',
        title: 'My job openings'
      }
    },
    {
      path: '/my/applicants',
      name: 'MyApplicants',
      component: MyApplicants,
      meta: {
        auth: 3,
        icon: 'people',
        title: 'My job applicants'
      }
    },

    // employee
    {
      path: '/my/applications',
      name: 'MyJobApplications',
      component: MyJobApplications,
      meta: {
        auth: 4,
        icon: 'work',
        title: 'My job applications'
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
        icon: 'people'
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
