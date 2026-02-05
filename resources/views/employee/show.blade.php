@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="page-header">
    <h1>Leave Request Details</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('employee.leave.download', $leaveRequest) }}" class="btn btn-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Download PDF
        </a>
        <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Single Card with All Details -->
<div class="details-card-single">
    <div class="details-card-header-single">
        <div>
            <h2>Request #{{ $leaveRequest->id }}</h2>
            <p class="header-subtitle">Your leave request details</p>
        </div>
        <span class="badge-large badge-{{ $leaveRequest->status }}">
            @if($leaveRequest->status === 'approved')
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            @elseif($leaveRequest->status === 'rejected')
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            @endif
            {{ ucfirst($leaveRequest->status) }}
        </span>
    </div>

    <div class="details-card-body-single">
        <!-- Leave Details Section -->
        <div class="details-section">
            <div class="section-header-inline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <h3>Leave Information</h3>
            </div>
            <div class="info-grid-2">
                <div class="info-item-inline">
                    <label>Leave Type</label>
                    <span>
                        <span class="leave-type-badge {{ $leaveRequest->leave_type }}">
                            {{ ucfirst($leaveRequest->leave_type) }}
                        </span>
                    </span>
                </div>
                <div class="info-item-inline">
                    <label>Duration</label>
                    <span class="duration-highlight">{{ $leaveRequest->total_days }} {{ $leaveRequest->total_days > 1 ? 'Days' : 'Day' }}</span>
                </div>
                <div class="info-item-inline">
                    <label>Start Date</label>
                    <span>{{ $leaveRequest->start_date->format('F d, Y') }}</span>
                </div>
                <div class="info-item-inline">
                    <label>End Date</label>
                    <span>{{ $leaveRequest->end_date->format('F d, Y') }}</span>
                </div>
                <div class="info-item-inline">
                    <label>Submitted On</label>
                    <span>{{ $leaveRequest->created_at->format('F d, Y • h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Reason Section -->
        <div class="details-section">
            <div class="section-header-inline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                </svg>
                <h3>Reason</h3>
            </div>
            <div class="reason-box-inline">
                {{ $leaveRequest->reason }}
            </div>
        </div>

        <!-- Review Information (if reviewed) -->
        @if($leaveRequest->status !== 'pending')
            <div class="divider"></div>
            <div class="details-section">
                <div class="section-header-inline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    <h3>Review Information</h3>
                </div>
                <div class="info-grid-2">
                    <div class="info-item-inline">
                        <label>Reviewed By</label>
                        <span>{{ $leaveRequest->reviewer ? $leaveRequest->reviewer->name : 'Admin' }}</span>
                    </div>
                    <div class="info-item-inline">
                        <label>Reviewed On</label>
                        <span>{{ $leaveRequest->reviewed_at ? $leaveRequest->reviewed_at->format('F d, Y • h:i A') : 'N/A' }}</span>
                    </div>
                    @if($leaveRequest->admin_comment)
                        <div class="info-item-inline full-width">
                            <label>Admin Comment</label>
                            <div class="comment-box-inline">
                                {{ $leaveRequest->admin_comment }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Cancel Button for Pending Requests -->
        @if($leaveRequest->status === 'pending')
            <div class="divider"></div>
            <div class="cancel-section">
                <form action="{{ route('employee.leave.destroy', $leaveRequest) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure you want to CANCEL this leave request? This action cannot be undone.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Cancel Request
                    </button>
                </form>
                <p class="cancel-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    You can only cancel pending requests. Once reviewed, the request cannot be cancelled.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection