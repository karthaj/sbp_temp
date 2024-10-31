window.menu = (function() {
 
  return {
    'init': function() {
    
      $('#menuItems').nestable().on('change', menu.updateOutput);

      menu.updateOutput($('#menuItems').data('output', $('#menu_items')));

      $(document).on('click', '.item-trash', function() { 
      	var item = $(this).data('id');
    	 	axios.delete('/merchant/menus/items/'+item+'/delete')
    	 	.then((response) => {
  	     
          window.location.href = response.data.redirect_path

  	    }).catch((error) => {
            console.log(error.response)
  	    })
      })

      $(document).on('click', '#btnDeleteMenu', function() { 
      	var navigation = $(this).data('id');
      	menu.deleteMenu(navigation)
      })

      $('.dd').on('change', function() {
        $(".btn-action-add").attr('disabled', 'disabled');
        $(".btn-action-save").attr('disabled', 'disabled');
        $(".btn-action-cancel").attr('disabled', 'disabled');
        var items = $("#menu_items").val();
        axios.post('/merchant/menus/item/nest', {
          items: items
        })
        .then((response) => {
          $(".btn-action-add").removeAttr('disabled');
          $(".btn-action-save").removeAttr('disabled');
          $(".btn-action-cancel").removeAttr('disabled');
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: "Something went wrong",
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
        })
       
      });
 
    },
    'updateOutput': function(e) {
    	var list   = e.length ? e : $(e.target),
	            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required.');
        }
    },
    'validateCustomLinks': function() {
    	$("#customLinksForm").validate({ 
        rules: {
            link_url : { required: true },
            custom_label : { required: true },       
        },
        messages: {
            link_url: {
              required: 'Menu url is required.',
              url: 'Invalid url.'
            },
            custom_label: {
              required: 'Menu label is required.'
            }
        },
      });
    },
    'validateCategoryForm': function() {
    	$("#categoryForm").validate({ 
        rules: {
            category : { required: true },       
        },
        messages: {
            category: {
              required: 'Please choose a category.'
            }
        },
      });
    },
    'validateProductForm': function() {
    	$("#productForm").validate({ 
        rules: {
            product : { required: true },       
        },
        messages: {
            product: {
              required: 'Please choose a product.'
            }
        },
      });
    },
    'deleteMenu': function(menu) {
    	swal({
            title: 'Delete '+menu.name+' ?',
            text: "Are you sure you want to delete the menu "+menu.name+" ? This action cannot be reversed.",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete menu!',
            allowOutsideClick: false              
        }).then((result) => {
          if (result.value) {
            $("#btnDeleteMenu").attr('disabled', 'disabled');
            $(".btn-action-add").attr('disabled', 'disabled');
            $(".btn-action-save").attr('disabled', 'disabled');
            $(".btn-action-cancel").attr('disabled', 'disabled');

            axios.delete('/merchant/menus/'+menu.slug).then((response) => {
                swal('Deleted!', 'Menu deleted successfully!', 'success');
                setTimeout(function(){ 
                	window.location.href = response.data.redirect_path
                }, 1000);
	            
            }).catch((error) => {  
              $("#btnDeleteMenu").removeAttr('disabled');
              $(".btn-action-add").removeAttr('disabled');
              $(".btn-action-save").removeAttr('disabled');
              $(".btn-action-cancel").removeAttr('disabled');
              swal('Oops...', 'Something went wrong!', 'error');
            });
          }; 
        });
    },
    'validatePageForm': function() {
      $("#pageForm").validate({ 
        rules: {
            page : { required: true },       
        },
        messages: {
            page: {
              required: 'Please choose a page.'
            }
        },
      });
    },
  };
})();
