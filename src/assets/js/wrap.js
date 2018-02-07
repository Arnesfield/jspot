const monthNames = [
  'January', 'February', 'March',
  'April', 'May', 'June',
  'July', 'August', 'September',
  'October', 'November', 'December'
]
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
    return e == 2 ? 'Admin' : 'Normal'
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
