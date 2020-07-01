import VueRouter from 'vue-router';

import Logs from './Logs.vue';

Vue.use(BootstrapVue);
Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  base: __dirname,
  routes: [
    {
      path: '/',
      component: Logs,
    }
  ],
});

export default new Vue({
  router,
}).$mount('#vue-content-reports');
