@foreach($categories as $category)
    @if($loop->first)
    <ul>
    @endif
    @if($edit_category) 
        @if($edit_category && ($category->id !== $edit_category->id))
            <li id="{{ $category->id }}" class="{{ $category->children->count() ? 'folder' : ''}} {{ $edit_category ? $category->id == $edit_category->parent->id ? 'active' : '' : '' }} expanded" disabled>{{ title_case($category->name) }}
           
                @include ('product::partials._categories', ['categories' => $category->children, 'edit_category' => $edit_category])

            </li>
        @endif 
    @else
        <li id="{{ $category->id }}" class="{{ $category->children->count() ? 'folder' : ''}} {{ $category->is_root_category ? 'active' : '' }} expanded" disabled>{{ title_case($category->name) }}
       
            @include ('product::partials._categories', ['categories' => $category->children, 'edit_category' => ''])

        </li>
    @endif
    
    @if($loop->last)
    </ul>
    @endif
@endforeach