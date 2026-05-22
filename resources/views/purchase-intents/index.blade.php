{{-- resources/views/purchase-intents/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Purchase Intents')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Purchase Intents</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                        <option value="po_raised" {{ request('status') == 'po_raised' ? 'selected' : '' }}>PO Raised</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="batch_number" class="form-control" placeholder="Batch Number" value="{{ request('batch_number') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('purchase-intents.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        
        @if(request('batch_number'))
        <div class="mb-3">
            <form method="POST" action="{{ route('purchase-intents.batch-acknowledge') }}" class="d-inline">
                @csrf
                <input type="hidden" name="batch_number" value="{{ request('batch_number') }}">
                <button type="submit" class="btn btn-warning" onclick="return confirm('Acknowledge all intents in this batch?')">
                    <i class="fas fa-check-double"></i> Acknowledge All in Batch
                </button>
            </form>
        </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Intent #</th>
                        <th>Batch #</th>
                        <th>Item Code</th>
                        <th>Description</th>
                        <th>Required</th>
                        <th>Available</th>
                        <th>Shortfall</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($intents as $intent)
                    <tr>
                        <td><code>{{ $intent->intent_number }}</code></td>
                        <td>{{ $intent->batch_number }}</td>
                        <td>{{ $intent->item_code ?? 'N/A' }}</td>
                        <td>{{ Str::limit($intent->item_description, 40) }}</td>
                        <td class="text-end">{{ number_format($intent->required_quantity, 3) }}</td>
                        <td class="text-end">{{ number_format($intent->available_quantity, 3) }}</td>
                        <td class="text-end text-danger">{{ number_format($intent->shortfall_quantity, 3) }}</td>
                        <td>
                            @if($intent->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($intent->status == 'acknowledged')
                                <span class="badge bg-info">Acknowledged</span>
                            @else
                                <span class="badge bg-success">PO Raised</span>
                            @endif
                        </td>
                        <td>{{ $intent->created_at->format('Y-m-d') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('purchase-intents.show', $intent->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($intent->status == 'pending')
                            <button class="btn btn-sm btn-success acknowledge-btn" data-id="{{ $intent->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No purchase intents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3 d-flex justify-content-end">
            {{ $intents->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- PO Raised Modal -->
<div class="modal fade" id="poModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark PO as Raised</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="po_intent_id">
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
    $(document).ready(function() {
        let currentIntentId = null;
        
        $('.acknowledge-btn').click(function() {
            const id = $(this).data('id');
            const btn = $(this);
            
            if(confirm('Acknowledge this purchase intent?')) {
                $.post('/purchase-intents/' + id + '/acknowledge', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    if (response.success) {
                        btn.prop('disabled', true);
                        btn.closest('tr').find('.badge').removeClass('bg-warning').addClass('bg-info').text('Acknowledged');
                        alert('Intent acknowledged successfully');
                    }
                }).fail(function(xhr) {
                    alert('Failed to acknowledge: ' + (xhr.responseJSON?.message || 'Unknown error'));
                });
            }
        });
        
        // PO Modal handling
        $('.po-btn').click(function() {
            currentIntentId = $(this).data('id');
            $('#po_intent_id').val(currentIntentId);
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
                    $('#poModal').modal('hide');
                    location.reload();
                }
            }).fail(function(xhr) {
                alert('Failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
            });
        });
    });
</script>
@endpush