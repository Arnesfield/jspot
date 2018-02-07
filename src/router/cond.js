export default function(router, http, bus) {

  let beforeEach = function(to, from, next) {
    // set title
    const title = to.meta.title || to.name
    // do loading
    bus.progress.active = true

    // if router auth not the same with session auth
    // console.log(to.meta.auth + ' ' + bus.session.auth)
    if (to.meta.auth < bus.session.auth || bus.session.auth == 0) {
      bus.progress.active = false
      
      // go to login if user is not set
      if (bus.session.auth == 0) {
        if (to.path == '/login') {
          document.title = title
          next()
          return
        }
        next(false)
        router.push('/login')
      }
      // if session auth but route not auth
      else if (bus.session.auth >= 2) {
        if (to.path == '/dashboard') {
          document.title = title
          next()
          return
        }
        next(false)
        router.push('/dashboard')
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
    bus.checkSession(to, http)
    beforeEach(to, from, next)
  })
}
