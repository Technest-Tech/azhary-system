@extends('admin.layouts.app')

@section('title', 'Create Course')
@section('page-title', 'Create New Course')

@section('content')
    <!-- Main Form -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 1200px; margin: 0 auto;">
        <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
            @csrf
            
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
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                                placeholder="Student name" readonly>
                        <input type="hidden" name="student_id" id="student_id" required>
                        <select id="student_select" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                                onchange="document.getElementById('student_id').value = this.value; document.getElementById('student_name').value = this.options[this.selectedIndex].text.split(' (')[0];">
                            <option value="">Select a student</option>
                            @if(old('teacher_id'))
                                @foreach($students->where('teacher_id', old('teacher_id')) as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div id="student_loading" style="display: none; margin-top: 8px; color: #64748b; font-size: 12px;">
                            <i class="fas fa-spinner fa-spin"></i> Loading students...
                        </div>
                        <div id="student_error" style="display: none; margin-top: 8px; color: #dc2626; font-size: 12px;"></div>
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
                                   value="{{ old('class_time') }}" required>
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
                                    <option value="{{ $subject->name }}" {{ old('course_type') == $subject->name ? 'selected' : '' }}>
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
                                   value="{{ old('course_date', date('Y-m-d')) }}" required>
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
                                    <option value="{{ $i }}" {{ old('duration_hours', 1) == $i ? 'selected' : '' }}>{{ $i }}h</option>
                                @endfor
                            </select>
                            <select name="duration_minutes" id="duration_minutes" 
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                <option value="0" {{ old('duration_minutes', 0) == 0 ? 'selected' : '' }}>00m</option>
                                <option value="15" {{ old('duration_minutes') == 15 ? 'selected' : '' }}>15m</option>
                                <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30m</option>
                                <option value="45" {{ old('duration_minutes') == 45 ? 'selected' : '' }}>45m</option>
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
                            <option value="Present" {{ old('status', 'Present') == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                            <option value="Free" {{ old('status') == 'Free' ? 'selected' : '' }}>Free</option>
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
                               value="{{ old('homework') }}" placeholder="Assigned homework">
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
                                <option value="{{ $evaluation->id }}" {{ old('evaluation_id') == $evaluation->id ? 'selected' : '' }}>
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
                                  placeholder="Content covered in this course">{{ old('content') }}</textarea>
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
                                  placeholder="Additional notes">{{ old('notes') }}</textarea>
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

                    <!-- Course Name (hidden but required) -->
                    <input type="hidden" name="name" id="name" value="{{ old('name', 'Course') }}" required>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('admin.dashboard') }}" 
                   style="padding: 14px 28px; background: #f1f5f9; color: #475569; border: 2px solid #e2e8f0; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" id="submitBtn" onclick="showLoadingOverlay()"
                        style="padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;">
                    <i class="fas fa-save" id="submitIcon"></i>
                    <span id="submitText">Create Course</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; visibility: hidden; opacity: 0; transition: opacity 0.3s ease;">
        <div style="background: white; border-radius: 20px; padding: 48px 56px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: slideUp 0.4s ease;">
            <div style="margin-bottom: 24px;">
                <div class="loading-spinner" style="width: 56px; height: 56px; border: 4px solid #e5e7eb; border-top: 4px solid #10b981; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto;"></div>
            </div>
            <h3 style="color: #1f2937; font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">Creating Course...</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Generating report and sending to WhatsApp.<br>Please wait, this may take a few seconds.</p>
        </div>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        // Store old student_id for restoration after AJAX load
        var oldStudentId = @json(old('student_id'));
        
        // Function to load students by teacher ID
        function loadStudentsByTeacher(teacherId) {
            var studentSelect = document.getElementById('student_select');
            var studentId = document.getElementById('student_id');
            var studentName = document.getElementById('student_name');
            var studentLoading = document.getElementById('student_loading');
            var studentError = document.getElementById('student_error');
            
            // Reset student fields
            studentSelect.innerHTML = '<option value="">Select a student</option>';
            studentId.value = '';
            studentName.value = '';
            studentError.style.display = 'none';
            
            if (!teacherId) {
                return;
            }
            
            // Show loading
            studentLoading.style.display = 'block';
            studentSelect.disabled = true;
            
            // Fetch students via AJAX
            fetch('/admin/teachers/' + teacherId + '/students', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                studentLoading.style.display = 'none';
                studentSelect.disabled = false;
                
                if (data.students && data.students.length > 0) {
                    data.students.forEach(function(student) {
                        var option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.name;
                        // Preserve selected student if it matches old value
                        if (oldStudentId && student.id == oldStudentId) {
                            option.selected = true;
                            studentId.value = student.id;
                            studentName.value = student.name;
                        }
                        studentSelect.appendChild(option);
                    });
                } else {
                    studentSelect.innerHTML = '<option value="">No students found for this teacher</option>';
                }
            })
            .catch(error => {
                studentLoading.style.display = 'none';
                studentSelect.disabled = false;
                studentError.textContent = 'Error loading students. Please try again.';
                studentError.style.display = 'block';
                console.error('Error:', error);
            });
        }
        
        // AJAX: Load students when teacher is selected
        document.getElementById('teacher_id').addEventListener('change', function() {
            oldStudentId = null; // Clear old value when teacher changes
            loadStudentsByTeacher(this.value);
        });
        
        // Load students on page load if teacher is already selected
        document.addEventListener('DOMContentLoaded', function() {
            var teacherSelect = document.getElementById('teacher_id');
            if (teacherSelect.value) {
                loadStudentsByTeacher(teacherSelect.value);
            }
        });
        
        // Function to show loading overlay
        function showLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
                overlay.style.visibility = 'visible';
                overlay.style.opacity = '1';
                
                // Disable all form inputs
                var form = document.querySelector('form[method="POST"]');
                if (form) {
                    var inputs = form.querySelectorAll('input, select, textarea, button');
                    inputs.forEach(function(input) {
                        if (input.type !== 'hidden' && input.type !== 'submit') {
                            input.disabled = true;
                            input.style.pointerEvents = 'none';
                            input.style.opacity = '0.6';
                            input.style.cursor = 'not-allowed';
                        }
                    });
                }
                
                // Prevent any interaction with the overlay
                overlay.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
                
                overlay.onmousedown = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
            }
        }
        
        // Form submit handler
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    showLoadingOverlay();
                });
            }
        });
    </script>
@endsection

