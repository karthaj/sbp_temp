// refer shopbox js for vue components


window.discount = (function() {
  var parentElem = $('#listOfvalues');
  var formFeature = $('#featureForm');
  var productType = $('#product_type');
  var master_qty = $("#master_qty");
  return {
    'init': function() {
      	$('.autonumeric').autoNumeric('init');
	 	$('#expiry_date').datepicker({
			startDate: new Date(),
			format: 'yyyy-mm-dd',
		});
    },
    'toggleDiscount': function() {
      $(document).on('change', '#discount_type', function() { 
          var currency = $(this).attr('data-currency');
          if($(this).val() == 'percentage') {
            $("#discountBlock").find('.input-group').each(function(index) {
              var el = $(this).find('input');
              el.autoNumeric('update', {vMin:0, vMax: 100})
              $(this).find('span').remove();
              $('<span class="input-group-addon"><i class="fa fa-percent"></i></span>').insertBefore(el)
            })
          } else { 
            $("#discountBlock").find('.input-group').each(function() {
              var el = $(this).find('input');
              el.autoNumeric('update', {vMin:0, vMax: 999999999})
              $(this).find('span').remove();
              $('<span class="input-group-addon">'+currency+'</span>').insertBefore(el)
            })
          }
      })
    },
    'toggleCustomerRestriction': function() {
    	$(document).on('change', '.customer-restriction', function() { 
    		var restriction = $(this).val();

    		if(restriction === 'everyone') {
    			$("#customerGroup, #customerWrapper").addClass('hide');
    		} else if(restriction === 'specific_group') {
    			$("#customerGroup").removeClass('hide');
    			$("#customerWrapper").addClass('hide');
    		} else if(restriction === 'specific_customer') {
    			$("#customerGroup").addClass('hide');
    			$("#customerWrapper").removeClass('hide');
    		}

    	});
    },
    'toggleDiscountCondition': function() {
    	$(document).on('change', '.discount-condition', function() { 
    		var restriction = $(this).val();

    		if(restriction === 'entire_order') {
    			$("#specificCategory, #specificProduct").addClass('hide');
    		} else if(restriction === 'specific_category') {
    			$("#specificCategory").removeClass('hide');
    			$("#specificProduct").addClass('hide');
    		} else if(restriction === 'specific_product') {
    			$("#specificCategory").addClass('hide');
    			$("#specificProduct").removeClass('hide');
    		}

    	});
    }
    
  };
})();
