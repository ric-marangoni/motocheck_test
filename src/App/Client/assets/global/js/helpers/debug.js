// Dev only
function debug(message, type = 'info') {
  const types = {
    info: 'color: #535ded; font-weight: bold;',
    error: 'color: #ff5252; font-weight: bold;',
  };

  console.log('%c --- ' + message, types[type]);
}

export default debug;
