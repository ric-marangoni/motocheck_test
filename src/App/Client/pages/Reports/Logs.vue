<template>
  <section class="Logs">
    <PageHeader title="Repositories Report" icon-title="insert_chart_outlined" />

    <div class="Logs__Content">
      <LogsFilter @setFilter="getLogs" />

      <div class="Logs__Table">
        <DataTable
          :items="items"
          :fields="fields"
          :currentPage="currentPage"
          :isBusy="isBusy"
          :perPage="perPage"
          :multiple-actions="multipleActions"
          :filterButton="false"
          text-loading="loading repositories..."
          @listLimit="listLimit"
          @filter="filterTable"
        ></DataTable>

        <DataTablePagination @list="listPagination" :isBusy="isBusy" :perPage="perPage" :totalItems="totalItems" />
      </div>
    </div>
  </section>
</template>

<script>
import github from '../../api/github';

//Utils
import EventBus from '../../utils/EventBus';

//Components
import DataTable from '../../components/DataTable/DataTable';
import DataTablePagination from '../../components/DataTable/DataTablePagination';
import Loader from '../../components/Loader';
import PageHeader from '../../components/PageHeader';
import FlashMessage from '../../components/FlashMessage';

import LogsFilter from './LogsFilter';

export default {
  name: 'Logs',

  components: {
    DataTable,
    DataTablePagination,
    Loader,
    PageHeader,
    FlashMessage,
    LogsFilter,
  },

  data() {
    return {
      fields: [
        {
          key: 'owner',
          disabled: true,
          label: 'Owner'
        },
        {
          key: 'project',
          disabled: true,
          label: 'Project'
        },
        {
          key: 'stars',
          disabled: true,
          label: 'Stars',
          style: {
            width: '100px',
          },
        },
        {
          key: 'forks',
          disabled: true,
          label: 'Forks',
        },
        {
          key: 'watchers',
          disabled: true,
          label: 'Watchers',
        },
        {
          key: 'url',
          disabled: true,
          label: 'Url',
        }
      ],

      items: [],

      isBusy: false,

      multipleActions: false,

      currentPage: 1,

      perPage: 10,

      totalItems: 0
    };
  },

  methods: {
    getLogs(filter, csv = false) {
      let params = {
        limit: this.perPage,
        offset: (this.currentPage - 1) * this.perPage,
        ...filter,
      };

      if (csv) {
        const esc = encodeURIComponent;
        const query = Object.keys(params)
          .map(k => `${esc(k)}=${esc(params[k])}`)
          .join('&');
        window.open(`${window.location.origin}/reports/get-repositories?${query}&exportCsv=1`, '_blank');
        return false;
      }

      this.isBusy = true;

      github
        .gitgub_list_repositories(params)
        .then(response => {
          if (!response) {
            this.flashMessage({
              message: `Ops! Was not able to load the report :(`,
              type: 'warning',
            });

            throw 'Ops! Was not able to load the report :(';
          }

          const { rows, totalRows, limit } = ((response || {}).data || {}).data;
          this.items = rows || [];
          this.totalItems = parseInt(totalRows || 0, 10);
          this.perPage = parseInt(limit || 0, 10);
        })
        .then(() => {
          this.disabledBtn = false;
          this.isBusy = false;
        })
        .catch(({ message }) => {
          this.flashMessage({
            message: `Error! ${message}`,
            type: 'danger',
          });
        });
    },

    listPagination(value) {
      this.currentPage = value;
      this.getLogs();
    },

    listLimit(val) {
      this.perPage = val;
      this.getLogs();
    },

    filterTable(filters) {
      if (filters['actions'] !== undefined) {
        delete filters['actions'];
      }

      this.setParamsSearchAndList(filters);
    }
  },

  created() {
    EventBus.$on('listLimit', this.listLimit);
    this.getLogs();
  },
};
</script>

<style lang="scss" scoped>
.Logs {
  &__Content {
    padding: 36px 15px;
  }

  &__Table {
    margin: 24px 0;
  }
}
</style>
