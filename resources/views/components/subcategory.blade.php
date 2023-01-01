<?php $dash .= '-- '; ?>
@foreach ($sub_categories as $subcategory)
    <option value="{{ $subcategory->id }}">{{ $dash }}{{ $subcategory->name }}</option>
    @if (count($subcategory->sub_categories))
        @include('components.subcategory', ['sub_categories' => $subcategory->sub_categories])
    @endif
@endforeach
