@props(['editRoute', 'deleteId'])

<div class="inline-flex items-center justify-end gap-1">
    <a href="{{ $editRoute }}" class="btn-icon text-primary-600 hover:bg-primary-50" title="Chỉnh sửa">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button" class="btn-delete btn-icon text-red-600 hover:bg-red-50" data-id="{{ $deleteId }}" title="Xóa">
        <i class="fas fa-trash-can"></i>
    </button>
</div>
