import { firstWord } from './firstWord';

export default function interpolate(string, values) {
  try {
    let parsed = string;

    const replacers = Object.entries(values);

    replacers.map(replacer => {
      const key = replacer[0];
      const value = replacer[1] !== null ? firstWord(replacer[1]) : '';

      parsed = parsed.replace(`{${key}}`, value || '');
    });

    return parsed;
  } catch (error) {
    console.error(error);
    return string;
  }
}
