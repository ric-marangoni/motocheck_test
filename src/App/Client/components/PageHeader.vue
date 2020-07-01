<template>
  <div class="PageHeader">
    <div class="PageHeader__details">
      <i class="mi">{{ iconTitle }}</i>

      <h4>
        {{ title }}
        <template v-if="view">
          {{ view }}
        </template>
      </h4>
    </div>

    <router-link :to="navLink" v-if="navLink && showAction">
      <b-button variant="success">
        <i class="mi mr-2">{{ navIcon }}</i>
        {{ navText }}
      </b-button>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'PageHeader',

  props: {
    view: {
      type: String,
    },
    title: {
      type: String,
      required: true,
    },
    iconTitle: {
      type: String,
      required: true,
    },
    navLink: {
      type: String,
    },
    navText: {
      type: String,
    },
    navIcon: {
      type: String,
    },
    showAction: {
      type: Boolean,
      default: true,
    },
  },
};
</script>

<style lang="scss" scoped>
.PageHeader {
  position: relative;
  top: 0;
  left: 0;
  padding: 0 12px 0 70px;
  background-color: #263238;
  min-height: 80px;
  max-height: 80px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  &__details {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    i,
    h4 {
      color: #fff;
      margin: 0 12px 0 0;
    }
  }

  > a {
    > button {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    &:hover {
      text-decoration: none;
    }
  }
}
</style>
