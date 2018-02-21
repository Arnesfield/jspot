import monthNames from './months'

const leadZero = (num) => (num < 10 ? '0' : '') + num
const shortMonth = (month) => month.substring(0, 3)

let _date = function(e) {
  let date = new Date(e * 1000)
  
  let year = date.getFullYear()
  let month = shortMonth(monthNames[date.getMonth()])
  let day = leadZero(date.getDay())
  
  return month + ' ' + day + ', ' + year
}

let _time = function(e) {
  let date = new Date(e * 1000)

  let hours = leadZero(date.getHours())
  let mins = leadZero(date.getMinutes())
  let secs = leadZero(date.getSeconds())

  return hours + ':' + mins + ':' + secs
}

export default {
  date: (e) => _date(e),
  time: (e) => _time(e),
  datetime(e) {
    let rawDate = new Date(e * 1000)

    let date = _date(rawDate);
    let time = _time(rawDate);

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
  }
}
