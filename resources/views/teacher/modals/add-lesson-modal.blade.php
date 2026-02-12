<form method="POST" action="{{ route('teacher.courses.store') }}" enctype="multipart/form-data" id="addLessonForm">
    @csrf
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Student Name -->
            <div>
                <label for="modal_student_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user" style="color: #3b82f6;"></i>
                    {{ __('teacher.name') }}
                </label>
                <input type="text" name="student_name" id="modal_student_name" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                       placeholder="{{ __('teacher.student_name') }}" readonly>
                <input type="hidden" name="student_id" id="modal_student_id" required>
                <select id="modal_student_select" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                        onchange="document.getElementById('modal_student_id').value = this.value; document.getElementById('modal_student_name').value = this.options[this.selectedIndex].text.split(' (')[0];">
                    <option value="">{{ __('teacher.select_a_student') }}</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
                @error('student_id')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Class Time -->
            <div>
                <label for="modal_class_time" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clock" style="color: #3b82f6;"></i>
                    {{ __('teacher.class_time') }}
                </label>
                <div style="position: relative;">
                    <input type="time" name="class_time" id="modal_class_time" 
                           style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           required>
                    <i class="fas fa-clock" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                </div>
                @error('class_time')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Course -->
            <div>
                <label for="modal_course_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-book" style="color: #3b82f6;"></i>
                    {{ __('teacher.course') }}
                </label>
                <div style="position: relative;">
                    <select name="course_type" id="modal_course_type" 
                            style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                        <option value="">{{ __('teacher.select_a_course') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
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
                <label for="modal_course_date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar" style="color: #3b82f6;"></i>
                    {{ __('teacher.date') }}
                </label>
                <div style="position: relative;">
                    <input type="date" name="course_date" id="modal_course_date" 
                           style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           required>
                    <i class="fas fa-calendar" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                </div>
                @error('course_date')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Duration -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-stopwatch" style="color: #3b82f6;"></i>
                    {{ __('teacher.duration') }}
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <select name="duration_hours" id="modal_duration_hours" 
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                        @for($i = 0; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}h</option>
                        @endfor
                    </select>
                    <select name="duration_minutes" id="modal_duration_minutes" 
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                        <option value="0" selected>00m</option>
                        <option value="15">15m</option>
                        <option value="30">30m</option>
                        <option value="45">45m</option>
                    </select>
                </div>
                @error('duration_hours')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="modal_status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-check" style="color: #3b82f6;"></i>
                    {{ __('teacher.status') }}
                </label>
                <select name="status" id="modal_status" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                    <option value="Present" selected>{{ __('teacher.present') }}</option>
                    <option value="Absent">{{ __('teacher.absent') }}</option>
                    <option value="Free">{{ __('teacher.free') }}</option>
                </select>
                @error('status')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Homework -->
            <div>
                <label for="modal_homework" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clipboard-list" style="color: #3b82f6;"></i>
                    {{ __('teacher.homework') }}
                </label>
                <input type="text" name="homework" id="modal_homework" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                       placeholder="{{ __('teacher.homework_placeholder') }}">
                @error('homework')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Evaluation -->
            <div>
                <label for="modal_evaluation_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sun" style="color: #3b82f6;"></i>
                    {{ __('teacher.evaluation') }}
                </label>
                <select name="evaluation_id" id="modal_evaluation_id" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;">
                    <option value="">{{ __('teacher.select_an_evaluation') }}</option>
                    @foreach($evaluations as $evaluation)
                        <option value="{{ $evaluation->id }}">{{ $evaluation->name }} : {{ $evaluation->max_percentage }} %</option>
                    @endforeach
                </select>
                @error('evaluation_id')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="modal_content" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-file-alt" style="color: #3b82f6;"></i>
                    {{ __('teacher.content') }}
                </label>
                <textarea name="content" id="modal_content" rows="4" 
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                          placeholder="{{ __('teacher.content_placeholder') }}"></textarea>
                @error('content')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="modal_notes" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sticky-note" style="color: #3b82f6;"></i>
                    {{ __('teacher.notes') }}
                </label>
                <textarea name="notes" id="modal_notes" rows="4" 
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; resize: vertical;"
                          placeholder="{{ __('teacher.notes_placeholder') }}"></textarea>
                @error('notes')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Souvenir -->
            <div>
                <label for="modal_souvenir_image" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-camera" style="color: #3b82f6;"></i>
                    {{ __('teacher.souvenir') }}
                </label>
                <div style="position: relative;">
                    <input type="text" id="modal_souvenir_image_text" 
                           style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           placeholder="{{ __('teacher.souvenir_placeholder') }}" readonly>
                    <input type="file" name="souvenir_image" id="modal_souvenir_image" accept="image/*" 
                           style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;"
                           onchange="document.getElementById('modal_souvenir_image_text').value = this.files[0] ? this.files[0].name : '';">
                    <i class="fas fa-folder" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"></i>
                </div>
                @error('souvenir_image')
                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Course Name (hidden but required) -->
            <input type="hidden" name="name" value="Course">
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
        <button type="button" onclick="closeAddLessonModal()" 
                style="padding: 14px 28px; background: #64748b; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            {{ __('teacher.cancel') }}
        </button>
        <button type="submit" name="save_only" value="1" id="modalSubmitBtn"
                style="padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;">
            <i class="fas fa-save" id="modalSubmitIcon"></i>
            <span id="modalSubmitText">{{ __('teacher.save_course') }}</span>
        </button>
    </div>
</form>

<!-- Loading Overlay for Modal -->
<div id="modalLoadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 99999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 48px 56px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: modalSlideUp 0.4s ease;">
        <div style="margin-bottom: 24px;">
            <div style="width: 56px; height: 56px; border: 4px solid #e5e7eb; border-top: 4px solid #10b981; border-radius: 50%; animation: modalSpin 0.8s linear infinite; margin: 0 auto;"></div>
        </div>
        <h3 style="color: #1f2937; font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">{{ __('teacher.create_course') }}...</h3>
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Generating report and sending to WhatsApp.<br>Please wait, this may take a few seconds.</p>
    </div>
</div>

<style>
    @keyframes modalSpin { to { transform: rotate(360deg); } }
    @keyframes modalSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    document.getElementById('addLessonForm').addEventListener('submit', function(e) {
        var btn = document.getElementById('modalSubmitBtn');
        var overlay = document.getElementById('modalLoadingOverlay');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';
        document.getElementById('modalSubmitIcon').className = '';
        document.getElementById('modalSubmitIcon').style.cssText = 'width:16px;height:16px;border:3px solid rgba(255,255,255,0.3);border-top:3px solid white;border-radius:50%;animation:modalSpin 0.8s linear infinite;';
        document.getElementById('modalSubmitText').textContent = 'Processing...';
        overlay.style.display = 'flex';
    });
</script>
