<template>
  <div class="table-container">
    <div class="table__header">
      <div class="table__custom_filters">
        <div class="table__clear_filters" v-if="filterButton">
          <button class="btn btn-light btn-sm" @click="clearAllFilters"><i class="mi align-mi">delete_outline</i> Limpar Filtro(s)</button>
        </div>
      </div>

      <div class="table__filter_limit_perpage">
        <label class="label_filter_limit_perpage">
          Showing:
          <select class="form-control input-sm select_filter_limit_perpage d-inline" v-model="limitPerPage">
            <option value="10">10</option>
            <option value="30">30</option>
            <option value="50">50</option>
          </select>
        </label>
      </div>
    </div>

    <b-table class="table" thead-class="hidden_header" show-empty bordered hover :items="items" :fields="fields" :current-page="currentPage" :busy="isBusy" :per-page="0">
      <!-- Slot filters -->
      <template v-slot:thead-top="{ fields }">
        <td class="table__thead_td" v-for="field in fields" :key="field.key">
          <template v-if="field.disabled === undefined">
            <div class="table__filter_wrap" :class="filters[field.key] !== '' ? 'not-empty' : 'empty'">
              <template v-if="filters[field.key] !== false && field.type !== 'date' && field.type !== 'number'">
                <div class="table__thead_filter">
                  <b-form-input class="table__filter_input show-label" v-model="filters[field.key]" v-on:keyup="filterInputKeyUp" :placeholder="field.label" />
                  <i class="mi">search</i>
                </div>
              </template>

              <template v-if="field.type === 'number'">
                <div class="table__thead_filter">
                  <b-form-input
                    class="table__filter_input-small"
                    v-model="filters[field.key]"
                    v-on:keyup="filterInputKeyUp"
                    v-on:change="filterInputKeyUp"
                    :formatter="format"
                    :placeholder="field.label"
                  />
                  <i class="mi">search</i>
                </div>
              </template>

              <template v-if="field.type === 'date'">
                <div class="table__thead_filter">
                  <datepicker v-model="filters[field.key]" :placeholder="field.label" :language="ptBR" @selected="filterInputKeyUp"></datepicker>
                  <i class="mi">search</i>
                </div>
              </template>

              <template v-if="field.key !== 'actions' && filters[field.key] !== ''">
                <div class="table__thead_filter_clear" @click="clearFilter(field.key)">
                  <i class="mi">clear</i>
                </div>
              </template>
            </div>
          </template>
          <template v-else>
            <div class="table__thead_filter">
              <b-form-input
                class="table__filter_input show-label"
                v-on:keyup="filterInputKeyUp"
                :placeholder="field.label"
                :disabled="true"
                :style="field.style ? field.style : ''"
                :class="field.class ? field.class : ''"
              />
            </div>
          </template>
        </td>
      </template>

      <!-- Slot Actions-->
      <template v-slot:cell(actions)="data" v-if="actions">
        <template v-if="multipleActions === false">
          <div :style="actions.divStyle ? actions.divStyle : ''">
            <button type="button" class="btn btn-sm" :class="actions.class ? actions.class : ''" :style="actions.style ? actions.style : ''" @click="actionClick(actions.event, data.item)">
              <i v-if="actions.icon" class="mi" :class="actions.hasOwnProperty('name') && 'align-mi'">{{ actions.icon }}</i>
              {{ actions.name }}
            </button>
          </div>
        </template>
        <template v-else>
          <div>
            <b-dropdown size="sm" right :text="textAction">
              <b-dropdown-item href="#" v-for="(action, index) in activeActions" :key="action.event + index" @click="actionClick(action.event, data.item)">
                <i v-if="action.icon" class="mi mr-2">{{ action.icon }}</i>
                <span>{{ action.hasOwnProperty('conditionalName') ? action.conditionalName(data.item) : action.name }}</span>
              </b-dropdown-item>
            </b-dropdown>
          </div>
        </template>
      </template>

      <!--Slot Loading-->
      <Loader slot="table-busy" :text="textLoading" />
    </b-table>
  </div>
</template>

<script>
import { map } from 'ramda';

import Datepicker from 'vuejs-datepicker';
import { ptBR } from 'vuejs-datepicker/dist/locale';
import debounce from 'lodash.debounce';
import EventBus from '../../utils/EventBus';

// Components
import Loader from '../Loader';

export default {
  name: 'DataTable',
  components: {
    Datepicker,
    Loader,
  },
  data() {
    const filters = this.fields.map(field => {
      const key = field['key'];

      return {
        [key]: key === 'actions' ? false : '',
      };
    });

    return {
      filters: Object.assign(...filters),
      ptBR: ptBR,
      limitPerPage: 10,
    };
  },
  props: {
    items: {
      type: Array,
    },
    fields: {
      type: Array,
    },
    isBusy: {
      type: Boolean,
    },
    currentPage: {
      type: Number,
    },
    actions: {
      type: [Array, Object],
      default: () => [],
    },
    multipleActions: {
      type: Boolean,
      default: true,
    },
    textAction: {
      type: String,
      default: 'Ações',
    },
    textLoading: {
      type: String,
      default: 'Carregando...',
    },
    showLimitPerPage: {
      type: Boolean,
      default: true,
    },
    filterButton: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    activeActions() {
      return this.actions.filter(action => {
        return action.hasOwnProperty('conditionalShow') ? action.conditionalShow : true;
      });
    },
  },
  methods: {
    actionClick(event, item) {
      this.$emit(event, item);
    },
    filterInputKeyUp: debounce(function() {
      this.$emit('filter', this.filters);
    }, 300),
    format(value, event) {
      return value.replace(/\D/g, '');
    },
    clearAllFilters() {
      map(item => {
        if (item.key !== 'actions') {
          this.filters[item.key] = '';
        }
      }, this.fields);

      this.filterInputKeyUp();
    },
    clearFilter(field) {
      this.filters[field] = '';

      this.filterInputKeyUp();
    },
  },
  watch: {
    limitPerPage(val) {
      EventBus.$emit('listLimit', parseInt(val, 10));
    },
  },
};
</script>

<style lang="scss">
.table-container {
  overflow-x: auto;
  max-width: 100%;
}

.table {
  background: #fff;
}

td.table__thead_td {
  padding: 0;
}

td.table__thead_td:first-child {
  max-width: 130px;
}

.table__thead_filter {
  align-items: center;
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  min-height: 62px;
  padding: 12px 20px;
  transition: 0.2s all ease-in-out;
  width: 100%;

  .form-control {
    box-shadow: none;
  }
}

.table__header {
  display: flex;
  justify-content: flex-end;
  padding-top: 12px;
  border: 1px solid #dee2e6;
  border-bottom: none;

  & .table__custom_filters {
    flex: 1;
  }

  & .table__clear_filters {
    padding-left: 10px;
  }

  & .label_filter_limit_perpage {
    font-size: 14px;
    padding-right: 15px;
  }

  & .select_filter_limit_perpage {
    width: 65px;
  }
}

.table__filter_wrap {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
}

.not-empty,
.table__filter_wrap:hover {
  background: #e0e0e0;
}

.table__thead_filter .mi {
  margin-right: 8px;
  opacity: 0;
  transition: 0.2s all ease-in-out;
}

.not-empty .mi,
.table__thead_filter:hover .mi,
.table__filter_input:focus + .mi,
.table__filter_input-small:focus + .mi {
  opacity: 1;
}

.table__filter_label {
  flex: 1;
}

.table__thead_filter_clear {
  cursor: pointer;
  color: #fc7373;
  margin-right: 8px;
  padding-top: 7px;
}

.table__filter_input,
.table__filter_input-small,
.table.vdp-datepicker input {
  background: transparent;
  border: none;
  flex: 1;
  font-size: 14px;
  padding-left: 0;

  &:focus {
    outline: none;
  }

  &[disabled='disabled'] {
    background-color: transparent;
  }
}

.table__filter_input-small {
  max-width: 100px;
}

::-webkit-input-placeholder {
  /* Chrome/Opera/Safari */
  color: rgb(51, 51, 51);
  font-size: 14px;
}

::-moz-placeholder {
  /* Firefox 19+ */
  color: rgb(51, 51, 51);
  font-size: 14px;
}

:-ms-input-placeholder {
  /* IE 10+ */
  color: rgb(51, 51, 51);
  font-size: 14px;
}

:-moz-placeholder {
  /* Firefox 18- */
  color: rgb(51, 51, 51);
  font-size: 14px;
}

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
