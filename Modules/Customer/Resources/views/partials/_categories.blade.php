@foreach($categories as $category)
    @if($loop->first)
    <ul>
    @endif
        <li id="{{ $category->id }}" class="{{ $category->children->count() ? 'folder' : ''}}">{{ title_case($category->name) }}
       
            @include ('customer::partials._categories', ['categories' => $category->children])

        </li>
    @if($loop->last)
    </ul>
    @endif
@endforeach