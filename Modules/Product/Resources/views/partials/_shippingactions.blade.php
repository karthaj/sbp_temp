<div class="col-sm-1">
    <input type="checkbox" id="{{ $id }}" data-init-plugin="switchery" data-size="small" data-color="info" data-id="{{ $value }}" value="1" name="{{ $name }}" {{ $checked ? 'checked' : '' }}>
</div>
<div class="col-sm-2 text-center">
    <a class="blacklink" href="javascript:;" data-toggle="modal" data-target="#{{ $modal }}">Configure</a>
</div>