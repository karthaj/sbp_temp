window.categories = window.categories || {}
$(document).ready(function() {
    categories.initCatergoryTree();
    //categories.initFroala();
    categories.redactor();
    $('#products').addClass($.DirtyForms.ignoreClass);
});

// refer shopbox js for vue components

categories = (function() {
    return {
        'coverImage': function(input) {
             if (input.files && input.files[0]) {
                var reader = new FileReader;

                reader.onload = function (e) {
                    $('.cover-image-preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
                $("#btnDeleteCover").removeAttr('disabled');
                $('.btn-save').removeAttr('disabled');
            } 
        },
        'initCatergoryTree' : function () {
            $("#categoryTree").dynatree({
                debugLevel: 0,
                onActivate: function(node) {
                    $('#category').val(node.data.key);
                }
          });
        },
        'redactor': function () {
          $("#description").redactor({
            plugins: ['table', 'alignment', 'fontcolor', 'fontsize', 'imagemanager', 'inlinestyle', 'video', 'widget'],
            imageUpload: '/merchant/image/upload',
            imageManagerJson: '/merchant/images.json',
            imageResizable: true,
            imagePosition: true,
            callbacks: {
              image: {
                  uploadError: function(response)
                  {
                      console.log(response.message);
                  }
              }
            }
          });
        },
        'generateUrl': function (handle) {
          axios.post('/merchant/permalink', {
            entity: 'category',
            handle 
          }).then((response) => { 
            $("#url_handle").val(response.data.url);
          }).catch((error) => {
              console.log(error.response)
          })
        },
        'notify': function (message) {
          $('.page-content-wrapper').pgNotification({
              style: 'simple',
              message: message,
              position: 'top-right',
              timeout: 5000,
              type: "success"
          }).show();
        },
        'addProduct': function (category, product_id) {
          
          axios.post(`/merchant/categories/${category}/product/add`, {
            product_id
          }).then((response) => { 
            if(response.data) {
              $("#message").remove();
              $("#productList").append(response.data);
              this.notify('Product added successfully!');
            }
          }).catch((error) => {
              console.log(error.response)
          })
        },
        'removeProduct': function (category, product_id) {
          
          axios.post(`/merchant/categories/${category}/product/remove`, {
            product_id
          }).then((response) => { 
              this.notify('Product removed successfully!');
          }).catch((error) => {
              console.log(error.response)
          })
        }
    };
})();

$(document).on('click', '#btnDeleteCover', function(e){  
     $("#coverImage").removeClass('dirty');
     $('.cover-image-preview').attr('src', 'http://via.placeholder.com/141x180');
     $("#btnDeleteCover").attr('disabled', 'disabled');
     $('#alertUnsavedChanges').addClass('hide');
});

$(document).on('change', '#name', function(e){  
     $("#page_title").val($("#name").val());
     categories.generateUrl($(this).val());
});

$('#categoryTable input[type=checkbox]').click(function() {
    if ($(this).is(':checked')) {
        $(this).closest('tr').addClass('selected');
    } else {
        $(this).closest('tr').removeClass('selected');
    }

});

$(document).on('change', '#products', function(e){
    categories.addProduct($("#handle").val(), $(this).val());  
});

$(document).on('click', '.remove-product', function(e){
  e.preventDefault();

  $(this).closest('li').remove();

  if(!$("#productList li").length) {
    $("#productList").append('<li id="message" class="list-group-item justify-content-center">There are no products in this category</li>');
  }

  categories.removeProduct($("#handle").val(), $(this).attr('data-id'));

});
