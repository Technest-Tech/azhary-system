@extends('admin.layouts.app')

@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard_overview'))

@section('content')
    <!-- Top Metrics Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        <!-- Total Students Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #a855f7;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('admin.total_students') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Hours Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('admin.total_hours') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($totalHours, 1) }}</h3>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-dollar-sign" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('admin.monthly_revenue') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($monthlyRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Management Section -->
    <div class="card" style="background: white; border-radius: 16px; padding: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
        <!-- Search and Filters -->
        <div style="background: #f8fafc; padding: 24px; border-bottom: 1px solid #e2e8f0;">
            <form method="GET" action="{{ route('admin.dashboard') }}" style="display: grid; grid-template-columns: 1fr 1fr 2fr 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
                <!-- Teacher Filter (First) -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.teacher') }}</label>
                    <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">{{ __('admin.all_teachers') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Student Filter (Second) -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.students') }}</label>
                    <select name="student_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">{{ __('admin.all_students') }}</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Search -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.status') }}</label>
                    <select name="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>{{ __('admin.all_statuses') }}</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>{{ __('admin.present') }}</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>{{ __('admin.absent') }}</option>
                        <option value="Free" {{ request('status') == 'Free' ? 'selected' : '' }}>{{ __('admin.free') }}</option>
                    </select>
                </div>
                
                <!-- Per Page Selector -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.show') }}</label>
                    <select name="per_page" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                
                <!-- Date picker -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.date') }}</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                
                <!-- Submit button -->
                <div>
                    <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; height: fit-content;">
                        <i class="fas fa-search"></i> {{ __('admin.filter') }}
                    </button>
                </div>
            </form>
            
            <!-- Active Filter Tags -->
            @if(request('teacher_id') || request('student_id') || request('status') && request('status') != 'all' || request('date') || request('search'))
                <div style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 8px;">
                    @if(request('teacher_id'))
                        @php $selectedTeacher = $teachers->firstWhere('id', request('teacher_id')); @endphp
                        @if($selectedTeacher)
                            <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 6px; font-size: 14px; font-weight: 500;">
                                {{ $selectedTeacher->name }}
                                <a href="{{ route('admin.dashboard', array_merge(request()->except(['teacher_id', 'page']))) }}" style="color: white; text-decoration: none; margin-left: 4px;">×</a>
                            </span>
                        @endif
                    @endif
                    @if(request('student_id'))
                        @php $selectedStudent = $students->firstWhere('id', request('student_id')); @endphp
                        @if($selectedStudent)
                            <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 6px; font-size: 14px; font-weight: 500;">
                                {{ $selectedStudent->name }}
                                <a href="{{ route('admin.dashboard', array_merge(request()->except(['student_id', 'page']))) }}" style="color: white; text-decoration: none; margin-left: 4px;">×</a>
                            </span>
                        @endif
                    @endif
                    @if(request('status') && request('status') != 'all')
                        <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 6px; font-size: 14px; font-weight: 500;">
                            {{ request('status') }}
                            <a href="{{ route('admin.dashboard', array_merge(request()->except(['status', 'page']))) }}" style="color: white; text-decoration: none; margin-left: 4px;">×</a>
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Table Header -->
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                {{ __('admin.courses_management') }}: {{ $courses->total() }}
            </h3>
        </div>

        <!-- Course Table -->
        <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">
                            <input type="checkbox" style="cursor: pointer;">
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">No.</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Round</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.name') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.course') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.date') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.status') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.duration') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.homework') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.evaluation') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.content') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.notes') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Completed</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                        @php
                            // Calculate completion percentage based on n_value (cumulative hours) vs package hours
                            $completionPercentage = 0;
                            if ($course->student && $course->student->package_number > 0) {
                                // Use n_value which represents cumulative hours up to this course
                                // Calculate percentage: (cumulative hours / package hours) * 100
                                $completionPercentage = min(100, ($course->n_value / $course->student->package_number) * 100);
                            }
                            
                            // Use student's fixed color
                            $nameColor = $course->student && $course->student->teacher_id 
                                ? ($course->student->display_color ?? '#64748b') 
                                : '#64748b';
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" 
                            onmouseover="this.style.background='#f8fafc'" 
                            onmouseout="this.style.background='white'">
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="checkbox" style="cursor: pointer;">
                            </td>
                            <td style="padding: 16px; white-space: nowrap; font-weight: 600; color: #1e293b;">
                                {{ number_format($course->n_value, 2) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span style="font-weight: 600; color: #3b82f6;">R{{ $course->round ?? 1 }}</span>
                                    @php
                                        $paymentBadge = $course->getPaymentStatusBadge();
                                    @endphp
                                    <span style="display: inline-block; padding: 4px 10px; background: {{ $paymentBadge['bg_color'] }}; color: {{ $paymentBadge['text_color'] }}; border-radius: 12px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                                        {{ $paymentBadge['label'] }}
                                    </span>
                                    <button onclick="showRoundsModal({{ $course->student_id }})" 
                                            style="padding: 4px 8px; background: #e0f2fe; color: #0369a1; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 600;"
                                            title="View all rounds">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <span style="color: {{ $nameColor }}; font-weight: 600;">{{ $course->student->name ?? $course->student_name ?? 'N/A' }}</span>
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                {{ $course->course_type ?? '-' }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                @if($course->course_date)
                                    {{ $course->course_date->format('d/m/Y') }}
                                    @if($course->class_time)
                                        {{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                @if($course->status === 'Present')
                                    <span style="display: inline-block; padding: 6px 12px; background: #6ee7b7; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('admin.present') }}</span>
                                @elseif($course->status === 'Absent')
                                    <span style="display: inline-block; padding: 6px 12px; background: #fca5a5; color: #991b1b; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('admin.absent') }}</span>
                                @elseif($course->status === 'Free')
                                    <span style="display: inline-block; padding: 6px 12px; background: #e5e7eb; color: #374151; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('admin.free') }}</span>
                                @else
                                    <span style="display: inline-block; padding: 6px 12px; background: #cbd5e1; color: #475569; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $course->status ?? '-' }}</span>
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                {{ $course->duration_hours ?? 0 }}h {{ $course->duration_minutes ?? 0 }}m
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                @if($course->homework)
                                    <i class="fas fa-check" style="color: #34d399;"></i>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                @if($course->evaluation)
                                    {{ $course->evaluation->max_percentage ?? 0 }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b; max-width: 200px;">
                                {{ Str::limit($course->content ?? '-', 20) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b; max-width: 200px;">
                                {{ Str::limit($course->notes ?? '-', 20) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 100px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: {{ $completionPercentage }}%; height: 100%; background: #14b8a6; transition: width 0.3s;"></div>
                                    </div>
                                    <span style="font-size: 12px; font-weight: 600; color: #1e293b;">{{ number_format($completionPercentage, 1) }}%</span>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.courses.edit', $course) }}" 
                                       style="padding: 8px; color: #f59e0b; text-decoration: none; border-radius: 4px;"
                                       onmouseover="this.style.background='#fef3c7'" 
                                       onmouseout="this.style.background='transparent'"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display: inline;" 
                                          onsubmit="return confirm('{{ __('admin.delete_course_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="padding: 8px; color: #ef4444; background: none; border: none; cursor: pointer; border-radius: 4px;"
                                                onmouseover="this.style.background='#fee2e2'" 
                                                onmouseout="this.style.background='transparent'"
                                                title="Delete">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" style="padding: 40px; text-align: center; color: #64748b;">
                                <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>{{ __('admin.no_courses_found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-start; align-items: center;">
            <div style="display: flex; gap: 12px;">
                @if($courses->previousPageUrl())
                    <a href="{{ $courses->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
                       style="padding: 10px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        {{ __('admin.previous') }}
                    </a>
                @endif
                @if($courses->nextPageUrl())
                    <a href="{{ $courses->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
                       style="padding: 10px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        {{ __('admin.next') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Fixed Add Course Button -->
    <a href="{{ route('admin.courses.create') }}" 
       style="position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); z-index: 1000; transition: all 0.3s;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'">
        <i class="fas fa-plus"></i> {{ __('admin.add_course') }}
    </a>

    <!-- Rounds Modal -->
    <div id="roundsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
        <div style="background: white; margin: 50px auto; max-width: 900px; border-radius: 12px; padding: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #1e293b;" id="roundsModalTitle">Package Rounds</h2>
                <button onclick="closeRoundsModal()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
            <div id="roundsModalContent" style="max-height: 600px; overflow-y: auto;">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function showRoundsModal(studentId) {
            const modal = document.getElementById('roundsModal');
            const content = document.getElementById('roundsModalContent');
            const title = document.getElementById('roundsModalTitle');
            
            modal.style.display = 'flex';
            content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #3b82f6;"></i><p style="margin-top: 16px; color: #64748b;">Loading rounds...</p></div>';
            
            fetch(`/admin/students/${studentId}/rounds`)
                .then(response => response.json())
                .then(data => {
                    title.textContent = `Package Rounds - ${data.student.name}`;
                    
                    if (data.rounds.length === 0) {
                        content.innerHTML = '<p style="text-align: center; color: #64748b; padding: 40px;">No rounds found.</p>';
                        return;
                    }
                    
                    let html = '<div style="display: flex; flex-direction: column; gap: 24px;">';
                    
                    data.rounds.forEach(roundData => {
                        const isCurrentRound = roundData.round === Math.max(...data.rounds.map(r => r.round));
                        html += `
                            <div style="border: 2px solid ${isCurrentRound ? '#3b82f6' : '#e2e8f0'}; border-radius: 8px; padding: 16px; background: ${isCurrentRound ? '#eff6ff' : '#ffffff'};">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">
                                        Round ${roundData.round} ${isCurrentRound ? '<span style="background: #3b82f6; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 8px;">Current</span>' : ''}
                                    </h3>
                                    <span style="color: #64748b; font-size: 14px;">${roundData.courses_count} courses</span>
                                </div>
                                <div style="color: #64748b; font-size: 12px; margin-bottom: 12px;">
                                    ${roundData.start_date ? new Date(roundData.start_date).toLocaleDateString() : 'N/A'} - ${roundData.end_date ? new Date(roundData.end_date).toLocaleDateString() : 'N/A'}
                                </div>
                                <div style="overflow-x: auto;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="background: #f8fafc;">
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">Date</th>
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">Time</th>
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">Type</th>
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">Duration</th>
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">Status</th>
                                                <th style="padding: 8px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b;">N Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        `;
                        
                        roundData.courses.forEach(course => {
                            html += `
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 8px; font-size: 12px; color: #1e293b;">${course.course_date ? new Date(course.course_date).toLocaleDateString() : 'N/A'}</td>
                                    <td style="padding: 8px; font-size: 12px; color: #64748b;">${course.class_time || 'N/A'}</td>
                                    <td style="padding: 8px; font-size: 12px; color: #64748b;">${course.course_type || '-'}</td>
                                    <td style="padding: 8px; font-size: 12px; color: #64748b;">${course.duration_hours}h ${course.duration_minutes}m</td>
                                    <td style="padding: 8px; font-size: 12px;">
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: ${course.status === 'Present' ? '#6ee7b7' : course.status === 'Absent' ? '#fca5a5' : course.status === 'Free' ? '#e5e7eb' : '#cbd5e1'}; color: ${course.status === 'Present' ? '#065f46' : course.status === 'Absent' ? '#991b1b' : course.status === 'Free' ? '#374151' : '#475569'};">${course.status}</span>
                                    </td>
                                    <td style="padding: 8px; font-size: 12px; color: #1e293b; font-weight: 600;">${parseFloat(course.n_value).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        
                        html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    content.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading rounds:', error);
                    content.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 40px;">Error loading rounds. Please try again.</p>';
                });
        }
        
        function closeRoundsModal() {
            document.getElementById('roundsModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('roundsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRoundsModal();
            }
        });
        
    </script>
@endsection
