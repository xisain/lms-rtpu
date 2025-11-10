<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Completion Certificate</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            background: linear-gradient(135deg, #009999 0%, #057755 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-wrapper {
            width: 277mm;
            height: 190mm;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .corner {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 3px solid #009999;
        }

        .corner-tl {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }

        .corner-bl {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }

        .corner-br {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }

        .circle-decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            background: #667eea;
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            background: #009999;
            bottom: -50px;
            left: -50px;
        }

        .certificate-content {
            position: relative;
            z-index: 10;
            padding: 50px 80px;
            text-align: center;
        }

        .header {
            margin-bottom: 25px;
        }

        .header-badge img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            line-height: 80px;
            color: white;
            font-size: 40px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #2d3748;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #009999 0%, #057755 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 16px;
            color: #718096;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .presented-to {
            font-size: 18px;
            color: #4a5568;
            font-style: italic;
            margin-bottom: 15px;
        }

        .recipient-name {
            font-size: 42px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .recipient-name::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #009999, transparent);
        }

        .completion-text {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .course-name {
            font-size: 32px;
            font-weight: bold;
            color: #009999;
            margin: 20px 0 30px 0;
        }

        .details {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin: 30px 0;
            font-size: 14px;
            color: #718096;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            margin: 0px 90px 0px 90px
        }

        .detail-value {
            color: #2d3748;
            font-size: 14px;
        }

        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding: 0 100px;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-line {
            width: 200px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #cbd5e0, transparent);
            margin: 0 auto 10px;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 12px;
            color: #718096;
            font-style: italic;
        }

        .seal {
            position: absolute;
            bottom: 40px;
            left: 60px;
            width: 80px;
            height: 80px;
            border: 3px solid #009999;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            transform: rotate(-15deg);
        }

        .seal-text {
            font-size: 10px;
            font-weight: bold;
            color: #009999;
            text-align: center;
            line-height: 1.2;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <!-- Decorative corners -->
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <!-- Decorative circles -->
        <div class="circle-decoration circle-1"></div>
        <div class="circle-decoration circle-2"></div>

        <!-- Seal -->
        <div class="seal">
            <div class="seal-text">
                VERIFIED<br>CERTIFICATE
            </div>
        </div>

        <div class="certificate-content">
            <div class="header">
                <div class="header-badge">
                <img src="{{ public_path('images/logo-pnj.png') }}" alt="Logo" class="logo">
                </div>
                <div class="certificate-title">Certificate</div>
                <div class="subtitle">of Completion</div>
            </div>

            <div class="presented-to">This is proudly presented to</div>

            <div class="recipient-name">{{ $user->name }}</div>

            <div class="completion-text">
                for successfully completing the course
            </div>

            <div class="course-name">{{ $course->nama_course }}</div>

            <div class="details">
                <div class="detail-item">
                    <div class="detail-label">Certificate Number</div>
                    <div class="detail-value">{{ $certificate->certificate_number }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Issue Date</div>
                    <div class="detail-value">{{ $certificate->issued_date->format('F d, Y') }}</div>
                </div>
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $course->teacher->name }}</div>
                    <div class="signature-title">Course Instructor</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Platform Director</div>
                    <div class="signature-title">Education Platform</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
