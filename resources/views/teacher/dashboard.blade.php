@extends('teacher.layouts.app')

@section('title', __('teacher.dashboard'))
@section('page-title', __('teacher.dashboard_overview'))

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <!-- Top Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 24px; margin-bottom: 32px;">
        <!-- Total Students Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('teacher.total_students') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $studentsCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Hours this Month Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('teacher.hours_this_month') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($thisMonthHours, 1) }}</h3>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-dollar-sign" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('teacher.monthly_revenue') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($thisMonthRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Teacher Performance Level Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-line" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">{{ __('teacher.teacher_performance_level') }}</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($teacherPerformanceLevel, 2) }}%</h3>
                </div>
            </div>
        </div>

        <!-- Bar Chart Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <canvas id="weeklyChart" style="max-height: 120px;"></canvas>
        </div>
    </div>

    <!-- Course Management Section -->
    <div class="card" style="background: white; border-radius: 16px; padding: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
        <!-- Search and Filters -->
        <div style="background: #f8fafc; padding: 24px; border-bottom: 1px solid #e2e8f0;">
            <form method="GET" action="{{ route('teacher.dashboard') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; align-items: end;">
                <!-- Search -->
                <div>
                    <label for="dashboard_search" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.search') }}</label>
                    <input type="text" name="search" id="dashboard_search" value="{{ request('search') }}" placeholder="{{ __('teacher.search') }}"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                <!-- Status Filter -->
                <div>
                    <label for="dashboard_status" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.status') }}</label>
                    <select name="status" id="dashboard_status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="all">{{ __('teacher.all_statuses') }}</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>{{ __('teacher.present') }}</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>{{ __('teacher.absent') }}</option>
                        <option value="Free" {{ request('status') == 'Free' ? 'selected' : '' }}>{{ __('teacher.free') }}</option>
                    </select>
                </div>
                <!-- Month/Year Filter -->
                <div>
                    <label for="dashboard_month_year" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.month_year') }}</label>
                    <select name="month_year" id="dashboard_month_year" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">{{ __('teacher.all_months') }}</option>
                        @for($i = 0; $i < 12; $i++)
                            @php
                                $date = now()->subMonths($i);
                                $value = $date->format('n-Y');
                                $label = $date->format('F Y');
                                $selected = request('month_year') == $value ? 'selected' : '';
                            @endphp
                            <option value="{{ $value }}" {{ $selected }}>{{ $label }}</option>
                        @endfor
                    </select>
                </div>
                <!-- From date -->
                <div>
                    <label for="dashboard_date_from" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.from_date') }}</label>
                    <input type="date" name="date_from" id="dashboard_date_from" value="{{ request('date_from') }}"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                <!-- To date -->
                <div>
                    <label for="dashboard_date_to" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.to_date') }}</label>
                    <input type="date" name="date_to" id="dashboard_date_to" value="{{ request('date_to') }}"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                <!-- Items per page -->
                <div>
                    <label for="dashboard_per_page" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 13px;">{{ __('teacher.per_page') }}</label>
                    <select name="per_page" id="dashboard_per_page" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="10" {{ request('per_page', 100) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 100) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 100) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>{{ __('teacher.all') }}</option>
                    </select>
                </div>
                <!-- Submit button -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-size: 13px; visibility: hidden;">.</label>
                    <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-search"></i> {{ __('teacher.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Header -->
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                {{ __('teacher.number_of_courses', ['count' => $courses->total()]) }}
            </h3>
        </div>

        <!-- Course Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; white-space: normal;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">
                            <input type="checkbox" style="cursor: pointer;">
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.no') }} / {{ __('teacher.round') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.name') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.course') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.date') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.status') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.duration') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.homework') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.evaluation') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.content') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.notes') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.completed') }}</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($courses->isEmpty())
                        <tr>
                            <td colspan="13" style="padding: 40px; text-align: center; color: #64748b;">
                                <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>{{ __('teacher.no_courses_found') }}</p>
                            </td>
                        </tr>
                    @else
                        @foreach($courses as $course)
                            @php
                                $completionPercentage = 0;
                                if ($course->student && $course->student->package_number > 0) {
                                    $completionPercentage = min(100, ($course->n_value / $course->student->package_number) * 100);
                                }
                                $nameColor = $course->student->display_color ?? '#1565c0';
                                $roundNum = (int)($course->round ?? 0);
                                $roundLabel = $roundNum === 0 ? __('teacher.round') . ' 0' : __('teacher.round') . ' ' . $roundNum;
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 16px;"><input type="checkbox" style="cursor: pointer;" onclick="event.stopPropagation();"></td>
                                <td style="padding: 16px;">
                                    <span style="font-weight: 600; color: #1e293b;">{{ number_format($course->n_value, 2) }}</span>
                                    <span style="display: inline-block; margin-left: 8px; padding: 4px 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $roundLabel }}</span>
                                </td>
                                    <td style="padding: 16px;"><span style="display: inline-block; padding: 6px 12px; background: {{ $nameColor }}; color: #fff; font-weight: 600; font-size: 14px; border-radius: 8px;">{{ $course->student->name }}</span></td>
                                    <td style="padding: 16px; color: #64748b;">{{ $course->course_type }}</td>
                                    <td style="padding: 16px; color: #64748b;">{{ $course->course_date->format('d/m/Y') }} {{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}</td>
                                    <td style="padding: 16px;">
                                        @php
                                            $statusLabel = $course->status === 'Present' ? __('teacher.present') : ($course->status === 'Absent' ? __('teacher.absent') : __('teacher.free'));
                                            $adminLabel = $course->admin_status === 'approved' ? __('teacher.approved') : ($course->admin_status === 'pending' ? __('teacher.pending') : ($course->admin_status === 'rejected' ? __('teacher.rejected') : 'Approved'));
                                            $combined = $statusLabel . ' · ' . $adminLabel;
                                            if ($course->status === 'Present' && $course->admin_status === 'approved') {
                                                $badgeStyle = 'background: #6ee7b7; color: #065f46;';
                                            } elseif ($course->status === 'Present' && $course->admin_status === 'pending') {
                                                $badgeStyle = 'background: #f59e0b; color: white;';
                                            } elseif ($course->status === 'Absent') {
                                                $badgeStyle = 'background: #fca5a5; color: #991b1b;';
                                            } elseif ($course->status === 'Free') {
                                                $badgeStyle = 'background: #e5e7eb; color: #374151;';
                                            } elseif ($course->admin_status === 'rejected') {
                                                $badgeStyle = 'background: #ef4444; color: white;';
                                            } else {
                                                $badgeStyle = 'background: #10b981; color: white;';
                                            }
                                        @endphp
                                        <span style="display: inline-block; padding: 6px 12px; {{ $badgeStyle }} border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $combined }}</span>
                                    </td>
                                    <td style="padding: 16px; color: #64748b;">{{ $course->duration_hours }}h {{ $course->duration_minutes }}m</td>
                                    <td style="padding: 16px; color: #64748b; max-width: 200px;">{{ Str::limit($course->homework ?? '-', 30) }}</td>
                                    <td style="padding: 16px; color: #64748b;">@if($course->evaluation){{ $course->evaluation->name }} : {{ $course->evaluation->max_percentage }}% @else - @endif</td>
                                    <td style="padding: 16px; color: #64748b; max-width: 200px;">{{ Str::limit($course->content ?? '-', 30) }}</td>
                                    <td style="padding: 16px; color: #64748b; max-width: 200px;">{{ Str::limit($course->notes ?? '-', 30) }}</td>
                                    <td style="padding: 16px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 100px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;"><div style="width: {{ $completionPercentage }}%; height: 100%; background: #14b8a6; transition: width 0.3s;"></div></div>
                                            <span style="font-size: 12px; font-weight: 600; color: #1e293b;">{{ number_format($completionPercentage, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td style="padding: 16px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <button type="button" onclick="event.stopPropagation(); openEditCourseModal({{ $course->id }})" style="padding: 8px; color: #f59e0b; text-decoration: none; border-radius: 4px; background: none; border: none; cursor: pointer;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='transparent'"><i class="fas fa-edit"></i></button>
                                            <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('{{ __('teacher.delete_confirm') }}');" onclick="event.stopPropagation();">@csrf @method('DELETE')<button type="submit" style="padding: 8px; color: #ef4444; background: none; border: none; cursor: pointer; border-radius: 4px;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='transparent'"><i class="fas fa-times"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-start; align-items: center;">
            <div style="display: flex; gap: 12px;">
                @if($courses->previousPageUrl())
                    <a href="{{ $courses->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
                       style="padding: 10px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        {{ __('teacher.previous') }}
                    </a>
                @endif
                @if($courses->nextPageUrl())
                    <a href="{{ $courses->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
                       style="padding: 10px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        {{ __('teacher.next') }}
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
        <i class="fas fa-plus"></i> {{ __('teacher.add_a_course') }}
    </button>

    <!-- ==================== COURSE CREATE/EDIT MODAL ==================== -->
    <div id="courseModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 2000; overflow-y: auto;">
        <div style="background: white; margin: 30px auto; max-width: 1100px; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: slideUp 0.3s ease;">
            <!-- Modal Header -->
            <div style="padding: 24px 32px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="courseModalTitle" style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-plus-circle" style="color: #3b82f6;"></i>
                    <span>{{ __('teacher.create_new_course') }}</span>
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
                            <!-- Student Selection (real-time search) -->
                            <div style="position: relative; overflow: visible;">
                                <label for="modal_student_search" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-user" style="color: #3b82f6;"></i> {{ __('teacher.student') }}
                                </label>
                                <input type="text" id="modal_student_search" autocomplete="off" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="{{ __('teacher.type_to_search_student') }}">
                                <input type="hidden" name="student_id" id="modal_student_id" required>
                                <input type="hidden" name="student_name" id="modal_student_name" value="">
                                <div id="modal_student_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 4px; max-height: 220px; overflow-y: auto; background: white; border: 2px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 9999;"></div>
                                <div class="field-error" data-field="student_id" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Class Time -->
                            <div>
                                <label for="modal_class_time" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-clock" style="color: #3b82f6;"></i> {{ __('teacher.class_time') }}
                                </label>
                                <input type="time" name="class_time" id="modal_class_time" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" required>
                                <div class="field-error" data-field="class_time" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Course Type -->
                            <div>
                                <label for="modal_course_type" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-book" style="color: #3b82f6;"></i> {{ __('teacher.course') }}
                                </label>
                                <select name="course_type" id="modal_course_type" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="">{{ __('teacher.select_a_course') }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-field="course_type" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="modal_course_date" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-calendar" style="color: #3b82f6;"></i> {{ __('teacher.date') }}
                                </label>
                                <input type="date" name="course_date" id="modal_course_date" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" value="{{ date('Y-m-d') }}" required>
                                <div class="field-error" data-field="course_date" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Duration -->
                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-stopwatch" style="color: #3b82f6;"></i> {{ __('teacher.duration') }}
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
                                    <i class="fas fa-user-check" style="color: #3b82f6;"></i> {{ __('teacher.status') }}
                                </label>
                                <select name="status" id="modal_status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="Present" selected>{{ __('teacher.present') }}</option>
                                    <option value="Absent">{{ __('teacher.absent') }}</option>
                                    <option value="Free">{{ __('teacher.free') }}</option>
                                </select>
                                <div class="field-error" data-field="status" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Homework -->
                            <div>
                                <label for="modal_homework" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-clipboard-list" style="color: #3b82f6;"></i> {{ __('teacher.homework') }}
                                </label>
                                <input type="text" name="homework" id="modal_homework" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="{{ __('teacher.homework_placeholder') }}">
                                <div class="field-error" data-field="homework" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Evaluation -->
                            <div>
                                <label for="modal_evaluation_id" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-sun" style="color: #3b82f6;"></i> {{ __('teacher.evaluation') }}
                                </label>
                                <select name="evaluation_id" id="modal_evaluation_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;">
                                    <option value="">{{ __('teacher.select_an_evaluation') }}</option>
                                    @foreach($evaluations as $evaluation)
                                        <option value="{{ $evaluation->id }}">{{ $evaluation->name }} : {{ $evaluation->max_percentage }} %</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-field="evaluation_id" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="modal_content" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-file-alt" style="color: #3b82f6;"></i> {{ __('teacher.content') }}
                                </label>
                                <textarea name="content" id="modal_content" rows="3" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;" placeholder="{{ __('teacher.content_placeholder') }}"></textarea>
                                <div class="field-error" data-field="content" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="modal_notes" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-sticky-note" style="color: #3b82f6;"></i> {{ __('teacher.notes') }}
                                </label>
                                <textarea name="notes" id="modal_notes" rows="3" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;" placeholder="{{ __('teacher.notes_placeholder') }}"></textarea>
                                <div class="field-error" data-field="notes" style="display:none; color: #dc2626; font-size: 12px; margin-top: 4px;"></div>
                            </div>

                            <!-- Souvenir Image -->
                            <div>
                                <label for="modal_souvenir_image" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-camera" style="color: #3b82f6;"></i> {{ __('teacher.souvenir') }}
                                </label>
                                <div id="modal_souvenir_preview" style="display: none; margin-bottom: 8px;">
                                    <img id="modal_souvenir_img" src="" alt="{{ __('teacher.souvenir') }}" style="max-width: 200px; max-height: 120px; border-radius: 8px; border: 2px solid #e2e8f0;">
                                </div>
                                <div style="position: relative;">
                                    <input type="text" id="modal_souvenir_image_text" style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="{{ __('teacher.click_to_upload_image') }}" readonly>
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
                    <i class="fas fa-times"></i> {{ __('teacher.cancel') }}
                </button>
                <button type="button" id="btnSaveOnly" onclick="submitCourseForm(false)" style="padding: 14px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    <span>{{ __('teacher.save_only') }}</span>
                </button>
                <button type="button" id="btnSaveWhatsapp" onclick="submitCourseForm(true)" style="padding: 14px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fab fa-whatsapp"></i>
                    <span>{{ __('teacher.save_send_whatsapp') }}</span>
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
            <h3 id="loadingTitle" style="color: #1f2937; font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">{{ __('teacher.saving_course') }}</h3>
            <p id="loadingSubtitle" style="color: #6b7280; font-size: 14px; margin: 0;">{{ __('teacher.please_wait_processing') }}</p>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="successToast" style="display: none; position: fixed; top: 24px; right: 24px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); z-index: 4000; font-weight: 600; font-size: 14px; align-items: center; gap: 10px; animation: slideDown 0.3s ease;">
        <i class="fas fa-check-circle"></i>
        <span id="successToastText">{{ __('teacher.course_created_success') }}</span>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection

@section('scripts')
<script>
    window.teacherCreateNewCourseLabel = @json(__('teacher.create_new_course'));
    window.teacherEditCourseLabel = @json(__('teacher.edit_course'));
    window.teacherNoMatchingStudents = @json(__('teacher.no_matching_students'));
    window.teacherLoadingRounds = @json(__('teacher.loading_rounds'));
    window.teacherPleaseWait = @json(__('teacher.please_wait'));
    window.teacherGeneratingWhatsapp = @json(__('teacher.generating_whatsapp_report'));
    window.teacherCourseSavedSuccess = @json(__('teacher.course_created_success'));
    window.teacherErrorOccurred = @json(__('teacher.error_occurred'));
    window.teacherUnexpectedError = @json(__('teacher.unexpected_error'));
    // Initialize bar chart
    const ctx = document.getElementById('weeklyChart');
    if (ctx) {
        const weeklyData = @json($weeklyData);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(d => d.label),
                datasets: [{
                    label: '{{ __('teacher.performance') }}',
                    data: weeklyData.map(d => d.value),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // ===================== STUDENT REAL-TIME SEARCH =====================
    window.modalStudentsList = @json($students->map(function($s) { return ['id' => $s->id, 'name' => $s->name]; })->values());
    function renderModalStudentDropdown(query) {
        var dropdown = document.getElementById('modal_student_dropdown');
        if (!dropdown) return;
        var q = String(query || '').trim().toLowerCase();
        var list = window.modalStudentsList || [];
        var filtered = !q ? list : list.filter(function(s) { return String(s.name || '').toLowerCase().indexOf(q) !== -1; });
        if (filtered.length === 0) {
            dropdown.innerHTML = '<div style="padding: 12px 16px; color: #64748b; font-size: 14px;">' + (window.teacherNoMatchingStudents || 'No matching students') + '</div>';
        } else {
            dropdown.innerHTML = filtered.map(function(s) {
                var name = String(s.name || '');
                return '<div class="modal-student-option" data-id="' + s.id + '" data-name="' + name.replace(/"/g, '&quot;') + '" style="padding: 12px 16px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'white\'">' + name + '</div>';
            }).join('');
        }
        dropdown.style.display = 'block';
    }
    (function() {
        var search = document.getElementById('modal_student_search');
        var dropdown = document.getElementById('modal_student_dropdown');
        if (!search || !dropdown) return;
        search.addEventListener('focus', function() { renderModalStudentDropdown(this.value); });
        search.addEventListener('input', function() {
            document.getElementById('modal_student_id').value = '';
            document.getElementById('modal_student_name').value = '';
            renderModalStudentDropdown(this.value);
        });
        search.addEventListener('keyup', function() { renderModalStudentDropdown(this.value); });
        dropdown.addEventListener('click', function(e) {
            var el = e.target.closest('.modal-student-option');
            if (!el) return;
            document.getElementById('modal_student_id').value = el.getAttribute('data-id');
            document.getElementById('modal_student_name').value = el.getAttribute('data-name');
            document.getElementById('modal_student_search').value = el.getAttribute('data-name');
            this.style.display = 'none';
        });
        document.addEventListener('click', function(e) {
            if (!search.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none';
        });
    })();

    // Rounds Modal
    function showRoundsModal(studentId) {
        const modal = document.getElementById('roundsModal');
        const content = document.getElementById('roundsModalContent');
        const title = document.getElementById('roundsModalTitle');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #3b82f6;"></i><p style="margin-top: 16px; color: #64748b;">' + (window.teacherLoadingRounds || 'Loading rounds...') + '</p></div>';
        
        fetch(`/teacher/students/${studentId}/rounds`)
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
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: ${course.status === 'Present' ? '#6ee7b7' : course.status === 'Absent' ? '#fca5a5' : '#fcd34d'}; color: ${course.status === 'Present' ? '#065f46' : course.status === 'Absent' ? '#991b1b' : '#92400e'};">${course.status}</span>
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
        document.getElementById('modal_student_id').value = '';
        document.getElementById('modal_student_name').value = '';
        var searchEl = document.getElementById('modal_student_search');
        if (searchEl) { searchEl.value = ''; }
        var dropEl = document.getElementById('modal_student_dropdown');
        if (dropEl) { dropEl.style.display = 'none'; }
        document.getElementById('modal_souvenir_preview').style.display = 'none';
        document.getElementById('modal_souvenir_image_text').value = '';
        
        // Update title
        document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-plus-circle" style="color: #3b82f6;"></i><span>' + (window.teacherCreateNewCourseLabel || 'Create New Course') + '</span>';
        
        clearFormErrors();
        document.getElementById('courseModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function openEditCourseModal(courseId) {
        openCourseModal();
        document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-edit" style="color: #f59e0b;"></i><span>' + (window.teacherEditCourseLabel || 'Edit Course') + '</span>';
        document.getElementById('courseFormMethod').value = 'PUT';
        document.getElementById('courseFormCourseId').value = courseId;
        
        // Disable buttons while loading
        document.getElementById('btnSaveOnly').disabled = true;
        document.getElementById('btnSaveWhatsapp').disabled = true;
        
        // Fetch course data
        fetch('/teacher/courses/' + courseId + '/data', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Failed to load course data');
            var c = data.course;
            
            document.getElementById('modal_student_id').value = c.student_id;
            document.getElementById('modal_student_name').value = c.student_name;
            var searchEl = document.getElementById('modal_student_search');
            if (searchEl) searchEl.value = c.student_name || '';
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

    // Close modals when clicking outside
    document.getElementById('courseModal').addEventListener('click', function(e) {
        if (e.target === this) closeCourseModal();
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
            url = '/teacher/courses/' + courseId;
            formData.append('_method', 'PUT');
        } else {
            url = '/teacher/courses';
        }
        
        // Frontend Validation
        var errors = {};
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
            loadingSubtitle.innerHTML = (window.teacherGeneratingWhatsapp || 'Generating report and sending to WhatsApp...') + '<br>' + (window.teacherPleaseWait || 'Please wait...');
        } else {
            loadingTitle.textContent = method === 'PUT' ? 'Updating Course...' : 'Saving Course...';
            loadingSubtitle.textContent = window.teacherPleaseWait || 'Please wait...';
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
                closeCourseModal();
                showSuccessToast(data.message || (window.teacherCourseSavedSuccess || 'Course saved successfully!'));
                
                // Update overlay message to success state
                loadingTitle.textContent = 'Success!';
                loadingSubtitle.textContent = 'Reloading page...';
                
                setTimeout(function() { window.location.reload(); }, 1200);
            } else {
                overlay.style.display = 'none';
                document.getElementById('courseFormErrors').style.display = 'block';
                document.getElementById('courseFormErrors').textContent = data.message || (window.teacherErrorOccurred || 'An error occurred.');
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
                document.getElementById('courseFormErrors').textContent = err.message || (window.teacherUnexpectedError || 'An unexpected error occurred. Please try again.');
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
    // Close rounds modal when clicking outside
    document.getElementById('roundsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoundsModal();
        }
    });
</script>
@endsection
