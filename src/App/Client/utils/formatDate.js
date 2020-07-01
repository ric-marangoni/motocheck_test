import { format } from 'date-fns';

function formatDate(date) {
  return format(date, 'DD/MM/YYYY - HH:mm');
}

export default formatDate;
