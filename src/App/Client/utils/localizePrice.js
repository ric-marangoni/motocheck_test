function localizePrice(input) {
  let value = input;
  if (typeof input === 'string') {
    value = value.indexOf('.') !== -1 && value.indexOf(',') !== -1 ? value.replace('.', '') : value;
    value = value.indexOf(',') !== -1 ? value.replace(',', '.') : value;
    value = parseFloat(value);
  }

  return value.toLocaleString('pt-br', {
    style: 'currency',
    currency: 'BRL',
  });
}

export default localizePrice;
