@extends('admin.layouts.app')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@section('content')
    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
        <div class="card" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <p style="color: #64748b; font-size: 14px; font-weight: 600; margin: 0 0 8px 0;">Total pending</p>
            <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalPending }}</h3>
        </div>
        <div class="card" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <p style="color: #64748b; font-size: 14px; font-weight: 600; margin: 0 0 8px 0;">Total amount due</p>
            <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 0;">{{ number_format($totalAmountDue, 0) }} €</h3>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.payment') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 16px; align-items: end;">
            <!-- Student Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Search for a student...</label>
                <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Search for a student..." 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Teacher Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Teacher</label>
                <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="">All teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pack Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Pack</label>
                <select name="pack" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('pack') == 'all' || !request('pack') ? 'selected' : '' }}>All packs</option>
                    @foreach($packs as $pack)
                        <option value="{{ $pack }}" {{ request('pack') == $pack ? 'selected' : '' }}>
                            {{ $pack }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Reminder Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Reminder</label>
                <select name="reminder" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('reminder') == 'all' || !request('reminder') ? 'selected' : '' }}>All reminders</option>
                    <option value="nothing" {{ request('reminder') == 'nothing' ? 'selected' : '' }}>Nothing</option>
                </select>
            </div>

            <!-- Submit Button (hidden, auto-submit on change) -->
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <!-- Payment Table -->
    <div class="card" style="background: white; border-radius: 12px; padding: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                <thead>
                    <tr style="background: #10b981; color: white;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Student Name</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Teacher</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Phone</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Pack</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Price (€)</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Payment Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Reminder</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Last action</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $lastCourse = $student->courses->first();
                            // Only count courses with name "0.0" (reached package limit, need payment)
                            $coursesBeyondLimit = $student->courses->filter(function($course) {
                                return $course->name === '0.0';
                            });
                            $totalPrice = $coursesBeyondLimit->sum('income');
                        @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; white-space: nowrap;">{{ $student->name }}</td>
                            <td style="padding: 16px; color: #64748b; white-space: nowrap;">{{ $student->teacher->name ?? 'N/A' }}</td>
                            <td style="padding: 16px; color: #64748b; white-space: nowrap;">{{ $student->phone }}</td>
                            <td style="padding: 16px; color: #64748b; white-space: nowrap;">{{ $student->package_number }}</td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; white-space: nowrap;">{{ number_format($totalPrice, 0) }} €</td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <select class="payment-status-select" data-student-id="{{ $student->id }}" 
                                        style="width: 100%; min-width: 150px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: white; cursor: pointer;">
                                    @foreach($paymentStatuses as $status)
                                        <option value="{{ $status->id }}" 
                                                {{ $student->payment_status_id == $status->id ? 'selected' : '' }}>
                                            @if($status->name === 'EN ATTENTE DE PAYEMENT')
                                                Awaiting payment
                                            @elseif($status->name === 'PAYÉ')
                                                Active
                                            @elseif($status->name === 'SUSPENDU')
                                                Suspended
                                            @elseif($status->name === 'ARRÊTÉ')
                                                Arrested
                                            @else
                                                {{ $status->display_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <select style="width: 100%; min-width: 120px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: white;">
                                    <option>Nothing</option>
                                </select>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 13px; white-space: nowrap;">
                                @if($lastCourse)
                                    {{ $lastCourse->course_date->format('m/d/Y') }} {{ $lastCourse->class_time ? $lastCourse->class_time->format('g:i A') : '' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="text" class="student-notes" data-student-id="{{ $student->id }}" 
                                       value="" 
                                       placeholder="Add notes..." 
                                       style="width: 100%; min-width: 150px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5; display: block;"></i>
                                <p style="font-size: 16px; font-weight: 600;">No students with pending payments</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
    <script>
        // Auto-submit form on filter change
        document.querySelectorAll('select[name="teacher_id"], select[name="pack"], select[name="reminder"], input[name="student_search"]').forEach(function(element) {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Handle payment status update
        document.querySelectorAll('.payment-status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const studentId = this.getAttribute('data-student-id');
                const statusId = this.value;
                
                fetch(`/admin/students/${studentId}/payment-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_status_id: statusId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message or reload page
                        location.reload();
                    } else {
                        alert('Error updating payment status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating payment status');
                });
            });
        });

        // Handle notes update (optional - can be implemented with debounce)
        document.querySelectorAll('.student-notes').forEach(function(input) {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const studentId = this.getAttribute('data-student-id');
                const notes = this.value;
                
                timeout = setTimeout(function() {
                    // Implement notes update API if needed
                    // fetch(`/admin/students/${studentId}/notes`, { ... })
                }, 1000);
            });
        });
    </script>
    @endsection
@endsection

