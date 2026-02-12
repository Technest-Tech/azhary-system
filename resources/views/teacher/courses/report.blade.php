<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Report - {{ $course->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            padding: 40px;
            color: #1e293b;
        }
        
        .report-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .report-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .report-header p {
            color: #64748b;
            font-size: 16px;
        }
        
        .report-section {
            margin-bottom: 32px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .content-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin-top: 8px;
        }
        
        .content-box p {
            color: #475569;
            line-height: 1.6;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-present {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-absent {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .evaluation-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 8px;
        }
        
        .evaluation-box h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 4px;
        }
        
        .evaluation-box p {
            color: #1e40af;
            font-size: 14px;
        }
        
        .souvenir-image {
            margin-top: 16px;
            text-align: center;
        }
        
        .souvenir-image img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .report-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .print-button:hover {
            background: #2563eb;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                padding: 0;
            }
            
            .report-container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Report
    </button>
    
    <div class="report-container">
        <div class="report-header">
            <h1>Course Report</h1>
            <p>Azhary Academy - Course Details</p>
        </div>
        
        <!-- Basic Information -->
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Student Name</span>
                    <span class="info-value">{{ $course->student->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Course Name</span>
                    <span class="info-value">{{ $course->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Course Type</span>
                    <span class="info-value">{{ $course->course_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date</span>
                    <span class="info-value">{{ $course->course_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Time</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($course->class_time)->format('H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Duration</span>
                    <span class="info-value">{{ $course->duration_hours }}h {{ $course->duration_minutes }}m</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="status-badge status-{{ strtolower($course->status) }}">
                        @if($course->status === 'Present')
                            Present
                        @elseif($course->status === 'Absent')
                            Absent
                        @elseif($course->status === 'Free')
                            Free
                        @else
                            {{ $course->status }}
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teacher</span>
                    <span class="info-value">{{ $course->teacher->name }}</span>
                </div>
            </div>
        </div>
        
        <!-- Evaluation -->
        @if($course->evaluation)
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Evaluation
            </h2>
            <div class="evaluation-box">
                <h3>{{ $course->evaluation->name }} : {{ $course->evaluation->max_percentage }}%</h3>
                @if($course->evaluation->description)
                    <p>{{ $course->evaluation->description }}</p>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Homework -->
        @if($course->homework)
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                Homework
            </h2>
            <div class="content-box">
                <p>{{ $course->homework }}</p>
            </div>
        </div>
        @endif
        
        <!-- Content -->
        @if($course->content)
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-file-alt"></i>
                Content Covered
            </h2>
            <div class="content-box">
                <p>{{ $course->content }}</p>
            </div>
        </div>
        @endif
        
        <!-- Notes -->
        @if($course->notes)
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-sticky-note"></i>
                Notes
            </h2>
            <div class="content-box">
                <p>{{ $course->notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Souvenir Image -->
        @if($course->souvenir_image)
        <div class="report-section">
            <h2 class="section-title">
                <i class="fas fa-camera"></i>
                Souvenir
            </h2>
            <div class="souvenir-image">
                <img src="{{ asset('storage/' . $course->souvenir_image) }}" alt="Souvenir Image">
            </div>
        </div>
        @endif
        
        <div class="report-footer">
            <p>Generated on {{ now()->format('d/m/Y H:i') }}</p>
            <p>Azhary Academy - Educational Management System</p>
        </div>
    </div>
</body>
</html>

