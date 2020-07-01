export const socket = {
  protocol: {
    command: {
      REGISTER: 'register',
      MESSAGE: 'message',
      MESSAGE_NUMBER: 'message-number',
      MESSAGE_UNLOCK: 'message-unlock',
      DIRECT_MESSAGE: 'direct-message',
      DASHBOARD: 'dashboard',
      REPORT: 'report',
      TRANSFER: 'transfer',
      ROOM: 'room',
      CHANGE_ROOM: 'change-room',
      UPDATE: 'update',
      TYPING: 'typing',
      FINISH: 'finish',
    },
    origin: {
      CUSTOMER: 'bastion',
      ATENDANT: 'roz',
      QUEUE: 'mercy',
      BOT: 'torbjorn',
    },
  },
  application: {
    command: {
      MESSAGE: 'message',
      REQUEST_FILE: 'request-file',
      REDIRECT: 'redirect',
    },
  },
};
