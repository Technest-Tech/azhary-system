<form id="recurringCourseForm" onsubmit="event.preventDefault(); submitRecurringForm();">
    @csrf
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Student Name -->
            <div>
                <label for="recurring_student_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user" style="color: #3b82f6;"></i>
                    Student Name
                </label>
                <input type="text" id="recurring_student_name" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                       placeholder="Search for a student..." readonly>
                <input type="hidden" name="student_id" id="recurring_student_id" required>
                <select id="recurring_student_select" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; margin-top: 8px;"
                        onchange="document.getElementById('recurring_student_id').value = this.value; document.getElementById('recurring_student_name').value = this.options[this.selectedIndex].text.split(' (')[0];"
                        required>
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Time -->
            <div>
                <label for="recurring_class_time" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clock" style="color: #3b82f6;"></i>
                    Time
                </label>
                <div style="position: relative;">
                    <input type="time" name="class_time" id="recurring_class_time" 
                           style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           value="10:00" required>
                    <i class="fas fa-clock" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                </div>
            </div>

            <!-- Course -->
            <div>
                <label for="recurring_course_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-book" style="color: #3b82f6;"></i>
                    Course
                </label>
                <div style="position: relative;">
                    <select name="course_type" id="recurring_course_type" 
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
            </div>

            <!-- Start Date -->
            <div>
                <label for="recurring_start_date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar" style="color: #3b82f6;"></i>
                    Start Date
                </label>
                <div style="position: relative;">
                    <input type="date" name="start_date" id="recurring_start_date" 
                           style="width: 100%; padding: 12px 48px 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                           value="{{ date('Y-m-d') }}" required>
                    <i class="fas fa-calendar" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Day of Week -->
            <div>
                <label for="recurring_day_of_week" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar-week" style="color: #3b82f6;"></i>
                    Day of the week
                </label>
                <select name="day_of_week" id="recurring_day_of_week" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>

            <!-- Duration -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-stopwatch" style="color: #3b82f6;"></i>
                    Duration
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <select name="duration_hours" id="recurring_duration_hours" 
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                        @for($i = 0; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}h</option>
                        @endfor
                    </select>
                    <select name="duration_minutes" id="recurring_duration_minutes" 
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" required>
                        <option value="0" selected>0m</option>
                        <option value="15">15m</option>
                        <option value="30">30m</option>
                        <option value="45">45m</option>
                    </select>
                </div>
            </div>

            <!-- Repetition Type -->
            <div>
                <label for="recurring_repetition_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-redo" style="color: #3b82f6;"></i>
                    Repetition Type
                </label>
                <select name="recurrence_type" id="recurring_repetition_type" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; appearance: none;" 
                        onchange="toggleRecurrenceValue()" required>
                    <option value="weekly">Weekly</option>
                    <option value="weeks_count">For a number of weeks</option>
                    <option value="months_count">For a number of months</option>
                    <option value="endless">Always (Endless)</option>
                </select>
            </div>

            <!-- Recurrence Value (conditional) -->
            <div id="recurrence_value_container" style="display: none;">
                <label for="recurring_recurrence_value" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                    Number
                </label>
                <input type="number" name="recurrence_value" id="recurring_recurrence_value" 
                       min="1" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;"
                       placeholder="Enter number">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div style="display: flex; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
        <button type="submit" 
                style="padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
            <i class="fas fa-sync-alt"></i>
            Generate Recurring Courses
        </button>
    </div>
</form>

<script>
function toggleRecurrenceValue() {
    const repetitionType = document.getElementById('recurring_repetition_type').value;
    const container = document.getElementById('recurrence_value_container');
    const input = document.getElementById('recurring_recurrence_value');
    
    if (repetitionType === 'weeks_count' || repetitionType === 'months_count') {
        container.style.display = 'block';
        input.required = true;
    } else {
        container.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}
</script>

