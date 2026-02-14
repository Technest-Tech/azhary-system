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
                            
                            // Use student's fixed color (sharp, visible palette)
                            $nameColor = $course->student && $course->student->teacher_id 
                                ? ($course->student->display_color ?? '#1565c0') 
                                : '#1565c0';
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" 
                            onmouseover="this.style.background='#f8fafc'" 
                            onmouseout="this.style.background='white'">
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="checkbox" style="cursor: pointer;">
                            </td>
                            <td style="padding: 16px; white-space: nowrap; font-weight: 600; color: #1e293b;">
                                @if($course->name === '0' || $course->name === '0.0')
                                    <span style="color: #94a3b8; font-style: italic;">—</span>
                                @else
                                    <span style="color: #3b82f6;">{{ $course->name }}</span>
                                @endif
                                <span style="color: #94a3b8; font-size: 11px; margin-left: 4px;">({{ number_format($course->n_value, 2) }}h)</span>
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
                                <span style="display: inline-block; padding: 6px 12px; background: {{ $nameColor }}; color: #fff; font-weight: 600; font-size: 14px; border-radius: 8px;">{{ $course->student->name ?? $course->student_name ?? 'N/A' }}</span>
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
                                    <button type="button" onclick="openEditCourseModal({{ $course->id }})" 
                                       style="padding: 8px; color: #f59e0b; text-decoration: none; border-radius: 4px; background: none; border: none; cursor: pointer;"
                                       onmouseover="this.style.background='#fef3c7'" 
                                       onmouseout="this.style.background='transparent'"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
    <button type="button" onclick="openCourseModal()" 
       style="position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); z-index: 1000; transition: all 0.3s; border: none; cursor: pointer; font-size: 14px;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'">
        <i class="fas fa-plus"></i> {{ __('admin.add_course') }}
    </button>

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

    <!-- Course Create/Edit Modal -->
    <div id="courseModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 2000; overflow-y: auto;">
        <div style="background: white; margin: 30px auto; max-width: 1100px; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: slideUp 0.3s ease;">
            <!-- Modal Header -->
            <div style="padding: 24px 32px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="courseModalTitle" style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-plus-circle" style="color: #3b82f6;"></i>
                    <span>Create New Course</span>
                </h2>
                <button onclick="closeCourseModal()" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #64748b; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="padding: 32px;">
                <!-- Global Error Messages -->
                <div id="courseFormErrors" style="display: none; margin-bottom: 20px; padding: 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 14px;"></div>

                <form id="courseForm" enctype="multipart/form-data">
                    <input type="hidden" id="courseFormMethod" value="POST">
                    <input type="hidden" id="courseFormCourseId" value="">
                    <input type="hidden" name="name" id="modal_name" value="Course">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                        <!-- Left Column -->
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Teacher Selection -->
                            <div>
                                <label for="modal_teacher_id" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-chalkboard-teacher" style="color: #3b82f6;"></i> Teacher
                                </label>
                                <select name="teacher_id" id="modal_teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="">Select a teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-field="teacher_id" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Student Selection -->
                            <div>
                                <label for="modal_student_select" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-user" style="color: #3b82f6;"></i> Student
                                </label>
                                <input type="text" id="modal_student_name" name="student_name" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="Student name" readonly>
                                <input type="hidden" name="student_id" id="modal_student_id" required>
                                <select id="modal_student_select" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                                        onchange="document.getElementById('modal_student_id').value = this.value; document.getElementById('modal_student_name').value = this.options[this.selectedIndex].text.split(' (')[0];">
                                    <option value="">Select a student</option>
                                </select>
                                <div id="modal_student_loading" style="display: none; margin-top: 8px; color: #64748b; font-size: 12px;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading students...
                                </div>
                                <div class="field-error" data-field="student_id" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Class Time -->
                            <div>
                                <label for="modal_class_time" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-clock" style="color: #3b82f6;"></i> Class Time
                                </label>
                                <input type="time" name="class_time" id="modal_class_time" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" required>
                                <div class="field-error" data-field="class_time" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Course Type -->
                            <div>
                                <label for="modal_course_type" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-book" style="color: #3b82f6;"></i> Course
                                </label>
                                <select name="course_type" id="modal_course_type" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="">Select a course</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-field="course_type" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="modal_course_date" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-calendar" style="color: #3b82f6;"></i> Date
                                </label>
                                <input type="date" name="course_date" id="modal_course_date" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" value="{{ date('Y-m-d') }}" required>
                                <div class="field-error" data-field="course_date" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Duration -->
                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-stopwatch" style="color: #3b82f6;"></i> Duration
                                </label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <select name="duration_hours" id="modal_duration_hours" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                        @for($i = 0; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}h</option>
                                        @endfor
                                    </select>
                                    <select name="duration_minutes" id="modal_duration_minutes" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                        <option value="0" selected>00m</option>
                                        <option value="15">15m</option>
                                        <option value="30">30m</option>
                                        <option value="45">45m</option>
                                    </select>
                                </div>
                                <div class="field-error" data-field="duration_hours" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="modal_status" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-user-check" style="color: #3b82f6;"></i> Status
                                </label>
                                <select name="status" id="modal_status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="Present" selected>Present</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Free">Free</option>
                                </select>
                                <div class="field-error" data-field="status" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Homework -->
                            <div>
                                <label for="modal_homework" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-clipboard-list" style="color: #3b82f6;"></i> Homework
                                </label>
                                <input type="text" name="homework" id="modal_homework" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="Assigned homework">
                                <div class="field-error" data-field="homework" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Evaluation -->
                            <div>
                                <label for="modal_evaluation_id" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-sun" style="color: #3b82f6;"></i> Evaluation
                                </label>
                                <select name="evaluation_id" id="modal_evaluation_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;">
                                    <option value="">Select an evaluation</option>
                                    @foreach($evaluations as $evaluation)
                                        <option value="{{ $evaluation->id }}">{{ $evaluation->name }} : {{ $evaluation->max_percentage }} %</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-field="evaluation_id" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="modal_content" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-file-alt" style="color: #3b82f6;"></i> Content
                                </label>
                                <textarea name="content" id="modal_content" rows="3" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;" placeholder="Content covered in this course"></textarea>
                                <div class="field-error" data-field="content" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="modal_notes" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-sticky-note" style="color: #3b82f6;"></i> Notes
                                </label>
                                <textarea name="notes" id="modal_notes" rows="3" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;" placeholder="Additional notes"></textarea>
                                <div class="field-error" data-field="notes" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Souvenir Image -->
                            <div>
                                <label for="modal_souvenir_image" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-camera" style="color: #3b82f6;"></i> Souvenir
                                </label>
                                <div id="modal_souvenir_preview" style="display: none; margin-bottom: 8px;">
                                    <img id="modal_souvenir_img" src="" alt="Souvenir" style="max-width: 200px; max-height: 120px; border-radius: 8px; border: 2px solid #e2e8f0;">
                                </div>
                                <div style="position: relative;">
                                    <input type="text" id="modal_souvenir_image_text" style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="Click to upload an image" readonly>
                                    <input type="file" name="souvenir_image" id="modal_souvenir_image" accept="image/*" style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; top: 0; left: 0;"
                                           onchange="document.getElementById('modal_souvenir_image_text').value = this.files[0] ? this.files[0].name : '';">
                                    <i class="fas fa-folder" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"></i>
                                </div>
                                <div class="field-error" data-field="souvenir_image" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer - Action Buttons -->
            <div style="padding: 20px 32px; border-top: 1px solid #e2e8f0; display: flex; gap: 12px; justify-content: flex-end; background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" onclick="closeCourseModal()" style="padding: 14px 24px; background: #f1f5f9; color: #475569; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" id="btnSaveOnly" onclick="submitCourseForm(false)" style="padding: 14px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    <span>Save Only</span>
                </button>
                <button type="button" id="btnSaveWhatsapp" onclick="submitCourseForm(true)" style="padding: 14px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fab fa-whatsapp"></i>
                    <span>Save & Send WhatsApp Report</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="courseLoadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 3000; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 20px; padding: 48px 56px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: slideUp 0.4s ease;">
            <div style="margin-bottom: 24px;">
                <div style="width: 56px; height: 56px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto;"></div>
            </div>
            <h3 id="loadingTitle" style="color: #1f2937; font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">Saving Course...</h3>
            <p id="loadingSubtitle" style="color: #6b7280; font-size: 14px; margin: 0;">Please wait while we process your request.</p>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="successToast" style="display: none; position: fixed; top: 24px; right: 24px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); z-index: 4000; font-weight: 600; font-size: 14px; display: none; align-items: center; gap: 10px; animation: slideDown 0.3s ease;">
        <i class="fas fa-check-circle"></i>
        <span id="successToastText">Course saved successfully!</span>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        // ===================== ROUNDS MODAL =====================
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
        
        document.getElementById('roundsModal').addEventListener('click', function(e) {
            if (e.target === this) closeRoundsModal();
        });

        // ===================== COURSE MODAL =====================
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function openCourseModal() {
            // Reset form
            document.getElementById('courseForm').reset();
            document.getElementById('courseFormMethod').value = 'POST';
            document.getElementById('courseFormCourseId').value = '';
            document.getElementById('modal_name').value = 'Course';
            document.getElementById('modal_course_date').value = new Date().toISOString().split('T')[0];
            document.getElementById('modal_duration_hours').value = '1';
            document.getElementById('modal_duration_minutes').value = '0';
            document.getElementById('modal_status').value = 'Present';
            document.getElementById('modal_student_select').innerHTML = '<option value="">Select a student</option>';
            document.getElementById('modal_student_id').value = '';
            document.getElementById('modal_student_name').value = '';
            document.getElementById('modal_souvenir_preview').style.display = 'none';
            document.getElementById('modal_souvenir_image_text').value = '';
            
            // Update title
            document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-plus-circle" style="color: #3b82f6;"></i><span>Create New Course</span>';
            
            clearFormErrors();
            document.getElementById('courseModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditCourseModal(courseId) {
            // Show loading in modal
            openCourseModal();
            document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-edit" style="color: #f59e0b;"></i><span>Edit Course</span>';
            document.getElementById('courseFormMethod').value = 'PUT';
            document.getElementById('courseFormCourseId').value = courseId;
            
            // Disable buttons while loading
            document.getElementById('btnSaveOnly').disabled = true;
            document.getElementById('btnSaveWhatsapp').disabled = true;
            
            // Fetch course data
            fetch('/admin/courses/' + courseId + '/data', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error('Failed to load course data');
                var c = data.course;
                
                document.getElementById('modal_teacher_id').value = c.teacher_id;
                document.getElementById('modal_name').value = c.name;
                document.getElementById('modal_class_time').value = c.class_time;
                document.getElementById('modal_course_type').value = c.course_type;
                document.getElementById('modal_course_date').value = c.course_date;
                document.getElementById('modal_duration_hours').value = c.duration_hours;
                document.getElementById('modal_duration_minutes').value = c.duration_minutes;
                document.getElementById('modal_status').value = c.status;
                document.getElementById('modal_homework').value = c.homework || '';
                document.getElementById('modal_evaluation_id').value = c.evaluation_id || '';
                document.getElementById('modal_content').value = c.content || '';
                document.getElementById('modal_notes').value = c.notes || '';
                
                // Show souvenir preview if exists
                if (c.souvenir_image) {
                    document.getElementById('modal_souvenir_preview').style.display = 'block';
                    document.getElementById('modal_souvenir_img').src = '/storage/' + c.souvenir_image;
                }
                
                // Load students for this teacher, then select the right one
                loadModalStudents(c.teacher_id, c.student_id, c.student_name);
                
                document.getElementById('btnSaveOnly').disabled = false;
                document.getElementById('btnSaveWhatsapp').disabled = false;
            })
            .catch(err => {
                console.error('Error loading course:', err);
                document.getElementById('courseFormErrors').style.display = 'block';
                document.getElementById('courseFormErrors').textContent = 'Failed to load course data. Please try again.';
                document.getElementById('btnSaveOnly').disabled = false;
                document.getElementById('btnSaveWhatsapp').disabled = false;
            });
        }

        function closeCourseModal() {
            document.getElementById('courseModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close course modal when clicking outside
        document.getElementById('courseModal').addEventListener('click', function(e) {
            if (e.target === this) closeCourseModal();
        });

        // ===================== STUDENT LOADING =====================
        function loadModalStudents(teacherId, selectStudentId, selectStudentName) {
            var studentSelect = document.getElementById('modal_student_select');
            var studentIdField = document.getElementById('modal_student_id');
            var studentNameField = document.getElementById('modal_student_name');
            var loading = document.getElementById('modal_student_loading');
            
            studentSelect.innerHTML = '<option value="">Select a student</option>';
            studentIdField.value = '';
            studentNameField.value = '';
            
            if (!teacherId) return;
            
            loading.style.display = 'block';
            studentSelect.disabled = true;
            
            fetch('/admin/teachers/' + teacherId + '/students', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                loading.style.display = 'none';
                studentSelect.disabled = false;
                
                if (data.students && data.students.length > 0) {
                    data.students.forEach(function(student) {
                        var opt = document.createElement('option');
                        opt.value = student.id;
                        opt.textContent = student.name;
                        if (selectStudentId && student.id == selectStudentId) {
                            opt.selected = true;
                            studentIdField.value = student.id;
                            studentNameField.value = student.name;
                        }
                        studentSelect.appendChild(opt);
                    });
                    // If we had a name but no matching ID (edge case)
                    if (selectStudentName && !studentIdField.value) {
                        studentNameField.value = selectStudentName;
                    }
                } else {
                    studentSelect.innerHTML = '<option value="">No students found for this teacher</option>';
                }
            })
            .catch(() => {
                loading.style.display = 'none';
                studentSelect.disabled = false;
                studentSelect.innerHTML = '<option value="">Error loading students</option>';
            });
        }

        // When teacher changes in modal, reload students
        document.getElementById('modal_teacher_id').addEventListener('change', function() {
            loadModalStudents(this.value, null, null);
        });

        // ===================== FORM SUBMISSION =====================
        function clearFormErrors() {
            document.getElementById('courseFormErrors').style.display = 'none';
            document.getElementById('courseFormErrors').textContent = '';
            document.querySelectorAll('.field-error').forEach(function(el) {
                el.style.display = 'none';
                el.textContent = '';
            });
        }

        function showFormErrors(errors) {
            clearFormErrors();
            var globalErrors = [];
            
            Object.keys(errors).forEach(function(field) {
                var errorDiv = document.querySelector('.field-error[data-field="' + field + '"]');
                if (errorDiv) {
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = errors[field][0];
                } else {
                    globalErrors.push(errors[field][0]);
                }
            });
            
            if (globalErrors.length > 0) {
                var errBox = document.getElementById('courseFormErrors');
                errBox.style.display = 'block';
                errBox.innerHTML = globalErrors.join('<br>');
            }
        }

        function submitCourseForm(sendWhatsapp) {
            clearFormErrors();
            
            var method = document.getElementById('courseFormMethod').value;
            var courseId = document.getElementById('courseFormCourseId').value;
            var form = document.getElementById('courseForm');
            var formData = new FormData(form);
            formData.append('send_whatsapp', sendWhatsapp ? '1' : '0');
            
            // Determine URL
            var url;
            if (method === 'PUT' && courseId) {
                url = '/admin/courses/' + courseId;
                formData.append('_method', 'PUT');
            } else {
                url = '/admin/courses';
            }
            
            // Frontend Validation
            var errors = {};
            if (!formData.get('teacher_id')) errors['teacher_id'] = ['The teacher field is required.'];
            if (!formData.get('student_id')) errors['student_id'] = ['The student field is required.'];
            if (!formData.get('class_time')) errors['class_time'] = ['The class time field is required.'];
            if (!formData.get('course_type')) errors['course_type'] = ['The course type field is required.'];
            if (!formData.get('course_date')) errors['course_date'] = ['The date field is required.'];
            
            if (Object.keys(errors).length > 0) {
                showFormErrors(errors);
                return;
            }

            // Show loading overlay
            var overlay = document.getElementById('courseLoadingOverlay');
            var loadingTitle = document.getElementById('loadingTitle');
            var loadingSubtitle = document.getElementById('loadingSubtitle');
            
            if (sendWhatsapp) {
                loadingTitle.textContent = method === 'PUT' ? 'Updating Course...' : 'Creating Course...';
                loadingSubtitle.innerHTML = 'Generating report and sending to WhatsApp.<br>Please wait, this may take a few seconds.';
            } else {
                loadingTitle.textContent = method === 'PUT' ? 'Updating Course...' : 'Saving Course...';
                loadingSubtitle.textContent = 'Please wait...';
            }
            
            overlay.style.display = 'flex';
            
            // Disable buttons
            document.getElementById('btnSaveOnly').disabled = true;
            document.getElementById('btnSaveWhatsapp').disabled = true;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(function(response) {
                if (response.status === 422) {
                    return response.json().then(function(data) {
                        throw { type: 'validation', errors: data.errors };
                    });
                }
                if (!response.ok) {
                    throw { type: 'server', message: 'Server error (' + response.status + ')' };
                }
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // KEEP OVERLAY VISIBLE until reload
                    closeCourseModal(); // Close the modal form behind the overlay
                    showSuccessToast(data.message || 'Course saved successfully!');
                    
                    // Update overlay message to success state
                    loadingTitle.textContent = 'Success!';
                    loadingSubtitle.textContent = 'Reloading page...';
                    
                    setTimeout(function() { window.location.reload(); }, 1200);
                } else {
                    overlay.style.display = 'none';
                    document.getElementById('courseFormErrors').style.display = 'block';
                    document.getElementById('courseFormErrors').textContent = data.message || 'An error occurred.';
                    document.getElementById('btnSaveOnly').disabled = false;
                    document.getElementById('btnSaveWhatsapp').disabled = false;
                }
            })
            .catch(function(err) {
                overlay.style.display = 'none';
                document.getElementById('btnSaveOnly').disabled = false;
                document.getElementById('btnSaveWhatsapp').disabled = false;
                
                if (err.type === 'validation') {
                    showFormErrors(err.errors);
                } else {
                    document.getElementById('courseFormErrors').style.display = 'block';
                    document.getElementById('courseFormErrors').textContent = err.message || 'An unexpected error occurred. Please try again.';
                }
            });

        }

        function showSuccessToast(message) {
            var toast = document.getElementById('successToast');
            document.getElementById('successToastText').textContent = message;
            toast.style.display = 'flex';
            setTimeout(function() { toast.style.display = 'none'; }, 4000);
        }

        // Show success message from session flash (for non-AJAX redirects)
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showSuccessToast(@json(session('success')));
            });
        @endif
    </script>
@endsection
