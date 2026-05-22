@extends('layouts.app')

@section('title', 'Upload BOM')

@section('content')
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-upload"></i> Upload Bill of Materials</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('boms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                      <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project *</label>
                        <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_code }} - {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bom_file" class="form-label">BOM File *</label>
                        <input type="file" name="bom_file" id="bom_file" class="form-control @error('bom_file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Accepted formats: Excel (.xlsx, .xls) or CSV. Max size: 10MB.
                            The file should contain columns: Part No., Description, Quantity, etc.
                        </div>
                        @error('bom_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>
                    
                  
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="revision" class="form-label">Revision</label>
                                <input type="text" name="revision" id="revision" class="form-control" value="{{ old('revision', '00') }}" placeholder="e.g., 00, 01">
                                <div class="form-text">Optional BOM revision number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="original_bom_number" class="form-label">Original BOM Number</label>
                                <input type="text" name="original_bom_number" id="original_bom_number" class="form-control" value="{{ old('original_bom_number') }}" placeholder="e.g., P23.1.3.02914">
                                <div class="form-text">Optional reference to original BOM document</div>
                            </div>
                        </div>
                    </div>
                    
                     <div class="mt-4">
            <div class="">
                <h6 class="mb-0"><i class="fas fa-download"></i> Sample BOM Template</h6>
            </div>
            <div class="">
                <p>Download a sample BOM template to understand the expected format:</p>
                <a href="{{ asset('samples/sample-bom-template.xlsx') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-download"></i> Download Sample Template
                </a>
            </div>
        </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('boms.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload BOM
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File validation on client side
    document.getElementById('bom_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls', 'csv'].includes(ext)) {
                alert('Invalid file type. Please upload Excel or CSV file.');
                this.value = '';
            }
            if (file.size > 10 * 1024 * 1024) {
                alert('File size exceeds 10MB limit.');
                this.value = '';
            }
        }
    });
</script>
@endpush