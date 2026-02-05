<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    // Remove the __construct method entirely
    // The 'auth' middleware is already applied in routes/web.php

    public function dashboard()
    {
        $user = Auth::user();
        
        // Use get() instead of calling the relationship as a method
        $leaveRequests = $user->leaveRequests()->latest()->paginate(10);
        
        $stats = [
            'total_requests' => $user->leaveRequests()->count(),
            'pending' => $user->leaveRequests()->where('status', 'pending')->count(),
            'approved' => $user->leaveRequests()->where('status', 'approved')->count(),
            'rejected' => $user->leaveRequests()->where('status', 'rejected')->count(),
        ];

        return view('employee.dashboard', compact('leaveRequests', 'stats'));
    }

    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:sick,vacation,personal,emergency,maternity,paternity',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('employee.dashboard')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Ensure employee can only view their own requests
        if ($leaveRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employee.show', compact('leaveRequest'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        // Ensure employee can only delete their own pending requests
        if ($leaveRequest->user_id !== Auth::id() || !$leaveRequest->isPending()) {
            abort(403);
        }

        $leaveRequest->delete();

        return redirect()->route('employee.dashboard')
            ->with('success', 'Leave request cancelled successfully!');
    }
    // REPLACE the downloadPdf() and generatePdfHtml() methods in BOTH AdminController.php and EmployeeController.php

// ============================================
// FOR EmployeeController.php
// Replace BOTH downloadPdf() and generatePdfHtml() methods
// ============================================

public function downloadPdf($id)
{
    $leaveRequest = LeaveRequest::with('user', 'reviewer')->findOrFail($id);
    
    // SECURITY: Ensure employee can only download their own requests
    if ($leaveRequest->user_id !== Auth::id()) {
        abort(403, 'Unauthorized access to this leave request.');
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
    
    // Set margins - smaller for full page
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
    $pdf->Output($filename, 'D'); // D = download
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