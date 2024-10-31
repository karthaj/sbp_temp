<template>
  
  <transfer :stores="stores" :searchendpoint="searchendpoint" :endpoint="endpoint" :transfer-endpoint="transferEndpoint"/>

</template>

<script>
    import queryString from 'query-string'
    import transfer from './Transfer.vue'

    export default {
        props: ['stores', 'searchendpoint', 'endpoint', 'transfer-endpoint'],
        components: {
          transfer
        }
    }

</script>
