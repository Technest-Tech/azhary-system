@extends('admin.layouts.app')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
            <a href="{{ route('admin.students') }}" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    <i class="fas fa-user-edit" style="color: #14b8a6; margin-right: 8px;"></i>
                    Edit Student
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    Update student information
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Name -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Full Name <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="Enter full name">
                    @error('name')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Email Address <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="student@example.com">
                    @error('email')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Phone Number <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="+1234567890">
                    @error('phone')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Section -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Section
                    </label>
                    <select name="section" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Select Section</option>
                        <option value="A" {{ old('section', $student->section) == 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ old('section', $student->section) == 'B' ? 'selected' : '' }}>Section B</option>
                        <option value="C" {{ old('section', $student->section) == 'C' ? 'selected' : '' }}>Section C</option>
                        <option value="D" {{ old('section', $student->section) == 'D' ? 'selected' : '' }}>Section D</option>
                    </select>
                    @error('section')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Package Number -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Package Number <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="number" name="package_number" value="{{ old('package_number', $student->package_number) }}" required min="1"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="1">
                    @error('package_number')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Course/Subject -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Course/Subject <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="subject_id" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Select Course</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $student->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hour Rate -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Hour Rate (USD)
                    </label>
                    <input type="number" name="hour_rate" value="{{ old('hour_rate', $student->hour_rate) }}" required min="0" step="0.01"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="25.00">
                    @error('hour_rate')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Package Rate -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Package Rate (USD) <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="number" name="package_rate" value="{{ old('package_rate', $student->package_rate) }}" required min="0" step="0.01"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="200.00">
                    @error('package_rate')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Assign Teacher -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Assign Teacher <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="teacher_id" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $student->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Teacher Rate -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Teacher Rate (USD)
                    </label>
                    <input type="number" name="teacher_rate" value="{{ old('teacher_rate', $student->teacher_rate) }}" min="0" step="0.01"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="15.00">
                    @error('teacher_rate')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        New Password <span style="color: #64748b; font-weight: 400;">(Leave empty to keep current password)</span>
                    </label>
                    <input type="password" name="password"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="Enter new password (min. 8 characters)">
                    @error('password')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <button type="submit" class="btn" style="background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); color: white;">
                    <i class="fas fa-save"></i>
                    Update Student
                </button>
                <a href="{{ route('admin.students') }}" class="btn" style="background: #f1f5f9; color: #64748b; text-decoration: none;">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

