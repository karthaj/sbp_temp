$(document).ready(function() {
    Pages.init();
});

var Pages = (function() {
  return {
    'init': function() {

      $("#content").redactor({
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

      $(document).on('keyup keydown keypress input', '#title', function(e){  
         var title = $("#title").val();
         $("#url_handle").val(title.trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase());
         $("#page_title").val(title);
      });

      $(document).on('change', '#type', function(e) {

        if($(this).val() === 'page') {
          $('#content').froalaEditor('html.set', '');
          $("#enable_form").closest('.form-group').addClass('hide');
          $("#map").closest('.form-group').addClass('hide');
          $("#reset").closest('.form-group').addClass('hide');
        } else {
          $('#content').froalaEditor('html.set', Pages.getDefaultTemplate());
          $("#enable_form").closest('.form-group').removeClass('hide');
          $("#map").closest('.form-group').removeClass('hide');
          $("#reset").closest('.form-group').removeClass('hide');
        }

      })

      $(document).on('click', '#reset', function(e) {
        $('#content').summernote('code', Pages.getDefaultTemplate());
      })
    },
    'getDefaultTemplate': function() {
      var content = '<div class="mt-lg -0">'+
                         '<h2 class="text-uppercase">information about us</h2>'+
                   '</div>'+
                   '<p>Consectetur aliquet a erat per sem nisi leo placerat dui a adipiscing a sagittis vestibulum. Sagittis posuere id nam quis vestibulum faucibus a est tristique ridiculus sed.</p>'+
                   '<p>Sagittis posuere id nam quis vestibulum vestibulum a facilisi at elit hendrerit scelerisque sodales nam dis orci non aliquet enim.</p>'+
                   '<div class="mb-3">'+
                     '<h2 class="text-uppercase">contact us</h2>'+
                   '</div>'+
          
                   '<div class="row clearfix">'+
                      '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
                        '<div class="clearfix mb-5">'+
                          '<i class="aapl-envelope icons pull-left mr-4 h1"></i>'+
                          '<p class="pull-left mb-0">'+
                            'Tel: xxx-xx-xx-xx <br>'+
                            'E-Mail: info@store.com'+
                          '</p>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
                        '<div class="clearfix mb-5">'+
                          '<i class="aapl-map-marker icons pull-left mr-4 h1"></i>'+
                          '<p class="pull-left mb-0">'+
                            'Your store address <br>'+ 
                            'here'+
                          '</p>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
                        '<div class="clearfix mb-5">'+
                          '<i class="aapl-paper-plane icons pull-left mr-4 h1"></i>'+
                          '<p class="pull-left mb-0">'+
                            'Free standard shipping <br>'+
                            'on all orders.'+
                          '</p>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
                        '<div class="clearfix mb-5">'+
                          '<i class="aapl-headset icons pull-left mr-4 h1"></i>'+
                          '<p class="pull-left mb-0">'+
                            'Support forum provide <br>'+
                            'for over 24h, every day'+
                          '</p>'+
                        '</div>'+
                      '</div>'+
                   '</div>';

        return content;
    }
  };
})();