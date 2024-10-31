<template>
  <div class="card card-default">
    <div class="card-header">
      <div class="card-title">Services</div>
    </div>
    <div class="card-block">
      <div v-if="services.length" class="table-responsive">
        <div class="dataTables_wrapper no-footer">
          <table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
            <thead>
              <tr>
                <th style="width:30%">Name</th>
                <th style="width:30%">Ends on</th>
                <th style="width:40%"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services" :key="service.id">
                <td class="v-align-middle semi-bold">{{ service.name }}</td>
                <td class="v-align-middle">{{ service.ends_at }}</td>
                <td v-if="service.plugin && service.recurring" class="v-align-middle semi-bold">
                  <a v-if="service.recurring" href="#" @click.prevent="cancel(service)">cancel service</a>
                  <span v-else class="badge badge-info">cancellation on progress</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> 
      <p v-else-if="services.length === 0" class="text-center my-5">You don't have any services yet.</p>
    </div>
    <div v-if="show" class="pgn-wrapper" data-position="top"><div class="pgn push-on-sidebar-open pgn-bar"><div :class="'alert alert-'+type"><div class="container"><span>{{ message }}</span><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div></div></div>

    <div class="modal fade slide-up disable-scroll" id="modalConfirmation" tabindex="-1" role="dialog" aria-hidden="false">
      <div class="modal-dialog ">
        <div class="modal-content-wrapper">
          <div class="modal-content">
            <div class="modal-header clearfix text-left">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
              </button>
              <h5><span class="semi-bold">Warning</span></h5>
            </div>
            <div class="modal-body">
              <p class="p-b-10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam, nam.</p>
              <div class="checkbox check-info">
                <input type="checkbox" v-model="agree" id="agree">
                <label for="agree">I agree</label>
              </div>        
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-action-add" :disabled="!agree" @click.prevent="save">Save</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
    </div>
  </div> 
</template>

<script>
    import eventHub from '../../bus.js'

    export default {
        props: ['data'],
        data () {
            return {
                services: [],
                type: 'success',
                message: '',
                show: false,
                agree: false,
                service: ''
            }
        },
        methods: {
          cancel(service) {
            this.service = service;
            $('#modalConfirmation').modal('show')
          },
          save() {
            axios.post('billing/service/cancel', {
              id: this.service.id
            })
            .then((response) => { 
                this.message = 'Service updated successfully.'
                this.show = true;
                this.service.recurring = false
                $('#modalConfirmation').modal('hide')
            }).catch((error) => { 
              console.log(error)
              this.message = 'Something went wrong!' 
              this.type = 'danger';
              this.show = true;
              $('#modalConfirmation').modal('hide')
            });
          }
        },
        mounted() {
          this.services = this.data;
        },
    }

</script>
