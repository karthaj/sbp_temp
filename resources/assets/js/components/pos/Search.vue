<template>
	
<div class="col-md-6 card mb-0 bg-grayscale">
   <div class="row form-group mt-2">
       <div class="col-12">
            <input id="productSearch" class="form-control form-control-sm" type="text" placeholder="BARCODE/SKU/PRODUCT NAME" v-model="query" @input="filter" v-on:keyup.enter="add">    
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6"><em>({{ productsCount }} products)</em></div>
        <div class="col-6 text-right">
            <a href="#" role="button" class="abtn" @click.prevent="toggleModal">
                <span>ALL PRODUCTS</span>
            </a>
        </div>
    </div> 
</div> 

</template>

<script>

import eventHub from '../../bus.js'
import { mapMutations, mapGetters } from 'vuex'
	
export default {
	
	data () {
		return {
			query: ''
		}
	},
	computed: {
		...mapGetters({

			productsCount: 'productsCount'

		})
	},
	methods: {
		...mapMutations({

            filterProducts: 'filterProducts'

        }),
		filter () {

			this.filterProducts(this.query)
		},
		add () {
			console.log('enter')
		},
		toggleModal () {

			eventHub.$emit('modal.category')

		}

	}

}

</script>