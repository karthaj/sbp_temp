// refer shopbox js for vue components

$(document).on('change', '#country', function(e){ 
  var endpoint = $(this).attr('data-url');
      customer.getStates($("#country").val(), endpoint)
});

customer = (function() {
  return {
   
    'getStates': function (country, endpoint) {
      axios.get(endpoint, {
        params: {
          country: country
        }
      }).then((response) => { 
        $("#stateProvince").html(response.data.states)
        if(response.data.result) {
        	$("#state").select2();
        }
        
      }).catch((error) => {
         console.log(error)
      })
    },

    'initCatergoryTree' : function () {
        $("#categoryTree").dynatree({
            onActivate: function(node) {
                $('#category').val(node.data.key);
                $('#title').val(node.data.title);
            }
      });
    },

    'initAutonumeric' : function () {
      $('.autonumeric').autoNumeric('init');
    },

    'validateCategoryDiscountForm': function () {
      $("#categoryDiscountForm").validate({ 
        rules: {

            category : { required: true },        
        },
        messages: {
            category: {
              required: 'Please choose a category.'
            }
        },
        submitHandler: customer.addCategoryDiscount

      });
    },

    'addCategoryDiscount': function() {
      var category = $("#category").val();
      customer.checkCategoryExists(category)
      
      var title = $("#title").val();
      var discount = $("#discount").val();

      var elem = '<div class="row item align-items-center mb-2">'+
                    '<div class="col">'+title+  
                      '<input type="hidden" name="category_discount['+category+'][category]" value="'+category+'">'+
                    '</div>'+
                    '<div class="col">Discount: '+discount+' %'+
                      '<input type="hidden" name="category_discount['+category+'][reduction]" value="'+discount+'">'+
                    '</div>'+
                    '<div class="col">'+
                      '<button class="btn btn-default btn-default-custom btn-remove"><i class="pg-trash"></i></button>'+
                    '</div>'+
                  '</div>';

      $(".notification").remove();
      $("#categoryLevelDiscount").append(elem);
      
      $("#discount").val(0.00);
      $("#categoryLevelDiscount").find('.error').remove();
      
    },

    'removeCategoryDiscount': function () {
      $(document).on('click', '.btn-remove', function() { 
        
        if($('#categoryLevelDiscount .item').length  == 1) {
          $("#categoryLevelDiscount").html('<p class="notification">No category level discounts have been created for this group.</p>');
        } else {
           $(this).closest('.row').remove();
        } 

      })
    
    },

    'checkCategoryExists': function (category) {

      $("#categoryLevelDiscount").find('input').each(function(index) {
        
        if($(this).attr('name') == 'category_discount['+category+'][category]') {
          if($(this).val() == category) {
            $(this).closest('.row').remove();
          }
        }
      
      })

    },

    
  };
})();
