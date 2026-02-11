@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    @if(Auth::user()->isManager())
        <div style="background-color: #fef3c7; border: 2px solid #f59e0b; padding: 10px 15px; border-radius: 6px; display: inline-block;">
            <strong>👔 Manager View:</strong> {{ Auth::user()->department }} Department
        </div>
    @endif
</div>

<!-- Statistics Cards -->
<div class="stats-grid stats-grid-5">
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

    <div class="stat-card stat-employees">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_employees'] }}</h3>
            <p>{{ Auth::user()->isManager() ? 'Team Members' : 'Employees' }}</p>
        </div>
    </div>
</div>

<!-- Leave Requests Table -->
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div>
                <h2 style="margin: 0;">
                    @if(Auth::user()->isManager())
                        {{ Auth::user()->department }} Department Leave Requests
                    @else
                        All Leave Requests
                    @endif
                </h2>
                @if(request()->hasAny(['search', 'status', 'leave_type', 'department', 'sort']))
                    <span style="font-size: 13px; color: var(--text-secondary); margin-top: 5px; display: block;">
                        Filtered Results
                    </span>
                @endif
            </div>
            
            <!-- Search Form in Header -->
            <div style="display: flex; gap: 10px; align-items: center;">
                <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 10px; margin: 0;">
                    <div style="position: relative;">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search employee or reason..."
                               style="padding: 10px 40px 10px 15px; border: 2px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 280px;">
                        <button type="submit" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Hidden fields to preserve filters -->
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('leave_type'))
                        <input type="hidden" name="leave_type" value="{{ request('leave_type') }}">
                    @endif
                    @if(request('department'))
                        <input type="hidden" name="department" value="{{ request('department') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </form>
                
                <!-- Advanced Filters Toggle Button -->
                <button onclick="toggleFilters()" class="btn btn-secondary" style="white-space: nowrap;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Filters
                </button>
            </div>
        </div>
    </div>
    
    <!-- Advanced Filters Section (Hidden by default) -->
    <div id="advancedFilters" style="display: none; border-top: 2px solid var(--border-color); background-color: #f8fafc;">
        <div style="padding: 20px;">
            <form action="{{ route('admin.dashboard') }}" method="GET">
                <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
                    <!-- Keep search value -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="leave_type">Leave Type</label>
                        <select id="leave_type" name="leave_type">
                            <option value="">All Types</option>
                            <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick</option>
                            <option value="vacation" {{ request('leave_type') == 'vacation' ? 'selected' : '' }}>Vacation</option>
                            <option value="personal" {{ request('leave_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="emergency" {{ request('leave_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="maternity" {{ request('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                            <option value="paternity" {{ request('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity</option>
                        </select>
                    </div>

                    @if(Auth::user()->isAdmin())
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="">All Departments</option>
                            <option value="Accounting" {{ request('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                            <option value="Creative" {{ request('department') == 'Creative' ? 'selected' : '' }}>Creative</option>
                            <option value="Engineering" {{ request('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Management" {{ request('department') == 'Management' ? 'selected' : '' }}>Management</option>
                            <option value="Merchandising" {{ request('department') == 'Merchandising' ? 'selected' : '' }}>Merchandising</option>
                            <option value="Planning" {{ request('department') == 'Planning' ? 'selected' : '' }}>Planning</option>
                            <option value="Purchasing" {{ request('department') == 'Purchasing' ? 'selected' : '' }}>Purchasing</option>
                            <option value="Research and Deployment" {{ request('department') == 'Research and Deployment' ? 'selected' : '' }}>Research and Deployment</option>
                            <option value="Sales" {{ request('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Visual" {{ request('department') == 'Visual' ? 'selected' : '' }}>Visual</option>
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="days_high" {{ request('sort') == 'days_high' ? 'selected' : '' }}>Most Days</option>
                            <option value="days_low" {{ request('sort') == 'days_low' ? 'selected' : '' }}>Least Days</option>
                            <option value="employee_az" {{ request('sort') == 'employee_az' ? 'selected' : '' }}>Employee (A-Z)</option>
                            <option value="employee_za" {{ request('sort') == 'employee_za' ? 'selected' : '' }}>Employee (Z-A)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card-body">
        @if($leaveRequests->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @if(Auth::user()->isAdmin())
                            <th>Department</th>
                            @endif
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                            <tr>
                                <td>
                                    <strong>{{ $request->user->name }}</strong><br>
                                    <small>{{ $request->user->position }}</small>
                                </td>
                                @if(Auth::user()->isAdmin())
                                <td>{{ $request->user->department }}</td>
                                @endif
                                <td>
                                    <span class="leave-type-badge {{ $request->leave_type }}">
                                        {{ ucfirst($request->leave_type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $request->start_date->format('M d') }} - 
                                    {{ $request->end_date->format('M d, Y') }}
                                </td>
                                <td>{{ $request->total_days }}</td>
                                <td>
                                    <span class="badge {{ $request->getStatusBadgeClass() }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('admin.leave.show', $request) }}" 
                                       class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $leaveRequests->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                @if(request()->hasAny(['search', 'status', 'leave_type', 'department']))
                    <p>🔍 No leave requests found matching your criteria.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        Clear Filters
                    </a>
                @else
                    <p>📭 No leave requests found.</p>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
function toggleFilters() {
    const filters = document.getElementById('advancedFilters');
    if (filters.style.display === 'none') {
        filters.style.display = 'block';
    } else {
        filters.style.display = 'none';
    }
}

// Auto-open filters if they are being used
@if(request()->hasAny(['status', 'leave_type', 'department', 'sort']))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('advancedFilters').style.display = 'block';
    });
@endif
</script>
@endsection