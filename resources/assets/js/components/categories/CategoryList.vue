<template>
	
<ul class="category-list">
	<li>
	  	<table class="table table-category table-hover">
		    <tbody>
		      <tr>
		        <td class="v-align-middle" :style="padding"><i class="aapl-arrow-right mr-1"></i>{{ category.name }}</td>
		        <td class="v-align-middle products">{{ category.products }}</td>
		        <td class="v-align-middle status" v-if="category.status">
		          <a href="#" @click.prevent="updateStatus(category.id, 0, allow)"><i class="aapl-checkmark-circle"></i></a>
		        </td>
		        <td class="v-align-middle status" v-else>
		          <a href="#" @click.prevent="updateStatus(category.id, 1, allow)"><i class="aapl-cross-circle"></i></a>
		        </td>
		        <td class="v-align-middle action">
		          <div class="dropdown">
		            <a v-if="allow.update" :href="category.edit">Edit</a>
		            <a  data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
		              <i class="aapl-chevron-down-circle ml-2"></i>
		            </a>
		            <div class="dropdown-menu" role="menu">
		              <a :href="category.view" target="_blank" class="dropdown-item">View</a>
		              <a v-if="allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteCategory(category.id, allow)">Delete</a>
		            </div>
		          </div>
		        </td>
		      </tr>
		    </tbody>
	  	</table>
	  	<category-list v-for="category, key in category.children" :key="category.id" :category="category" :indent="indent + 20" :allow="allow"></category-list>
	</li>
</ul>

</template>

<script>

import category from './category'

export default {
	props: {
		category: {
			required: true,
			type: Object
		},
		indent: {
			required: true,
			type: Number
		},
		allow: {
			required: true,
			type: Object
		}
	},
	mixins: [category],
	computed: {
		padding () {
			return {
				paddingLeft: this.indent + 'px'
			}
		}
	}
}

</script>