@foreach($permissions as $permission)
    @if($permission->parent_id == null)
    <li>
        <p><b>{{ ucfirst($permission->name) }}</b></p>
    </li>
    @endif
    @if($permission->parent_id != null)
    <li>
      <div class="checkbox check-primary  custom-checkbox m-0">
        <input type="checkbox" value="{{ strtolower($permission->name) }}" id="{{ $name.str_slug($permission->name,'_') }}" name="{{ $name.'['.str_slug(strtolower($permission->name),'_').']' }}" {{ $user ? $user->permissions->contains('name', $permission->name) ? 'checked' :  '' : '' }}>
        <label for="{{ $name.str_slug($permission->name,'_') }}" class="m-0">{{ ucfirst($permission->name) }}</label>
      </div>
    </li> 
    @endif
    @include('zpanel.elements._permissions', ['permissions' => $permission->childPermissions, 'user' => $user])
@endforeach
