Vue.component('feature-table', require('./components/features/Index.vue'));

const app = new Vue({
    el: '#app'
});

$(document).ready(function() {
  form.init();
});

/**
 * Display category form management
 */
var form = (function() {
  var parentElem = $('#listOfvalues');
  var formFeature = $('#featureForm');
  return {
    'init': function() {
      /** Click event on the add button */
      $(document).on('click', '.add-value', function(e) {
        e.preventDefault();
        var elm =  '<div class="row align-items-center">'
            elm +=      '<div class="col-6 pl-0 form-group">'
            elm +=          '<input type="text" id="value" class="form-control" name="value[]">'
            elm +=      '</div>'
            elm +=      '<div class="col-1">'
            elm +=          '<a href="javascript:;"><i class="pg-plus_circle add-value"></i></a>'
            elm +=          '<a href="javascript:;"><i class="pg-minus_circle pl-1 remove-value"></i></a>'
            elm +=      '</div>';
            elm += '</div>';
        parentElem.append(elm);
      });
      $(document).on('click', '.remove-value', function(e) {
        e.preventDefault();
        var _this = $(this);
        if($('#listOfvalues .row').length == 1) {
            _this.remove()
        } else {
             _this.closest('.row').remove();
        }
      });
      formFeature.validate({
        rules: {
            name: {
                required: true,
                maxlength: 191
            }
        }
      });
    }
  };
})();