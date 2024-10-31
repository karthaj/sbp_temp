<template>
	
	<nav aria-label="Page navigation example">
	  <ul class="pagination justify-content-end">
	    <li class="page-item" :class="{'disabled': !pagination.links.previous  }">
	      <a class="page-link" href="#" aria-label="Previous" @click.prevent="switchPage(pagination.current_page - 1)">
	        <span aria-hidden="true">&laquo;</span>
	        <span class="sr-only">Previous</span>
	      </a>
	    </li>

		<template v-if="section > 1">
			<li class="page-item">
		    	<a class="page-link" href="#" @click.prevent="switchPage(1)">1</a>
		    </li>
		    <li class="page-item">
		    	<a class="page-link" href="#" @click.prevent="goBackASection">...</a>
		    </li>
		</template>

	    <li class="page-item" v-for="page in pages" :class="{'active': pagination.current_page === page }">
	    	<a class="page-link" href="#" @click.prevent="switchPage(page)">{{ page }}</a>
	    </li>

	    <template v-if="section < sections">
			<li class="page-item">
		    	<a class="page-link" href="#" @click.prevent="switchPage(pagination.total_pages)">{{ pagination.total_pages }}</a>
		    </li>
		    <li class="page-item">
		    	<a class="page-link" href="#" @click.prevent="goForwardASection">...</a>
		    </li>
		</template>

	    <li class="page-item" :class="{'disabled': !pagination.links.next  }">
	      <a class="page-link" href="#" aria-label="Next" @click.prevent="switchPage(pagination.current_page + 1)">
	        <span aria-hidden="true">&raquo;</span>
	        <span class="sr-only">Next</span>
	      </a>
	    </li>
	  </ul>
	</nav>

</template>

<script>
	import eventHub from '../../bus.js'

	export default {
		props: {
			pagination: Object,
			for: {
				type: String,
				default: 'default'
			}
		},
		data () {
			return {
				numbersPerSection: 7
			}
		},
		computed: {
			section () {
				return Math.ceil(this.pagination.current_page / this.numbersPerSection);
			},
			sections () {
				return Math.ceil(this.pagination.total_pages / this.numbersPerSection);
			},
			lastPage () {
				let lastPage = ((this.section - 1) * this.numbersPerSection) + this.numbersPerSection;

				if (this.section === this.sections) {
					lastPage = this.pagination.total_pages;
				}

				return lastPage;
			},
			pages () {
				return _.range((this.section - 1) * this.numbersPerSection + 1, this.lastPage + 1);
			}
		},
		methods: {
			switchPage (page) {

				if (this.pageIsOutOfBounds(page)) {
					return;
				}

				eventHub.$emit(this.for + '.switched-page', page)
			},
			pageIsOutOfBounds (page) {
				return page < 1 || page > this.pagination.total_pages;
			},
			firstPageOfSection (section) {
				return (section - 1) * this.numbersPerSection + 1;
			}, 
			goForwardASection () {
				this.switchPage(this.firstPageOfSection(this.section + 1));
			},
			goBackASection () {
				this.switchPage(this.firstPageOfSection(this.section - 1));
			},
		},
		mounted() {
			if (this.pagination.current_page > this.pagination.total_pages) {
				this.switchPage(this.pagination.total_pages);
			}
		}
	}

</script>