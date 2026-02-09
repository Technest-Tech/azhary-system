<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Cours</title>
    @php
        // Convert logo to base64 for PDF compatibility
        $logoPath = public_path('logo.png');
        
        function imageToBase64($path) {
            if (file_exists($path)) {
                $imageData = base64_encode(file_get_contents($path));
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $mime = $ext === 'jpg' ? 'jpeg' : ($ext === 'png' ? 'png' : $ext);
                return 'data:image/' . $mime . ';base64,' . $imageData;
            }
            return null;
        }
        
        $logoSrc = imageToBase64($logoPath);
    @endphp
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
            background-color: #e0f2fe; /* Light pastel blue background */
            color: #000;
            position: relative;
            width: 1240px; /* A4 width at 150dpi */
            min-height: 1754px; /* A4 height at 150dpi */
            height: 1754px; /* Fixed height to match A4 page */
            padding: 20px 50px 30px 50px;
            margin: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        html {
            width: 1240px;
            height: 1754px;
            overflow: visible;
        }
        
        /* Logo Section - Top Left */
        .logo-container {
            position: absolute;
            top: 30px;
            left: 50px;
            z-index: 10;
        }
        
        .logo-wrapper {
            background: white;
            border: 3px solid #0d9488; /* Teal border */
            border-radius: 15px;
            padding: 15px 20px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .logo-wrapper img {
            width: 84px;
            height: auto;
            display: block;
        }
        
        /* Title Banner Section - Centered Top */
        .title-section {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 12px;
            position: relative;
            z-index: 5;
            flex-shrink: 0;
        }
        
        .title-banner {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); /* Teal gradient background */
            color: white;
            padding: 25px 60px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 3px solid #0d9488;
            position: relative;
            overflow: visible;
        }
        
        .title-banner::before,
        .title-banner::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid white;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .title-banner::before {
            top: -8px;
            left: -8px;
            border-right: none;
            border-bottom: none;
            border-radius: 5px 0 0 0;
        }
        
        .title-banner::after {
            bottom: -8px;
            right: -8px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 5px 0;
            box-shadow: -2px -2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .title-text {
            font-size: 38px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            letter-spacing: 4px;
            white-space: nowrap;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        /* Student Name Section */
        .student-name-section {
            text-align: center;
            margin: 15px 0 10px 0;
            position: relative;
            z-index: 3;
            flex-shrink: 0;
        }
        
        .student-name-box {
            display: inline-block;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            padding: 18px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
            border: 3px solid #0d9488;
        }
        
        .student-name-text {
            font-size: 32px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        /* Warning/Evaluation Section */
        .evaluation-warning {
            text-align: center;
            margin: 8px 0 8px 0;
            position: relative;
            z-index: 3;
            flex-shrink: 0;
        }
        
        .evaluation-warning-content {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            color: #000;
            font-weight: 600;
        }
        
        .warning-icon {
            font-size: 28px;
        }
        
        /* Horizontal Divider Line */
        .divider-line {
            width: 100%;
            height: 2px;
            background: #0d9488;
            margin: 8px 0 15px 0;
            flex-shrink: 0;
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            position: relative;
            z-index: 3;
            flex-shrink: 0;
            flex-wrap: wrap;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .info-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }
        
        .info-box {
            background: #0d9488; /* Teal background */
            border-radius: 25px;
            padding: 14px 28px;
            font-size: 20px;
            font-weight: 600;
            color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            white-space: nowrap;
        }
        
        .info-box.white {
            background: white;
            color: #000;
            border: 2px solid #0d9488;
        }
        
        .info-box.blank {
            background: white;
            color: transparent;
            border: 2px solid #e5e7eb;
        }
        
        /* Main Content Section - 2x2 Grid */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 25px;
            margin-bottom: 20px;
            flex: 1;
            min-height: 0;
            position: relative;
            z-index: 3;
        }
        
        .content-card {
            background: white;
            border: 3px solid #0d9488; /* Teal border */
            border-radius: 18px;
            padding: 25px;
            min-height: 0;
            height: 100%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
        }
        
        .card-title {
            font-size: 26px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }
        
        .card-icon {
            font-size: 30px;
        }
        
        .card-content {
            font-size: 22px;
            color: #374151;
            line-height: 2;
            flex: 1;
        }
        
        /* Souvenir Image Container */
        .souvenir-image-container {
            margin-top: 10px;
            text-align: center;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .souvenir-image-container img {
            max-width: 100%;
            max-height: 240px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            object-fit: contain;
        }
        
        .souvenir-placeholder {
            color: #9ca3af;
            font-size: 18px;
            font-style: italic;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: auto;
            padding-top: 15px;
            position: relative;
            z-index: 3;
            flex-shrink: 0;
        }
        
        .footer-message {
            color: #000;
            font-size: 24px;
            font-weight: 600;
            line-height: 1.8;
        }
        
        .footer-message .emoji {
            font-size: 26px;
            margin: 0 4px;
        }
    </style>
</head>
<body>
    <!-- Logo - Top Left -->
    <div class="logo-container">
        @if($logoSrc)
            <div class="logo-wrapper">
                <img src="{!! $logoSrc !!}" alt="Azhary Logo">
            </div>
        @endif
    </div>
    
    <!-- Title Section -->
    <div class="title-section">
        <div class="title-banner">
            <div class="title-text">LE RAPPORT D'AUJOURD'HUI</div>
        </div>
    </div>
    
    <!-- Student Name Section -->
    <div class="student-name-section">
        <div class="student-name-box">
            <div class="student-name-text">{{ $course->student_name ?? ($course->student->name ?? 'N/A') }}</div>
        </div>
    </div>
    
    <!-- Evaluation Warning -->
    <div class="evaluation-warning">
        <div class="evaluation-warning-content">
            <span class="warning-icon">⚠️</span>
            <span>{{ $course->evaluation ? $course->evaluation->name : 'Mutawassit (Moyen)' }}</span>
        </div>
    </div>
    
    <!-- Divider Line -->
    <div class="divider-line"></div>
    
    <!-- Info Section -->
    <div class="info-section">
        <div class="info-item">
            <div class="info-icon">📅</div>
            <div class="info-box">{{ $course->course_date ? $course->course_date->format('Y/m/d') : 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-icon">📚</div>
            <div class="info-box">{{ $course->student && $course->student->subject ? $course->student->subject->name : 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-icon">🕐</div>
            <div class="info-box">{{ $course->duration_hours ?? 0 }}h {{ $course->duration_minutes ?? 0 }}m</div>
        </div>
        @if($course->evaluation)
        <div class="info-item">
            <div class="info-box">{{ $course->evaluation->name }}</div>
        </div>
        @endif
        <div class="info-item">
            <div class="info-box white">{{ ucfirst(strtolower($course->status ?? 'N/A')) }}</div>
        </div>
    </div>
    
    <!-- Main Content Section - 2x2 Grid -->
    <div class="main-content">
        <!-- Top Left: CONTENU -->
        <div class="content-card">
            <div class="card-header">
                <div class="card-icon">📄</div>
                <div class="card-title">CONTENU</div>
            </div>
            <div class="card-content">
                {{ $course->content ?? 'Aucun contenu disponible' }}
            </div>
        </div>
        
        <!-- Top Right: NOTES -->
        <div class="content-card">
            <div class="card-header">
                <div class="card-icon">∞</div>
                <div class="card-title">NOTES</div>
            </div>
            <div class="card-content">
                {{ $course->notes ?? 'Aucune note disponible' }}
            </div>
        </div>
        
        <!-- Bottom Left: SOUVENIR -->
        <div class="content-card">
            <div class="card-header">
                <div class="card-icon">📷</div>
                <div class="card-title">SOUVENIR</div>
            </div>
            <div class="souvenir-image-container">
                @if($course->souvenir_image)
                    @php
                        $imagePath = storage_path('app/public/' . $course->souvenir_image);
                        if (file_exists($imagePath)) {
                            $imageData = base64_encode(file_get_contents($imagePath));
                            $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
                            $mime = $ext === 'jpg' ? 'jpeg' : ($ext === 'png' ? 'png' : $ext);
                            $imageSrc = 'data:image/' . $mime . ';base64,' . $imageData;
                        } else {
                            $imageSrc = null;
                        }
                    @endphp
                    @if($imageSrc)
                        <img src="{!! $imageSrc !!}" alt="Souvenir">
                    @else
                        <p class="souvenir-placeholder">Image non trouvée</p>
                    @endif
                @else
                    <p class="souvenir-placeholder">Aucune image disponible</p>
                @endif
            </div>
        </div>
        
        <!-- Bottom Right: LE DEVOIR -->
        <div class="content-card">
            <div class="card-header">
                <div class="card-icon">⛰️</div>
                <div class="card-title">LE DEVOIR</div>
            </div>
            <div class="card-content" style="text-align: center;">
                @if($course->homework)
                    @if(strtolower(trim($course->homework)) === 'fait' || strtolower(trim($course->homework)) === 'done')
                        <div style="font-weight: 600; font-size: 24px; color: #059669;">Fait ✨ 👓</div>
                    @else
                        {{ $course->homework }}
                    @endif
                @else
                    Aucun devoir assigné
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-message">
            Merci d'avoir rejoint la famille 'Azhary' <span class="emoji">✨</span> <span class="emoji">😍</span>
        </div>
    </div>
</body>
</html>
