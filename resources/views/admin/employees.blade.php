@extends('layouts.app')

@section('title', 'Employees List')

@section('content')
<div class="page-header">
    <h1>Employees</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            Add New Employee
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Employees Table Card -->
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div>
                <h2 style="margin: 0;">All Employees ({{ $employees->total() }})</h2>
                @if(request()->hasAny(['search', 'department', 'sort']))
                    <span style="font-size: 13px; color: var(--text-secondary); margin-top: 5px; display: block;">
                        Filtered Results
                    </span>
                @endif
            </div>
            
            <!-- Search Form in Header -->
            <div style="display: flex; gap: 10px; align-items: center;">
                <form action="{{ route('admin.employees') }}" method="GET" style="display: flex; gap: 10px; margin: 0;">
                    <div style="position: relative;">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search employees..."
                               style="padding: 10px 40px 10px 15px; border: 2px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 280px;">
                        <button type="submit" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Hidden fields to preserve filters -->
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
            <form action="{{ route('admin.employees') }}" method="GET">
                <div class="form-row">
                    <!-- Keep search value -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="">All Departments</option>
                            <option value="Accounting" {{ request('department') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                            <option value="Creative" {{ request('department') == 'Creative' ? 'selected' : '' }}>Creative</option>
                            <option value="Engineering" {{ request('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Merchandising" {{ request('department') == 'Merchandising' ? 'selected' : '' }}>Merchandising</option>
                            <option value="Planning" {{ request('department') == 'Planning' ? 'selected' : '' }}>Planning</option>
                            <option value="Purchasing" {{ request('department') == 'Purchasing' ? 'selected' : '' }}>Purchasing</option>
                            <option value="Research and Deployment" {{ request('department') == 'Research and Deployment' ? 'selected' : '' }}>Research and Deployment</option>
                            <option value="Sales" {{ request('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Visual" {{ request('department') == 'Visual' ? 'selected' : '' }}>Visual</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="requests_high" {{ request('sort') == 'requests_high' ? 'selected' : '' }}>Most Requests</option>
                            <option value="requests_low" {{ request('sort') == 'requests_low' ? 'selected' : '' }}>Least Requests</option>
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
                    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
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
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Total Requests</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td><strong>{{ $employee->name }}</strong></td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->department }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $employee->leave_requests_count }} {{ $employee->leave_requests_count == 1 ? 'request' : 'requests' }}
                                    </span>
                                </td>
                                <td>{{ $employee->created_at->format('M d, Y') }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" 
                                       class="btn btn-sm btn-info">Edit</a>
                                    
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete {{ $employee->name }}? All their leave requests will also be deleted.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                @if(request()->hasAny(['search', 'department']))
                    <p>🔍 No employees found matching your search criteria.</p>
                    <a href="{{ route('admin.employees') }}" class="btn btn-secondary">
                        Clear Filters
                    </a>
                @else
                    <p>👥 No employees found.</p>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                        Add First Employee
                    </a>
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
@if(request()->hasAny(['department', 'sort']))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('advancedFilters').style.display = 'block';
    });
@endif
</script>
@endsection