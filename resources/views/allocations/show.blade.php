@extends('layouts.app')

@section('title', 'Allocation Details')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Material Allocation Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Allocation Number:</strong><br>
                        <code>{{ $allocation->allocation_number }}</code>
                    </div>
                    <div class="col-md-6">
                        <strong>BOM Reference:</strong><br>
                        <a href="{{ route('boms.show', $allocation->bom_header_id) }}">
                            {{ $allocation->bomHeader->bom_number ?? 'N/A' }}
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Item Code:</strong><br>
                        {{ $allocation->item_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Item Description:</strong><br>
                        {{ $allocation->item_description }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Allocated Quantity:</strong><br>
                        <span class="text-success">{{ number_format($allocation->allocated_quantity, 3) }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Original Required:</strong><br>
                        {{ number_format($allocation->original_required_quantity, 3) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Allocated To:</strong><br>
                        {{ $allocation->allocated_to }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Allocated By:</strong><br>
                        {{ $allocation->allocated_by }}
                    </div>
                    <div class="col-md-6">
                        <strong>Allocated At:</strong><br>
                        {{ $allocation->allocated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('allocations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button class="btn btn-success acknowledge-btn" data-id="{{ $allocation->id }}">
                        <i class="fas fa-check"></i> Acknowledge Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.acknowledge-btn').click(function() {
        const id = $(this).data('id');
        
        if(confirm('Acknowledge receipt of these materials?')) {
            $.post('/allocations/' + id + '/acknowledge', {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                if (response.success) {
                    alert('Materials acknowledged successfully');
                    window.location.href = '{{ route("allocations.index") }}';
                }
            }).fail(function(xhr) {
                alert('Failed to acknowledge: ' + (xhr.responseJSON?.message || 'Unknown error'));
            });
        }
    });
</script>
@endpush