<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header separator">
                <div class="card-title">
                  <button class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)" v-if="response.allow.deletion">
                    <i class="aapl-trash2"></i>
                  </button>
                  <a :href="route" class="btn btn-action-add btn-xs" v-if="response.allow.create">Add Category</a>
                </div>
                <div class="pull-right  ml-4">
                  <div class="col-xs-12">
                    <input type="text" class="form-control pull-right" placeholder="Search" v-model="quickSearchQuery">
                  </div>
                </div>
                <div class="pull-right ml-4">
                  <div class="col-xs-12">
                    <select class="pull-right form-control"  v-model="limit" @change="getRecords">
                      <option value="">View all</option>
                      <option value="10">View 10</option>
                      <option value="20">View 20</option>
                      <option value="30">View 30</option>
                      <option value="50">View 50</option>
                      <option value="100">View 100</option>
                    </select>
                  </div>
                </div>
              </div>

              <div  class="card-block pt-20">
                <div class="table-responsive"  v-if="filteredRecords.length">
                  <table class="table table-category table-hover">
                    <thead>
                      <tr>
                        <th scope="col" class="sorting" @click="sortBy('name')">
                            name
                            <i v-if="sort.key === 'name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                        </th>
                        <th class="products sorting" @click="sortBy('products')">
                            products
                            <i v-if="sort.key === 'products'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                        </th>
                        <th class="status sorting" @click="sortBy('status')">
                            status
                            <i v-if="sort.key === 'status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                        </th>
                        <th class="action">Action</th>
                      </tr> 
                    </thead>
                  </table>
                  <ul class="category-list">
                    <li v-for="category, index in filteredRecords">
                      <table class="table table-category table-hover">
                        <tbody>
                          <tr>
                            <td class="v-align-middle font-weight-bold">{{ category.name }}</td>
                            <td class="v-align-middle products">{{ category.products }}</td>
                            <td class="v-align-middle status" v-if="category.status">
                              <a href="#" @click.prevent="updateStatus(category.id, 0, response.allow)"><i class="aapl-checkmark-circle"></i></a>
                            </td>
                            <td class="v-align-middle status" v-else>
                              <a href="#" @click.prevent="updateStatus(category.id, 1, response.allow)"><i class="aapl-cross-circle"></i></a>
                            </td>
                            <td class="v-align-middle action">
                              <div class="dropdown">
                                <a v-if="response.allow.update" :href="category.edit">Edit</a>
                                <a  data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                  <i class="aapl-chevron-down-circle ml-2"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                  <a :href="category.view" target="_blank" class="dropdown-item">View</a>
                                  <a v-if="response.allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteCategory(category.id, response.allow)">Delete</a>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <category-list v-for="category, index in category.children" :key="category.id" :category="category" :indent="30" :allow="response.allow"></category-list>
                    </li>
                  </ul>
                  <pagination :pagination="response.pagination" for="categories" class="mt-4"></pagination>
                </div>
                <p class="text-center" v-else>No records found</p>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import category from './category'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'route'],
        components: {
          Pagination,
        },
        mixins: [category],
        data () {
            return {
              response: {
                  displayable: [],
                  records: [],
                  allow: {},
                  pagination: {}
              },
              sort: {
                  key: 'name',
                  order: 'asc'
              },
              limit : 10,
              quickSearchQuery : '',
              search: {
                  value: '',
                  operator: 'equals',
                  column: ''
              },
              selected: [],
              page: 1
            }
        },
        computed: {
            filteredRecords () {
                let data = this.response.records
                data = data.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                    })
                })
                if (this.sort.key) {
                    data = _.orderBy(data, (i) => {
                        let value = i[this.sort.key]
                        if(!isNaN(parseFloat(value))) {
                            return parseFloat(value)
                        }
                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.order)
                }

                return data 
            },
            canSelectItems () {
              return this.filteredRecords.length <= 500
            }
        },
        methods: {
            getRecords (page = 1) { 
              this.page = page;
              return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                  this.response = response.data.data
              })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    page: this.page,
                    ...this.search
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            }
        },
        mounted() {
            this.getRecords()
            eventHub.$on('categories.switched-page', this.getRecords)
            eventHub.$on('category.refresh', this.getRecords)
        },
    }

</script>

