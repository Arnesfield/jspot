import randomNumber from './randomNumber'

export default function(jobs, users) {
  // clear combined first
  let combined = JSON.parse(JSON.stringify(jobs))
  // loop to all users
  users.forEach((user, i) => {
    // create random number between length of combined
    let rand = randomNumber(0, combined.length)
    // insert that user on that rand baby
    combined.splice(rand, 0, users[i])
  })
  return combined
}