export default {
  model: false,
  inst: {
    ManageUsers: {
      color: 'accent',
      before: 'add',
      after: 'close',
      click: 'add--user',
      tip: 'Add User',
      btns: null
    },
    MyJobOpenings: {
      color: 'accent',
      before: 'add',
      click: 'add--job-opening',
      tip: 'Create job opening',
      btns: null
    },
    Profile: {
      hidden: true,
      color: 'accent',
      before: '',
      click: null,
      tip: null,
      btns: null
    }
  }
}
