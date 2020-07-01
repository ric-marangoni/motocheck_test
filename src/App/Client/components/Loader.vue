<template>
  <div class="text-center my-2" :class="[{ fullsize: fullsize }, background]">
    <template v-if="customLoading">
      <img id="loading_logo" class="image_loading_logo" src="/images/crm-services-logo-orange.svg" alt="" />
    </template>
    <template v-else>
      <b-spinner class="align-middle" :class="foreground"></b-spinner>
      <strong class="text ml-3" :class="foreground" v-if="text">{{ text }}</strong>
    </template>
  </div>
</template>

<script>
export default {
  name: 'Loader',
  props: {
    text: {
      type: String,
      default: '',
    },
    fullsize: {
      type: Boolean,
      default: false,
    },
    background: {
      type: String,
      default: '',
    },
    foreground: {
      type: String,
      default: 'text-slate-800',
    },
    customLoading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style scoped>
.text {
  margin-left: 4px;
}

.fullsize {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  background: rgba(0, 0, 0, 0.4);
  margin: 0 !important;
}

.image_loading_logo {
  margin-top: 1.5px;
  vertical-align: -6px;
  width: 142px;
}

@keyframes color_invert {
  0% {
    filter: brightness(1);
    -webkit-filter: brightness(1);
    transform: perspective(120px) rotateY(180deg);
  }
  50% {
    filter: brightness(0) invert(1) drop-shadow(8px 8px 10px gray);
    -webkit-filter: brightness(0) invert(1) drop-shadow(8px 8px 10px gray);
    transform: rotateY(-16deg);
  }
  100% {
    filter: brightness(1);
    -webkit-filter: brightness(1);
    transform: perspective(120px) rotateY(180deg) scaleX(-1);
  }
}

#loading_logo {
  animation-name: color_invert;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-direction: alternate;
  animation-timing-function: ease-in-out;
}
</style>
