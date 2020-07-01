<template>
  <div style="z-index: 99998">
    <b-toast id="toast-modal" class="d-flex align-items-center" :no-auto-hide="data.visible" :auto-hide-delay="data.delay" :variant="data.type" :append-toast="true" solid>
      <div slot="toast-title">
        <i class="mi fs-20" v-html="getIcon"></i>
        <strong class="ml-1 flex-grow-1" v-html="data.title"></strong>
        <small class="text-muted mr-2" v-if="data.subtitle" v-html="data.subtitle"></small>
      </div>
      <div v-html="data.message"></div>
    </b-toast>
  </div>
</template>

<script>
import { map } from 'ramda';
import EventBus from '../utils/EventBus';

Vue.use(BootstrapVue.ToastPlugin);

export default {
  name: 'FlashMessage',
  data() {
    return {
      data: {},
    };
  },
  mounted() {
    EventBus.$on('flashMessage', this.show);
  },
  methods: {
    show(value) {
      if (value.error) {
        let errorMessage = '';
        const { errors } = value.error.data;

        if (typeof errors === 'object') {
          map(error => {
            errorMessage += error + '<br>';
          }, errors);
        } else {
          errorMessage = errors;
        }

        value.message = errorMessage;
        value.type = 'danger';
      }

      let delay = 5000; //seg
      if (value.delay) {
        delay = value.delay;
      }

      let visible = false;
      if (value.visible) {
        visible = true;
      }

      const infoOption = {
        title: 'Info',
        subtitle: '',
        message: '',
        delay,
        visible,
      };

      const dangerOption = {
        title: 'Erro!',
        subtitle: '',
        message: '',
        delay,
        visible,
      };

      const options = {
        default: infoOption,
        primary: infoOption,
        secondary: infoOption,
        error: dangerOption,
        danger: dangerOption,
        warning: {
          title: 'Ops!',
          subtitle: '',
          message: '',
          delay,
          visible,
        },
        success: {
          title: 'Sucesso!',
          subtitle: '',
          message: '',
          delay,
          visible,
        },
        info: infoOption,
      };

      const data = options[value.type];
      this.data = Object.assign({}, data, value);
      this.getType();

      this.$bvToast.show('toast-modal');
    },
    getType() {
      const types = {
        primary: 'primary',
        default: null,
        info: 'info',
        secondary: 'secondary',
        question: 'secondary',
        success: 'success',
        danger: 'danger',
        error: 'danger',
        warning: 'warning',
      };

      this.data.type = types[this.data.type];
    },
  },
  computed: {
    getIcon() {
      const icons = {
        default: 'info',
        primary: 'info',
        secondary: 'help',
        danger: 'error',
        warning: 'warning',
        success: 'check_circle',
        info: 'info',
      };

      return icons[this.data.type];
    },
  },
};
</script>

<style scoped>
.toast-header {
  display: flex;
}

.toast-header div {
  display: flex !important;
  flex: 1;
}
</style>
