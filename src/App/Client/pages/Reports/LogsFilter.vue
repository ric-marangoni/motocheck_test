<template>
  <div class="LogsFilter">
    <div class="LogsFilter__Collapse">
      <div class="d-flex">
        <div class="flex-grow-1"></div>

        <div class="d-flex">
          <button type="button" class="btn btn-secondary mr-2" @click="exportCsv">CSV Report</button>
          <Button @save="importData" :attributes="{ text: 'Import Data', textLoading: 'Loading', icon: 'save' }" :disabledLoading="disabledBtn" type="success" />
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import factory from '../../api';
import { map, pickBy } from 'ramda';
import { format, parseISO } from 'date-fns';

import EventBus from '../../utils/EventBus';
import Button from '../../components/Button';
import github from "../../api/github";

export default {
  name: 'LogsFilter',

  components: { Button },

  props: {},

  data: () => ({
    disabledBtn: false
  }),

  computed: {},

  created() {},

  mounted() {},

  methods: {
    async exportCsv() {
      this.paramsSearch = pickBy(val => val, this.filter);
      this.$emit('setFilter', this.paramsSearch, true);
    },

    importData() {
      this.disabledBtn = true;
      github
          .gitgub_import_data()
          .then(response => {
            if (!response) {
              this.flashMessage({
                message: `Ops! Was not able to load the report :(`,
                type: 'warning',
              });

              throw 'Ops! Was not able to load the report :(';
            }
          })
          .then(() => {
            this.searchFilter();
          })
          .catch(({ message }) => {
            this.flashMessage({
              message: `Error! ${message}`,
              type: 'danger',
            });
            this.disabledBtn = false;
          });
    },

    searchFilter() {
      this.disabledBtn = true;
      this.setParamsSearchAndList(this.filter);
    },

    async setParamsSearchAndList(data) {
      this.paramsSearch = pickBy(val => val, data);
      this.$emit('setFilter', this.paramsSearch);
      this.disabledBtn = false;
    },

    flashMessage(data, error = null) {
      if (error !== null) {
        data.error = error;
      }

      EventBus.$emit('flashMessage', data);
    },
  },

  watch: {},
};
</script>

<style lang="scss" scoped>
.LogsFilter {
  &__CollapseButton {
  }

  &__Collapse {
    margin-top: 24px;
  }
}

.vdp-datepicker {
  & .form-control {
    background-color: #fff !important;
  }
}

.div_logs {
  border-top: 3px solid #007bff;
  padding-top: 30px;
}

.v-select {
  background: #ffffff;
  min-width: 380px;
}

.filter-select {
  ::placeholder {
    color: #6c757d;
  }
  .vs__selected {
    color: #495057;
  }
  .vs--searchable,
  .vs__search,
  .vs__dropdown-toggle {
    cursor: pointer;
  }

  .vs__selected-options {
    padding: 2px 2px;
  }

  .vs__dropdown-menu {
    left: auto;
    right: 0;
    border-top-style: dashed;
  }
}
</style>
