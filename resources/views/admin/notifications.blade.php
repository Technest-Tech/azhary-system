@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
    <!-- Filters Section -->
    <div class="card" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 20px;">Filters</h3>
        <form method="GET" action="{{ route('admin.notifications') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr 2fr auto; gap: 16px; align-items: end;">
            <!-- Type Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Card type:</label>
                <select name="type" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>All types</option>
                    <option value="absence_approval" {{ request('type') == 'absence_approval' ? 'selected' : '' }}>Absence Approval</option>
                    <option value="progress_update" {{ request('type') == 'progress_update' ? 'selected' : '' }}>Progress Update</option>
                    <option value="birthday" {{ request('type') == 'birthday' ? 'selected' : '' }}>Birthday</option>
                </select>
            </div>

            <!-- Teacher Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Teacher:</label>
                <select name="teacher_id" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="">All teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Date (from):</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Student Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Search for a student:</label>
                <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Student name..." 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Reset Button -->
            <div>
                <a href="{{ route('admin.notifications') }}" 
                   style="display: inline-block; padding: 12px 24px; background: #dbeafe; color: #1e40af; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap;">
                    Reset filters
                </a>
            </div>

            <!-- Submit Button (hidden, auto-submit on change) -->
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <!-- Notifications Grid -->
    @if($notifications->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($notifications as $notification)
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative;">
                    <!-- Dismiss Button -->
                    <form method="POST" action="{{ route('admin.notifications.dismiss', $notification) }}" style="position: absolute; top: 12px; right: 12px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 18px; padding: 4px 8px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>

                    <!-- Student Name -->
                    <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                        {{ $notification->student->name }}
                    </h4>

                    <!-- Type Badge -->
                    <div style="margin-bottom: 12px;">
                        @if($notification->type === 'absence_approval')
                            <span style="display: inline-block; padding: 6px 12px; background: #fee2e2; color: #dc2626; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                absence approval
                            </span>
                        @elseif($notification->type === 'progress_update')
                            <span style="display: inline-block; padding: 6px 12px; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                Progress Update
                            </span>
                        @else
                            <span style="display: inline-block; padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                🎂 Birthday Alert 🎂
                            </span>
                        @endif
                    </div>

                    <!-- Message -->
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 12px;">
                        {{ $notification->message }}
                    </p>

                    <!-- Teacher and Package Info -->
                    @if($notification->teacher)
                        <p style="color: #64748b; font-size: 13px; margin-bottom: 8px;">
                            <strong>Teacher:</strong> {{ $notification->teacher->name }}, Package number: {{ $notification->student->package_number }}
                        </p>
                    @endif

                    <!-- Date -->
                    <p style="color: #94a3b8; font-size: 12px; margin-bottom: 16px;">
                        {{ $notification->created_at->format('Y-m-d H:i:s') }}
                    </p>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @if($notification->type === 'absence_approval')
                            @php
                                $course = $notification->course;
                                $isApproved = $notification->is_approved === true || ($course && $course->admin_status === 'approved');
                                $isRejected = $notification->is_approved === false || ($course && $course->admin_status === 'rejected');
                            @endphp
                            
                            @if($isApproved)
                                <button type="button" disabled style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: not-allowed; opacity: 0.7;">
                                    ✔ Approved
                                </button>
                            @elseif($isRejected)
                                <button type="button" disabled style="padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: not-allowed; opacity: 0.7;">
                                    X Rejected
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.notifications.approve', $notification) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                        ✔ Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.notifications.reject', $notification) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                        X Reject
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $notification->student->phone) }}" 
                               target="_blank"
                               style="display: inline-block; padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                Chat on WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: 24px;">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="card" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
            <i class="fas fa-bell-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
            <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No notifications found</p>
            <p style="font-size: 14px;">Notifications will appear here when students are absent or complete their packages</p>
        </div>
    @endif

    @section('scripts')
    <script>
        // Auto-submit form on filter change
        document.querySelectorAll('select[name="type"], select[name="teacher_id"], input[name="date_from"], input[name="student_search"]').forEach(function(element) {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
    @endsection
@endsection

