<template>

<div id="productCategory" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 mb-2 text-center" v-if="categories.length" v-for="category in categories" :key="category.id">
                        <div class="card"><div class="card-img"><a href="#">{{ category.name }}</a></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>

import eventHub from '../../bus.js'
import { mapActions, mapGetters } from 'vuex'

export default {

	props: {
		endpoint: {
			type: String,
			required: true
		}
	},
	computed: {

		...mapGetters({

			categories: 'categories'

		})

	},
	methods: {

		...mapActions({

            getCategories: 'getCategories'

        }),
		toggleModal () {

			$("#productCategory").modal('show')
			this.getCategories(this.endpoint)
		}

	},
	mounted () {
		
		eventHub.$on('modal.category', this.toggleModal)

	}

}

</script>