@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

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
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Total Students</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <!-- Monthly Hours Card -->
        <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Monthly Hours</p>
                    <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 4px 0 0 0;">{{ number_format($monthlyHours, 1) }}</h3>
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
                    <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase;">Monthly Revenue</p>
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
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Teacher</label>
                    <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Student Filter (Second) -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Student</label>
                    <select name="student_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Search -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Status</label>
                    <select name="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All statuses</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    </select>
                </div>
                
                <!-- Per Page Selector -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Show</label>
                    <select name="per_page" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                
                <!-- Date picker -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                
                <!-- Submit button -->
                <div>
                    <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; height: fit-content;">
                        <i class="fas fa-search"></i> Filter
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
                Number of courses: {{ $courses->total() }}
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
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Name</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Course</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Date</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Duration</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Homework</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Evaluation</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Content</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Notes</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">Completed</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                        @php
                            // Calculate completion percentage
                            $completionPercentage = 0;
                            if ($course->student && $course->student->package_number > 0) {
                                $completionPercentage = min(100, ($course->n_value / $course->student->package_number) * 100);
                            }
                            
                            // Color coding for student names
                            $nameColor = '#3b82f6';
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" 
                            onmouseover="this.style.background='#f8fafc'" 
                            onmouseout="this.style.background='white'">
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="checkbox" style="cursor: pointer;">
                            </td>
                            <td style="padding: 16px; white-space: nowrap; font-weight: 600; color: #1e293b;">
                                {{ number_format($course->n_value, 1) }}
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
                                    <span style="display: inline-block; padding: 6px 12px; background: #10b981; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">Present</span>
                                @elseif($course->status === 'Pending')
                                    <span style="display: inline-block; padding: 6px 12px; background: #f59e0b; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">Pending</span>
                                @elseif($course->status === 'Absent')
                                    <span style="display: inline-block; padding: 6px 12px; background: #ef4444; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">Absent</span>
                                @elseif($course->status === 'Late')
                                    <span style="display: inline-block; padding: 6px 12px; background: #f59e0b; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">Late</span>
                                @else
                                    <span style="display: inline-block; padding: 6px 12px; background: #94a3b8; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $course->status ?? '-' }}</span>
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                {{ $course->duration_hours ?? 0 }}h {{ $course->duration_minutes ?? 0 }}m
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #64748b;">
                                @if($course->homework)
                                    <i class="fas fa-check" style="color: #10b981;"></i>
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
                                <div style="width: 100px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $completionPercentage }}%; height: 100%; background: #14b8a6; transition: width 0.3s;"></div>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.courses.edit', $course) }}" 
                                       style="padding: 8px; color: #f59e0b; text-decoration: none; border-radius: 4px;"
                                       onmouseover="this.style.background='#fef3c7'" 
                                       onmouseout="this.style.background='transparent'">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this course?')">
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
                            <td colspan="13" style="padding: 40px; text-align: center; color: #64748b;">
                                <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>No courses found</p>
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
                        < Previous
                    </a>
                @endif
                @if($courses->nextPageUrl())
                    <a href="{{ $courses->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" 
                       style="padding: 10px 20px; background: #06b6d4; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        Next >
                    </a>
                @endif
            </div>
            <a href="{{ route('admin.courses.create') }}" 
               style="padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Add a course
            </a>
        </div>
    </div>
@endsection
