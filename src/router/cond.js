import toNumberArray from '@/assets/js/toNumberArray'

export default function(router, http, bus) {

  let beforeEach = function(to, from, next) {
    // set title
    const title = to.meta.title || to.name
    // do loading
    bus.progress.active = true

    // convert to array
    let toAuth = toNumberArray(to.meta.auth)
    let sessAuth = toNumberArray(bus.session.auth)

    const nonauth = [0, 10]
    const auth = [2, 3, 4]

    const SESS_EXISTS = bus.authHas(sessAuth, auth)
    const TO_HAS_SESS = bus.authHas(toAuth, sessAuth, 10)
    const IS_ADMIN = bus.authHas(sessAuth, 2)
    const IS_NORMAL = bus.authHas(sessAuth, [3, 4])

    if (TO_HAS_SESS) {
      document.title = title
      next()
    } else {
      next(false)
      if (SESS_EXISTS) {
        if (IS_ADMIN) {
          router.push('/manage/users')
        } else if (IS_NORMAL) {
          router.push('/dashboard')
        }
      } else {
        router.push('/login')
      }
    }

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
