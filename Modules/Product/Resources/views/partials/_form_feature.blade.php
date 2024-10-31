{{ 
  html_entity_decode(
    '<div class="row align-items-end">
      <div class="col-md-4 form-group">
        <label>Feature</label>
        <select name="feature" class="form-control feature-selector">
          <option value="">feature 1</option>
        </select>
      </div>
      <div class="col-md-4 form-group">
        <label>Value</label>
        <select name="feature" class="form-control feature-value-selector">
          <option value="">value 1</option>
        </select>
      </div>
      <div class="col-md-3 form-group">
        <label for="">Custom value</label>
        <input type="text" class="form-control">
      </div>
      <div class="col-md-1 form-group">
        <button class="btn btn-default btn-default-custom delete" type="button"><i class="pg-trash"></i>
              </button>
      </div>
    </div>'
) }}