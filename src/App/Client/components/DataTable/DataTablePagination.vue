<template>
  <b-row>
    <b-col>
      <span class="datatable__registers">{{ totalRecord }}</span>
    </b-col>
    <b-col>
      <b-pagination
        class="mt-10"
        align="right"
        size="md"
        prev-text="Previous"
        next-text="Next"
        v-model="currentPagePagination"
        :class="{ hide_opacity: isBusy }"
        :per-page="perPage"
        :total-rows="totalItems"
      >
      </b-pagination>
    </b-col>
  </b-row>
</template>

<script>
Vue.use(BootstrapVue.PaginationPlugin);

export default {
  name: 'DataTablePagination',
  data() {
    const INITIAL_PER_PAGE_ITEMS = 10;

    return {
      currentPagePagination: 1,
      currentPerPageItems: INITIAL_PER_PAGE_ITEMS,
    };
  },
  props: {
    isBusy: {
      type: [Number, Boolean],
      default: false,
    },
    currentPage: {
      type: Number,
    },
    perPage: {
      type: Number,
      required: true,
    },
    totalItems: {
      type: Number,
      required: true,
    },
  },
  computed: {
    totalRecord() {
      let lastNumber = this.currentPagePagination * this.perPage;
      let firstNumber = lastNumber - this.perPage + 1;
      if (lastNumber > this.totalItems) {
        lastNumber = this.totalItems;
      }

      return `Showing from ${firstNumber} to ${lastNumber} from ${this.totalItems} records`;
    },
  },
  watch: {
    currentPagePagination(val) {
      if (this.currentPerPageItems == this.perPage) {
        this.$emit('list', parseInt(val, 10));
      }

      this.currentPerPageItems = this.perPage;
    },
  },
};
</script>

<style>
.datatable__registers {
  display: inline-block;
  padding: 30px;
}

.pagination {
  padding: 25px;
}

.pagination .page-item {
  padding: 0 10px;
}

.page-item.active .page-link {
  color: #fff;
  background-color: #777;
  border-color: #777;
}
</style>
