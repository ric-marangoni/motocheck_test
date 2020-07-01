function pluralize(value, stringOne, stringMore) {
  if (value === 0 || value == '') {
    return null;
  }
  return value > 1 ? value + ' ' + stringMore : '1 ' + stringOne;
}
