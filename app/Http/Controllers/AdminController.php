<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $query = LeaveRequest::with('user');
        
        // If manager, only show leave requests from their department
        if ($user->isManager()) {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('department', $user->department);
            });
        }
        
        // Search by employee name or reason
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhere('reason', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->whereHas('user', function($userQuery) use ($request) {
                $userQuery->where('department', $request->department);
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'days_high':
                $query->orderBy('total_days', 'desc');
                break;
            case 'days_low':
                $query->orderBy('total_days', 'asc');
                break;
            case 'employee_az':
            case 'employee_za':
                $query->with('user');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $leaveRequests = $query->paginate(15);
        
        // Sort by employee name if needed
        if (in_array($sort, ['employee_az', 'employee_za'])) {
            $sorted = $leaveRequests->sortBy(function($request) {
                return $request->user->name;
            });
            if ($sort === 'employee_za') {
                $sorted = $sorted->sortByDesc(function($request) {
                    return $request->user->name;
                });
            }
            $leaveRequests->setCollection($sorted->values());
        }
        
        // Stats - filtered by department for managers
        if ($user->isManager()) {
            $stats = [
                'total_requests' => LeaveRequest::whereHas('user', function($q) use ($user) {
                    $q->where('department', $user->department);
                })->count(),
                'pending' => LeaveRequest::where('status', 'pending')
                    ->whereHas('user', function($q) use ($user) {
                        $q->where('department', $user->department);
                    })->count(),
                'approved' => LeaveRequest::where('status', 'approved')
                    ->whereHas('user', function($q) use ($user) {
                        $q->where('department', $user->department);
                    })->count(),
                'rejected' => LeaveRequest::where('status', 'rejected')
                    ->whereHas('user', function($q) use ($user) {
                        $q->where('department', $user->department);
                    })->count(),
                'total_employees' => User::whereIn('role', ['employee', 'manager'])
                    ->where('department', $user->department)
                    ->count(),
            ];
        } else {
            // Admin sees all
            $stats = [
                'total_requests' => LeaveRequest::count(),
                'pending' => LeaveRequest::where('status', 'pending')->count(),
                'approved' => LeaveRequest::where('status', 'approved')->count(),
                'rejected' => LeaveRequest::where('status', 'rejected')->count(),
                'total_employees' => User::whereIn('role', ['employee', 'manager'])->count(),
            ];
        }

        return view('admin.dashboard', compact('leaveRequests', 'stats'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        
        // If manager, check if leave request is from their department
        if ($user->isManager() && $leaveRequest->user->department !== $user->department) {
            abort(403, 'You can only view leave requests from your department.');
        }
        
        $leaveRequest->load('user', 'reviewer');
        return view('admin.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        
        // If manager, check if leave request is from their department
        if ($user->isManager() && $leaveRequest->user->department !== $user->department) {
            abort(403, 'You can only approve leave requests from your department.');
        }
        
        $leaveRequest->update([
            'status' => 'approved',
            'admin_comment' => $request->input('admin_comment'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Leave request approved successfully!');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        
        // If manager, check if leave request is from their department
        if ($user->isManager() && $leaveRequest->user->department !== $user->department) {
            abort(403, 'You can only reject leave requests from your department.');
        }
        
        $request->validate([
            'admin_comment' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_comment' => $request->input('admin_comment'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Leave request rejected.');
    }

    public function employees(Request $request)
    {
        $user = Auth::user();
        $query = User::whereIn('role', ['employee', 'manager'])->withCount('leaveRequests');
        
        // If manager, only show users from their department
        if ($user->isManager()) {
            $query->where('department', $user->department);
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        
        // Sort
        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'requests_high':
                $query->orderBy('leave_requests_count', 'desc');
                break;
            case 'requests_low':
                $query->orderBy('leave_requests_count', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $employees = $query->paginate(15);

        return view('admin.employees', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.create-employee');
    }

    public function storeEmployee(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'role' => 'required|in:employee,manager',
        ]);
        
        // If manager, force department to be the same as theirs
        if ($user->isManager()) {
            $validated['department'] = $user->department;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department' => $validated['department'],
            'position' => $validated['position'],
            'role' => $validated['role'],
        ]);

        $roleLabel = $validated['role'] === 'manager' ? 'Manager' : 'Employee';

        return redirect()->route('admin.employees')
            ->with('success', $roleLabel . ' account created successfully!');
    }

    public function editEmployee(User $user)
    {
        $currentUser = Auth::user();
        
        // Prevent editing admin accounts
        if ($user->role === 'admin') {
            abort(403, 'Cannot edit admin accounts.');
        }
        
        // If manager, can only edit users from their department
        if ($currentUser->isManager() && $user->department !== $currentUser->department) {
            abort(403, 'You can only edit accounts from your department.');
        }

        return view('admin.edit-employee', compact('user'));
    }

    public function updateEmployee(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Prevent editing admin accounts
        if ($user->role === 'admin') {
            abort(403, 'Cannot edit admin accounts.');
        }
        
        // If manager, can only edit users from their department
        if ($currentUser->isManager() && $user->department !== $currentUser->department) {
            abort(403, 'You can only edit accounts from your department.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'role' => 'required|in:employee,manager',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // If manager, force department to be the same as theirs
        if ($currentUser->isManager()) {
            $validated['department'] = $currentUser->department;
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'position' => $validated['position'],
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.employees')
            ->with('success', 'Account information updated successfully!');
    }

    public function destroyEmployee(User $user)
    {
        $currentUser = Auth::user();
        
        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            abort(403, 'Cannot delete admin accounts.');
        }
        
        // If manager, can only delete users from their department
        if ($currentUser->isManager() && $user->department !== $currentUser->department) {
            abort(403, 'You can only delete accounts from your department.');
        }

        $user->delete();

        return redirect()->route('admin.employees')
            ->with('success', 'Account deleted successfully!');
    }

    public function downloadPdf($id)
    {
        $leaveRequest = LeaveRequest::with('user', 'reviewer')->findOrFail($id);
        $user = Auth::user();
        
        // If manager, check if leave request is from their department
        if ($user->isManager() && $leaveRequest->user->department !== $user->department) {
            abort(403, 'You can only download leave requests from your department.');
        }
        
        // Create new PDF document
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Leave Request System');
        $pdf->SetAuthor($leaveRequest->user->name);
        $pdf->SetTitle('Leave Request #' . $leaveRequest->id);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Generate HTML content
        $html = $this->generatePdfHtml($leaveRequest);
        
        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF document
        $filename = 'leave_request_' . $leaveRequest->id . '_' . str_replace(' ', '_', $leaveRequest->user->name) . '.pdf';
        $pdf->Output($filename, 'D');
    }

    private function generatePdfHtml($leaveRequest)
    {
        $statusColor = $leaveRequest->status === 'approved' ? '#10b981' : 
                       ($leaveRequest->status === 'rejected' ? '#ef4444' : '#f59e0b');
        
        $statusBg = $leaveRequest->status === 'approved' ? '#d1fae5' : 
                    ($leaveRequest->status === 'rejected' ? '#fee2e2' : '#fef3c7');
        
        $html = '
        <style>
            body { 
                font-family: helvetica; 
                font-size: 10px; 
                line-height: 1.4;
            }
            .container {
                border: 3px solid #8C1007;
                padding: 15px;
                min-height: 260mm;
            }
            .header { 
                text-align: center; 
                margin-bottom: 15px;
                border-bottom: 3px solid #8C1007;
                padding-bottom: 12px;
            }
            .logo { 
                font-size: 18px; 
                font-weight: bold; 
                color: #8C1007; 
                margin-bottom: 6px;
                letter-spacing: 1px;
            }
            .request-id { 
                font-size: 14px; 
                font-weight: bold; 
                margin-bottom: 6px;
                color: #3E0703;
            }
            .status-badge { 
                display: inline-block; 
                padding: 5px 18px; 
                background-color: ' . $statusBg . '; 
                color: ' . $statusColor . ';
                border: 2px solid ' . $statusColor . ';
                border-radius: 4px; 
                font-weight: bold;
                font-size: 11px;
                text-transform: uppercase;
            }
            .section { 
                margin-bottom: 20px;
                background-color: #f8fafc;
                padding: 12px;
                border-left: 4px solid #8C1007;
            }
            .section-title { 
                font-size: 12px; 
                font-weight: bold; 
                color: white;
                background-color: #8C1007;
                padding: 6px 10px;
                margin: -12px -12px 12px -12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 10px;
            }
            tr {
                border-bottom: 1px solid #e2e8f0;
            }
            td { 
                padding: 6px 8px; 
                vertical-align: top;
            }
            td.label { 
                width: 25%; 
                font-size: 9px; 
                color: #64748b; 
                text-transform: uppercase; 
                font-weight: bold;
            }
            td.value { 
                width: 25%; 
                font-size: 10px; 
                color: #3E0703;
                font-weight: 500;
            }
            .reason-box { 
                background-color: white; 
                border: 2px solid #8C1007; 
                padding: 10px; 
                margin-top: 5px;
                font-size: 10px;
                line-height: 1.5;
                border-radius: 4px;
            }
            .comment-box { 
                background-color: #fffbeb; 
                border: 2px solid #f59e0b; 
                padding: 10px; 
                margin-top: 5px;
                font-size: 10px;
                line-height: 1.5;
                border-radius: 4px;
            }
            .footer { 
                text-align: center; 
                font-size: 8px; 
                color: #64748b; 
                margin-top: 25px;
                padding-top: 12px;
                border-top: 2px solid #8C1007;
            }
            .highlight {
                color: #8C1007;
                font-weight: bold;
                font-size: 12px;
            }
        </style>
        
        <div class="container">
            <div class="header">
                <div class="logo">EMPLOYEE LEAVE REQUEST SYSTEM</div>
                <div class="request-id">Leave Request #' . $leaveRequest->id . '</div>
                <span class="status-badge">' . strtoupper($leaveRequest->status) . '</span>
            </div>
            
            <div class="section">
                <div class="section-title">EMPLOYEE INFORMATION</div>
                <table>
                    <tr>
                        <td class="label">Full Name</td>
                        <td class="value">' . htmlspecialchars($leaveRequest->user->name) . '</td>
                        <td class="label">Position</td>
                        <td class="value">' . htmlspecialchars($leaveRequest->user->position) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Department</td>
                        <td class="value">' . htmlspecialchars($leaveRequest->user->department) . '</td>
                        <td class="label">Email</td>
                        <td class="value">' . htmlspecialchars($leaveRequest->user->email) . '</td>
                    </tr>
                </table>
            </div>
            
            <div class="section">
                <div class="section-title">LEAVE DETAILS</div>
                <table>
                    <tr>
                        <td class="label">Leave Type</td>
                        <td class="value">' . strtoupper($leaveRequest->leave_type) . '</td>
                        <td class="label">Total Duration</td>
                        <td class="value"><span class="highlight">' . $leaveRequest->total_days . ' ' . ($leaveRequest->total_days > 1 ? 'DAYS' : 'DAY') . '</span></td>
                    </tr>
                    <tr>
                        <td class="label">Start Date</td>
                        <td class="value">' . $leaveRequest->start_date->format('M d, Y (l)') . '</td>
                        <td class="label">End Date</td>
                        <td class="value">' . $leaveRequest->end_date->format('M d, Y (l)') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Date Submitted</td>
                        <td class="value" colspan="3">' . $leaveRequest->created_at->format('M d, Y @ h:i A') . '</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td class="label" style="width: 25%;">Reason for Leave</td>
                        <td style="width: 75%;">
                            <div class="reason-box">' . nl2br(htmlspecialchars($leaveRequest->reason)) . '</div>
                        </td>
                    </tr>
                </table>
            </div>';
        
        if ($leaveRequest->status !== 'pending') {
            $html .= '
            <div class="section">
                <div class="section-title">REVIEW INFORMATION</div>
                <table>
                    <tr>
                        <td class="label">Reviewed By</td>
                        <td class="value">' . ($leaveRequest->reviewer ? htmlspecialchars($leaveRequest->reviewer->name) : 'System Administrator') . '</td>
                        <td class="label">Review Date</td>
                        <td class="value">' . ($leaveRequest->reviewed_at ? $leaveRequest->reviewed_at->format('M d, Y @ h:i A') : 'N/A') . '</td>
                    </tr>';
            
            if ($leaveRequest->admin_comment) {
                $html .= '
                    <tr>
                        <td class="label" style="width: 25%;">Admin Comment</td>
                        <td colspan="3" style="width: 75%;">
                            <div class="comment-box">' . nl2br(htmlspecialchars($leaveRequest->admin_comment)) . '</div>
                        </td>
                    </tr>';
            }
            
            $html .= '
                </table>
            </div>';
        }
        
        $html .= '
            <div class="footer">
                <strong>Document Generated:</strong> ' . now()->format('M d, Y @ h:i A') . '<br>
                Employee Leave Request System | Confidential Document
            </div>
        </div>';
        
        return $html;
    }
}