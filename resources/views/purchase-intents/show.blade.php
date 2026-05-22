{{-- resources/views/purchase-intents/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Purchase Intent Details')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Purchase Intent Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Intent Number:</strong><br>
                        <code>{{ $intent->intent_number }}</code>
                    </div>
                    <div class="col-md-6">
                        <strong>Batch Number:</strong><br>
                        {{ $intent->batch_number }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Item Code:</strong><br>
                        {{ $intent->item_code ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        @if($intent->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($intent->status == 'acknowledged')
                            <span class="badge bg-info">Acknowledged</span>
                        @else
                            <span class="badge bg-success">PO Raised</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Item Description:</strong><br>
                    {{ $intent->item_description }}
                </div>
                
                <div class="mb-3">
                    <strong>Material Specification:</strong><br>
                    {{ $intent->material_specification ?? 'N/A' }}
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Required Quantity:</strong><br>
                        {{ number_format($intent->required_quantity, 3) }} {{ $intent->bomLineItem->uom ?? 'NOS' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Available Quantity:</strong><br>
                        {{ number_format($intent->available_quantity, 3) }}
                    </div>
                    <div class="col-md-4">
                        <strong class="text-danger">Shortfall Quantity:</strong><br>
                        <span class="text-danger">{{ number_format($intent->shortfall_quantity, 3) }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Priority:</strong><br>
                    <span class="badge bg-{{ $intent->priority == 'high' ? 'danger' : ($intent->priority == 'medium' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($intent->priority) }}
                    </span>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Raised By:</strong><br>
                        {{ $intent->raiser->name ?? 'System' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Date Raised:</strong><br>
                        {{ $intent->created_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
                
                @if($intent->acknowledged_at)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Acknowledged By:</strong><br>
                        {{ $intent->acknowledger->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Acknowledged At:</strong><br>
                        {{ $intent->acknowledged_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
                @endif
                
                @if($intent->po_reference)
                <div class="mb-3">
                    <strong>PO Reference:</strong><br>
                    <code>{{ $intent->po_reference }}</code>
                </div>
                @endif
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase-intents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @if($intent->status == 'pending')
                    <button class="btn btn-warning acknowledge-btn" data-id="{{ $intent->id }}">
                        <i class="fas fa-check"></i> Acknowledge
                    </button>
                    @endif
                    @if($intent->status == 'acknowledged')
                    <button class="btn btn-success po-btn" data-id="{{ $intent->id }}">
                        <i class="fas fa-file-invoice"></i> Mark PO Raised
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PO Modal -->
<div class="modal fade" id="poModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark PO as Raised</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="po_reference" class="form-label">PO Reference Number</label>
                    <input type="text" id="po_reference" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePoBtn">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentIntentId = {{ $intent->id }};
    
    $('.acknowledge-btn').click(function() {
        if(confirm('Acknowledge this purchase intent?')) {
            $.post('/purchase-intents/' + currentIntentId + '/acknowledge', {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                if (response.success) {
                    location.reload();
                }
            });
        }
    });
    
    $('.po-btn').click(function() {
        $('#poModal').modal('show');
    });
    
    $('#savePoBtn').click(function() {
        const poRef = $('#po_reference').val();
        if(!poRef) {
            alert('Please enter PO reference number');
            return;
        }
        
        $.post('/purchase-intents/' + currentIntentId + '/po-raised', {
            _token: '{{ csrf_token() }}',
            po_reference: poRef
        }).done(function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
</script>
@endpush