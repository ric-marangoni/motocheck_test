import localforage from 'localforage';

const store = localforage.createInstance({
  driver: localforage.LOCALSTORAGE,
  name: 'widowmaker',
  version: 1.0,
  storeName: 'keyvaluepairs',
});

export default store;
