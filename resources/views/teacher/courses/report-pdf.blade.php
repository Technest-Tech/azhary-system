<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Cours</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #0d9488;
            color: #000;
            position: relative;
            width: 210mm;
            min-height: 297mm;
            padding: 5mm 15mm 10mm 15mm;
        }
        
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            object-fit: cover;
        }
        
        .transparent-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(13, 148, 136, 0.5);
            z-index: 1;
        }
        
        .overlay-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(13, 148, 136, 0.2);
            z-index: 1.5;
        }
        
        .content-wrapper {
            position: relative;
            z-index: 2;
        }
        
        /* Decorative elements */
        .top-right-branch {
            position: absolute;
            top: 0;
            right: 0;
            width: 280px;
            height: auto;
            z-index: 3;
        }
        
        .bottom-left-branch {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 280px;
            height: auto;
            z-index: 3;
        }
        
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 0;
            position: relative;
            z-index: 5;
        }
        
        .title-frame-wrapper {
            position: relative;
            display: inline-block;
            margin: 0 auto 15px;
        }
        
        .title-with-muscles {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .title-muscle-left,
        .title-muscle-right {
            width: 90px;
            height: auto;
            flex-shrink: 0;
        }
        
        .title-frame-image {
            width: 350px;
            height: auto;
            position: relative;
        }
        
        .title-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            white-space: nowrap;
        }
        
        .student-name-box {
            background: white;
            border-radius: 50px;
            padding: 10px 25px;
            display: inline-block;
            margin: 0 auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .student-name-box span {
            font-size: 18px;
            font-weight: 600;
            color: #000;
            text-transform: uppercase;
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 22px;
            position: relative;
            z-index: 3;
        }
        
        .info-left {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .info-right {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .info-box {
            background: white;
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            color: #000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Main Content Section */
        .main-content {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            position: relative;
            z-index: 3;
        }
        
        .content-card, .notes-card {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        
        .card-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }
        
        .card-icon {
            font-size: 18px;
        }
        
        .card-content {
            font-size: 12px;
            color: #374151;
            line-height: 1.6;
            margin-top: 5px;
        }
        
        .divider-line {
            width: 3px;
            background: #fbbf24;
            margin: 0 10px;
        }
        
        /* Bottom Section */
        .bottom-section {
            display: flex;
            gap: 15px;
            margin-bottom: 18px;
            margin-top: 15px;
            position: relative;
            z-index: 3;
        }
        
        .souvenir-card, .homework-card {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .souvenir-image-container {
            margin-top: 12px;
            text-align: center;
        }
        
        .souvenir-image-container img {
            max-width: 100%;
            max-height: 140px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }
        
        .homework-content {
            font-size: 12px;
            color: #374151;
            line-height: 1.6;
            margin-top: 12px;
        }
        
        .homework-done {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #059669;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 15px;
            position: relative;
            z-index: 3;
        }
        
        .footer-message {
            color: #f97316;
            font-size: 14px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .footer-message span {
            margin: 0 5px;
        }
        
        /* Section Dividers */
        .section-divider {
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            margin: 15px 0;
            border-radius: 1px;
        }
    </style>
</head>
<body>
    @php
        // Convert public images to base64 for PDF compatibility
        $backgroundPath = public_path('report_background.jpg');
        $topBranchPath = public_path('top-right-branch.png');
        $bottomBranchPath = public_path('bootom-left-branch.png');
        $leftMusclePath = public_path('left-muscle.png');
        $rightMusclePath = public_path('right-muscle.png');
        $titleFramePath = public_path('title-bocorative.png');
        
        function imageToBase64($path) {
            if (file_exists($path)) {
                $imageData = base64_encode(file_get_contents($path));
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $mime = $ext === 'jpg' ? 'jpeg' : $ext;
                return 'data:image/' . $mime . ';base64,' . $imageData;
            }
            return null;
        }
        
        $backgroundSrc = imageToBase64($backgroundPath);
        $topBranchSrc = imageToBase64($topBranchPath);
        $bottomBranchSrc = imageToBase64($bottomBranchPath);
        $leftMuscleSrc = imageToBase64($leftMusclePath);
        $rightMuscleSrc = imageToBase64($rightMusclePath);
        $titleFrameSrc = imageToBase64($titleFramePath);
    @endphp
    
    <!-- Background Image -->
    @if($backgroundSrc)
        <img src="{{ $backgroundSrc }}" alt="Background" class="background-image">
    @endif
    
    <!-- Transparent Overlay Layers -->
    <div class="transparent-overlay"></div>
    <div class="overlay-layer"></div>
    
    <!-- Decorative Elements -->
    @if($topBranchSrc)
        <img src="{{ $topBranchSrc }}" alt="Branch" class="top-right-branch">
    @endif
    @if($bottomBranchSrc)
        <img src="{{ $bottomBranchSrc }}" alt="Branch" class="bottom-left-branch">
    @endif
    
    <div class="content-wrapper">
        <!-- Title Section -->
        <div class="title-section">
            <div class="title-with-muscles">
                @if($leftMuscleSrc)
                    <img src="{{ $leftMuscleSrc }}" alt="Muscle" class="title-muscle-left">
                @endif
                <div class="title-frame-wrapper">
                    @if($titleFrameSrc)
                        <img src="{{ $titleFrameSrc }}" alt="Title Frame" class="title-frame-image">
                    @endif
                    <div class="title-text">LE RAPPORT D'AUJOURD'HUI</div>
                </div>
                @if($rightMuscleSrc)
                    <img src="{{ $rightMuscleSrc }}" alt="Muscle" class="title-muscle-right">
                @endif
            </div>
            <div class="student-name-box">
                <span>{{ $course->student_name ?? ($course->student->name ?? 'N/A') }}</span>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="section-divider"></div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-item">
                    <div class="info-icon">📅</div>
                    <div class="info-box">{{ $course->course_date ? $course->course_date->format('Y-m-d') : 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📚</div>
                    <div class="info-box">{{ $course->student && $course->student->subject ? $course->student->subject->name : 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">🕐</div>
                    <div class="info-box">{{ $course->duration_hours ?? 0 }}h {{ $course->duration_minutes ?? 0 }}m</div>
                </div>
            </div>
            <div class="info-right">
                @if($course->evaluation)
                <div class="info-item" style="justify-content: center;">
                    <div class="info-icon">🌙</div>
                    <div class="info-box">{{ $course->evaluation->name }}</div>
                </div>
                @endif
                <div class="info-item" style="justify-content: center;">
                    <div class="info-box">{{ ucfirst(strtolower($course->status ?? 'N/A')) }}</div>
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="section-divider"></div>
        
        <!-- Main Content Section -->
        <div class="main-content">
            <div class="content-card">
                <div class="card-header">
                    <div class="card-icon">📄</div>
                    <div class="card-title">Contenu</div>
                </div>
                <div class="card-content">
                    {{ $course->content ?? 'Aucun contenu disponible' }}
                </div>
            </div>
            <div class="divider-line"></div>
            <div class="notes-card">
                <div class="card-header">
                    <div class="card-icon">👓</div>
                    <div class="card-title">Notes</div>
                </div>
                <div class="card-content">
                    {{ $course->notes ?? 'Aucune note disponible' }}
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="section-divider"></div>
        
        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="souvenir-card">
                <div class="card-header">
                    <div class="card-icon">📷</div>
                    <div class="card-title">Souvenir</div>
                </div>
                <div class="souvenir-image-container">
                    @if($course->souvenir_image)
                        @php
                            $imagePath = storage_path('app/public/' . $course->souvenir_image);
                            if (file_exists($imagePath)) {
                                $imageData = base64_encode(file_get_contents($imagePath));
                                $imageSrc = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                            } else {
                                $imageSrc = null;
                            }
                        @endphp
                        @if($imageSrc)
                            <img src="{{ $imageSrc }}" alt="Souvenir">
                        @else
                            <p style="color: #9ca3af; font-size: 12px;">Image non trouvée</p>
                        @endif
                    @else
                        <p style="color: #9ca3af; font-size: 12px;">Aucune image disponible</p>
                    @endif
                </div>
            </div>
            <div class="homework-card">
                <div class="card-header">
                    <div class="card-icon">🏠</div>
                    <div class="card-title">Le Devoir</div>
                </div>
                <div class="homework-content">
                    @if($course->homework)
                        @if(strtolower(trim($course->homework)) === 'fait' || strtolower(trim($course->homework)) === 'done')
                            <div class="homework-done">Fait ✨ 👓</div>
                        @else
                            {{ $course->homework }}
                        @endif
                    @else
                        Aucun devoir assigné
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="section-divider"></div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">
                Merci d'avoir rejoint la famille 'Madrassat Elkarim' <span>👨‍👩‍👧‍👦</span> <span>😍</span>
            </div>
        </div>
    </div>
</body>
</html>
