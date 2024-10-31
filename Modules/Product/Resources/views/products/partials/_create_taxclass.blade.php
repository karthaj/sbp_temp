<div class="modal fade slide-up disable-scroll" id="createNewTaxClass" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Create New <span class="semi-bold">Tax Class</span></h5>
        </div>
        <div class="modal-body">
          <form id="formTaxClass" role="form" data-url="{{ route('product.taxclass.save') }}" autocomplete="off">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>
              </div>
            </div>
            <div class="row justify-content-end">
              <div class="col-md-4 m-t-10 sm-m-t-10">
                <button type="button" class="btn btn-default btn-block m-t-5" data-dismiss="modal">Cancel</button>
              </div>
              <div class="col-md-4 m-t-10 sm-m-t-10">
                <button type="submit" class="btn btn-info btn-block m-t-5">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>