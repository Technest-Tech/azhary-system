@extends('teacher.layouts.app')

@section('title', __('teacher.timetable'))
@section('page-title', __('teacher.timetable'))

@section('styles')
<script>
    // Translations for JavaScript
    window.translations = {
        courseCreatedSuccess: @json(__('teacher.course_created_success')),
        courseDeletedSuccess: @json(__('teacher.course_deleted_success')),
        errorCreatingCourse: @json(__('teacher.error_creating_course')),
        errorDeletingCourse: @json(__('teacher.error_deleting_course')),
        errorTryAgain: @json(__('teacher.error_try_again')),
        deleteConfirm: @json(__('teacher.delete_confirm')),
        errorCreatingRecurring: @json(__('teacher.error_creating_recurring', ['message' => ''])),
        errorGeneratingEvents: @json(__('teacher.error_generating_events', ['message' => ''])),
    };
</script>
<style>
    .timetable-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .timetable-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .week-navigation {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .nav-button {
        padding: 8px 16px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .nav-button:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .today-button {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }

    .today-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .date-range {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .action-button {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-recurring {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .btn-recurring:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-refresh {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-refresh:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .view-toggles {
        display: flex;
        gap: 8px;
    }

    .view-toggle {
        padding: 8px 16px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .view-toggle.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: #10b981;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: 80px repeat(7, 1fr);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .calendar-header {
        background: #f8fafc;
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }

    .calendar-header.weekend {
        background: #f1f5f9;
    }

    .time-slot {
        background: #f8fafc;
        padding: 8px;
        text-align: center;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .calendar-cell {
        min-height: 60px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 4px;
        position: relative;
        cursor: crosshair;
        transition: background 0.1s;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    .calendar-cell:hover {
        background: #f1f5f9;
    }

    .calendar-cell.weekend {
        background: #fafafa;
    }

    .calendar-cell.weekend:hover {
        background: #f4f4f4;
    }

    .calendar-cell.selected {
        background: #dbeafe;
        border: 2px solid #3b82f6;
        box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.3);
    }

    .calendar-cell.selecting {
        background: #e0e7ff;
    }

    .course-block {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px;
        border-radius: 6px;
        margin: 2px 0;
        font-size: 12px;
        position: relative;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .course-block.recurring {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    }

    .course-block-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .course-status {
        font-size: 14px;
    }

    .course-delete {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .course-delete:hover {
        background: rgba(220, 38, 38, 0.8);
    color: white;
    }

    .course-title {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .course-details {
        font-size: 11px;
        opacity: 0.9;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 32px;
        max-width: 1000px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .modal-close {
        background: #f1f5f9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="timetable-container">
    <!-- Header -->
    <div class="timetable-header">
        <div class="week-navigation">
            <button class="nav-button" onclick="navigateWeek(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="today-button" onclick="goToToday()">{{ __('teacher.today') }}</button>
            <button class="nav-button" onclick="navigateWeek(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="date-range" id="dateRange">
                {{ $weekStart->format('d') }} - {{ $weekEnd->format('d') }} {{ $weekStart->format('M') }}. {{ $weekStart->format('Y') }}
            </div>
        </div>
        
        <div class="header-actions">
            <button class="action-button btn-recurring" onclick="openRecurringModal()">
                <i class="fas fa-sync-alt"></i>
                {{ __('teacher.course_recurrents') }}
            </button>
            <button class="action-button btn-refresh" onclick="loadEvents()">
                <i class="fas fa-sync"></i>
                {{ __('teacher.refresh') }}
            </button>
            <div class="view-toggles">
                <button class="view-toggle">{{ __('teacher.month') }}</button>
                <button class="view-toggle active">{{ __('teacher.week') }}</button>
                <button class="view-toggle">{{ __('teacher.day') }}</button>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid">
        <!-- Time column header -->
        <div class="calendar-header"></div>
        
        <!-- Day headers -->
        @php
            $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $weekDates = [];
            $dateForDay = $weekStart->copy();
            // Calculate dates for each day in the week
            foreach($days as $day) {
                $weekDates[] = $dateForDay->copy()->format('Y-m-d');
                $dateForDay->addDay();
            }
        @endphp
        @foreach($days as $index => $day)
            <div class="calendar-header {{ in_array($day, ['Saturday', 'Sunday']) ? 'weekend' : '' }}">
                {{ strtoupper(substr($day, 0, 3)) }}<br>
                <span style="font-size: 16px; font-weight: 700;">{{ Carbon\Carbon::parse($weekDates[$index])->format('d') }}</span>
            </div>
        @endforeach

        <!-- Time slots -->
        @for($hour = 6; $hour <= 23; $hour++)
            <!-- Time label -->
            <div class="time-slot">{{ $hour }} h</div>
            
            <!-- Cells for each day -->
            @foreach($days as $dayIndex => $day)
                <div class="calendar-cell {{ in_array($day, ['Saturday', 'Sunday']) ? 'weekend' : '' }}" 
                     data-hour="{{ $hour }}" 
                     data-day="{{ $dayIndex }}"
                     data-date="{{ $weekDates[$dayIndex] }}">
                </div>
            @endforeach
        @endfor
    </div>
</div>

<!-- Add Lesson Modal -->
<div id="addLessonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('teacher.add_a_course') }}</h2>
            <button class="modal-close" onclick="closeAddLessonModal()">&times;</button>
        </div>
        <div id="addLessonFormContent">
            @include('teacher.modals.add-lesson-modal')
        </div>
    </div>
</div>

<!-- Recurring Course Modal -->
<div id="recurringModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('teacher.add_recurring_courses') }}</h2>
            <button class="modal-close" onclick="closeRecurringModal()">&times;</button>
        </div>
        @include('teacher.recurring-course-form')
    </div>
</div>

<script>
let selectedCells = [];
let currentWeekStart = '{{ $weekStart->format('Y-m-d') }}';
let events = [];
let isDragging = false;
let dragStartCell = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    
    // Handle keyboard events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddLessonModal();
            closeRecurringModal();
        }
    });
    
    // Set up drag/swipe selection for calendar cells
    setupCellSelection();
    
    // Handle form submission
    const addLessonForm = document.getElementById('addLessonForm');
    if (addLessonForm) {
        addLessonForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("teacher.courses.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                redirect: 'follow'
            })
            .then(response => {
                if (response.ok || response.redirected) {
                    closeAddLessonModal();
                    loadEvents();
                    alert(window.translations.courseCreatedSuccess);
                } else {
                    return response.text().then(text => {
                        // Try to parse error messages from response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(text, 'text/html');
                        const errors = doc.querySelectorAll('.error, [style*="color: #dc2626"]');
                        if (errors.length > 0) {
                            let errorMsg = 'Please check the form:\n';
                            errors.forEach(err => {
                                errorMsg += '- ' + err.textContent.trim() + '\n';
                            });
                            alert(errorMsg);
                        } else {
                            alert(window.translations.errorCreatingCourse);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(window.translations.errorTryAgain);
            });
        });
    }
});

// Setup multi-cell selection via drag/swipe
function setupCellSelection() {
    const cells = document.querySelectorAll('.calendar-cell');
    
    cells.forEach(cell => {
        // Mouse events
        cell.addEventListener('mousedown', function(e) {
            e.preventDefault();
            isDragging = true;
            dragStartCell = cell;
            clearSelection();
            selectCell(cell);
        });
        
        cell.addEventListener('mouseenter', function(e) {
            if (isDragging && dragStartCell) {
                cell.classList.add('selecting');
                selectRange(dragStartCell, cell);
            }
        });
        
        cell.addEventListener('mouseleave', function(e) {
            if (!isDragging) {
                cell.classList.remove('selecting');
            }
        });
        
        // Touch events for mobile
        cell.addEventListener('touchstart', function(e) {
            e.preventDefault();
            isDragging = true;
            dragStartCell = cell;
            clearSelection();
            selectCell(cell);
        });
        
        cell.addEventListener('touchmove', function(e) {
            if (isDragging) {
                const touch = e.touches[0];
                const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                if (elementBelow && elementBelow.classList.contains('calendar-cell')) {
                    selectRange(dragStartCell, elementBelow);
                }
            }
        });
    });
    
    // End selection on mouse/touch up
    document.addEventListener('mouseup', function() {
        if (isDragging) {
            isDragging = false;
            // Remove selecting class from all cells
            document.querySelectorAll('.calendar-cell.selecting').forEach(cell => {
                cell.classList.remove('selecting');
            });
            
            // Small delay to allow for any final cell selection
            setTimeout(() => {
                if (selectedCells.length > 0) {
                    openAddLessonModal();
                }
            }, 100);
        }
    });
    
    document.addEventListener('touchend', function() {
        if (isDragging) {
            isDragging = false;
            // Remove selecting class from all cells
            document.querySelectorAll('.calendar-cell.selecting').forEach(cell => {
                cell.classList.remove('selecting');
            });
            
            // Small delay to allow for any final cell selection
            setTimeout(() => {
                if (selectedCells.length > 0) {
                    openAddLessonModal();
                }
            }, 100);
        }
    });
}

function clearSelection() {
    selectedCells.forEach(c => c.classList.remove('selected'));
    selectedCells = [];
}

function selectCell(cell) {
    if (!selectedCells.includes(cell)) {
        cell.classList.add('selected');
        selectedCells.push(cell);
    }
}

function selectRange(startCell, endCell) {
    const startDate = startCell.dataset.date;
    const startHour = parseInt(startCell.dataset.hour);
    const startDay = parseInt(startCell.dataset.day);
    
    const endDate = endCell.dataset.date;
    const endHour = parseInt(endCell.dataset.hour);
    const endDay = parseInt(endCell.dataset.day);
    
    // Determine min/max values
    const minDate = startDate <= endDate ? startDate : endDate;
    const maxDate = startDate >= endDate ? startDate : endDate;
    const minHour = Math.min(startHour, endHour);
    const maxHour = Math.max(startHour, endHour);
    const minDay = Math.min(startDay, endDay);
    const maxDay = Math.max(startDay, endDay);
    
    // Clear current selection
    clearSelection();
    
    // Get all cells
    const allCells = Array.from(document.querySelectorAll('.calendar-cell'));
    
    // Select cells in the range
    allCells.forEach(cell => {
        const cellDate = cell.dataset.date;
        const cellHour = parseInt(cell.dataset.hour);
        const cellDay = parseInt(cell.dataset.day);
        
        // Check if cell is within the selection range
        const dateInRange = cellDate >= minDate && cellDate <= maxDate;
        const hourInRange = cellHour >= minHour && cellHour <= maxHour;
        const dayInRange = cellDay >= minDay && cellDay <= maxDay;
        
        // Select if within the rectangular area defined by start and end
        if (dateInRange && hourInRange && dayInRange) {
            selectCell(cell);
        }
    });
}

function navigateWeek(direction) {
    const date = new Date(currentWeekStart);
    date.setDate(date.getDate() + (direction * 7));
    currentWeekStart = date.toISOString().split('T')[0];
    window.location.href = `{{ route('teacher.timetable') }}?week=${currentWeekStart}`;
}

function goToToday() {
    const today = new Date();
    const weekStart = new Date(today);
    // Find Saturday (day 6)
    // today.getDay(): 0=Sunday, 1=Monday, ..., 6=Saturday
    let dayOfWeek = today.getDay();
    // Calculate days to subtract to get to Saturday
    // Sat(6)->0, Sun(0)->1, Mon(1)->2, ..., Fri(5)->6
    let daysToSubtract = dayOfWeek === 6 ? 0 : (dayOfWeek + 1);
    weekStart.setDate(today.getDate() - daysToSubtract);
    currentWeekStart = weekStart.toISOString().split('T')[0];
    window.location.href = `{{ route('teacher.timetable') }}?week=${currentWeekStart}`;
}


function openAddLessonModal() {
    if (selectedCells.length === 0) return;
    
    // Sort selected cells by date and hour
    const sortedCells = Array.from(selectedCells).sort((a, b) => {
        const dateCompare = a.dataset.date.localeCompare(b.dataset.date);
        if (dateCompare !== 0) return dateCompare;
        return parseInt(a.dataset.hour) - parseInt(b.dataset.hour);
    });
    
    const firstCell = sortedCells[0];
    const lastCell = sortedCells[sortedCells.length - 1];
    
    const date = firstCell.dataset.date;
    const startHour = parseInt(firstCell.dataset.hour);
    const time = String(startHour).padStart(2, '0') + ':00';
    
    // Calculate duration based on number of cells selected
    // If cells are on the same day, calculate hours
    let durationHours = 1;
    let durationMinutes = 0;
    
    if (firstCell.dataset.date === lastCell.dataset.date) {
        // Same day - calculate duration based on hours spanned
        const endHour = parseInt(lastCell.dataset.hour);
        durationHours = (endHour - startHour) + 1;
        durationMinutes = 0;
    } else {
        // Multiple days or different pattern - default to 1 hour per cell, max 8 hours
        durationHours = Math.min(selectedCells.length, 8);
        durationMinutes = 0;
    }
    
    // Pre-fill date and time in the form
    const dateInput = document.getElementById('modal_course_date');
    const timeInput = document.getElementById('modal_class_time');
    const durationHoursInput = document.getElementById('modal_duration_hours');
    const durationMinutesInput = document.getElementById('modal_duration_minutes');
    
    if (dateInput) dateInput.value = date;
    if (timeInput) timeInput.value = time;
    if (durationHoursInput) {
        durationHoursInput.value = Math.min(durationHours, 8);
    }
    if (durationMinutesInput) {
        durationMinutesInput.value = durationMinutes;
    }
    
    // Reset form to initial state but keep our values
    const form = document.getElementById('addLessonForm');
    if (form) {
        // Don't reset, just set our values
        if (dateInput) dateInput.value = date;
        if (timeInput) timeInput.value = time;
        if (durationHoursInput) durationHoursInput.value = Math.min(durationHours, 8);
        if (durationMinutesInput) durationMinutesInput.value = durationMinutes;
    }
    
    // Show the modal
    document.getElementById('addLessonModal').classList.add('active');
}

function closeAddLessonModal() {
    document.getElementById('addLessonModal').classList.remove('active');
    selectedCells.forEach(c => c.classList.remove('selected'));
    selectedCells = [];
}


function openRecurringModal() {
    document.getElementById('recurringModal').classList.add('active');
}

function closeRecurringModal() {
    document.getElementById('recurringModal').classList.remove('active');
}

function loadEvents() {
    // Calculate week start and end dates (Saturday to Friday)
    const weekStartDate = new Date(currentWeekStart);
    const weekEndDate = new Date(weekStartDate);
    weekEndDate.setDate(weekEndDate.getDate() + 6); // Add 6 days to get Friday
    
    const startDate = weekStartDate.toISOString().split('T')[0];
    const endDate = weekEndDate.toISOString().split('T')[0];
    
    fetch(`{{ route('teacher.timetable.events') }}?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            events = data;
            renderEvents();
        })
        .catch(error => {
            console.error('Error loading events:', error);
        });
}

function renderEvents() {
    // Clear all course blocks
    document.querySelectorAll('.course-block').forEach(block => block.remove());
    
    events.forEach(event => {
        const date = event.date;
        const time = event.time;
        const [hour, minute] = time.split(':').map(Number);
        const durationHours = event.duration_hours || 1;
        
        // Find the cell for this event
        const cells = document.querySelectorAll(`.calendar-cell[data-date="${date}"]`);
        const targetHour = hour;
        
        cells.forEach(cell => {
            const cellHour = parseInt(cell.dataset.hour);
            if (cellHour === targetHour) {
                // Check if cell already has content
                if (cell.querySelector('.course-block')) {
                    // Add to existing or create new
                    return;
                }
                
                // Create course block
                const block = document.createElement('div');
                block.className = `course-block ${event.is_recurring ? 'recurring' : ''}`;
                block.style.height = `${Math.max(60, durationHours * 60)}px`;
                block.innerHTML = `
                    <div class="course-block-header">
                        <span class="course-status">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <button class="course-delete" onclick="deleteCourse(${event.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="course-title">${event.student_name}${event.is_recurring ? ' (recurrent)' : ''}</div>
                    <div class="course-details">${event.course_type} - ${event.duration}</div>
                `;
                
                block.onclick = function(e) {
                    if (e.target.closest('.course-delete')) return;
                    editCourse(event.id);
                };
                
                cell.appendChild(block);
            }
        });
    });
}

function deleteCourse(courseId) {
    if (!confirm(window.translations.deleteConfirm)) return;
    
    fetch(`/teacher/courses/${courseId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.ok || response.redirected) {
            loadEvents();
            alert(window.translations.courseDeletedSuccess);
        } else {
            alert(window.translations.errorDeletingCourse);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(window.translations.errorDeletingCourse);
    });
}

function editCourse(courseId) {
    window.location.href = `/teacher/courses/${courseId}/edit`;
}

// Recurring course form submission
function submitRecurringForm() {
    const form = document.getElementById('recurringCourseForm');
    const formData = new FormData(form);
    
    fetch('{{ route("teacher.recurring-courses.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Now generate the events
            generateRecurringEvents(data.id);
        } else {
            alert(window.translations.errorCreatingRecurring + (data.message || window.translations.errorTryAgain));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(window.translations.errorCreatingRecurring + window.translations.errorTryAgain);
    });
}

function generateRecurringEvents(recurringCourseId) {
    fetch('{{ route("teacher.recurring-courses.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            recurring_course_id: recurringCourseId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRecurringModal();
            loadEvents();
            const generatedMsg = @json(__('teacher.generated_recurring', ['count' => ':count'])).replace(':count', data.count);
            alert(generatedMsg);
        } else {
            alert(window.translations.errorGeneratingEvents + (data.message || window.translations.errorTryAgain));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(window.translations.errorGeneratingEvents + window.translations.errorTryAgain);
    });
}
</script>
@endsection

