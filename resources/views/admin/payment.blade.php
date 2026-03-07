@extends('admin.layouts.app')

@section('title', __('admin.payment_management'))
@section('page-title', __('admin.payment_management'))

@section('content')
    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
        <div class="card" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <p style="color: #64748b; font-size: 14px; font-weight: 600; margin: 0 0 8px 0;">{{ __('admin.total_pending') }}</p>
            <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 0;">{{ $totalPending }}</h3>
        </div>
        <div class="card" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <p style="color: #64748b; font-size: 14px; font-weight: 600; margin: 0 0 8px 0;">{{ __('admin.total_amount_due') }}</p>
            <h3 style="font-size: 28px; font-weight: 700; color: #1e293b; margin: 0;">{{ number_format($totalAmountDue, 0) }} €</h3>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.payment') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 16px; align-items: end;">
            <!-- Student Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.search_student_placeholder') }}</label>
                <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="{{ __('admin.search_student_placeholder') }}" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Teacher Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.teacher') }}</label>
                <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="">{{ __('admin.all_teachers') }}</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pack Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.pack') }}</label>
                <select name="pack" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('pack') == 'all' || !request('pack') ? 'selected' : '' }}>{{ __('admin.all_packs') }}</option>
                    @foreach($packs as $pack)
                        <option value="{{ $pack }}" {{ request('pack') == $pack ? 'selected' : '' }}>
                            {{ $pack }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Reminder Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.reminder') }}</label>
                <select name="reminder" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('reminder') == 'all' || !request('reminder') ? 'selected' : '' }}>{{ __('admin.all_reminders') }}</option>
                    <option value="nothing" {{ request('reminder') == 'nothing' ? 'selected' : '' }}>{{ __('admin.nothing') }}</option>
                </select>
            </div>

            <!-- Submit Button (hidden, auto-submit on change) -->
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <!-- Payment Table -->
    <div class="card" style="background: white; border-radius: 12px; padding: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                <thead>
                    <tr style="background: #10b981; color: white;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.student_name_header') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.teacher') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.phone') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.pack') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.price') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.payment_status_header') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.reminder_header') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.last_action') }}</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; font-size: 14px; white-space: nowrap;">{{ __('admin.notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $lastCourse = $student->courses->first();
                            // Count courses with name "0.0" or admin_status='pending' (reached package limit, need payment)
                            $coursesBeyondLimit = $student->courses->filter(function($course) {
                                return $course->name === '0.0' || $course->admin_status === 'pending';
                            });
                            $totalPrice = $coursesBeyondLimit->sum('income');
                        @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; white-space: nowrap;">{{ $student->name }}</td>
                            <td style="padding: 16px; color: #64748b; white-space: nowrap;">{{ $student->teacher->name ?? 'N/A' }}</td>
                            <td style="padding: 16px; color: #64748b; white-space: nowrap;">{{ $student->phone }}</td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <span class="editable-pack" data-student-id="{{ $student->id }}" data-value="{{ $student->package_number }}" 
                                      style="cursor: pointer; padding: 4px 10px; border-radius: 6px; color: #64748b; display: inline-block; min-width: 40px; transition: background 0.2s;" 
                                      onmouseover="this.style.background='#f1f5f9'" onmouseout="if(!this.querySelector('input')) this.style.background='transparent'" 
                                      title="Click to edit">{{ $student->package_number }}</span>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <span class="editable-price" data-student-id="{{ $student->id }}" data-value="{{ $student->package_rate }}" 
                                      style="cursor: pointer; padding: 4px 10px; border-radius: 6px; color: #1e293b; font-weight: 600; display: inline-block; min-width: 60px; transition: background 0.2s;" 
                                      onmouseover="this.style.background='#f1f5f9'" onmouseout="if(!this.querySelector('input')) this.style.background='transparent'" 
                                      title="Click to edit">{{ number_format($student->package_rate, 0) }} €</span>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <select class="payment-status-select" data-student-id="{{ $student->id }}" 
                                        style="width: 100%; min-width: 150px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: white; cursor: pointer;">
                                    @foreach($paymentStatuses as $status)
                                        <option value="{{ $status->id }}" 
                                                {{ $student->payment_status_id == $status->id ? 'selected' : '' }}>
                                            @if($status->name === 'EN ATTENTE DE PAYEMENT')
                                                {{ __('admin.awaiting_payment') }}
                                            @elseif($status->name === 'PAYÉ')
                                                {{ __('admin.active_status') }}
                                            @elseif($status->name === 'SUSPENDU')
                                                {{ __('admin.suspended') }}
                                            @elseif($status->name === 'ARRÊTÉ')
                                                {{ __('admin.arrested') }}
                                            @else
                                                {{ $status->display_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <select style="width: 100%; min-width: 120px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: white;">
                                    <option>{{ __('admin.nothing') }}</option>
                                </select>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 13px; white-space: nowrap;">
                                @if($lastCourse)
                                    {{ $lastCourse->course_date->format('m/d/Y') }} {{ $lastCourse->class_time ? $lastCourse->class_time->format('g:i A') : '' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <input type="text" class="student-notes" data-student-id="{{ $student->id }}" 
                                       value="" 
                                       placeholder="{{ __('admin.add_notes') }}" 
                                       style="width: 100%; min-width: 150px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 13px;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5; display: block;"></i>
                                <p style="font-size: 16px; font-weight: 600;">{{ __('admin.no_pending_payments') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Auto-submit form on filter change
        document.querySelectorAll('select[name="teacher_id"], select[name="pack"], select[name="reminder"], input[name="student_search"]').forEach(function(element) {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Handle payment status update
        document.querySelectorAll('.payment-status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const studentId = this.getAttribute('data-student-id');
                const statusId = this.value;
                
                fetch(`/admin/students/${studentId}/payment-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        payment_status_id: statusId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating payment status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating payment status');
                });
            });
        });

        // ===================== INLINE EDIT PACK =====================
        document.addEventListener('click', function(e) {
            var cell = e.target.closest('.editable-pack');
            if (!cell || cell.querySelector('input')) return;
            var studentId = cell.getAttribute('data-student-id');
            var currentVal = cell.getAttribute('data-value');
            var input = document.createElement('input');
            input.type = 'number';
            input.min = '1';
            input.step = '1';
            input.value = parseInt(currentVal);
            input.style.cssText = 'width: 64px; padding: 4px 8px; font-weight: 600; color: #1e293b; border: 2px solid #10b981; border-radius: 6px; font-size: 14px; outline: none;';
            input.onblur = function() { saveStudentField(input, cell, studentId, 'package_number', true); };
            input.onkeydown = function(ev) {
                if (ev.key === 'Enter') { input.blur(); }
                if (ev.key === 'Escape') { revertField(cell, currentVal); }
            };
            cell.textContent = '';
            cell.appendChild(input);
            cell.style.background = 'transparent';
            input.focus();
            input.select();
        });

        // ===================== INLINE EDIT PRICE =====================
        document.addEventListener('click', function(e) {
            var cell = e.target.closest('.editable-price');
            if (!cell || cell.querySelector('input')) return;
            var studentId = cell.getAttribute('data-student-id');
            var currentVal = cell.getAttribute('data-value');
            var input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.step = '0.01';
            input.value = parseFloat(currentVal);
            input.style.cssText = 'width: 80px; padding: 4px 8px; font-weight: 600; color: #1e293b; border: 2px solid #10b981; border-radius: 6px; font-size: 14px; outline: none;';
            input.onblur = function() { saveStudentField(input, cell, studentId, 'package_rate', false); };
            input.onkeydown = function(ev) {
                if (ev.key === 'Enter') { input.blur(); }
                if (ev.key === 'Escape') { revertPriceField(cell, currentVal); }
            };
            cell.textContent = '';
            cell.appendChild(input);
            cell.style.background = 'transparent';
            input.focus();
            input.select();
        });

        function saveStudentField(input, cell, studentId, fieldName, isInt) {
            var val = isInt ? parseInt(input.value) : parseFloat(input.value);
            if (isNaN(val) || val < 0) { 
                revertField(cell, cell.getAttribute('data-value')); 
                return; 
            }
            var body = {};
            body[fieldName] = val;

            // Show saving indicator
            input.style.borderColor = '#f59e0b';
            input.disabled = true;

            fetch('/admin/students/' + studentId + '/quick-update', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(body)
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    // Reload page to reflect recalculated values
                    location.reload();
                } else {
                    revertField(cell, cell.getAttribute('data-value'));
                }
            })
            .catch(function() { revertField(cell, cell.getAttribute('data-value')); });
        }

        function revertField(cell, currentVal) {
            cell.innerHTML = '';
            cell.textContent = currentVal;
            cell.setAttribute('data-value', currentVal);
        }

        function revertPriceField(cell, currentVal) {
            cell.innerHTML = '';
            cell.textContent = parseFloat(currentVal).toFixed(0) + ' €';
        }

        // Handle notes update (optional - can be implemented with debounce)
        document.querySelectorAll('.student-notes').forEach(function(input) {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const studentId = this.getAttribute('data-student-id');
                const notes = this.value;
                
                timeout = setTimeout(function() {
                    // Implement notes update API if needed
                }, 1000);
            });
        });
    </script>
    @endsection
@endsection

