@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="page-header">
    <h1>Leave Request Details</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.leave.download', $leaveRequest) }}" class="btn btn-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Download PDF
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
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
            <h2>Leave Request Information</h2>
            <p class="header-subtitle">Review and manage this leave request</p>
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
        <!-- Employee Section -->
        <div class="details-section">
            <div class="section-header-inline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <h3>Employee Information</h3>
            </div>
            <div class="info-grid-2">
                <div class="info-item-inline">
                    <label>Full Name</label>
                    <span>{{ $leaveRequest->user->name }}</span>
                </div>
                <div class="info-item-inline">
                    <label>Position</label>
                    <span>{{ $leaveRequest->user->position }}</span>
                </div>
                <div class="info-item-inline">
                    <label>Department</label>
                    <span>{{ $leaveRequest->user->department }}</span>
                </div>
                <div class="info-item-inline">
                    <label>Email</label>
                    <span>{{ $leaveRequest->user->email }}</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Leave Details Section -->
        <div class="details-section">
            <div class="section-header-inline">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <h3>Leave Details</h3>
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
                <div class="info-item-inline full-width">
                    <label>Reason for Leave</label>
                    <div class="reason-box-inline">
                        {{ $leaveRequest->reason }}
                    </div>
                </div>
                <div class="info-item-inline">
                    <label>Submitted On</label>
                    <span>{{ $leaveRequest->created_at->format('F d, Y • h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Review Information (if reviewed) -->
        @if($leaveRequest->status !== 'pending')
            <div class="divider"></div>
            <div class="details-section">
                <div class="section-header-inline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                    </svg>
                    <h3>Review Information</h3>
                </div>
                <div class="info-grid-2">
                    <div class="info-item-inline">
                        <label>Reviewed By</label>
                        <span>{{ $leaveRequest->reviewer ? $leaveRequest->reviewer->name : 'System' }}</span>
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

        <!-- Action Forms for Pending -->
        @if($leaveRequest->status === 'pending')
            <div class="divider"></div>
            
            <!-- Approve Section -->
            <div class="details-section">
                <div class="section-header-inline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <h3>Approve Request</h3>
                </div>
                <form action="{{ route('admin.leave.approve', $leaveRequest) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="approve_comment">Comment (Optional)</label>
                        <textarea name="admin_comment" id="approve_comment" rows="3" placeholder="Add a comment for the employee..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Are you sure you want to APPROVE this leave request?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Approve Request
                    </button>
                </form>
            </div>

            <div class="divider"></div>

            <!-- Reject Section -->
            <div class="details-section">
                <div class="section-header-inline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <h3>Reject Request</h3>
                </div>
                <form action="{{ route('admin.leave.reject', $leaveRequest) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="reject_comment">Reason for Rejection <span style="color: var(--danger-color);">*</span></label>
                        <textarea name="admin_comment" id="reject_comment" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                        <small>This will be visible to the employee</small>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to REJECT this leave request?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Reject Request
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection