@extends('admin.layouts.app')

@section('title', 'Academy Settings')
@section('page-title', 'Academy Settings')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
            <span style="font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; border-bottom: 1px solid #e2e8f0;">
            <button onclick="switchTab('subjects')" id="subjectsTab" 
                style="padding: 16px 24px; border: none; background: {{ $activeTab === 'subjects' ? '#8b5cf6' : 'transparent' }}; color: {{ $activeTab === 'subjects' ? 'white' : '#64748b' }}; cursor: pointer; font-weight: 600; border-bottom: 2px solid {{ $activeTab === 'subjects' ? '#8b5cf6' : 'transparent' }}; transition: all 0.2s;">
                <i class="fas fa-book" style="margin-right: 8px;"></i>
                Subjects
            </button>
            <button onclick="switchTab('evaluations')" id="evaluationsTab" 
                style="padding: 16px 24px; border: none; background: {{ $activeTab === 'evaluations' ? '#8b5cf6' : 'transparent' }}; color: {{ $activeTab === 'evaluations' ? 'white' : '#64748b' }}; cursor: pointer; font-weight: 600; border-bottom: 2px solid {{ $activeTab === 'evaluations' ? '#8b5cf6' : 'transparent' }}; transition: all 0.2s;">
                <i class="fas fa-star" style="margin-right: 8px;"></i>
                Evaluations
            </button>
            <button onclick="switchTab('payment-statuses')" id="paymentStatusesTab" 
                style="padding: 16px 24px; border: none; background: {{ $activeTab === 'payment-statuses' ? '#8b5cf6' : 'transparent' }}; color: {{ $activeTab === 'payment-statuses' ? 'white' : '#64748b' }}; cursor: pointer; font-weight: 600; border-bottom: 2px solid {{ $activeTab === 'payment-statuses' ? '#8b5cf6' : 'transparent' }}; transition: all 0.2s;">
                <i class="fas fa-credit-card" style="margin-right: 8px;"></i>
                Payment Statuses
            </button>
        </div>
    </div>

    <!-- Subjects Tab Content -->
    <div id="subjectsContent" style="display: {{ $activeTab === 'subjects' ? 'block' : 'none' }};">
        <!-- Header -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                        <i class="fas fa-book" style="color: #8b5cf6; margin-right: 8px;"></i>
                        Course/Subject Management
                    </h2>
                    <p style="color: #64748b; font-size: 14px;">
                        Manage all available courses and subjects in the academy
                    </p>
                </div>
                <button onclick="openAddSubjectModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    <i class="fas fa-plus"></i>
                    Add New Subject
                </button>
            </div>
        </div>

    <!-- Subjects Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Subjects</h3>
        </div>

        @if($subjects->count() > 0)
            <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
                <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                ID
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Subject Name
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Description
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Status
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Students Count
                            </th>
                            <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 16px; white-space: nowrap; color: #64748b; font-weight: 600; font-size: 14px;">
                                #{{ $subject->id }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <p style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $subject->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px; max-width: 300px;">
                                {{ $subject->description ?? 'No description' }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                @if($subject->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>
                            <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                {{ $subject->students()->count() }} students
                            </td>
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="display: flex; gap: 8px;">
                                    <button onclick='editSubject(@json($subject))' style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No subjects found</p>
                <p style="font-size: 14px; margin-bottom: 24px;">Start by adding your first subject/course</p>
                <button onclick="openAddSubjectModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    <i class="fas fa-plus"></i>
                    Add Subject
                </button>
            </div>
        @endif
    </div>

    <!-- Add/Edit Subject Modal -->
    <div id="subjectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; max-width: 600px; width: 90%; position: relative;">
            <!-- Modal Header -->
            <div style="padding: 24px; border-bottom: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 id="modalTitle" style="font-size: 20px; font-weight: 700; color: #1e293b;">Add New Subject</h2>
                    <button onclick="closeSubjectModal()" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div style="padding: 24px;">
                <form id="subjectForm" method="POST" action="{{ route('admin.subjects.store') }}">
                    @csrf
                    <input type="hidden" id="subjectMethod" name="_method" value="POST">
                    <input type="hidden" id="subjectId" name="subject_id">

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            Subject Name <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="subjectName" name="name" required
                            style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                            placeholder="e.g., Mathematics, Science, English">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                            Description
                        </label>
                        <textarea id="subjectDescription" name="description" rows="3"
                            style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                            placeholder="Brief description of the subject"></textarea>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" id="subjectActive" name="is_active" value="1" checked
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Active</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; flex: 1;">
                            <i class="fas fa-save"></i>
                            Save Subject
                        </button>
                        <button type="button" onclick="closeSubjectModal()" class="btn" style="background: #f1f5f9; color: #64748b;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddSubjectModal() {
            document.getElementById('modalTitle').textContent = 'Add New Subject';
            document.getElementById('subjectForm').action = '{{ route("admin.subjects.store") }}';
            document.getElementById('subjectMethod').value = 'POST';
            document.getElementById('subjectId').value = '';
            document.getElementById('subjectName').value = '';
            document.getElementById('subjectDescription').value = '';
            document.getElementById('subjectActive').checked = true;
            document.getElementById('subjectModal').style.display = 'flex';
        }

        function editSubject(subject) {
            document.getElementById('modalTitle').textContent = 'Edit Subject';
            document.getElementById('subjectForm').action = `/admin/subjects/${subject.id}`;
            document.getElementById('subjectMethod').value = 'PUT';
            document.getElementById('subjectId').value = subject.id;
            document.getElementById('subjectName').value = subject.name;
            document.getElementById('subjectDescription').value = subject.description || '';
            document.getElementById('subjectActive').checked = subject.is_active;
            document.getElementById('subjectModal').style.display = 'flex';
        }

        function closeSubjectModal() {
            document.getElementById('subjectModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('subjectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeSubjectModal();
            }
        });
    </script>
    </div> <!-- End Subjects Tab -->

    <!-- Evaluations Tab Content -->
    <div id="evaluationsContent" style="display: {{ $activeTab === 'evaluations' ? 'block' : 'none' }};">
        <!-- Header -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                        <i class="fas fa-star" style="color: #8b5cf6; margin-right: 8px;"></i>
                        Evaluation Management
                    </h2>
                    <p style="color: #64748b; font-size: 14px;">
                        Manage evaluation criteria and grading scales for courses
                    </p>
                </div>
                <button onclick="openAddEvaluationModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    <i class="fas fa-plus"></i>
                    Add New Evaluation
                </button>
            </div>
        </div>

        <!-- Evaluations Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Evaluations</h3>
            </div>

            @if($evaluations->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    ID
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Name
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Description
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Range
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Status
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Usage
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluations as $evaluation)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 16px; white-space: nowrap; color: #64748b; font-weight: 600; font-size: 14px;">
                                    #{{ $evaluation->id }}
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 12px; background: {{ $evaluation->color ?? '#8b5cf6' }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                            <i class="fas fa-{{ $evaluation->icon ?? 'star' }}"></i>
                                        </div>
                                        <div>
                                            <p style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $evaluation->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                    {{ $evaluation->description ?? 'No description' }}
                                </td>
                                <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                    @if($evaluation->min_percentage !== null && $evaluation->max_percentage !== null)
                                        {{ $evaluation->min_percentage }}% - {{ $evaluation->max_percentage }}%
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    @if($evaluation->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                    {{ $evaluation->courses()->count() }} courses
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px;">
                                        <button onclick='editEvaluation(@json($evaluation))' style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.evaluations.destroy', $evaluation->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this evaluation?');">
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
                    <i class="fas fa-star" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No evaluations found</p>
                    <p style="font-size: 14px; margin-bottom: 24px;">Start by adding your first evaluation criteria</p>
                    <button onclick="openAddEvaluationModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                        <i class="fas fa-plus"></i>
                        Add Evaluation
                    </button>
                </div>
            @endif
        </div>

        <!-- Add/Edit Evaluation Modal -->
        <div id="evaluationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 16px; max-width: 700px; width: 90%; position: relative;">
                <!-- Modal Header -->
                <div style="padding: 24px; border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 id="evaluationModalTitle" style="font-size: 20px; font-weight: 700; color: #1e293b;">Add New Evaluation</h2>
                        <button onclick="closeEvaluationModal()" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; font-size: 20px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div style="padding: 24px;">
                    <form id="evaluationForm" method="POST" action="{{ route('admin.evaluations.store') }}">
                        @csrf
                        <input type="hidden" id="evaluationMethod" name="_method" value="POST">
                        <input type="hidden" id="evaluationId" name="evaluation_id">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Name <span style="color: #dc2626;">*</span>
                                </label>
                                <input type="text" id="evaluationName" name="name" required maxlength="255"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="e.g., MashAllah, Mumtaz (Excellent)">
                                @error('name')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Description
                                </label>
                                <input type="text" id="evaluationDescription" name="description" maxlength="255"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="e.g., 100%, 85% – 99%">
                                @error('description')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Min Percentage
                                </label>
                                <input type="number" id="evaluationMinPercentage" name="min_percentage" min="0" max="100"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="0">
                                @error('min_percentage')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Max Percentage
                                </label>
                                <input type="number" id="evaluationMaxPercentage" name="max_percentage" min="0" max="100"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="100">
                                @error('max_percentage')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Icon
                                </label>
                                <input type="text" id="evaluationIcon" name="icon" maxlength="50"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="e.g., star, check-circle, sun">
                                @error('icon')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Color
                                </label>
                                <input type="color" id="evaluationColor" name="color"
                                    style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                @error('color')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Sort Order
                                </label>
                                <input type="number" id="evaluationSortOrder" name="sort_order" min="0" max="999" value="0"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                    placeholder="0">
                                @error('sort_order')
                                    <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="display: flex; align-items: end;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" id="evaluationActive" name="is_active" value="1" checked
                                        style="width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Active</span>
                                </label>
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; flex: 1;">
                                <i class="fas fa-save"></i>
                                Save Evaluation
                            </button>
                            <button type="button" onclick="closeEvaluationModal()" class="btn" style="background: #f1f5f9; color: #64748b;">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- End Evaluations Tab -->

    <!-- Payment Statuses Tab Content -->
    <div id="paymentStatusesContent" style="display: {{ $activeTab === 'payment-statuses' ? 'block' : 'none' }};">
        <!-- Header with Add Button -->
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                        <i class="fas fa-credit-card" style="color: #8b5cf6; margin-right: 8px;"></i>
                        Payment Status Management
                    </h2>
                    <p style="color: #64748b; font-size: 14px;">
                        Manage available payment statuses for students
                    </p>
                </div>
                <button onclick="openAddPaymentStatusModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    <i class="fas fa-plus"></i>
                    Add New Payment Status
                </button>
            </div>
        </div>

        <!-- Payment Statuses Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Available Payment Statuses</h3>
            </div>

            @if($paymentStatuses->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Status Name
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Display Name
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Color Badge
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Status
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Usage
                                </th>
                                <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentStatuses as $status)
                                @php
                                    $studentCount = $status->students()->count();
                                @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 16px; white-space: nowrap;">
                                    <code style="padding: 6px 12px; background: #f1f5f9; border-radius: 6px; color: #1e293b; font-size: 13px; font-weight: 600;">
                                        {{ $status->name }}
                                    </code>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <p style="font-weight: 600; color: #1e293b; font-size: 14px;">{{ $status->display_name }}</p>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <span style="display: inline-block; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; background: {{ $status->color }}; color: white;">
                                        {{ $status->name }}
                                    </span>
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    @if($status->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding: 16px; white-space: nowrap; color: #475569; font-size: 14px;">
                                    {{ $studentCount }} {{ $studentCount === 1 ? 'student' : 'students' }}
                                </td>
                                <td style="padding: 16px; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px;">
                                        <button onclick='editPaymentStatus(@json($status))' style="padding: 6px 12px; background: #dcfce7; color: #16a34a; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.payment-statuses.destroy', $status->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this payment status?');">
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
                    <i class="fas fa-credit-card" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No payment statuses found</p>
                    <p style="font-size: 14px; margin-bottom: 24px;">Start by adding your first payment status</p>
                    <button onclick="openAddPaymentStatusModal()" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                        <i class="fas fa-plus"></i>
                        Add Payment Status
                    </button>
                </div>
            @endif
        </div>

        <!-- Add/Edit Payment Status Modal -->
        <div id="paymentStatusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 16px; max-width: 600px; width: 90%; position: relative;">
                <!-- Modal Header -->
                <div style="padding: 24px; border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 id="paymentStatusModalTitle" style="font-size: 20px; font-weight: 700; color: #1e293b;">Add New Payment Status</h2>
                        <button onclick="closePaymentStatusModal()" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; font-size: 20px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div style="padding: 24px;">
                    <form id="paymentStatusForm" method="POST" action="{{ route('admin.payment-statuses.store') }}">
                        @csrf
                        <input type="hidden" id="paymentStatusMethod" name="_method" value="POST">
                        <input type="hidden" id="paymentStatusId" name="payment_status_id">

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                Status Name (Code) <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" id="paymentStatusName" name="name" required maxlength="255"
                                style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                placeholder="e.g., PAYÉ, ARRÊTÉ">
                            @error('name')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                Display Name <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" id="paymentStatusDisplayName" name="display_name" required maxlength="255"
                                style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                placeholder="e.g., Paid, Stopped">
                            @error('display_name')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                Color <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="color" id="paymentStatusColor" name="color" required
                                style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            @error('color')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                                    Sort Order
                                </label>
                                <input type="number" id="paymentStatusSortOrder" name="sort_order" min="0" max="999" value="0"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div style="display: flex; align-items: end;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" id="paymentStatusActive" name="is_active" value="1" checked
                                        style="width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Active</span>
                                </label>
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; flex: 1;">
                                <i class="fas fa-save"></i>
                                Save Payment Status
                            </button>
                            <button type="button" onclick="closePaymentStatusModal()" class="btn" style="background: #f1f5f9; color: #64748b;">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openAddPaymentStatusModal() {
                document.getElementById('paymentStatusModalTitle').textContent = 'Add New Payment Status';
                document.getElementById('paymentStatusForm').action = '{{ route("admin.payment-statuses.store") }}';
                document.getElementById('paymentStatusMethod').value = 'POST';
                document.getElementById('paymentStatusId').value = '';
                document.getElementById('paymentStatusName').value = '';
                document.getElementById('paymentStatusDisplayName').value = '';
                document.getElementById('paymentStatusColor').value = '#8b5cf6';
                document.getElementById('paymentStatusSortOrder').value = '0';
                document.getElementById('paymentStatusActive').checked = true;
                document.getElementById('paymentStatusModal').style.display = 'flex';
            }

            function editPaymentStatus(status) {
                document.getElementById('paymentStatusModalTitle').textContent = 'Edit Payment Status';
                document.getElementById('paymentStatusForm').action = `/admin/payment-statuses/${status.id}`;
                document.getElementById('paymentStatusMethod').value = 'PUT';
                document.getElementById('paymentStatusId').value = status.id;
                document.getElementById('paymentStatusName').value = status.name;
                document.getElementById('paymentStatusDisplayName').value = status.display_name;
                document.getElementById('paymentStatusColor').value = status.color || '#8b5cf6';
                document.getElementById('paymentStatusSortOrder').value = status.sort_order || '0';
                document.getElementById('paymentStatusActive').checked = status.is_active;
                document.getElementById('paymentStatusModal').style.display = 'flex';
            }

            function closePaymentStatusModal() {
                document.getElementById('paymentStatusModal').style.display = 'none';
            }

            // Close modal when clicking outside
            document.getElementById('paymentStatusModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentStatusModal();
                }
            });
        </script>
    </div> <!-- End Payment Statuses Tab -->

    <script>
        function switchTab(tab) {
            // Hide all tab contents
            document.getElementById('subjectsContent').style.display = 'none';
            document.getElementById('evaluationsContent').style.display = 'none';
            if (document.getElementById('paymentStatusesContent')) {
                document.getElementById('paymentStatusesContent').style.display = 'none';
            }
            
            // Remove active class from all tabs
            document.getElementById('subjectsTab').style.background = 'transparent';
            document.getElementById('subjectsTab').style.color = '#64748b';
            document.getElementById('subjectsTab').style.borderBottom = '2px solid transparent';
            
            document.getElementById('evaluationsTab').style.background = 'transparent';
            document.getElementById('evaluationsTab').style.color = '#64748b';
            document.getElementById('evaluationsTab').style.borderBottom = '2px solid transparent';
            
            if (document.getElementById('paymentStatusesTab')) {
                document.getElementById('paymentStatusesTab').style.background = 'transparent';
                document.getElementById('paymentStatusesTab').style.color = '#64748b';
                document.getElementById('paymentStatusesTab').style.borderBottom = '2px solid transparent';
            }
            
            // Show selected tab content
            if (tab === 'subjects') {
                document.getElementById('subjectsContent').style.display = 'block';
                document.getElementById('subjectsTab').style.background = '#8b5cf6';
                document.getElementById('subjectsTab').style.color = 'white';
                document.getElementById('subjectsTab').style.borderBottom = '2px solid #8b5cf6';
            } else if (tab === 'evaluations') {
                document.getElementById('evaluationsContent').style.display = 'block';
                document.getElementById('evaluationsTab').style.background = '#8b5cf6';
                document.getElementById('evaluationsTab').style.color = 'white';
                document.getElementById('evaluationsTab').style.borderBottom = '2px solid #8b5cf6';
            } else if (tab === 'payment-statuses') {
                if (document.getElementById('paymentStatusesContent')) {
                    document.getElementById('paymentStatusesContent').style.display = 'block';
                }
                if (document.getElementById('paymentStatusesTab')) {
                    document.getElementById('paymentStatusesTab').style.background = '#8b5cf6';
                    document.getElementById('paymentStatusesTab').style.color = 'white';
                    document.getElementById('paymentStatusesTab').style.borderBottom = '2px solid #8b5cf6';
                }
            }
        }

        // Evaluation Modal Functions
        function openAddEvaluationModal() {
            document.getElementById('evaluationModalTitle').textContent = 'Add New Evaluation';
            document.getElementById('evaluationForm').action = '{{ route("admin.evaluations.store") }}';
            document.getElementById('evaluationMethod').value = 'POST';
            document.getElementById('evaluationId').value = '';
            document.getElementById('evaluationName').value = '';
            document.getElementById('evaluationDescription').value = '';
            document.getElementById('evaluationMinPercentage').value = '';
            document.getElementById('evaluationMaxPercentage').value = '';
            document.getElementById('evaluationIcon').value = '';
            document.getElementById('evaluationColor').value = '#8b5cf6';
            document.getElementById('evaluationSortOrder').value = '0';
            document.getElementById('evaluationActive').checked = true;
            document.getElementById('evaluationModal').style.display = 'flex';
        }

        // Auto-open modal if there are validation errors
        @if(session('open_evaluation_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                // Switch to evaluations tab
                switchTab('evaluations');
                
                // Open the modal
                document.getElementById('evaluationModal').style.display = 'flex';
                
                // Populate form with old input data
                @if(old('name'))
                    document.getElementById('evaluationName').value = '{{ old("name") }}';
                @endif
                @if(old('description'))
                    document.getElementById('evaluationDescription').value = '{{ old("description") }}';
                @endif
                @if(old('min_percentage'))
                    document.getElementById('evaluationMinPercentage').value = '{{ old("min_percentage") }}';
                @endif
                @if(old('max_percentage'))
                    document.getElementById('evaluationMaxPercentage').value = '{{ old("max_percentage") }}';
                @endif
                @if(old('icon'))
                    document.getElementById('evaluationIcon').value = '{{ old("icon") }}';
                @endif
                @if(old('color'))
                    document.getElementById('evaluationColor').value = '{{ old("color") }}';
                @endif
                @if(old('sort_order'))
                    document.getElementById('evaluationSortOrder').value = '{{ old("sort_order") }}';
                @endif
                @if(old('is_active'))
                    document.getElementById('evaluationActive').checked = {{ old('is_active') ? 'true' : 'false' }};
                @endif
            });
        @endif

        function editEvaluation(evaluation) {
            document.getElementById('evaluationModalTitle').textContent = 'Edit Evaluation';
            document.getElementById('evaluationForm').action = `/admin/evaluations/${evaluation.id}`;
            document.getElementById('evaluationMethod').value = 'PUT';
            document.getElementById('evaluationId').value = evaluation.id;
            document.getElementById('evaluationName').value = evaluation.name;
            document.getElementById('evaluationDescription').value = evaluation.description || '';
            document.getElementById('evaluationMinPercentage').value = evaluation.min_percentage || '';
            document.getElementById('evaluationMaxPercentage').value = evaluation.max_percentage || '';
            document.getElementById('evaluationIcon').value = evaluation.icon || '';
            document.getElementById('evaluationColor').value = evaluation.color || '#8b5cf6';
            document.getElementById('evaluationSortOrder').value = evaluation.sort_order || '';
            document.getElementById('evaluationActive').checked = evaluation.is_active;
            document.getElementById('evaluationModal').style.display = 'flex';
        }

        function closeEvaluationModal() {
            document.getElementById('evaluationModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('evaluationModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEvaluationModal();
            }
        });

        // Client-side validation for evaluation form
        document.getElementById('evaluationForm')?.addEventListener('submit', function(e) {
            const name = document.getElementById('evaluationName').value.trim();
            const minPercentage = document.getElementById('evaluationMinPercentage').value;
            const maxPercentage = document.getElementById('evaluationMaxPercentage').value;
            const sortOrder = document.getElementById('evaluationSortOrder').value;
            const icon = document.getElementById('evaluationIcon').value.trim();
            const color = document.getElementById('evaluationColor').value;

            let isValid = true;
            let errorMessage = '';

            // Clear previous error styles
            clearErrorStyles();

            // Validate name (required)
            if (!name) {
                showFieldError('evaluationName', 'Name is required.');
                isValid = false;
            } else if (name.length > 255) {
                showFieldError('evaluationName', 'Name cannot exceed 255 characters.');
                isValid = false;
            }

            // Validate percentages
            if (minPercentage && (minPercentage < 0 || minPercentage > 100)) {
                showFieldError('evaluationMinPercentage', 'Minimum percentage must be between 0 and 100.');
                isValid = false;
            }

            if (maxPercentage && (maxPercentage < 0 || maxPercentage > 100)) {
                showFieldError('evaluationMaxPercentage', 'Maximum percentage must be between 0 and 100.');
                isValid = false;
            }

            // Validate percentage range
            if (minPercentage && maxPercentage && parseInt(minPercentage) > parseInt(maxPercentage)) {
                showFieldError('evaluationMaxPercentage', 'Maximum percentage must be greater than or equal to minimum percentage.');
                isValid = false;
            }

            // Validate sort order
            if (sortOrder && (sortOrder < 0 || sortOrder > 999)) {
                showFieldError('evaluationSortOrder', 'Sort order must be between 0 and 999.');
                isValid = false;
            }

            // Validate icon length
            if (icon && icon.length > 50) {
                showFieldError('evaluationIcon', 'Icon name cannot exceed 50 characters.');
                isValid = false;
            }

            // Validate color format
            if (color && !/^#[0-9A-Fa-f]{6}$/.test(color)) {
                showFieldError('evaluationColor', 'Color must be a valid hex color code (e.g., #FF0000).');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });

        function clearErrorStyles() {
            const errorElements = document.querySelectorAll('.field-error');
            errorElements.forEach(el => el.remove());
            
            const inputs = document.querySelectorAll('#evaluationForm input');
            inputs.forEach(input => {
                input.style.borderColor = '#e2e8f0';
            });
        }

        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.style.borderColor = '#dc2626';
            
            // Remove existing error message
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Add new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.style.color = '#dc2626';
            errorDiv.style.fontSize = '12px';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;
            
            field.parentNode.appendChild(errorDiv);
        }

        // Real-time validation for percentage fields
        document.getElementById('evaluationMinPercentage')?.addEventListener('input', function() {
            const minVal = parseInt(this.value);
            const maxVal = parseInt(document.getElementById('evaluationMaxPercentage').value);
            
            if (minVal && maxVal && minVal > maxVal) {
                showFieldError('evaluationMinPercentage', 'Minimum percentage cannot be greater than maximum percentage.');
            } else {
                clearFieldError('evaluationMinPercentage');
            }
        });

        document.getElementById('evaluationMaxPercentage')?.addEventListener('input', function() {
            const maxVal = parseInt(this.value);
            const minVal = parseInt(document.getElementById('evaluationMinPercentage').value);
            
            if (minVal && maxVal && minVal > maxVal) {
                showFieldError('evaluationMaxPercentage', 'Maximum percentage must be greater than or equal to minimum percentage.');
            } else {
                clearFieldError('evaluationMaxPercentage');
            }
        });

        function clearFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            field.style.borderColor = '#e2e8f0';
            
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    </script>
@endsection

