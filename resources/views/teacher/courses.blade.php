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
                    <table style="width: 100%; min-width: 1200px; border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                <th style="padding: 20px 16px; text-align: left; font-weight: 700; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">
                                    <i class="fas fa-hashtag" style="color: #3b82f6; margin-right: 6px;"></i>
                                    {{ __('teacher.no') }}
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
                                    <i class="fas fa-user-shield" style="color: #8b5cf6; margin-right: 6px;"></i>
                                    {{ __('teacher.admin_status') }}
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
                            @foreach($courses as $index => $course)
                                <tr style="background: {{ $index % 2 === 0 ? '#ffffff' : '#fafbfc' }}; border-bottom: 1px solid #f1f5f9; transition: all 0.3s;" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'" onmouseout="this.style.background='{{ $index % 2 === 0 ? '#ffffff' : '#fafbfc' }}'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <td style="padding: 20px 16px; font-weight: 700; color: #1e293b; font-size: 16px; white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                                {{ number_format($course->n_value, 2) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        @php
                                            $studentColor = $course->student->display_color ?? '#1565c0';
                                            $darkerColor = \App\Services\StudentColorService::getDarkerShade($studentColor);
                                        @endphp
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, {{ $studentColor }} 0%, {{ $darkerColor }} 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                                {{ substr($course->student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <span style="display: inline-block; padding: 6px 12px; background: {{ $studentColor }}; color: #fff; font-weight: 600; font-size: 14px; border-radius: 8px;">{{ $course->student->name }}</span>
                                                <div style="font-size: 12px; color: #334155; font-weight: 600; margin-top: 4px;">Package: {{ $course->student->package_number }} lessons</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <div style="font-weight: 600; color: #1e293b; font-size: 14px; margin-bottom: 4px;">{{ $course->name }}</div>
                                        <div style="font-size: 12px; color: #64748b;">Created {{ $course->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #7c3aed; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid #d8b4fe;">
                                            <i class="fas fa-book"></i>
                                            {{ $course->course_type }}
                                        </span>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            <div style="font-weight: 600; color: #1e293b; font-size: 14px;">
                                                <i class="fas fa-calendar" style="color: #fbbf24; margin-right: 6px;"></i>
                                                {{ $course->course_date->format('M d, Y') }}
                                            </div>
                                            <div style="font-size: 12px; color: #64748b;">
                                                <i class="fas fa-clock" style="color: #34d399; margin-right: 4px;"></i>
                                                {{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #d97706; font-weight: 700; font-size: 12px;">
                                                <i class="fas fa-hourglass-half"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $course->duration_hours }}h {{ $course->duration_minutes }}m</div>
                                                <div style="font-size: 12px; color: #64748b;">{{ $course->total_hours }}h total</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        @if($course->status === 'Present')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #bbf7d0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-check-circle"></i>
                                                {{ __('teacher.present') }}
                                            </span>
                                        @elseif($course->status === 'Absent')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #fecaca; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-times-circle"></i>
                                                {{ __('teacher.absent') }}
                                            </span>
                                        @elseif($course->status === 'Free')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); color: #374151; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #d1d5db; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-gift"></i>
                                                {{ __('teacher.free') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        @if($course->admin_status === 'approved')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #bbf7d0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-check-circle"></i>
                                                {{ __('teacher.approved') }}
                                            </span>
                                        @elseif($course->admin_status === 'pending')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #fde68a; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-clock"></i>
                                                {{ __('teacher.pending') }}
                                            </span>
                                        @elseif($course->admin_status === 'rejected')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #fecaca; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-times-circle"></i>
                                                {{ __('teacher.rejected') }}
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-radius: 25px; font-size: 12px; font-weight: 700; border: 1px solid #bbf7d0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                <i class="fas fa-check-circle"></i>
                                                Approved
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #166534; font-weight: 700; font-size: 12px;">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: #1e293b; font-size: 16px;">${{ number_format($course->income, 2) }}</div>
                                                <div style="font-size: 12px; color: #64748b;">{{ __('teacher.earned') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 20px 16px; white-space: nowrap;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="{{ route('teacher.courses.edit', $course) }}" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706; border: 1px solid #fde68a; border-radius: 8px; text-decoration: none; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'" title="Edit">
                                                <i class="fas fa-edit" style="font-size: 14px;"></i>
                                            </a>
                                            <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('{{ __('teacher.delete_confirm') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'" title="Delete">
                                                    <i class="fas fa-trash" style="font-size: 14px;"></i>
                                                </button>
                                            </form>
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
    <a href="{{ route('teacher.courses.create') }}" 
       style="position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); z-index: 1000; transition: all 0.3s;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'">
        <i class="fas fa-plus"></i> {{ __('teacher.add_course') }}
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
