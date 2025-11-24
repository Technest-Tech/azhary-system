@extends('admin.layouts.app')

@section('title', 'Students Management')
@section('page-title', 'Students Management')

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
                    All Students
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    Manage and view all enrolled students in the academy
                </p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="btn" style="background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); color: white; text-decoration: none;">
                <i class="fas fa-plus"></i>
                Add New Student
            </a>
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
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Total Students</p>
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
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Active</p>
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
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Sections</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $students->unique('section')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">Students List</h3>
                <div style="display: flex; gap: 12px;">
                    <input type="text" placeholder="Search students..." style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; width: 250px;">
                    <button class="btn" style="background: #f1f5f9; color: #475569; padding: 8px 16px;">
                        <i class="fas fa-filter"></i>
                        Filter
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
                                ID
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Student
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Email
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Phone
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Section
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Package
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Teacher
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Rates
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Actions
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
                                <span class="badge badge-info">Section {{ $student->section }}</span>
                            </td>
                            <td style="padding: 16px; color: #475569; font-size: 14px; white-space: nowrap;">
                                Package #{{ $student->package_number }}
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
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Not Assigned</span>
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
                                    <a href="{{ route('admin.students.edit', $student->id) }}" style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="Delete">
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
                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No students found</p>
                <p style="font-size: 14px; margin-bottom: 24px;">Start by enrolling your first student to the academy</p>
                <button class="btn" style="background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); color: white;">
                    <i class="fas fa-plus"></i>
                    Add Student
                </button>
            </div>
        @endif
    </div>
@endsection
