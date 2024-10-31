window.blog = window.blog || {}
$(document).ready(function() {
    blog.froala();

    $(document).on('click', '#btnDeleteFeaturedImage', function(e){  
         var url = $(this).attr('delete-url');
         blog.deleteImage(url)
    });
});

// refer shopbox js for vue components


blog = (function() {
    return {
        'coverImage': function(input) {
             if (input.files && input.files[0]) {
                var reader = new FileReader;

                reader.onload = function (e) {
                    $('.featured-image-preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
                $("#btnFeaturedImage").removeAttr('disabled');
                $('.btn-save').removeAttr('disabled');
            } 
        },
        'froala' : function () {
            $("#content").froalaEditor({
              toolbarButtons: ['bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'color', 'clearFormatting', '|', 'paragraphFormat', 'lineHeight', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '|', 'insertLink', 'insertImage', 'insertTable', 'embedly', 'insertHR', 'help', 'html', 'fullscreen', '|', 'undo', 'redo'],
                // Set the image upload parameter.
              imageUploadParam: 'file',
       
              // Set the image upload URL.
              imageUploadURL: '/merchant/image/upload',
       
              // Set request type.
              imageUploadMethod: 'POST',
       
              // Set max image size to 5MB.
              imageMaxSize: 5 * 1920 * 1080,
       
              // Allow to upload PNG and JPG.
              imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],

              // Set page size.
              imageManagerPageSize: 20,
       
              // Set a scroll offset (value in pixels).
              imageManagerScrollOffset: 10,

              // Set the image upload URL.
              imageManagerLoadURL: '/merchant/image/manager',

              // Set the delete image request type.
              imageManagerDeleteMethod: "DELETE",
       
              // Set the image delete URL.
              imageManagerDeleteURL: '/merchant/image/manager'

            })
            .on('froalaEditor.image.beforeUpload', function (e, editor, images) {
              // Return false if you want to stop the image upload.
            })
            .on('froalaEditor.image.uploaded', function (e, editor, response) {
              // Image was uploaded to the server.
              console.log('Image was uploaded to the server.');
            })
            .on('froalaEditor.image.inserted', function (e, editor, $img, response) {
              // Image was inserted in the editor.
              console.log('Image was inserted in the editor.');
            })
            .on('froalaEditor.image.replaced', function (e, editor, $img, response) {
              // Image was replaced in the editor.
              console.log('Image was replaced in the editor.');
            })
            .on('froalaEditor.image.error', function (e, editor, error, response) {
              // Bad link.
              if (error.code == 1) {
                console.log(error);
              }
       
              // No link in upload response.
              else if (error.code == 2) {
                console.log(error);
              }
       
              // Error during image upload.
              else if (error.code == 3) {
                console.log(error);
              }
       
              // Parsing response failed.
              else if (error.code == 4) {
                console.log(error);
              }
       
              // Image too text-large.
              else if (error.code == 5) {
                console.log(error);
              }
       
              // Invalid image type.
              else if (error.code == 6) {
                console.log(error);
              }
       
              // Image can be uploaded only to same domain in IE 8 and IE 9.
              else if (error.code == 7) {
                console.log(error);
              }
            });
        },
        deleteImage(url) {
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
                            $("#btnDeleteFeaturedImage").closest('div').remove();
                            $(".featured-image-preview").attr('src', 'https://via.placeholder.com/141x180?text=Image');
                        })
                        .catch(function (error) {
                            swal('Oops...', 'Something went wrong!', 'error');
                        });
                    });
                },
                allowOutsideClick: false              
            }); 
        }
    };
})();

$(document).on('click', '#btnFeaturedImage', function(e){  
     $('.featured-image-preview').attr('src', 'http://via.placeholder.com/141x180?text=Image');
     $("#btnFeaturedImage").attr('disabled', 'disabled');
});
$(document).on('keyup keydown keypress input', '#title', function(e){  
     var name = $(this).val().toLowerCase();
     $("#url_handle").val(name.trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-'));
     $("#page_title").val($("#title").val());
});

$('#blogTable input[type=checkbox]').click(function() {
    if ($(this).is(':checked')) {
        $(this).closest('tr').addClass('selected');
    } else {
        $(this).closest('tr').removeClass('selected');
    }

});