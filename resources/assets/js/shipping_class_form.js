
// refer shopbox js for vue components
$(document).ready(function() {
  form.validateShippingClassForm();
});

window.form = (function() {
  return {
    'init': function() {
      $("#shipping_zones").select2();
    },
    'validateShippingClassForm': function () {
      $("#shippingClassForm").validate({ 
        rules: {
            shipping_class_name : { required: true, maxlength: 191 },
            'shipping_zones[]' : { required: true },    
        },
        messages: {
            shipping_class_name: {
              required: 'Please enter a name for this shipping class.'
            },
            'shipping_zones[]': {
              required: 'Please select one or more shipping zone.'
            },
        }
      });
    },  
  };
})();


