<template>
	
<div class="card-block">
    <div class="row">
       <div class="col">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <input type="text" id="search" class="form-control input-lg typeahead sample-typehead" placeholder="Search and add" autocomplete="off"> 
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col">
        <div data-pages="card" class="card card-default">
          	<div  class="card-block pt-20">
              	<div class="table-responsive" v-if="products.length">
                  	<table class="table table-hover">
                      	<thead>
	                        <tr>
	                          	<th style="width:1%"></th>
	                            <th>image</th>
	                            <th>name</th>
	                            <th>price</th>
	                            <th>Action</th>
	                        </tr>  
                      	</thead>
         
                      	<draggable :list="products" :options="{handle: '.handle'}" element="tbody" @start="drag=true" @end="drag=false" @change="update">
	                        <tr v-for="product, index in products" :key="product.id">
	                        	<td class="v-align-middle">
	                        		<i class="aapl-text-align-justify handle"></i>
	                        	</td>
		                        <td class="v-align-middle">
		                        	<img :src="product.image" :alt="product.name" class="img-fluid" width="40">
		                        </td>
		                        <td class="v-align-middle">{{ product.name }}</td>
		                        <td class="v-align-middle">{{ product.price }}</td>
		                        <td class="v-align-middle">
		                        	<a href="#" @click.prevent="remove(product.id, index)"><i class="aapl-trash2"></i></a>
		                        </td>
	                        </tr>
	                    </draggable>  
            
                  	</table>
              	</div>
              	<p  v-else class="text-center">No featured products added.</p>
          	</div>
        </div>
      </div>
    </div>
    <div class="form-group">
      	<div class="container-fluid container-fixed-lg footer action-wrapper">
		    <div class="small no-margin pull-right sm-pull-reset text-center">
		      <button class="btn btn-action-save" type="button" @click.prevent="save">Save</button>
		    </div>
		</div>
    </div>
  </div>

</template>

<script>

import draggable from 'vuedraggable'
	
export default {

	props: {
		searchendpoint: {
			type: String,
			required: true
		},
		endpoint: {
			type: String,
			required: true
		}
	},
	components: {
		draggable
	},
	data () {
		return {
			products: [],
			trashed: []
		}
	},
	methods: {

		getProduct(id) {

			axios.post(`/merchant/featured/products/${id}`).then((response) => { 
              this.addProduct(response.data)
            }).catch((error) => {
              console.log(error.response)
            })

		},
		addProduct(product) {

            var existing = this.products.find((p) => {
	            return p.id === product.id
	        })

            if(existing) {
                  return;
            }


			product.sort_order = this.products.length + 1;
			this.products.push(product);

		},
		update (e) {
			
			this.products.map((product, index) => {
				product.sort_order = index + 1
			});

		},
		save () {

			axios.post(this.endpoint, {
				products: this.products,
				trashed: this.trashed
			}).then((response) => { 
				this.trashed = [];
              	$('.page-content-wrapper').pgNotification({
	              style: 'simple',
	              message: 'Saved successfully!',
	              position: 'top-right',
	              timeout: 5000,
	              type: "success"
		      	}).show();
            }).catch((error) => {
              console.log(error.response)
            })

		},
		getProducts () {
			axios.get('/merchant/featured/get-products').then((response) => { 
              	this.products = response.data.products;
            }).catch((error) => {
              console.log(error.response)
            })
		},
		remove (id, index) {

			this.trashed.push(id);
			this.products.splice(index, 1);

		}

	},
	mounted() {

	    var vm = this;
	    var engine = new Bloodhound({
	        remote: {
	            url: this.searchendpoint + '?q=%QUERY%',
	            wildcard: '%QUERY%'
	        },
	        datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	        queryTokenizer: Bloodhound.tokenizers.whitespace
	    });
	           
	    var products = $("#search").typeahead({
	        hint: true,
	        highlight: true,
	        minLength: 1
        }, 
        {
	        source: engine,
	        name: 'products',
	        displayKey: 'name',
	        limit: 20,
	        templates: {
	            empty: [
	                '<div class="empty-message">',
	                  'product not found',
	                '</div>'
	              ].join('\n')
	        }
        }).bind('typeahead:select', function(ev, suggestion) {
	        vm.getProduct(suggestion.slug)
	        products.typeahead('val', '');
        }); 

        this.getProducts();
    }

}

</script>