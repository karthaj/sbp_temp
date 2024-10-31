<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header separator">
                <div class="card-title">
                  <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                  <a v-if="response.allow.create" :href="route" class="btn btn-action-add btn-xs">create blog post</a>
                </div>
                <div class="pull-right  ml-4">
                  <div class="col-xs-12">
                    <input type="text" class="form-control pull-right" placeholder="Search" v-model="q" v-on:keyup.enter="getRecords">
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
                  <div class="table-responsive" v-if="filteredRecords.length">
                      <table id="shippingClassTable" class="table table-hover tb100">
                          <thead>
                              <tr>
                                  <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_all" id="select_all" @change="toggleSelectAll" :checked="filteredRecords.length === selected.length">
                                        <label for="select_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  <th class="sorting" @click="sortBy('title')">
                                    title
                                    <i v-if="sort.key === 'title'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting" @click="sortBy('author')">
                                    author
                                    <i v-if="sort.key === 'author'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting" @click="sortBy('created_at')">
                                    date
                                    <i v-if="sort.key === 'created_at'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:15%" v-if="response.allow.deletion || response.allow.update">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                              <tr v-for="blog in filteredRecords "> 
                                  <td class="v-align-middle" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success text-center">
                                        <input type="checkbox" :value="blog.id" :id="'checkbox'+blog.id" v-model="selected">
                                        <label :for="'checkbox'+blog.id" class="no-padding no-margin"></label>
                                    </div>
                                  </td>
                                  <td class="v-align-middle">{{ blog.title }}</td>
                                  <td class="v-align-middle">{{ blog.author }}</td>
                                  <td class="v-align-middle">{{ blog.created_at }}</td>
                                  <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                    <div class="dropdown">
                                      <a v-if="response.allow.update" :href="'/merchant/blogs/'+blog.slug+'/edit'">Edit</a>
                                      <a  v-if="response.allow.deletion" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                        <i class="aapl-chevron-down-circle ml-2"></i>
                                      </a>
                                      <div v-if="response.allow.deletion" class="dropdown-menu dropdown-menu-right" role="menu">
                                        <a v-if="response.allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteItem(blog.id)">Delete</a>
                                      </div>
                                    </div>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                      <pagination :pagination="response.pagination" for="blogs" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No blog posts have been created.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'route'],
        components: {
          Pagination,
        },
        data () {
            return {
                response: {
                    displayable: [],
                    records: [],
                    allow: {},
                    pagination: {}
                },
                sort: {
                    key: 'title',
                    order: 'asc'
                },
                limit : 10,
                q : '',
                selected: [],
                page: 1
            }
        },
        computed: {
          filteredRecords () {
              let data = this.response.records
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
                    q: this.q
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            toggleSelectAll () { 
              if (this.selected.length > 0) {
                this.selected = []
                return
              }
              this.selected = _.map(this.filteredRecords, 'id')
            },
            deleteItem (item) {
              this.selected.push(item);
              this.destroy(this.selected);
            },
            destroy (selected) { 
                if(selected.length) {
                     this.deleteBlog(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one blog post to delete.')
                }
               
            },
            deleteBlog (endpoint) {
              if(this.response.allow.deletion) {
                swal({
                    title: 'Are you sure?',
                    text: "Deleted blog posts cannot be recovered. Do you still want to continue?",
                    type: 'warning',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false              
                }).then((result) => {
                  if (result.value) {
                    axios.delete(endpoint).then((response) => {
                        swal('Deleted!', 'Blog posts deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.selected = []
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
              }
            },
            alert (message) {
              swal({
                  text: message,
                  type: 'warning',
              })
            }
        },
        mounted() {
            this.getRecords()

            eventHub.$on('blogs.switched-page', this.getRecords)
        },
    }

</script>
