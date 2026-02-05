@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="page-header">
    <h1>My Leave Requests</h1>
    <a href="{{ route('employee.leave.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        New Request
    </a>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_requests'] }}</h3>
            <p>Total Requests</p>
        </div>
    </div>

    <div class="stat-card stat-pending">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Pending</p>
        </div>
    </div>

    <div class="stat-card stat-approved">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['approved'] }}</h3>
            <p>Approved</p>
        </div>
    </div>

    <div class="stat-card stat-rejected">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['rejected'] }}</h3>
            <p>Rejected</p>
        </div>
    </div>
</div>

<!-- Leave Requests Table -->
<div class="card">
    <div class="card-header">
        <h2>All Requests</h2>
    </div>
    <div class="card-body">
        @if($leaveRequests->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                            <tr>
                                <td>
                                    <span class="leave-type-badge {{ $request->leave_type }}">
                                        {{ ucfirst($request->leave_type) }}
                                    </span>
                                </td>
                                <td>{{ $request->start_date->format('M d, Y') }}</td>
                                <td>{{ $request->end_date->format('M d, Y') }}</td>
                                <td>{{ $request->total_days }} day(s)</td>
                                <td>
                                    <span class="badge {{ $request->getStatusBadgeClass() }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('employee.leave.show', $request) }}" 
                                       class="btn btn-sm btn-info">View</a>
                                    
                                    @if($request->isPending())
                                        <form action="{{ route('employee.leave.destroy', $request) }}" 
                                              method="POST" style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $leaveRequests->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 20px;">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>No leave requests found.</p>
                <a href="{{ route('employee.leave.create') }}" class="btn btn-primary">
                    Create Your First Request
                </a>
            </div>
        @endif
    </div>
</div>
@endsection