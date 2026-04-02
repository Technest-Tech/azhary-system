@extends('admin.layouts.app')

@section('title', 'Trash & Recovery')
@section('page-title', 'Recovery Management')

@section('content')
    <div class="card" style="margin-bottom: 24px; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-radius: 12px 12px 0 0; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
            <h3 class="card-title" style="margin: 0; font-size: 1.25rem; font-weight: 600;">
                <i class="fas fa-trash-restore" style="margin-right: 8px; color: #ef4444;"></i>
                Deleted Items Recovery
            </h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            
            <!-- Tabs Navigation -->
            <ul class="nav" style="display: flex; list-style: none; padding: 0; margin: 0 0 24px 0; border-bottom: 2px solid #e2e8f0; gap: 32px;">
                <li class="nav-item">
                    <a class="nav-link active" id="students-tab" data-tab="students" style="display: block; padding: 12px 0; margin-bottom: -2px; color: #3b82f6; font-weight: 600; border-bottom: 2px solid #3b82f6; cursor: pointer;">
                        <i class="fas fa-user-graduate"></i> Students ({{ $deletedStudents->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="teachers-tab" data-tab="teachers" style="display: block; padding: 12px 0; margin-bottom: -2px; color: #64748b; font-weight: 500; cursor: pointer;">
                        <i class="fas fa-chalkboard-teacher"></i> Teachers ({{ $deletedTeachers->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="courses-tab" data-tab="courses" style="display: block; padding: 12px 0; margin-bottom: -2px; color: #64748b; font-weight: 500; cursor: pointer;">
                        <i class="fas fa-book-open"></i> Courses ({{ $deletedCourses->count() }})
                    </a>
                </li>
            </ul>

            <!-- Students Tab Content -->
            <div id="students-content" class="tab-pane" style="display: block;">
                @if($deletedStudents->isEmpty())
                    <div style="text-align: center; padding: 48px; color: #94a3b8;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <h4 style="margin: 0;">No deleted students found.</h4>
                    </div>
                @else
                    <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 16px;">
                        <thead>
                            <tr style="background-color: #f8fafc;">
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Name</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Email</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Deleted At</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Deleted By</th>
                                <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deletedStudents as $student)
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 16px; color: #1e293b; font-weight: 500;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            @if($student->photo)
                                                <img src="{{ Storage::url($student->photo) }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                            @else
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span>{{ $student->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 16px; color: #64748b;">{{ $student->email }}</td>
                                    <td style="padding: 16px; color: #64748b;">
                                        {{ $student->deleted_at->format('M d, Y - h:i A') }}<br>
                                        <small style="color: #94a3b8;">{{ $student->deleted_at->diffForHumans() }}</small>
                                    </td>
                                    <td style="padding: 16px;">
                                        @if($student->deletedBy)
                                            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px; font-weight: 500; color: #475569;">
                                                <i class="{{ class_basename($student->deleted_by_type) == 'Teacher' ? 'fas fa-chalkboard-teacher' : 'fas fa-user-shield' }} " style="margin-right: 4px;"></i>
                                                {{ class_basename($student->deleted_by_type) }}: {{ $student->deletedBy->name }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8;">Unknown</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px; text-align: right;">
                                        <form method="POST" action="{{ route('admin.recovery.student', $student->id) }}" onsubmit="return confirm('Are you sure you want to recover this student?');" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: background 0.3s;">
                                                <i class="fas fa-undo"></i> Recover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Teachers Tab Content -->
            <div id="teachers-content" class="tab-pane" style="display: none;">
                @if($deletedTeachers->isEmpty())
                    <div style="text-align: center; padding: 48px; color: #94a3b8;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <h4 style="margin: 0;">No deleted teachers found.</h4>
                    </div>
                @else
                    <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 16px;">
                        <!-- (Similar header and body structure) -->
                        <thead>
                            <tr style="background-color: #f8fafc;">
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Name</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Deleted At</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Deleted By</th>
                                <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deletedTeachers as $teacher)
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 16px; color: #1e293b; font-weight: 500;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            @if($teacher->photo)
                                                <img src="{{ Storage::url($teacher->photo) }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                            @else
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b;">
                                                    {{ substr($teacher->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span>{{ $teacher->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 16px; color: #64748b;">
                                        {{ $teacher->deleted_at->format('M d, Y - h:i A') }}
                                    </td>
                                    <td style="padding: 16px;">
                                        @if($teacher->deletedBy)
                                            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px; font-weight: 500; color: #475569;">
                                                <i class="{{ class_basename($teacher->deleted_by_type) == 'Teacher' ? 'fas fa-chalkboard-teacher' : 'fas fa-user-shield' }} " style="margin-right: 4px;"></i>
                                                {{ class_basename($teacher->deleted_by_type) }}: {{ $teacher->deletedBy->name }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8;">Unknown</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px; text-align: right;">
                                        <form method="POST" action="{{ route('admin.recovery.teacher', $teacher->id) }}" onsubmit="return confirm('Recover this teacher?');" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 500; cursor: pointer;">
                                                <i class="fas fa-undo"></i> Recover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Courses Tab Content -->
            <div id="courses-content" class="tab-pane" style="display: none;">
                @if($deletedCourses->isEmpty())
                    <div style="text-align: center; padding: 48px; color: #94a3b8;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <h4 style="margin: 0;">No deleted courses found.</h4>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 16px;">
                            <thead>
                                <tr style="background-color: #f8fafc;">
                                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">Course Details</th>
                                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">Teacher & Student</th>
                                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">Date / Time</th>
                                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">Deleted Info</th>
                                    <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deletedCourses as $course)
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 16px; color: #1e293b;">
                                            <div style="font-weight: 600;">{{ $course->subject?->name ?? $course->course_type ?? 'Default Course' }}</div>
                                            <div style="font-size: 13px; color: #64748b;">Round: {{ $course->round }} | Lesson: {{ $course->name }}</div>
                                        </td>
                                        <td style="padding: 16px;">
                                            <div style="margin-bottom: 4px; font-weight: 500; color: #475569;"><i class="fas fa-chalkboard-teacher"></i> {{ $course->teacher?->name ?? 'Unknown Teacher' }}</div>
                                            <div style="font-size: 13px; color: #64748b;"><i class="fas fa-user-graduate"></i> {{ $course->student?->name ?? $course->student_name ?? 'Unknown Student' }}</div>
                                        </td>
                                        <td style="padding: 16px; color: #64748b; white-space: nowrap;">
                                            <div>🗓️ {{ $course->course_date ? $course->course_date->format('M d, Y') : 'N/A' }}</div>
                                            <div style="margin-top: 4px;">⏰ {{ $course->class_time ? \Carbon\Carbon::parse($course->class_time)->format('h:i A') : 'N/A' }}</div>
                                        </td>
                                        <td style="padding: 16px;">
                                            <div style="font-size: 13px; color: #94a3b8; margin-bottom: 4px;">{{ $course->deleted_at->format('Y-m-d H:i') }}</div>
                                            @if($course->deletedBy)
                                                <span style="background: #fee2e2; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #991b1b;">
                                                    <i class="{{ class_basename($course->deleted_by_type) == 'Teacher' ? 'fas fa-chalkboard-teacher' : 'fas fa-user-shield' }} " style="margin-right: 2px;"></i>
                                                    {{ class_basename($course->deleted_by_type) }}: {{ $course->deletedBy->name }}
                                                </span>
                                            @else
                                                <span style="color: #94a3b8; font-size: 12px;">Unknown</span>
                                            @endif
                                        </td>
                                        <td style="padding: 16px; text-align: right; vertical-align: middle;">
                                            <form method="POST" action="{{ route('admin.recovery.course', $course->id) }}" onsubmit="return confirm('Recover this course? This will recalculate the student\'s package tracking.');" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 500; cursor: pointer;">
                                                    <i class="fas fa-undo"></i> Recover
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Tabs Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabs = document.querySelectorAll('.nav-link');
            var contents = document.querySelectorAll('.tab-pane');

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    // Remove active from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        t.style.borderBottom = 'none';
                        t.style.color = '#64748b';
                        t.style.fontWeight = '500';
                    });
                    
                    // Hide all contents
                    contents.forEach(c => c.style.display = 'none');

                    // Activate clicked tab
                    this.classList.add('active');
                    this.style.borderBottom = '2px solid #3b82f6';
                    this.style.color = '#3b82f6';
                    this.style.fontWeight = '600';
                    
                    document.getElementById(this.getAttribute('data-tab') + '-content').style.display = 'block';
                });
            });

            // If success toast fired, might want to show it.
            @if(session('success'))
                var toast = document.getElementById('successToast');
                if(toast){
                    document.getElementById('successToastText').textContent = @json(session('success'));
                    toast.style.display = 'flex';
                    setTimeout(function() { toast.style.display = 'none'; }, 4000);
                } else {
                    alert(@json(session('success')));
                }
            @endif
        });
    </script>
@endsection
