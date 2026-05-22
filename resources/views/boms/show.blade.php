@extends('layouts.app')

@section('title', 'BOM Details - ' . $bom->bom_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> Bill of Materials: {{ $bom->bom_number }}
                        <span class="badge bg-{{ $bom->status === 'completed' ? 'success' : ($bom->status === 'processing' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($bom->status) }}
                        </span>
                        <span class="badge bg-info">Rev. {{ $bom->revision }}</span>
                    </h5>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-lock"></i> Read Only
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Project:</strong><br>
                        {{ $bom->project->project_name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Uploaded By:</strong><br>
                        {{ $bom->uploader->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Upload Date:</strong><br>
                        {{ $bom->created_at->format('Y-m-d H:i:s') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Processed At:</strong><br>
                        {{ $bom->processed_at ? $bom->processed_at->format('Y-m-d H:i:s') : 'Pending' }}
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-secondary">
                            <strong>File:</strong> {{ $bom->file_name }}
                        </div>
                    </div>
                </div>
                
                <!-- Inventory Summary Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h3>{{ $lineItems->where('inventory_status', 'in_stock')->count() }}</h3>
                                <small>In Stock Items</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h3>{{ $lineItems->where('inventory_status', 'partial')->count() }}</h3>
                                <small>Partial Stock</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center">
                                <h3>{{ $lineItems->where('inventory_status', 'out_of_stock')->count() }}</h3>
                                <small>Out of Stock</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <h3>{{ $lineItems->total() }}</h3>
                                <small>Total Items</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Line Items Table -->
                <h6 class="mt-3">Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Code / Part No.</th>
                                <th>Description</th>
                                <th>Material Spec</th>
                                <th>Required Qty</th>
                                <th>UOM</th>
                                <th>Allocated To</th>
                                <th>Status</th>
                                <th>Available Qty</th>
                                <th>Shortfall</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lineItems as $item)
                            <tr>
                                <td>{{ $item->line_number }}</td>
                                <td><code>{{ $item->item_code ?: $item->part_number }}</code></td>
                                <td>{{ Str::limit($item->description, 50) }}</td>
                                <td>{{ Str::limit($item->material_specification, 30) }}</td>
                                <td class="text-end">{{ number_format($item->quantity, 3) }}</td>
                                <td>{{ $item->uom }}</td>
                                <td>{{ $item->allocated_to }}</td>
                                <td>
                                    @if($item->inventory_status == 'in_stock')
                                        <span class="badge bg-success">✅ In Stock</span>
                                    @elseif($item->inventory_status == 'partial')
                                        <span class="badge bg-warning">⚠️ Partial Stock</span>
                                    @elseif($item->inventory_status == 'out_of_stock')
                                        <span class="badge bg-danger">❌ Out of Stock</span>
                                    @else
                                        <span class="badge bg-secondary">🔄 Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item->available_quantity, 3) }}</td>
                                <td class="text-end">{{ number_format($item->shortfall_quantity, 3) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No line items found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
               <div class="mt-3 d-flex justify-content-end">
                {{ $lineItems->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Poll for status updates if processing
    @if($bom->status === 'processing')
    let pollInterval = setInterval(function() {
        $.get('{{ route("boms.status", $bom->id) }}', function(data) {
            if (data.status === 'completed') {
                location.reload();
            }
        });
    }, 5000);
    
    // Stop polling after 5 minutes
    setTimeout(function() {
        clearInterval(pollInterval);
    }, 300000);
    @endif
</script>
@endpush