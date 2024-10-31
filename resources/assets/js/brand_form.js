
// refer shopbox js for vue components
$(document).ready(function() {
  form.init();
  $(document).on('change', '#logo', function() {
    form.previewImage(this);
  })
});

$(document).on('click', '#deleteLogo', function(e){  
     var url = $(this).attr('delete-url');
     form.destroy(url)
});

var form = (function() {
  var brandForm = $('#brandForm');
  return {
    'init': function() {
      brandForm.validate({
        rules: {
            name: {
                required: true,
                maxlength: 191
            },
            page_title: {
                maxlength: 70
            },
            meta_description: {
                maxlength: 160
            },
            meta_keywords: {
                maxlength: 160
            }
        }
      });
      $(document).on('keyup', '#name', function(e){  
           var name = $("#name").val().toLowerCase();
           $("#url_handle").val(name.trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-'));
           $("#page_title").val($("#name").val());
      });
    },
    'destroy': function (url) {
      swal({
        title: 'Are you sure?',
        text: "It will be deleted permanently!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) { 
                axios({
                  method:'delete',
                  url: url,
                  responseType: 'json'
                })
                .then(function (response) { 
                    swal('Deleted!', response.data.message, response.data.type);
                    $("#deleteLogo").closest('.form-group').remove();
                })
                .catch(function (error) {
                    swal('Oops...', 'Something went wrong!', 'error');
                });
            });
        },
        allowOutsideClick: false              
    }); 
    },
    'previewImage': function(input) {
         if (input.files && input.files[0]) {
            var reader = new FileReader;

            reader.onload = function (e) {
                $('.image-preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            $('.btn-save').removeAttr('disabled');
        } 
    }
  };
})();