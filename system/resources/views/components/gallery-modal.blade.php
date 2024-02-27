@props(['id','gallery','folder' => '','route' => 'images','column_name' => 'fisier','column_title' => 'titlu'])

@php $id = $id ?? rand(1,99); @endphp

<button class="btn btn-outline-info mx-2" data-bs-toggle="modal" data-bs-target=".gallery-modal-{{ $id }}"><i class="icon-eye-open"></i></button>

<div class="modal fade gallery-modal-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="gallery-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <x-gallery 
                    :gallery="$gallery" 
                    :folder="$folder" 
                    :route="$route" 
                    :column_name="$column_name" 
                    :column_title="$column_title" 
                    />
            </div>
        </div>
    </div>
</div>