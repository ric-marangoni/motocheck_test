/**
 * Regular expression to validate emails
 */
const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

/**
 *
 * @param {string} email
 */
export function isEmailAddress(email) {
  return re.test(String(email).toLowerCase());
}
