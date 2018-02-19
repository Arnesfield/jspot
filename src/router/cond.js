export default function(router, http, bus) {

  let beforeEach = function(to, from, next) {
    // set title
    const title = to.meta.title || to.name
    // do loading
    bus.progress.active = true

    // if router auth not the same with session auth
    // if component auth is 10
    // console.log(to.meta.auth + ' ' + bus.session.auth)
    if (!bus.authCheck(to.meta.auth) || bus.session.auth == 0 || bus.authHas(to.meta.auth, 10)) {
      bus.progress.active = false
      
      // go to login if user is not set
      if (bus.session.auth == 0 || bus.authHas(to.meta.auth, 10)) {
        // if component is also 0 auth
        if (bus.authHas(to.meta.auth, 0) || bus.authHas(to.meta.auth, 10)) {
          document.title = title
          next()
          return
        }
        next(false)
        router.push('/login')
      }
      // if session auth but route not auth
      else if (bus.session.auth >= 2) {
        // if component is also >= 2 auth
        // component should be greater than or equal to session auth
        if (bus.authCheck(to.meta.auth)) {
          document.title = title
          next()
          return
        }
        next(false)
        // go to manage users if auth is admin
        if (bus.session.auth != 2) {
          router.push('/dashboard')
        } else {
          router.push('/manage/users')
        }
      }
      return
    }
    
    document.title = title
    next()
    // stop loading
    bus.progress.active = false
  }

  bus.$on('change--session.auth', (route) => {
    beforeEach(route, route, (e) => {})
  })

  router.beforeEach((to, from, next) => {
    // check for session
    bus.sessionCheck(to, http)
    beforeEach(to, from, next)
  })
}
