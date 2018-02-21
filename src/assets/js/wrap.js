import monthNames from './months'

const leadZero = (num) => (num < 10 ? '0' : '') + num
const shortMonth = (month) => month.substring(0, 3)

let _date = function(e) {
  let date = new Date((Number(e) + 24*60*60) * 1000)

  let year = date.getUTCFullYear()
  let month = shortMonth(monthNames[date.getUTCMonth()])
  let day = leadZero(date.getUTCDate())
  
  return month + ' ' + day + ', ' + year
}

let _time = function(e) {
  let date = new Date((Number(e) + 24*60*60) * 1000)

  let hours = leadZero(date.getHours())
  let mins = leadZero(date.getMinutes())
  let secs = leadZero(date.getSeconds())

  return hours + ':' + mins + ':' + secs
}

export default {
  date: (e) => _date(e),
  time: (e) => _time(e),
  datetime(e) {

    let date = _date(e);
    let time = _time(e);

    return date + ' ' + time
  },
  HMSToHM(e) {
    return e.lastIndexOf(':') == 5
      ? e.substring(0, e.lastIndexOf(':'))
      : e
  },
  ageGroup(e) {
    if (e) {
      if (e.text) {
        return e.text
      }
      if (e.from && e.to) {
        return e.from + ' to ' + e.to
      }
    }
    return ''
  },
  userType(e) {
    switch (Number(e)) {
      case 2: return 'Admin'
      case 3: return 'Employer'
      case 4: return 'Employee'
    }
  },
  status(e) {
    let condition = null
    if (typeof e === 'boolean') {
      condition = e
    } else {
      condition = e == 1
    }
    return condition ? 'Activated' : 'Deactivated'
  },
  fullname(user, mname) {
    if (typeof user !== 'object' || user === null) {
      return ''
    }
    if (typeof mname !== 'boolean') {
      mname = true
    }
    
    let fullname = user.fname + ' '
    fullname += mname && user.mname ? user.mname + ' ' : ''
    fullname += user.lname

    return fullname
  },
  url(url) {
    const pattern = /^https?:\/\/|^\/\//i
    return pattern.test(url) ? url : 'https://' + url
  },
  urlImg(url) {
    // trim protocol
    url = url.replace(/(^\w+:|^)\/\//, '');
    if (url.indexOf('/') > -1) {
      url = url.substring(0, url.indexOf('/'))
    }
    return 'https://www.google.com/s2/favicons?domain=' + url
  },
  localImg(url) {
    // change this
    return 'http://localhost/jspot/public/uploads/images/' + url
  }
}
