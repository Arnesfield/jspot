import urlRegex from 'url-regex'

const msgs = {
  required: 'This field is required.',
  email: 'Invalid email.',
  chars: 'Invalid value.',
  chars8: 'Requires 8 or more characters.',
  match: 'Value does not match.',
  url: 'Invalid URL.',
  nonExisting: 'This already exists.',
  duplicateEmail: 'This email already exists.'
}

let rules = {
  required: (msg) => (e) => !!e || msg,
  email: (msg) => (e) => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    return pattern.test(e) || msg
  },
  chars: (msg, n) => (e) => (e ? e.length >= n : false) || msg,
  chars8: (msg) => (e) => (e ? e.length >= 8 : false) || msg,
  match: (msg, match) => (e) => e == match || msg,
  url: (msg) => (e) => {
    // optional
    if (!e) { return true }
    return urlRegex({ exact: true, strict: false }).test(e) || msg
  },
  nonExisting: (msg, arr) => (e) => {
    if (!arr) { return true }
    return arr.indexOf(e) == -1 || msg
  },
  duplicateEmail: (msg, val) => (e) => {
    let cond = typeof val === 'boolean' ? !val : false
    return cond ? true : msg
  }
}

export default function(rule, msg, param) {
  msg = msg ? msg : msgs[rule]
  return rules[rule](msg, param)
}
