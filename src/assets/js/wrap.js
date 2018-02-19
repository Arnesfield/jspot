import monthNames from './months'

const leadZero = (num) => (num < 10 ? '0' : '') + num
const shortMonth = (month) => month.substring(0, 3)

export default {
  date(e) {
    let date = new Date(e * 1000)

    let year = date.getFullYear()
    let month = shortMonth(monthNames[date.getMonth()])
    let day = leadZero(date.getDay())
    let hours = leadZero(date.getHours())
    let mins = leadZero(date.getMinutes())
    let secs = leadZero(date.getSeconds())

    return month + ' ' + day + ', ' + year + ' ' + hours + ':' + mins + ':' + secs
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
  }
}
