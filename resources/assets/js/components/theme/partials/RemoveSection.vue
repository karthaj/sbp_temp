<template>
	
<div class="px-3 my-3 list-view text-center">
  <button type="button" class="btn btn-action-delete" @click.prevent="removeSection">Remove Section</button>
</div>

</template>

<script>
import frame from '../../../frame'
import bus from "../../../bus"

export default {
	props: {
		section: {
			type:String,
			required: true
		},
		settings: {
			type: Object,
			required: true
		}
	},
	methods: {
		removeSection () {
			
	        delete this.settings.sections[this.section];

	        this.settings.content_for_index.forEach((value, index) => {
            
	            if(value == this.section) {
	              this.settings.content_for_index.splice(index, 1);
	            }
	            
	        })
	        
	        // frame.contentWindow.postMessage(this.settings, '*');
	        
	        // bus.$emit('section.removed', this.section);
	        bus.$emit('section.removed', {
	        	section_id: this.section
	        });
		}
	}
}

</script>