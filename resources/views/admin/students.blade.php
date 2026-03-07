@extends('admin.layouts.app')

@section('title', __('admin.students_management'))
@section('page-title', __('admin.students_management'))

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    <i class="fas fa-user-graduate" style="color: #14b8a6; margin-right: 8px;"></i>
                    {{ __('admin.all_students') }}
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    {{ __('admin.manage_view_students') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ccfbf1; display: flex; align-items: center; justify-content: center; color: #0f766e;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">{{ __('admin.total_students') }}</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $students->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">{{ __('admin.active') }}</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $students->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">{{ __('admin.sections') }}</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $students->unique('section')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">{{ __('admin.students_list') }}</h3>
                <div style="display: flex; gap: 12px;">
                    <input type="text" placeholder="{{ __('admin.search_students') }}" style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; width: 250px;">
                    <button class="btn" style="background: #f1f5f9; color: #475569; padding: 8px 16px;">
                        <i class="fas fa-filter"></i>
                        {{ __('admin.filter') }}
                    </button>
                </div>
            </div>
        </div>

        @if($students->count() > 0)
            <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
                <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.id') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.student') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.email') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.phone') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.section') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.package') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.teacher') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.rates') }}
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 16px; color: #64748b; font-weight: 600; font-size: 14px; white-space: nowrap;">
                                #{{ $student->id }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-weight: 600; color: #1e293b; font-size: 14px; margin: 0;">{{ $student->name }}</p>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">{{ $student->date_of_birth }}</p>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px; color: #475569; font-size: 14px; white-space: nowrap;">
                                <i class="fas fa-envelope" style="color: #94a3b8; margin-right: 8px;"></i>
                                {{ $student->email }}
                            </td>
                            <td style="padding: 16px; color: #475569; font-size: 14px; white-space: nowrap;">
                                <i class="fas fa-phone" style="color: #94a3b8; margin-right: 8px;"></i>
                                {{ $student->phone }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <span class="badge badge-info">{{ __('admin.section') }} {{ $student->section }}</span>
                            </td>
                            <td style="padding: 16px; color: #475569; font-size: 14px; white-space: nowrap;">
                                {{ __('admin.package') }} #{{ $student->package_number }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                @if($student->teacher)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px;">
                                            {{ strtoupper(substr($student->teacher->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p style="font-weight: 600; color: #1e293b; font-size: 13px; margin: 0;">{{ $student->teacher->name }}</p>
                                            @if($student->teacher_rate)
                                                <p style="font-size: 11px; color: #64748b; margin: 0;">${{ number_format($student->teacher_rate, 2) }}/hr</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ __('admin.not_assigned') }}</span>
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="font-size: 13px;">
                                    <div style="color: #64748b; margin-bottom: 4px;">
                                        <strong style="color: #1e293b;">${{ number_format($student->hour_rate, 2) }}</strong>/hr
                                    </div>
                                    <div style="color: #64748b;">
                                        <strong style="color: #1e293b;">${{ number_format($student->package_rate, 2) }}</strong>/pkg
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.students.edit', $student->id) }}" style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block;" title="{{ __('admin.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" style="display: inline;" onsubmit="return confirm('{{ __('admin.delete_student_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">{{ __('admin.no_students_found') }}</p>
                <p style="font-size: 14px; margin-bottom: 24px;">{{ __('admin.start_enrolling_student') }}</p>
                <a href="{{ route('admin.students.create') }}" class="btn" style="background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); color: white; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    {{ __('admin.add_student') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Fixed Add Student Button -->
    <a href="{{ route('admin.students.create') }}" 
       style="position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4); z-index: 1000; transition: all 0.3s; font-size: 14px;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(20, 184, 166, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(20, 184, 166, 0.4)'">
        <i class="fas fa-plus"></i> {{ __('admin.add_new_student') }}
    </a>
@endsection
