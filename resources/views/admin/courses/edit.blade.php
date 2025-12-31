@extends('admin.layouts.app')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('content')
    <!-- Main Form -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 1200px; margin: 0 auto;">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                <!-- Left Column -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Teacher Selection -->
                    <div>
                        <label for="teacher_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-chalkboard-teacher" style="color: #3b82f6;"></i>
                            Teacher
                        </label>
                        <select name="teacher_id" id="teacher_id" 
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                            <option value="">Select a teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Student Name (Nom) -->
                    <div>
                        <label for="student_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user" style="color: #3b82f6;"></i>
                            Name
                        </label>
                        <input type="text" name="student_name" id="student_name" 
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                               value="{{ old('student_name', $course->student->name ?? $course->student_name) }}" placeholder="Student name">
                        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id', $course->student_id) }}" required>
                        <select id="student_select" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                                onchange="document.getElementById('student_id').value = this.value; document.getElementById('student_name').value = this.options[this.selectedIndex].text.split(' (')[0];">
                            <option value="">Select a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $course->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Class Time (Heure de cours) -->
                    <div>
                        <label for="class_time" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="color: #3b82f6;"></i>
                            Class Time
                        </label>
                        <div style="position: relative;">
                            <input type="time" name="class_time" id="class_time" 
                                   style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                                   value="{{ old('class_time', \Carbon\Carbon::parse($course->class_time)->format('H:i')) }}" required>
                            <i class="fas fa-clock" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        </div>
                        @error('class_time')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Course (Cours) -->
                    <div>
                        <label for="course_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-book" style="color: #3b82f6;"></i>
                            Course
                        </label>
                        <div style="position: relative;">
                            <select name="course_type" id="course_type" 
                                    style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                <option value="">Select a course</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->name }}" {{ old('course_type', $course->course_type) == $subject->name ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-book" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        </div>
                        @error('course_type')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="course_date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-calendar" style="color: #3b82f6;"></i>
                            Date
                        </label>
                        <div style="position: relative;">
                            <input type="date" name="course_date" id="course_date" 
                                   style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                                   value="{{ old('course_date', $course->course_date->format('Y-m-d')) }}" required>
                            <i class="fas fa-calendar" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        </div>
                        @error('course_date')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Duration (Durée) -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-stopwatch" style="color: #3b82f6;"></i>
                            Duration
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <select name="duration_hours" id="duration_hours" 
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                @for($i = 0; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('duration_hours', $course->duration_hours) == $i ? 'selected' : '' }}>{{ $i }}h</option>
                                @endfor
                            </select>
                            <select name="duration_minutes" id="duration_minutes" 
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                <option value="0" {{ old('duration_minutes', $course->duration_minutes) == 0 ? 'selected' : '' }}>00m</option>
                                <option value="15" {{ old('duration_minutes', $course->duration_minutes) == 15 ? 'selected' : '' }}>15m</option>
                                <option value="30" {{ old('duration_minutes', $course->duration_minutes) == 30 ? 'selected' : '' }}>30m</option>
                                <option value="45" {{ old('duration_minutes', $course->duration_minutes) == 45 ? 'selected' : '' }}>45m</option>
                            </select>
                        </div>
                        @error('duration_hours')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status (Statut) -->
                    <div>
                        <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-check" style="color: #3b82f6;"></i>
                            Status
                        </label>
                        <select name="status" id="status" 
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                            <option value="Present" {{ old('status', $course->status) == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Pending" {{ old('status', $course->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Absent" {{ old('status', $course->status) == 'Absent' ? 'selected' : '' }}>Absent</option>
                            <option value="Late" {{ old('status', $course->status) == 'Late' ? 'selected' : '' }}>Late</option>
                        </select>
                        @error('status')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Homework (Le Devoir) -->
                    <div>
                        <label for="homework" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clipboard-list" style="color: #3b82f6;"></i>
                            Homework
                        </label>
                        <input type="text" name="homework" id="homework" 
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                               value="{{ old('homework', $course->homework) }}" placeholder="Assigned homework">
                        @error('homework')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Evaluation (Évaluation) -->
                    <div>
                        <label for="evaluation_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sun" style="color: #3b82f6;"></i>
                            Evaluation
                        </label>
                        <select name="evaluation_id" id="evaluation_id" 
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;">
                            <option value="">Select an evaluation</option>
                            @foreach($evaluations as $evaluation)
                                <option value="{{ $evaluation->id }}" {{ old('evaluation_id', $course->evaluation_id) == $evaluation->id ? 'selected' : '' }}>
                                    {{ $evaluation->name }} : {{ $evaluation->max_percentage }} %
                                </option>
                            @endforeach
                        </select>
                        @error('evaluation_id')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content (Contenu) -->
                    <div>
                        <label for="content" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-file-alt" style="color: #3b82f6;"></i>
                            Content
                        </label>
                        <textarea name="content" id="content" rows="4" 
                                  style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                                  placeholder="Content covered in this course">{{ old('content', $course->content) }}</textarea>
                        @error('content')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sticky-note" style="color: #3b82f6;"></i>
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                                  placeholder="Additional notes">{{ old('notes', $course->notes) }}</textarea>
                        @error('notes')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Souvenir (Image Upload) -->
                    <div>
                        <label for="souvenir_image" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-camera" style="color: #3b82f6;"></i>
                            Souvenir
                        </label>
                        @if($course->souvenir_image)
                            <div style="margin-bottom: 8px;">
                                <img src="{{ asset('storage/' . $course->souvenir_image) }}" alt="Souvenir" 
                                     style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                            </div>
                        @endif
                        <div style="position: relative;">
                            <input type="text" id="souvenir_image_text" 
                                   style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                                   placeholder="Paste an image here or click on the icon" readonly>
                            <input type="file" name="souvenir_image" id="souvenir_image" accept="image/*" 
                                   style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;"
                                   onchange="document.getElementById('souvenir_image_text').value = this.files[0] ? this.files[0].name : '';">
                            <i class="fas fa-folder" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"></i>
                        </div>
                        @error('souvenir_image')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Course Name -->
                    <input type="hidden" name="name" value="{{ old('name', $course->name) }}" required>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('admin.dashboard') }}" 
                   style="padding: 14px 28px; background: #f1f5f9; color: #475569; border: 2px solid #e2e8f0; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" 
                        style="padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-save"></i>
                    Update Course
                </button>
            </div>
        </form>
    </div>
@endsection

