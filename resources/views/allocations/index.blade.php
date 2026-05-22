@extends('layouts.app')

@section('title', 'Material Allocations')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-truck"></i> Material Allocations</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="allocations-table">
                <thead>
                    <tr>
                        <th>Allocation #</th>
                        <th>Item Code</th>
                        <th>Description</th>
                        <th>Allocated Qty</th>
                        <th>Required Qty</th>
                        <th>Allocated To</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allocations as $allocation)
                    <tr>
                        <td><code>{{ $allocation->allocation_number }}</code></td>
                        <td>{{ $allocation->item_code }}</td>
                        <td>{{ Str::limit($allocation->item_description, 50) }}</td>
                        <td class="text-end">{{ number_format($allocation->allocated_quantity, 3) }}</td>
                        <td class="text-end">{{ number_format($allocation->original_required_quantity, 3) }}</td>
                        <td>{{ $allocation->allocated_to }}</td>
                        <td>{{ $allocation->allocated_at->format('Y-m-d H:i') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('allocations.show', $allocation->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-success acknowledge-btn" data-id="{{ $allocation->id }}">
                                <i class="fas fa-check"></i> Acknowledge
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No material allocations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $allocations->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.acknowledge-btn').click(function() {
            const id = $(this).data('id');
            const btn = $(this);
            
            $.post('/allocations/' + id + '/acknowledge', {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                if (response.success) {
                    btn.prop('disabled', true).text('Acknowledged');
                    btn.removeClass('btn-success').addClass('btn-secondary');
                    alert('Allocation acknowledged');
                }
            }).fail(function(xhr) {
                alert('Failed to acknowledge: ' + (xhr.responseJSON?.message || 'Unknown error'));
            });
        });
    });
</script>
@endpush