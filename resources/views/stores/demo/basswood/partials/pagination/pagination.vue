<template>
	
	<div class="pagination-product d-md-flex align-items-center" :class="{'justify-content-md-center': this.for === 'blogs', 'justify-content-md-between': this.for !== 'blogs'}">
      	<div v-if="showTotal" class="showing-product">
	        <p> Showing {{ pagination.current_page }}-{{ pagination.total_pages }} of {{ pagination.total }} item(s) </p>
      	</div>
	    <div class="page-list">
	        <ul>
              	<li class="prev":class="{ 'disabled': !pagination.links.previous }"><a href="#" @click.prevent="switchPage(pagination.current_page - 1)"><i class="zmdi zmdi-chevron-left"></i>Previous</a></li>
				
				<template v-if="section > 1">
					<li>
						<a href="#" @click.prevent="switchPage(1)">1</a>
					</li>
					<li>
						<a href="#" @click.prevent="goBackASection">...</a>
					</li>
				</template>

              	<li v-for="page in pages">
	            	<a href="#" :class="{'active': pagination.current_page === page }" 
	            		@click.prevent="switchPage(page)">{{ page }}</a>
	            </li>

	            <template v-if="section < sections">
	            	<li>
						<a href="#" @click.prevent="goForwardASection">...</a>
					</li>
					<li>
						<a href="#" @click.prevent="switchPage(pagination.total_pages)">{{ pagination.total_pages }}</a>
					</li>
				</template>
          
              	<li class="next" :class="{ 'disabled': !pagination.links.next }"><a href="#"  @click.prevent="switchPage(pagination.current_page + 1)">Next<i class="zmdi zmdi-chevron-right"></i></a></li>
	        </ul>
	    </div>
	</div>

</template>

<script>
	import bus from '../../assets/js/bus.js'

	export default {
		props: {
			pagination: Object,
			for: {
				type: String,
				default: 'default'
			},
			showTotal: {
				type: Boolean,
				default: true
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

				bus.$emit(this.for + '.switched-page', page)
			},
			pageIsOutOfBounds (page) {
				return page < 1 || page > this.pagination.total_pages;
			},
			goForwardASection () {
				this.switchPage(this.firstPageOfSection(this.section + 1));
			},
			goBackASection () {
				this.switchPage(this.firstPageOfSection(this.section - 1));
			},
			firstPageOfSection (section) {
				return (section - 1) * this.numbersPerSection + 1;
			} 
		},
		mounted () {
			if (this.pagination.current_page > this.pagination.total_pages) {
				this.switchPage(this.pagination.total_pages);
			}
		}
	}

</script>