<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="dropdownfx">
                <li class="nav-item">
                  <a class="active" data-toggle="tab" role="tab" data-target="#featuresTab" href="#">Features</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" role="tab" data-target="#sortTab">Sort Features</a>
                </li>
              </ul>
              <div  class="card-block pt-20">
                <div class="tab-content">
                  <div class="tab-pane active" id="featuresTab">
                    <button class="btn button" @click.prevent="destroy"><i class="pg-trash"></i></button>
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
                    <div class="table-responsive">
                      <table id="featureTable" class="table table-hover" v-if="filteredRecords.length">
                          <thead>
                              <tr>
                                  <th style="width:1%">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_all" id="select_all" @change="selectAll(filteredRecords)">
                                        <label for="select_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  <th style="width:2" class="sorting" @click="sortBy('id')">
                                      ID
                                      <i v-if="sort.key === 'id'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:41%" class="sorting" @click="sortBy('name')">
                                      Name
                                      <i v-if="sort.key === 'name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:41%" class="sorting" @click="sortBy('sort_order')">
                                      Order
                                      <i v-if="sort.key === 'order'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:15%">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0">
                                <tr v-for="record in filteredRecords "> 
                                      <td class="v-align-middle">
                                        <div  class="checkbox check-success text-center">
                                            <input type="checkbox" :value="record.id" :id="'checkbox'+record.id"  @change="edit(record)">
                                            <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                        </div>
                                      </td>
                                      <template v-for="columnValue, column in record">
                                          <td class="v-align-middle" >{{ columnValue }}</td>
                                      </template>
                                      <td class="v-align-middle">
                                          <div class="dropdown">
                                              <a href="javascript:;" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                                <i class="card-icon card-icon-settings "></i>
                                              </a>
                                              <div class="dropdown-menu" role="menu" aria-labelledby="card-settings">
                                                <a :href="'/features/'+record.id+'/edit'" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                            </template>
                          </tbody>
                      </table>
                      <p class="text-center" v-else>No records found</p>
                    </div>
                  </div>
                  <div class="tab-pane " id="sortTab">
                    <div class="table-responsive">
                      <table class="table table-hover table-condensed" id="condensedTable">
                        <thead>
                          <tr>
                            <th style="width:30%">Value</th>
                            <th style="width:30%">Sort Order</th>
                          </tr>
                        </thead>
                      
                          <draggable :list="values" :element="'tbody'" @start="drag=true" @end="drag=false" @change="update" class="draggable">
                            <template v-for="value, index in values">
                                <tr>
                                  <td class="v-align-middle">{{ values[index].name }}</td>
                                  <td class="v-align-middle">{{ values[index].sort_order }}</td>
                                </tr>
                            </template>
                          </draggable>
                      
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
    import queryString from 'query-string'
    import draggable from 'vuedraggable'
    export default {
        props: ['endpoint', 'updateuri', 'data'],
         components: {
          draggable
        },
        data () {
            return {
                response: {
                    displayable: [],
                    records: []
                },
                sort: {
                    key: 'email',
                    order: 'asc'
                },
                limit : 10,
                quickSearchQuery : '',
                rowData: [],
                editing: {
                    id: []
                },
                values: [],
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

            }
        },
        methods: {
            getRecords () { 
                return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                    this.response = response.data.data
                })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    ...this.search
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            selectAll (filteredRecords) { 
                $('#userTable').find('input[type=checkbox]').prop('checked', $("#select_all").prop('checked'));
                if($("#select_all").prop('checked')) {
                    for (var i = 0; i < filteredRecords.length; i++) {
                      this.editing.id.push(filteredRecords[i].id)
                    }
                }else {
                    this.editing.id = []
                }
                
            },
            edit (record) {
                if ($("#checkbox"+record.id).prop('checked')) {  
                    this.editing.id.push(record.id)
                } else { 
                    this.editing.id.splice(this.editing.id.indexOf(record.id),1)
                }   
            },
            destroy () { 
                if(this.editing.id != '') {
                     this.deleteCategory(`${this.endpoint}/${this.editing.id}`)
                } else {
                    this.alert('Please choose at least one feature to delete.')
                }
               
            },
            deleteCategory (endpoint) {
                swal({
                    title: 'Are you sure?',
                    text: "It will be deleted permanently!",
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
                        swal('Deleted!', 'Features deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.editing.id = []
                          $('#featureTable').find('input[type=checkbox]').prop('checked', false);
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
            },
            alert (message) {
              swal({
                  text: message,
                  type: 'warning',
              })
            },
            update (e) {
              this.values.map((value, index) => { 
                value.sort_order = index + 1
              })
             
              axios.post('/features/order',{
                values: this.values
              }).then((response) => { 
                this.getRecords()
              }).catch((error) => {
                this.errors = error.response
              })
            },
        },
        mounted() {
            this.getRecords()
            this.values = JSON.parse(this.data)
        },
    }

</script>
