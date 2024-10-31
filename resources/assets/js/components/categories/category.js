
import eventHub from '../../bus.js'

export default {
  methods: {
  	updateStatus (id, status, allow) {
      if(allow.update) {
        axios.patch(`/merchant/categories/datatable/${id}`, {
          status
        }).then(() => {
            eventHub.$emit('category.refresh', 1);
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: error,
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
        })
      }
    },
    deleteCategory (item, allow) {
      if(allow.deletion) {
        swal({
            title: 'Are you sure?',
            text: "It will be deleted permanently!",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false              
        }).then((result) => {
          if (result.value) {
            axios.delete(`/merchant/categories/datatable/${item}`).then((response) => {
                swal('Deleted!', 'Category deleted successfully!', 'success');
                eventHub.$emit('category.refresh', 1);  
            }).catch((error) => {  
                swal('Oops...', 'Something went wrong!', 'error');
            });
          }; 
        });
      }
    }
  }
}