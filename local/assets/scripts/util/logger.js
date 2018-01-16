/**
 * Обертка над console, выводящая сообщения только, когда
 * глобальная переменная DEBUG === true
 */

/*  global DEBUG */


/* eslint-disable no-console */

export default {
  info(...args) {
    if (this.check()) console.info(...args);
  },

  warn(...args) {
    if (this.check()) console.warn(...args);
  },

  error(...args) {
    if (this.check()) console.error(...args);
  },

  check() {
    return DEBUG === true;
  },
};
