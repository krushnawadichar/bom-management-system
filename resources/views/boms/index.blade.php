{{-- resources/views/boms/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Bill of Materials')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Bill of Materials</h5>
        @can('upload-boms')
        <a href="{{ route('boms.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-upload"></i> Upload New BOM
        </a>
        @endcan
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->project_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('boms.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="boms-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>BOM Number</th>
                        <th>Revision</th>
                        <th>Project</th>
                        <th>File Name</th>
                        <th>Status</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boms as $bom)
                    <tr>
                        <td>{{ $bom->id }}</td>
                        <td>
                            <a href="{{ route('boms.show', $bom->id) }}" class="text-decoration-none">
                                {{ $bom->bom_number }}
                            </a>
                        </td>
                        <td>Rev. {{ $bom->revision }}</td>
                        <td>{{ $bom->project->project_name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($bom->file_name, 30) }}</td>
                        <td>
                            @if($bom->status == 'completed')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Completed</span>
                            @elseif($bom->status == 'processing')
                                <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Processing</span>
                            @elseif($bom->status == 'failed')
                                <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Failed</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td>{{ $bom->uploader->name ?? 'N/A' }}</td>
                        <td>{{ $bom->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('boms.show', $bom->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
              
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $boms->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#boms-table').DataTable({
            pageLength: 15,
            order: [[0, 'desc']],
            responsive: true
        });
    });
</script>
@endpush