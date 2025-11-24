@extends('admin.layouts.app')

@section('title', 'Student Management')
@section('page-title', 'Student Management')

@section('content')
    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Total Students</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-check" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Active Students</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $activeCount }}</h3>
                </div>
            </div>
        </div>

        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-slash" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Inactive Students</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $inactiveCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.management') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 16px; align-items: end;">
            <!-- Student Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Search for a student...</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for a student..." 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Teacher Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">All teachers</label>
                <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('teacher_id') == 'all' || !request('teacher_id') ? 'selected' : '' }}>All teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Course Type Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">All courses</label>
                <select name="course_type" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('course_type') == 'all' || !request('course_type') ? 'selected' : '' }}>All courses</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('course_type') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Status</label>
                <select name="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Suspended" {{ request('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <!-- Submit Button (hidden, auto-submit on change) -->
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <!-- Students Table -->
    <div class="card" style="background: white; border-radius: 12px; padding: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; min-width: 1400px; white-space: nowrap; table-layout: auto;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>NAME
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>TEACHER
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>COURSE
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>PHONE
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>PACK
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>PRICE OF A
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>THE REST
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>REMAINING MONEY (€)
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>FARE (€)
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>RECIPE PROF (€)
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>COURSES TAKEN
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>LAST ACTIVITY
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                            <i class="fas fa-sort" style="margin-right: 4px;"></i>STATUS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $activityStatus = $student->getActivityStatus();
                            $lastActivity = $student->getLastActivityDate();
                            $coursesTaken = $student->getCoursesTakenCount();
                            $remainingLessons = $student->getRemainingLessons();
                            $remainingMoney = $student->getRemainingMoney();
                            $fare = $student->getFare();
                            $teacherProfit = $student->getTeacherProfit();
                            $priceOfPack = $student->getPriceOfPack();
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 16px; white-space: nowrap;">
                                <a href="{{ route('admin.students.profile', $student->id) }}" style="color: #3b82f6; font-weight: 600; text-decoration: none; cursor: pointer;">
                                    {{ $student->name }}
                                </a>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <select class="inline-edit" data-field="teacher_id" data-student-id="{{ $student->id }}" 
                                        style="width: 100%; min-width: 150px; padding: 6px 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: white; cursor: pointer;">
                                    <option value="">No Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $student->teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px; white-space: nowrap;">
                                {{ $student->subject->name ?? 'Undefined' }}
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px; white-space: nowrap;">
                                {{ $student->phone ?? 'Undefined' }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="number" class="inline-edit" data-field="package_number" data-student-id="{{ $student->id }}" 
                                       value="{{ $student->package_number }}" min="1"
                                       style="width: 80px; padding: 6px 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; text-align: center;">
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                {{ number_format($priceOfPack, 2) }} €
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                {{ $remainingLessons }}
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                {{ number_format($remainingMoney, 2) }} €
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                {{ number_format($fare, 2) }} €
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                {{ number_format($teacherProfit, 2) }} €
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px; white-space: nowrap;">
                                {{ $coursesTaken }}
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 13px; white-space: nowrap;">
                                @if($lastActivity)
                                    {{ $lastActivity->format('m/d/Y') }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                @php
                                    $statusColors = [
                                        'Active' => '#10b981',
                                        'Inactive' => '#f59e0b',
                                        'Suspended' => '#ef4444',
                                        'Archived' => '#6b7280'
                                    ];
                                    $statusColor = $statusColors[$activityStatus] ?? '#64748b';
                                @endphp
                                <select class="inline-edit" data-field="payment_status_id" data-student-id="{{ $student->id }}" 
                                        style="width: 100%; padding: 6px 10px; border: 1px solid {{ $statusColor }}; border-radius: 6px; font-size: 12px; background: white; cursor: pointer; color: {{ $statusColor }}; font-weight: 600;">
                                    @foreach($paymentStatuses as $status)
                                        <option value="{{ $status->id }}" 
                                                {{ $student->payment_status_id == $status->id ? 'selected' : '' }}
                                                style="color: {{ $status->color }};">
                                            @if($status->name === 'EN ATTENTE DE PAYEMENT')
                                                Awaiting payment
                                            @elseif($status->name === 'PAYÉ')
                                                Active
                                            @elseif($status->name === 'SUSPENDU')
                                                Suspended
                                            @elseif($status->name === 'ARRÊTÉ')
                                                Archived
                                            @else
                                                {{ $status->display_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" style="padding: 40px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5; display: block;"></i>
                                <p style="font-size: 16px; font-weight: 600;">No students found</p>
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
        document.querySelectorAll('select[name="teacher_id"], select[name="course_type"], select[name="status"], input[name="search"]').forEach(function(element) {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
            element.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    this.closest('form').submit();
                }
            });
        });

        // Handle inline editing
        document.querySelectorAll('.inline-edit').forEach(function(element) {
            element.addEventListener('change', function() {
                const studentId = this.getAttribute('data-student-id');
                const field = this.getAttribute('data-field');
                const value = this.value;
                
                // Show loading state
                const originalValue = this.value;
                this.style.opacity = '0.5';
                this.disabled = true;

                fetch(`/admin/students/${studentId}/quick-update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        [field]: value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to update all calculated fields
                        location.reload();
                    } else {
                        alert('Error updating student: ' + (data.message || 'Unknown error'));
                        this.value = originalValue;
                    }
                    this.style.opacity = '1';
                    this.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating student');
                    this.value = originalValue;
                    this.style.opacity = '1';
                    this.disabled = false;
                });
            });
        });
    </script>
    @endsection
@endsection

