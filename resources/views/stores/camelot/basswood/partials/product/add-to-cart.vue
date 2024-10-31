    <template>
	
<div class="product-add-to-cart">
    <span v-if="product.preorder || product.backorder || product.in_stock" class="control-label">Quantity</span>
    <div v-if="product.preorder || product.backorder || product.in_stock" class="cart-plus-minus">
        <input class="cart-plus-minus-box" type="text" name="qtybutton" min="1" v-model.number="qty" v-on:keypress="isNumber($event)">
        <div class="dec qtybutton"  @click="decrement"><i class="zmdi zmdi-chevron-down"></i></div>
        <div class="inc qtybutton" @click="increment"><i class="zmdi zmdi-chevron-up"></i></div>
     </div>
     <div class="add">
        <button class="action-btn cart cart-item add-to-cart" :class="action" type="button" @click.prevent="addToCart" :disabled="disabled">
            <i class="zmdi zmdi-shopping-cart-plus"></i>
            <i class="fa fa-circle-o-notch fa-spin"></i>
            <i class="fa fa-check"></i>
            <i class="fa fa-times"></i>
            <span class="addto">{{ label }}</span>
        </button>
        <span v-if="!product.preorder && !product.backorder" class="product-availability">
            <i v-if="product.in_stock" class="zmdi zmdi-check"></i> 
            <i v-else class="zmdi zmdi-close"></i>
            {{ availability }}
        </span>
     </div>
</div>

</template>

<script>
	
import bus from '../../assets/js/bus'
import settings from '../../assets/js/settings'

export default {
	props: {
		product: {
			type: Object,
			required: true
		}
	},
    mixins: [settings],
    data () {
        return {
            disabled: true,
            label: 'add to cart',
            qty: 1,
            product_id: '',
            attribute_id: '',
            stock: 0,
            availability: '',
            action: {
                loading: false,
                'add-item': false,
                'disabled-add-to-cart': false
            }
        }
    },
    watch: {
      qty: function (val) {

        if(!this.product.preorder && !this.product.backorder) {
            if(val > this.stock) {
              this.qty = this.stock;
            } 
        }

        if(val === '' || val === 0 || val < 0) {
          this.qty = 0;
        } 
      }
    },
    methods: {
        increment() {

            if(!this.product.preorder && !this.product.backorder) {
                if(this.qty < this.stock) {
                  this.qty++;
                  return;
                }
            }
            
            this.qty++;
            
        },
        decrement() {
            if(this.qty === 1) {
              this.qty = 1;
            } else {
              this.qty--;
            }
            
        },
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
              evt.preventDefault();;
            } else {
              return true;
            }
        },
        addToCart () {

            this.disabled = true;
            this.label = 'adding'
            this.action.loading = true;
            this.action['add-item'] = true;

            axios.post('/cart/add', {
                product_id: this.product_id,
                attribute_id: this.attribute_id,
                qty: this.qty
            }).then((response) => { 
                this.getCart();
                this.disabled = false;
                this.label = 'added';
                this.action.loading = false;
                this.action['add-item'] = true;
                setTimeout(() => {
                    this.label = 'add-to-cart';
                    this.action.loading = false;
                    this.action['add-item'] = false; 
                }, 1000);
            }).catch((error) => {

                this.disabled = false;
                this.label = 'add to cart';
                this.action.loading = false;
                this.action['add-item'] = false;

                if(error.response.data.product_id.length) {
                    this.$snotify.warning(error.response.data.product_id[0]);
                } else if(error.response.data.attribute_id.length) {
                    this.$snotify.warning(error.response.data.attribute_id[0]);
                } else if(error.response.data.qty.length) {
                    this.$snotify.warning(error.response.data.qty[0]);
                }
            })

        }
    },
    updated () {
        this.action['disabled-add-to-cart'] = this.disabled;
    },
    mounted () {

        if(this.product.type !== 'variant') {

            if(this.product.preorder || this.product.backorder) {
                this.disabled = false;
            } else if(this.product.in_stock) {
                this.disabled = false;
            }
            
        }

        this.stock = this.product.stock;
        this.product_id = this.product.id;

        if(this.product.preorder) {
            this.label = 'pre-order';
        }

        if(this.product.in_stock) {
            this.availability = this.product.instock_label;
        } else {
            this.availability = this.product.outofstock_label;
        }
        
        bus.$on('variation.refresh', (variation) => {

            if(!this.product.preorder && !this.product.backorder) {
                this.disabled = !Boolean(variation.stock);
            } else {
                this.disabled = false;
            }
           
            this.stock = variation.stock;
            this.attribute_id = variation.id;
            this.qty = 1;

        });

        bus.$on('selection.cleared', () => {
            this.disabled = true;
        });
    }
}

</script>