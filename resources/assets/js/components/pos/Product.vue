<template>
    
<div class="col-md-6 product"> 
    <div class="row">                        
        <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-2" v-if="products.length" v-for="product in products">
            <div class="card product-card">
                <div class="card-img">
                    <a href="#"><img class="card-img-top" :src="product.image" :alt="product.name"></a>
                </div>
                <div class="product-price"><span>{{ product.price }}</span></div>
                <div class="product-title">
                    {{ product.name }}
                    <template v-if="product.variation"> {{ product.variation }}</template>
                </div>
            </div>
        </div>
    </div>
</div> 

</template>

<script>

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

            products: 'products'

        })
    },
    methods: {
        ...mapActions({

            getProducts: 'getProducts'

        })
    },
    mounted () {

        this.getProducts(this.endpoint)

        if(this.products.length) {
            var t = _.filter(this.products, ['product_id', 278])

            console.log(t)
        }
        
    }

}
    
</script>