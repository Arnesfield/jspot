export default {
  model: false,
  inst: {
    '/manage/users': {
      color: 'accent',
      before: 'add',
      after: 'close',
      click: 'add--user',
      tip: 'Add User',
      btns: null
    }
  }
}
