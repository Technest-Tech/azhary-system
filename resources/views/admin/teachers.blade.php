@extends('admin.layouts.app')

@section('title', 'Teachers Management')
@section('page-title', 'Teachers Management')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    <i class="fas fa-chalkboard-teacher" style="color: #667eea; margin-right: 8px;"></i>
                    All Teachers
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    Manage and view all faculty members in the academy
                </p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary" style="text-decoration: none;">
                <i class="fas fa-plus"></i>
                Add New Teacher
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Total Teachers</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $teachers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Active</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">{{ $teachers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <p style="color: #64748b; font-size: 13px; font-weight: 600;">Total Courses</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1e293b;">12</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">Teachers List</h3>
                <div style="display: flex; gap: 12px;">
                    <input type="text" placeholder="Search teachers..." style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; width: 250px;">
                    <button class="btn" style="background: #f1f5f9; color: #475569; padding: 8px 16px;">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>

        @if($teachers->count() > 0)
            <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
                <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                ID
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Teacher
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Email
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Phone
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Date of Birth
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Currency
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 16px; white-space: nowrap; color: #64748b; font-weight: 600; font-size: 14px;">
                                #{{ $teacher->id }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $teacher->name }}</p>
                                        <p style="font-size: 12px; color: #64748b;">Teacher</p>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                <i class="fas fa-envelope" style="color: #94a3b8; margin-right: 8px;"></i>
                                {{ $teacher->email }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                <i class="fas fa-phone" style="color: #94a3b8; margin-right: 8px;"></i>
                                {{ $teacher->phone }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                <i class="fas fa-calendar" style="color: #94a3b8; margin-right: 8px;"></i>
                                {{ $teacher->date_of_birth }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <span class="badge badge-info">{{ $teacher->currency }}</span>
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; gap: 8px;">
                                    <button onclick="viewTeacherDetails({{ $teacher->id }})" style="padding: 6px 12px; background: #dbeafe; color: #3b82f6; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No teachers found</p>
                <p style="font-size: 14px; margin-bottom: 24px;">Start by adding your first teacher to the academy</p>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Teacher
                </button>
            </div>
        @endif
    </div>

    <!-- Teacher Details Modal -->
    <div id="teacherDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; max-width: 1200px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative;">
            <!-- Modal Header -->
            <div style="padding: 24px; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; background: white; z-index: 10; border-radius: 16px 16px 0 0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 id="modalTeacherName" style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Teacher Details</h2>
                        <p style="color: #64748b; font-size: 14px;">View teacher statistics and assigned students</p>
                    </div>
                    <button onclick="closeTeacherModal()" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div style="padding: 24px;">
                <!-- Stats Cards -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <!-- Students & Courses -->
                    <div class="card" style="padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0;">Students & Courses</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f1f5f9;">
                            <div>
                                <p style="color: #64748b; font-size: 11px; margin: 0;">Active Students</p>
                                <p id="modalActiveStudents" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">0</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="color: #64748b; font-size: 11px; margin: 0;">Total Courses</p>
                                <p id="modalTotalCourses" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hours Statistics -->
                    <div class="card" style="padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0;">Hours Statistics</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f1f5f9;">
                            <div>
                                <p style="color: #64748b; font-size: 11px; margin: 0;">This Month</p>
                                <p id="modalHoursThisMonth" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">0</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="color: #64748b; font-size: 11px; margin: 0;">Total Hours</p>
                                <p id="modalTotalHours" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Income Statistics -->
                    <div class="card" style="padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ccfbf1; display: flex; align-items: center; justify-content: center; color: #0f766e;">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0;">Income Statistics</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f1f5f9;">
                            <div>
                                <p style="color: #64748b; font-size: 11px; margin: 0;">This Month</p>
                                <p id="modalMonthlyIncome" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">$0</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="color: #64748b; font-size: 11px; margin: 0;">Total Income</p>
                                <p id="modalTotalIncome" style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">$0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hourly Rate -->
                    <div class="card" style="padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #6366f1;">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="color: #64748b; font-size: 12px; font-weight: 600; margin: 0;">Current Rate</p>
                            </div>
                        </div>
                        <div style="padding: 8px 0; border-top: 1px solid #f1f5f9;">
                            <p style="color: #64748b; font-size: 11px; margin: 0;">Hourly Rate</p>
                            <p id="modalHourlyRate" style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">$0</p>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assigned Students</h3>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0;">
                                    <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Student</th>
                                    <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Course</th>
                                    <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Package</th>
                                    <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Rates</th>
                                    <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="modalStudentsTableBody">
                                <tr>
                                    <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                                        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                        <p style="margin-top: 12px;">Loading...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewTeacherDetails(teacherId) {
            const modal = document.getElementById('teacherDetailsModal');
            modal.style.display = 'flex';
            
            // Fetch teacher details
            fetch(`/admin/teachers/${teacherId}/details`)
                .then(response => response.json())
                .then(data => {
                    // Update modal title
                    document.getElementById('modalTeacherName').textContent = data.teacher.name;
                    
                    // Update stats
                    document.getElementById('modalActiveStudents').textContent = data.stats.activeStudents;
                    document.getElementById('modalTotalCourses').textContent = data.stats.totalCourses;
                    document.getElementById('modalHoursThisMonth').textContent = data.stats.hoursThisMonth;
                    document.getElementById('modalTotalHours').textContent = data.stats.totalHours;
                    document.getElementById('modalMonthlyIncome').textContent = '$' + data.stats.monthlyIncome;
                    document.getElementById('modalTotalIncome').textContent = '$' + data.stats.totalIncome;
                    document.getElementById('modalHourlyRate').textContent = '$' + data.stats.hourlyRate;
                    
                    // Update students table
                    const tbody = document.getElementById('modalStudentsTableBody');
                    if (data.students.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-user-slash" style="font-size: 32px; opacity: 0.5;"></i>
                                    <p style="margin-top: 12px;">No students assigned</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        tbody.innerHTML = data.students.map(student => `
                            <tr style="border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                            ${student.name.charAt(0).toUpperCase()}
                                        </div>
                                        <div>
                                            <p style="font-weight: 600; color: #1e293b; font-size: 14px; margin: 0;">${student.name}</p>
                                            <p style="font-size: 12px; color: #64748b; margin: 0;">Section ${student.section}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    ${student.subject ? `
                                        <span class="badge" style="background: #f3e8ff; color: #7c3aed; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            ${student.subject.name}
                                        </span>
                                    ` : '<span style="color: #94a3b8; font-size: 13px;">Not assigned</span>'}
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="font-size: 13px;">
                                        <p style="color: #64748b; margin: 0;">Package #${student.package_number}</p>
                                    </div>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="font-size: 12px;">
                                        <div style="margin-bottom: 4px;">
                                            <span style="color: #64748b;">Student:</span> 
                                            <strong style="color: #1e293b;">$${parseFloat(student.hour_rate).toFixed(2)}/hr</strong>
                                        </div>
                                        ${student.teacher_rate ? `
                                            <div>
                                                <span style="color: #64748b;">Teacher:</span> 
                                                <strong style="color: #16a34a;">$${parseFloat(student.teacher_rate).toFixed(2)}/hr</strong>
                                            </div>
                                        ` : ''}
                                    </div>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="/admin/students/${student.id}/edit" style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="/admin/students/${student.id}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error fetching teacher details:', error);
                    alert('Failed to load teacher details');
                });
        }

        function closeTeacherModal() {
            document.getElementById('teacherDetailsModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('teacherDetailsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTeacherModal();
            }
        });
    </script>
@endsection

