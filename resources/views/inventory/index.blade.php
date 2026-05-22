@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-boxes"></i> Inventory Management</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Item Code or Name" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Material Grade</th>
                        <th>On Hand</th>
                        <th>Reserved</th>
                        <th>Available</th>
                        <th>Min Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventory as $item)
                    <tr class="{{ $item->available_quantity <= $item->minimum_stock_level ? 'table-warning' : '' }}">
                        <td><code>{{ $item->item_code }}</code></td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->material_grade }}</td>
                        <td class="text-end">{{ number_format($item->quantity_on_hand, 3) }}</td>
                        <td class="text-end">{{ number_format($item->quantity_reserved, 3) }}</td>
                        <td class="text-end">{{ number_format($item->available_quantity, 3) }}</td>
                        <td class="text-end">{{ number_format($item->minimum_stock_level, 3) }}</td>
                        <td>
                            @if($item->available_quantity <= $item->minimum_stock_level)
                                <span class="badge bg-danger">Low Stock</span>
                            @else
                                <span class="badge bg-success">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-stock" data-id="{{ $item->id }}" data-qty="{{ $item->quantity_on_hand }}">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-end">
        {{ $inventory->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStockForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Stock Quantity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quantity_on_hand" class="form-label">Quantity On Hand</label>
                        <input type="number" step="0.001" name="quantity_on_hand" id="quantity_on_hand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="minimum_stock_level" class="form-label">Minimum Stock Level</label>
                        <input type="number" step="0.001" name="minimum_stock_level" id="minimum_stock_level" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.edit-stock').click(function() {
            const id = $(this).data('id');
            const currentQty = $(this).data('qty');
            
            $('#quantity_on_hand').val(currentQty);
            $('#editStockForm').attr('action', '/inventory/' + id + '/update');
            $('#editStockModal').modal('show');
        });
    });
</script>
@endpush