<template>
	
<div id="sectionsView" class="view bg-white">
  <div class="navbar navbar-default">
    <div class="navbar-inner">
      <a href="javascript:;" class="link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#section-setting-list" data-view-animation="push-parrallax">
        <i class="pg-arrow_left"></i>
      </a>
      <div class="view-heading">Add section</div>
    </div>
  </div>
  <div class="list-view-wrapper">
    <div class="list-view-group-container">
      <ul class="list-group">
      	<template v-if="sections.length">
      		<li v-for="section in sections" class="list-group-item justify-content-between">
	          	<span class="text-master">{{ section.name }}</span>
	      		<button type="button" class="btn btn-xs btn-action-add"  @click.prevent="addSection(section)">add</button>
	      	</li>
      	</template>
      	<p v-else class="clearfix text-center">No sections available<p>
      	<hr>
      </ul>
    </div>
    <br><br>
  </div>
</div>

</template>

<script>

import bus from "../../../bus"
import frame from '../../../frame'

export default {
	props: {
		theme: {
			type: String,
			required: true
		},
		sections: {
			type: Array,
			required: true
		},
		settings: {
			type: Object,
			required: true
		}
	},
	methods:{
		addSection (section) {

			var index = Math.floor((Math.random() * 1000000000000));
		    var section = section.defaults;
           
		    this.$set(this.settings.sections, index.toString(), section);
		    this.settings.content_for_index.push(index.toString());
			// bus.$emit('section.added', index.toString());
			bus.$emit('section.added',{
				section_id: index.toString(),
				settings: this.settings.sections[index.toString()]
			});
		    // frame.contentWindow.postMessage(this.settings, '*');

		    // $(frame).attr('src', $(frame).attr('src'));
		}
	}
}

</script>