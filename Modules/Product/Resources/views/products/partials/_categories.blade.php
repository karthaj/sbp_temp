@foreach($categories as $category)  
    @if($loop->first)
    <ul>
    @endif
        <li id="{{ $category->id }}" class="{{ $category->children->count() ? 'folder' : ''}} expanded {{ $category->is_root_category ? 'active' : '' }}" disabled>{{ title_case($category->name) }}
       
            @include ('product::products.partials._categories', ['categories' => $category->children])

        </li>
    @if($loop->last)
    </ul>
    @endif
@endforeach