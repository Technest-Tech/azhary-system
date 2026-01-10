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
            <form method="GET" action="{{ route('teacher.dashboard') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
                <!-- Search -->
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('teacher.search') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <select name="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="all">{{ __('teacher.all_statuses') }}</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>{{ __('teacher.present') }}</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>{{ __('teacher.pending') }}</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>{{ __('teacher.absent') }}</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>{{ __('teacher.late') }}</option>
                    </select>
                </div>
                
                <!-- Month/Year Filter -->
                <div>
                    <select name="month_year" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
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
                
                <!-- Items per page -->
                <div>
                    <select name="per_page" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                
                <!-- Date picker -->
                <div>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           placeholder="jj/mm/aaaa">
                </div>
                
                <!-- Submit button -->
                <div>
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
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">
                            <input type="checkbox" style="cursor: pointer;">
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.no') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.name') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.course') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.date') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.status') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.admin_status') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.duration') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.homework') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.evaluation') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.content') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.notes') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('teacher.completed') }}</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">{{ __('teacher.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                        @php
                            // Calculate completion percentage based on total hours taken vs package hours
                            $completionPercentage = 0;
                            if ($course->student && $course->student->package_number > 0) {
                                // Sum all total_hours from all courses for this student
                                $totalHoursTaken = $course->student->courses()->sum('total_hours') ?? 0;
                                // Calculate percentage: (hours taken / package hours) * 100
                                $completionPercentage = min(100, ($totalHoursTaken / $course->student->package_number) * 100);
                            }
                            
                            // Color coding for student names
                            $nameColor = '#64748b';
                            if ($course->status === 'Pending') {
                                $nameColor = '#fbbf24';
                            } elseif ($course->student->package_number > 0) {
                                if ($completionPercentage >= 80) {
                                    $nameColor = '#f9a8d4'; // Soft Pink
                                } elseif ($completionPercentage >= 50) {
                                    $nameColor = '#fde047'; // Soft Yellow
                                } else {
                                    $nameColor = '#c084fc'; // Soft Purple
                                }
                            }
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" 
                            onmouseover="this.style.background='#f8fafc'" 
                            onmouseout="this.style.background='white'">
                            <td style="padding: 16px;">
                                <input type="checkbox" style="cursor: pointer;">
                            </td>
                            <td style="padding: 16px; font-weight: 600; color: #1e293b;">
                                {{ number_format((($courses->firstItem() ?? 1) - 1 + $index + 0.5), 1) }}
                            </td>
                            <td style="padding: 16px;">
                                <span style="color: {{ $nameColor }}; font-weight: 600;">{{ $course->student->name }}</span>
                            </td>
                            <td style="padding: 16px; color: #64748b;">
                                {{ $course->course_type }}
                            </td>
                            <td style="padding: 16px; color: #64748b;">
                                {{ $course->course_date->format('d/m/Y') }} {{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}
                            </td>
                            <td style="padding: 16px;">
                                @if($course->status === 'Present')
                                    <span style="display: inline-block; padding: 6px 12px; background: #6ee7b7; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('teacher.present') }}</span>
                                @elseif($course->status === 'Pending')
                                    <span style="display: inline-block; padding: 6px 12px; background: #fcd34d; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('teacher.pending') }}</span>
                                @elseif($course->status === 'Absent')
                                    <span style="display: inline-block; padding: 6px 12px; background: #fca5a5; color: #991b1b; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('teacher.absent') }}</span>
                                @else
                                    <span style="display: inline-block; padding: 6px 12px; background: #fcd34d; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ __('teacher.late') }}</span>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                @if($course->admin_status === 'approved')
                                    <span style="display: inline-block; padding: 6px 12px; background: #10b981; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">✔ {{ __('teacher.approved') }}</span>
                                @elseif($course->admin_status === 'pending')
                                    <span style="display: inline-block; padding: 6px 12px; background: #f59e0b; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">⏳ {{ __('teacher.pending') }}</span>
                                @elseif($course->admin_status === 'rejected')
                                    <span style="display: inline-block; padding: 6px 12px; background: #ef4444; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">✗ {{ __('teacher.rejected') }}</span>
                                @else
                                    <span style="display: inline-block; padding: 6px 12px; background: #10b981; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">✔ {{ __('teacher.approved') }}</span>
                                @endif
                            </td>
                            <td style="padding: 16px; color: #64748b;">
                                {{ $course->duration_hours }}h {{ $course->duration_minutes }}m
                            </td>
                            <td style="padding: 16px; color: #64748b; max-width: 200px;">
                                {{ Str::limit($course->homework ?? '-', 30) }}
                            </td>
                            <td style="padding: 16px; color: #64748b;">
                                @if($course->evaluation)
                                    {{ $course->evaluation->name }} : {{ $course->evaluation->max_percentage }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 16px; color: #64748b; max-width: 200px;">
                                {{ Str::limit($course->content ?? '-', 30) }}
                            </td>
                            <td style="padding: 16px; color: #64748b; max-width: 200px;">
                                {{ Str::limit($course->notes ?? '-', 30) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 100px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: {{ $completionPercentage }}%; height: 100%; background: #14b8a6; transition: width 0.3s;"></div>
                                    </div>
                                    <span style="font-size: 12px; font-weight: 600; color: #1e293b;">{{ number_format($completionPercentage, 1) }}%</span>
                                </div>
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('teacher.courses.edit', $course) }}" 
                                       style="padding: 8px; color: #f59e0b; text-decoration: none; border-radius: 4px;"
                                       onmouseover="this.style.background='#fef3c7'" 
                                       onmouseout="this.style.background='transparent'">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" style="display: inline;" 
                                          onsubmit="return confirm('{{ __('teacher.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="padding: 8px; color: #ef4444; background: none; border: none; cursor: pointer; border-radius: 4px;"
                                                onmouseover="this.style.background='#fee2e2'" 
                                                onmouseout="this.style.background='transparent'">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="padding: 40px; text-align: center; color: #64748b;">
                                <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>{{ __('teacher.no_courses_found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and Add Button -->
        <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
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
            <a href="{{ route('teacher.courses.create') }}" 
               style="padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> {{ __('teacher.add_a_course') }}
            </a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
