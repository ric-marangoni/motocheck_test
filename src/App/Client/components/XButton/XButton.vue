<template>
  <button type="button" class="xbutton" :class="cssClass" :disabled="disable ? disable : isDisabled" @click="callback($event)">
    <slot></slot>
    <img v-if="shouldRender" :src="externalLink" class="external-link" />
  </button>
</template>

<script>
import externalLink from '../../assets/global/images/external-link.svg';

export default {
  name: 'XButton',
  components: {
    externalLink,
  },
  props: {
    icon: {
      type: Boolean,
      default: false,
    },
    color: {
      type: String,
      default: 'blue',
    },
    size: {
      type: String,
      default: 'x-large',
    },
    shape: {
      type: String,
      default: 'rounded',
    },
    spacing: {
      type: String,
      default: 'serried',
    },
    active: {
      type: Boolean,
      default: true,
    },
    onClickSelfDisable: {
      type: Boolean,
      default: false,
    },
    disable: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    setDisable() {
      this.isDisabled = !this.isDisabled;
    },
    callback(e) {
      if (this.onClickSelfDisable) {
        this.setDisable();
      }
      this.$emit('click', e);
    },
    shouldDisable() {
      if (!this.active) {
        this.setDisable();
      }
    },
    shouldShowIcon() {
      if (this.icon) {
        this.shouldRender = true;
      }
    },
    setCssClasses(color, size, shape, spacing) {
      this.cssClass.push(color, size, shape, spacing);
    },
    validateProps() {
      if (!this.colors.includes(this.color)) {
        throw new Error(`the color passed as argument is not valid, the valid colors are:[${this.colors}]`);
      }

      if (!this.sizes.includes(this.size)) {
        throw new Error(`Error: the size passed as argument is not valid, the valid sizes are:[${this.sizes}]`);
      }

      if (!this.shapes.includes(this.shape)) {
        throw new Error(`Error: the shape passed as argument is not valid, the valid shapes are:[${this.shapes}]`);
      }

      if (!this.spacings.includes(this.spacing)) {
        throw new Error(`Error: the shape passed as argument is not valid, the valid shapes are:[${this.spacings}]`);
      }
    },
  },
  data() {
    return {
      cssClass: ['xbutton'],
      sizes: ['x-small', 'small', 'medium', 'large', 'x-large'],
      shapes: ['rectangle', 'rounded', 'circle'],
      colors: ['blue', 'orange', 'black', 'clear', 'white'],
      spacings: ['serried', 'baggy'],
      isDisabled: this.disable,
      shouldRender: false,
      externalLink: externalLink,
    };
  },
  mounted() {
    this.validateProps();

    this.setCssClasses(this.color, this.size, this.shape, this.spacing);

    this.shouldDisable();

    this.shouldShowIcon();
  },
  watch: {
    active(value) {
      this.setDisable();
    },
  },
};
</script>

<style scoped lang="scss">
.xbutton {
  min-height: 30px;
  line-height: 12px;
  font-weight: bold;
  background-image: none;
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  outline: none;
  overflow: visible;
  padding: 6px 12px;
  text-align: center;
  text-transform: none;
  touch-action: manipulation;
  font-size: 12px;
  white-space: normal;
  color: rgba(0, 0, 0, 0.3);
  border: 1.5px solid rgba(0, 0, 0, 0.3);
  background-color: rgba(0, 0, 0, 0.3);

  &.serried {
    margin: 0 auto;
  }

  &.baggy {
    margin: 6px auto 0;
  }

  &.x-small {
    width: 15%;
  }

  &.small {
    width: 33.3%;
  }

  &.medium {
    width: 66.6%;
  }

  &.large {
    width: 80%;
  }

  &.x-large {
    width: 100%;
  }

  &.rounded {
    border-radius: 10px;
  }

  &.rectangle {
    border-radius: 0px;
  }

  &.circle {
    border-radius: 50%;
  }

  &.orange {
    color: #f4831f;
    border-color: #f4831f;
    background-color: #fff;
  }

  &.blue {
    color: #0855a1;
    border-color: #0855a1;
    background-color: #fff;
  }

  &.black {
    color: #000;
    border-color: #000;
    background-color: #fff;
  }

  &.external-link {
    vertical-align: baseline;
    margin-left: 5px;
  }

  &:disabled {
    background: rgba(0, 0, 0, 0.3);
    color: rgba(0, 0, 0, 0.4);
    cursor: not-allowed;
    border: 1px solid rgba(0, 0, 0, 0.3);
  }
}
</style>
