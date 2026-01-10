@extends('teacher.layouts.app')

@section('title', __('teacher.create_course'))
@section('page-title', __('teacher.create_new_course'))

@section('content')
    <!-- Main Form -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 1200px; margin: 0 auto;">
        <form method="POST" action="{{ route('teacher.courses.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                <!-- Left Column -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Student Name (Nom) -->
                    <div>
                        <label for="student_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user" style="color: #3b82f6;"></i>
                            {{ __('teacher.name') }}
                        </label>
                        <input type="text" name="student_name" id="student_name" 
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                                placeholder="{{ __('teacher.student_name') }}" readonly>
                        <input type="hidden" name="student_id" id="student_id" required>
                        <select id="student_select" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                                onchange="document.getElementById('student_id').value = this.value; document.getElementById('student_name').value = this.options[this.selectedIndex].text.split(' (')[0];">
                            <option value="">{{ __('teacher.select_a_student') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                            {{ __('teacher.class_time') }}
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
                            {{ __('teacher.course') }}
                        </label>
                        <div style="position: relative;">
                            <select name="course_type" id="course_type" 
                                    style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                <option value="">{{ __('teacher.select_a_course') }}</option>
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
                            {{ __('teacher.date') }}
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
                            {{ __('teacher.duration') }}
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
                            {{ __('teacher.status') }}
                        </label>
                        <select name="status" id="status" 
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                                    <option value="Present" {{ old('status', 'Present') == 'Present' ? 'selected' : '' }}>{{ __('teacher.present') }}</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>{{ __('teacher.pending') }}</option>
                            <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>{{ __('teacher.absent') }}</option>
                            <option value="Late" {{ old('status') == 'Late' ? 'selected' : '' }}>{{ __('teacher.late') }}</option>
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
                            {{ __('teacher.homework') }}
                        </label>
                        <input type="text" name="homework" id="homework" 
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                               value="{{ old('homework') }}" placeholder="{{ __('teacher.homework') }}">
                        @error('homework')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Evaluation (Évaluation) -->
                    <div>
                        <label for="evaluation_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sun" style="color: #3b82f6;"></i>
                            {{ __('teacher.evaluation') }}
                        </label>
                        <select name="evaluation_id" id="evaluation_id" 
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;">
                            <option value="">{{ __('teacher.select_an_evaluation') }}</option>
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
                            {{ __('teacher.content') }}
                        </label>
                        <textarea name="content" id="content" rows="4" 
                                  style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                                  placeholder="{{ __('teacher.content') }}">{{ old('content') }}</textarea>
                        @error('content')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sticky-note" style="color: #3b82f6;"></i>
                            {{ __('teacher.notes') }}
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                                  placeholder="{{ __('teacher.notes') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Souvenir (Image Upload) -->
                    <div>
                        <label for="souvenir_image" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-camera" style="color: #3b82f6;"></i>
                            {{ __('teacher.souvenir') }}
                        </label>
                        <div style="position: relative;">
                            <input type="text" id="souvenir_image_text" 
                                   style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                                   placeholder="{{ __('teacher.souvenir_placeholder') }}" readonly>
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
                <button type="submit" name="save_only" value="1" 
                        style="padding: 14px 28px; background: #64748b; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-save"></i>
                    {{ __('teacher.save') }}
                </button>
                <button type="submit" name="generate_report" value="1" 
                        style="padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-file-alt"></i>
                    {{ __('teacher.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
