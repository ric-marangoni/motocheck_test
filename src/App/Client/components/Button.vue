<template>
  <div :class="attr.divClass">
    <button type="button" :disabled="verifyDisabled" @click="save" :class="[attr.class, 'flex-grow-1 justify-content-center', type ? `btn-${type}` : '']" class="btn d-flex">
      <i class="mi mr-2" :class="classLoading" v-html="iconSaveAndLoading" v-show="attr.icon || attr.iconLoading"></i>{{ disabledLoading ? attr.textLoading : attr.text }}
    </button>
  </div>
</template>

<script>
export default {
  name: 'Button',
  props: {
    attributes: {
      type: Object,
    },
    disabledLoading: {
      type: Boolean,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    type: {
      type: String,
    },
  },
  data() {
    return {
      attr: {},
    };
  },
  methods: {
    save() {
      this.$emit('save');
    },
  },
  computed: {
    iconSaveAndLoading() {
      return this.disabledLoading ? this.attr.iconLoading : this.attr.icon;
    },
    classLoading() {
      return this.disabledLoading ? 'loading' : '';
    },
    verifyDisabled() {
      return this.disabledLoading || this.disabled;
    },
  },
  mounted() {
    const attrFormat = {
      text: 'Salvar',
      textLoading: 'Salvando',
      icon: 'save',
      iconLoading: 'loop',
      class: '',
      divClass: '',
    };

    this.attr = Object.assign({}, attrFormat, this.attributes);
  },
};
</script>

<style scoped></style>
