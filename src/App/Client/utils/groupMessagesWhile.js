import { dropWhile, takeWhile } from 'ramda';

function groupMessagesWhile(items) {
  const grouped = [];

  function group(arr, cond) {
    const isCond = x => {
      return x.payload.origin === cond;
    };
    const newArr = dropWhile(isCond, arr);
    grouped.push(takeWhile(isCond, arr));

    if (newArr[0]) {
      group(newArr, newArr[0].payload.origin);
    }
  }

  group(items, 'bastion');

  return grouped;
}

export default groupMessagesWhile;
