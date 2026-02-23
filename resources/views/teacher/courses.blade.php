@extends('teacher.layouts.app')

@section('title', __('teacher.courses_management'))
@section('page-title', __('teacher.courses_management'))

@section('content')
    <!-- Enhanced Filters -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border: 1px solid #e2e8f0;">
        <div class="card-header" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border-radius: 12px 12px 0 0; margin: -1px -1px 0 -1px;">
            <h3 class="card-title" style="color: white; display: flex; align-items: center; gap: 12px; margin: 0;">
                <i class="fas fa-filter" style="color: #93c5fd;"></i>
                {{ __('teacher.advanced_filters') }}
            </h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form method="GET" action="{{ route('teacher.courses') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; align-items: end;">
                <!-- Student Filter -->
                <div style="position: relative;">
                    <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-user-graduate" style="color: #3b82f6; margin-right: 6px;"></i>
                        Student
                    </label>
                    <div style="position: relative;">
                        <select name="student_id" id="student_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px;">
                            <option value="">{{ __('teacher.all_students') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div style="position: relative;">
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-check-circle" style="color: #34d399; margin-right: 6px;"></i>
                        {{ __('teacher.status') }}
                    </label>
                    <select name="status" id="status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px;">
                        <option value="">{{ __('teacher.all_status') }}</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>✅ {{ __('teacher.present') }}</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>❌ {{ __('teacher.absent') }}</option>
                        <option value="Free" {{ request('status') == 'Free' ? 'selected' : '' }}>🎁 {{ __('teacher.free') }}</option>
                    </select>
                </div>
                
                <!-- Course Type Filter -->
                <div style="position: relative;">
                    <label for="course_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-book" style="color: #8b5cf6; margin-right: 6px;"></i>
                        {{ __('teacher.course_type') }}
                    </label>
                    <select name="course_type" id="course_type" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px;">
                        <option value="">{{ __('teacher.all_types') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}" {{ request('course_type') == $subject->name ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date From Filter -->
                <div style="position: relative;">
                    <label for="date_from" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-calendar-alt" style="color: #fbbf24; margin-right: 6px;"></i>
                        {{ __('teacher.from_date') }}
                    </label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s;">
                </div>
                
                <!-- Date To Filter -->
                <div style="position: relative;">
                    <label for="date_to" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-calendar-check" style="color: #34d399; margin-right: 6px;"></i>
                        {{ __('teacher.to_date') }}
                    </label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s;">
                </div>
                
                <!-- Per page -->
                <div style="position: relative;">
                    <label for="per_page" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                        <i class="fas fa-list" style="color: #64748b; margin-right: 6px;"></i>
                        {{ __('teacher.per_page') }}
                    </label>
                    <select name="per_page" id="per_page" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; transition: all 0.3s; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px;">
                        <option value="10" {{ request('per_page', 100) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 100) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 100) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>{{ __('teacher.all') }}</option>
                    </select>
                </div>
                
                <!-- Filter Button -->
                <div style="display: flex; gap: 12px; align-items: end;">
                    <button type="submit" style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                        <i class="fas fa-search"></i>
                        {{ __('teacher.apply_filters') }}
                    </button>
                    <a href="{{ route('teacher.courses') }}" style="padding: 12px 16px; background: #f1f5f9; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-times"></i>
                        {{ __('teacher.clear') }}
                    </a>
                </div>
            </form>
            
            <!-- Active Filters Display -->
            @if(request()->hasAny(['student_id', 'status', 'course_type', 'date_from', 'date_to']))
                <div style="margin-top: 20px; padding: 16px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                        <span style="font-weight: 600; color: #1e40af;">{{ __('teacher.active_filters') }}:</span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if(request('student_id'))
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-user-graduate"></i>
                                {{ __('teacher.student') }}: {{ $students->where('id', request('student_id'))->first()->name ?? 'Unknown' }}
                            </span>
                        @endif
                        @if(request('status'))
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-check-circle"></i>
                                {{ __('teacher.status') }}: {{ request('status') === 'Present' ? __('teacher.present') : (request('status') === 'Absent' ? __('teacher.absent') : __('teacher.late')) }}
                            </span>
                        @endif
                        @if(request('course_type'))
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f3e8ff; color: #7c3aed; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-book"></i>
                                {{ __('teacher.type') }}: {{ request('course_type') }}
                            </span>
                        @endif
                        @if(request('date_from'))
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-calendar-alt"></i>
                                {{ __('teacher.from_date') }}: {{ request('date_from') }}
                            </span>
                        @endif
                        @if(request('date_to'))
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-calendar-check"></i>
                                {{ __('teacher.to_date') }}: {{ request('date_to') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Advanced Courses Table -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-bottom: 1px solid #e2e8f0; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 12px; margin: 0; color: #1e293b; font-size: 20px; font-weight: 700;">
                        <i class="fas fa-graduation-cap" style="color: #3b82f6;"></i>
                        {{ __('teacher.my_courses') }}
                    </h3>
                    <p style="color: #64748b; margin: 8px 0 0 0; font-size: 14px;">{{ __('teacher.manage_teaching_courses') }}</p>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($courses->count() > 0)
                <div style="overflow-x: auto; overflow-y: visible; -webkit-overflow-scrolling: touch;">
                    <table style="width: 100%; min-width: 1200px; border-collapse: separate; border-spacing: 0; white-space: nowrap;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-hashtag" style="color: #3b82f6; margin-right: 6px;"></i>
                                    {{ __('teacher.no') }} / {{ __('teacher.round') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-user-graduate" style="color: #34d399; margin-right: 6px;"></i>
                                    {{ __('teacher.student') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-book" style="color: #8b5cf6; margin-right: 6px;"></i>
                                    {{ __('teacher.course_name') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-tag" style="color: #fbbf24; margin-right: 6px;"></i>
                                    {{ __('teacher.type') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-calendar-alt" style="color: #f87171; margin-right: 6px;"></i>
                                    {{ __('teacher.date_time') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-clock" style="color: #6366f1; margin-right: 6px;"></i>
                                    {{ __('teacher.duration') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-check-circle" style="color: #34d399; margin-right: 6px;"></i>
                                    {{ __('teacher.status') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-dollar-sign" style="color: #059669; margin-right: 6px;"></i>
                                    {{ __('teacher.income') }}
                                </th>
                                <th style="padding: 20px 16px; text-align: center; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-cog" style="color: #64748b; margin-right: 6px;"></i>
                                    {{ __('teacher.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                @php
                                    $studentColor = $course->student->display_color ?? '#1565c0';
                                    $darkerColor = \App\Services\StudentColorService::getDarkerShade($studentColor);
                                    $roundNum = (int)($course->round ?? 0);
                                    $roundLabel = $roundNum === 0 ? __('teacher.round') . ' 0' : __('teacher.round') . ' ' . $roundNum;
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9; white-space: nowrap;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <span style="font-weight: 700; color: #1e293b; font-size: 16px;">{{ number_format($course->n_value, 2) }}</span>
                                        <span style="display: inline-block; margin-left: 8px; padding: 4px 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $roundLabel }}</span>
                                    </td>
                                        <td style="padding: 20px 16px; white-space: nowrap;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, {{ $studentColor }} 0%, {{ $darkerColor }} 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">{{ substr($course->student->name, 0, 1) }}</div>
                                                <div style="white-space: nowrap;">
                                                    <span style="display: inline-block; padding: 6px 12px; background: {{ $studentColor }}; color: #fff; font-weight: 600; font-size: 14px; border-radius: 8px;">{{ $course->student->name }}</span>
                                                    <span style="font-size: 12px; color: #334155; font-weight: 600; margin-left: 8px;">Package: {{ $course->student->package_number }} lessons</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 20px 16px; white-space: nowrap;"><span style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $course->name }}</span> <span style="font-size: 12px; color: #64748b;">Created {{ $course->created_at->diffForHumans() }}</span></td>
                                        <td style="padding: 20px 16px; white-space: nowrap;"><span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #7c3aed; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid #d8b4fe;"><i class="fas fa-book"></i> {{ $course->course_type }}</span></td>
                                        <td style="padding: 20px 16px; white-space: nowrap;"><span style="font-weight: 600; color: #1e293b; font-size: 14px;"><i class="fas fa-calendar" style="color: #fbbf24; margin-right: 6px;"></i>{{ $course->course_date->format('M d, Y') }}</span> <span style="font-size: 12px; color: #64748b;"><i class="fas fa-clock" style="color: #34d399; margin-right: 4px;"></i>{{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}</span></td>
                                        <td style="padding: 20px 16px; white-space: nowrap;"><span style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $course->duration_hours }}h {{ $course->duration_minutes }}m</span> <span style="font-size: 12px; color: #64748b;">{{ $course->total_hours }}h total</span></td>
                                        <td style="padding: 20px 16px; white-space: nowrap;">
                                            @php
                                                $statusLabel = $course->status === 'Present' ? __('teacher.present') : ($course->status === 'Absent' ? __('teacher.absent') : __('teacher.free'));
                                                $adminLabel = $course->admin_status === 'approved' ? __('teacher.approved') : ($course->admin_status === 'pending' ? __('teacher.pending') : ($course->admin_status === 'rejected' ? __('teacher.rejected') : 'Approved'));
                                                $combined = $statusLabel . ' · ' . $adminLabel;
                                                if ($course->status === 'Present' && $course->admin_status === 'approved') {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534;';
                                                } elseif ($course->status === 'Present' && $course->admin_status === 'pending') {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706;';
                                                } elseif ($course->status === 'Absent') {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626;';
                                                } elseif ($course->status === 'Free') {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); color: #374151;';
                                                } elseif ($course->admin_status === 'rejected') {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626;';
                                                } else {
                                                    $badgeStyle = 'background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534;';
                                                }
                                            @endphp
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; {{ $badgeStyle }} border-radius: 25px; font-size: 12px; font-weight: 700;">{{ $combined }}</span>
                                        </td>
                                        <td style="padding: 20px 16px; white-space: nowrap;"><span style="font-weight: 700; color: #1e293b; font-size: 16px;">${{ number_format($course->income, 2) }}</span></td>
                                        <td style="padding: 20px 16px; white-space: nowrap;">
                                            <div style="display: flex; gap: 8px; justify-content: center;">
                                                <button type="button" onclick="event.stopPropagation(); openEditCourseModal({{ $course->id }})" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706; border: 1px solid #fde68a; border-radius: 8px; cursor: pointer;" title="Edit"><i class="fas fa-edit" style="font-size: 14px;"></i></button>
                                                <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('{{ __('teacher.delete_confirm') }}');" onclick="event.stopPropagation();">@csrf @method('DELETE')<button type="submit" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; cursor: pointer;" title="Delete"><i class="fas fa-trash" style="font-size: 14px;"></i></button></form>
                                            </div>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer with Summary -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-top: 1px solid #e2e8f0; padding: 20px 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(59, 130, 246, 0.1); border-radius: 20px; border: 1px solid rgba(59, 130, 246, 0.2);">
                                <i class="fas fa-list" style="color: #3b82f6;"></i>
                                <span style="font-weight: 600; color: #1e40af; font-size: 14px;">{{ __('teacher.courses_count', ['count' => $courses->count()]) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(16, 185, 129, 0.1); border-radius: 20px; border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-clock" style="color: #34d399;"></i>
                                <span style="font-weight: 600; color: #166534; font-size: 14px;">{{ __('teacher.total_hours', ['hours' => $courses->sum('total_hours')]) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(5, 150, 105, 0.1); border-radius: 20px; border: 1px solid rgba(5, 150, 105, 0.2);">
                                <i class="fas fa-dollar-sign" style="color: #059669;"></i>
                                <span style="font-weight: 600; color: #166534; font-size: 14px;">{{ __('teacher.earned_amount', ['amount' => number_format($courses->sum('income'), 2)]) }}</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 12px;">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ __('teacher.showing_courses', ['count' => $courses->count(), 'total' => $courses->total()]) }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 80px 24px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                        <i class="fas fa-book-open" style="font-size: 48px; color: #94a3b8;"></i>
                    </div>
                    <h3 style="color: #374151; margin-bottom: 12px; font-size: 24px; font-weight: 700;">{{ __('teacher.no_courses_found') }}</h3>
                    <p style="color: #64748b; margin-bottom: 32px; font-size: 16px; max-width: 400px; margin-left: auto; margin-right: auto; line-height: 1.6;">{{ __('teacher.start_creating') }}</p>
                    <a href="{{ route('teacher.courses.create') }}" style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 32px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s; text-decoration: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'">
                        <i class="fas fa-plus"></i> {{ __('teacher.create_first_course') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Fixed Add Course Button -->
    <button type="button" onclick="openCourseModal()"
       style="position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); z-index: 1000; transition: all 0.3s; border: none; cursor: pointer; font-size: 14px;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'">
        <i class="fas fa-plus"></i> {{ __('teacher.add_course') }}
    </button>

    <!-- ==================== COURSE CREATE/EDIT MODAL ==================== -->
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
                            <!-- Student Selection (real-time search) -->
                            <div style="position: relative; overflow: visible;">
                                <label for="modal_student_search" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    <i class="fas fa-user" style="color: #3b82f6;"></i> Student
                                </label>
                                <input type="text" id="modal_student_search" autocomplete="off" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;" placeholder="Type to search student...">
                                <input type="hidden" name="student_id" id="modal_student_id" required>
                                <input type="hidden" name="student_name" id="modal_student_name" value="">
                                <div id="modal_student_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 4px; max-height: 220px; overflow-y: auto; background: white; border: 2px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 9999;"></div>
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
    <div id="successToast" style="display: none; position: fixed; top: 24px; right: 24px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); z-index: 4000; font-weight: 600; font-size: 14px; align-items: center; gap: 10px; animation: slideDown 0.3s ease;">
        <i class="fas fa-check-circle"></i>
        <span id="successToastText">Course saved successfully!</span>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

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
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ===================== STUDENT REAL-TIME SEARCH =====================
        window.modalStudentsList = @json($students->map(function($s) { return ['id' => $s->id, 'name' => $s->name]; })->values());
        function renderModalStudentDropdown(query) {
            var dropdown = document.getElementById('modal_student_dropdown');
            if (!dropdown) return;
            var q = String(query || '').trim().toLowerCase();
            var list = window.modalStudentsList || [];
            var filtered = !q ? list : list.filter(function(s) { return String(s.name || '').toLowerCase().indexOf(q) !== -1; });
            if (filtered.length === 0) {
                dropdown.innerHTML = '<div style="padding: 12px 16px; color: #64748b; font-size: 14px;">No matching students</div>';
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

        // ===================== COURSE MODAL FUNCTIONS =====================
        function openCourseModal() {
            document.getElementById('courseFormMethod').value = 'POST';
            document.getElementById('courseFormCourseId').value = '';
            document.getElementById('courseForm').reset();
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
            
            document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-plus-circle" style="color: #3b82f6;"></i><span>Create New Course</span>';
            
            clearFormErrors();
            document.getElementById('courseModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditCourseModal(courseId) {
            openCourseModal();
            document.getElementById('courseModalTitle').innerHTML = '<i class="fas fa-edit" style="color: #f59e0b;"></i><span>Edit Course</span>';
            document.getElementById('courseFormMethod').value = 'PUT';
            document.getElementById('courseFormCourseId').value = courseId;
            
            document.getElementById('btnSaveOnly').disabled = true;
            document.getElementById('btnSaveWhatsapp').disabled = true;
            
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
                    closeCourseModal();
                    showSuccessToast(data.message || 'Course saved successfully!');
                    
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

        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showSuccessToast(@json(session('success')));
            });
        @endif

        // ===================== ROUNDS MODAL =====================
        function showRoundsModal(studentId) {
            const modal = document.getElementById('roundsModal');
            const content = document.getElementById('roundsModalContent');
            const title = document.getElementById('roundsModalTitle');
            
            modal.style.display = 'flex';
            content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #3b82f6;"></i><p style="margin-top: 16px; color: #64748b;">Loading rounds...</p></div>';
            
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
        
        // Close modal when clicking outside
        document.getElementById('roundsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRoundsModal();
            }
        });
        
    </script>
@endsection
