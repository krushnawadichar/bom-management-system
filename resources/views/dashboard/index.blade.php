@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total BOMs Uploaded</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBoms }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Intents</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingIntents }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Allocations Made</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAllocations }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Low Stock Items</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockItems }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">BOM Uploads (Last 6 Months)</h6>
            </div>
            <div class="card-body">
                <canvas id="bomUploadsChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Intent Status Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="intentStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent BOM Uploads</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>BOM Number</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBoms as $bom)
                            <tr>
                                <td><a href="{{ route('boms.show', $bom->id) }}">{{ $bom->bom_number }}</a></td>
                                <td>{{ $bom->project->project_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $bom->status === 'completed' ? 'success' : ($bom->status === 'processing' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($bom->status) }}
                                    </span>
                                </td>
                                <td>{{ $bom->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('boms.index') }}" class="btn btn-sm btn-primary">View All BOMs</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Purchase Intents</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Intent #</th>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentIntents as $intent)
                            <tr>
                                <td><a href="{{ route('purchase-intents.show', $intent->id) }}">{{ $intent->intent_number }}</a></td>
                                <td>{{ Str::limit($intent->item_description, 30) }}</td>
                                <td>
                                    <span class="badge bg-{{ $intent->status === 'pending' ? 'warning' : ($intent->status === 'acknowledged' ? 'info' : 'success') }}">
                                        {{ ucfirst($intent->status) }}
                                    </span>
                                </td>
                                <td>{{ $intent->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('purchase-intents.index') }}" class="btn btn-sm btn-primary">View All Intents</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // BOM Uploads Chart
    const bomCtx = document.getElementById('bomUploadsChart').getContext('2d');
    const bomData = @json($bomUploadsByMonth);
    
    new Chart(bomCtx, {
        type: 'line',
        data: {
            labels: bomData.map(item => item.month),
            datasets: [{
                label: 'BOM Uploads',
                data: bomData.map(item => item.count),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    
    // Intent Status Chart
    const intentCtx = document.getElementById('intentStatusChart').getContext('2d');
    const intentData = @json($intentStatusDistribution);
    
    new Chart(intentCtx, {
        type: 'doughnut',
        data: {
            labels: intentData.map(item => item.status),
            datasets: [{
                data: intentData.map(item => item.count),
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush