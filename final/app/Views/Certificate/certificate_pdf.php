<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .certificate-container {
            width: 100%;
            min-height: 297mm;
            background: #ffffff;
            border: 3px solid #206bc4;
            padding: 40px;
            position: relative;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #206bc4;
            padding-bottom: 20px;
        }
        
        .clinic-logo {
            font-size: 28px;
            font-weight: bold;
            color: #206bc4;
            margin-bottom: 5px;
        }
        
        .clinic-name {
            font-size: 18px;
            font-weight: 600;
            color: #206bc4;
            margin-bottom: 2px;
        }
        
        .clinic-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .certificate-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #206bc4;
            margin: 40px 0 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .certificate-subtitle {
            text-align: center;
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
            font-style: italic;
        }
        
        .content-section {
            margin-bottom: 25px;
        }
        
        .content-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .content-row-half {
            display: table-cell;
            width: 48%;
            padding-right: 20px;
            vertical-align: top;
        }
        
        .content-row-half:last-child {
            padding-right: 0;
        }
        
        .label {
            font-size: 12px;
            font-weight: 700;
            color: #206bc4;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .value {
            font-size: 14px;
            color: #333;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
            min-height: 20px;
        }
        
        .certificate-type-badge {
            display: inline-block;
            background: #206bc4;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        
        .description-section {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #206bc4;
            margin: 20px 0;
            border-radius: 2px;
        }
        
        .description-label {
            font-size: 12px;
            font-weight: 700;
            color: #206bc4;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .description-text {
            font-size: 13px;
            color: #333;
            line-height: 1.8;
        }
        
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        
        .signature-block {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-block:last-child {
            text-align: right;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            width: 150px;
            margin: 60px auto 10px;
        }
        
        .signature-block:last-child .signature-line {
            margin-left: auto;
            margin-right: 0;
        }
        
        .signature-name {
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        
        .signature-title {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }
        
        .validity-badge {
            margin-top: 30px;
            padding: 15px;
            background: #e7f3ff;
            border-left: 4px solid #206bc4;
            border-radius: 2px;
        }
        
        .validity-label {
            font-size: 11px;
            font-weight: 700;
            color: #206bc4;
            text-transform: uppercase;
        }
        
        .validity-dates {
            font-size: 13px;
            color: #333;
            margin-top: 5px;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 40px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        
        .certificate-id {
            text-align: right;
            font-size: 10px;
            color: #999;
            margin-top: 20px;
        }
        
        .decoration-line {
            text-align: center;
            color: #206bc4;
            font-size: 24px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Header -->
        <div class="certificate-header">
            <div class="clinic-logo">🏥</div>
            <div class="clinic-name">CLINIC MANAGEMENT SYSTEM</div>
            <div class="clinic-address">Professional Medical Services Center</div>
        </div>
        
        <!-- Certificate Title -->
        <div class="decoration-line">✦</div>
        <div class="certificate-title">Medical Certificate</div>
        <div class="certificate-subtitle">
            This is to certify that the bearer of this document has been medically evaluated
        </div>
        
        <!-- Certificate Type Badge -->
        <div style="text-align: center;">
            <div class="certificate-type-badge">
                <?php 
                    $types = [
                        'fit_to_study' => 'Fit to Study',
                        'medical_leave' => 'Medical Leave',
                        'injury_report' => 'Injury Report',
                        'others' => 'Medical Certificate'
                    ];
                    echo $types[$cert['certificate_type']] ?? $cert['certificate_type'];
                ?>
            </div>
        </div>
        
        <!-- Patient Information -->
        <div class="content-section">
            <div class="content-row">
                <div class="content-row-half">
                    <div class="label">Patient Name</div>
                    <div class="value"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></div>
                </div>
                <div class="content-row-half">
                    <div class="label">Patient ID</div>
                    <div class="value">#<?= esc($patient['patient_id']) ?></div>
                </div>
            </div>
            
            <div class="content-row">
                <div class="content-row-half">
                    <div class="label">Date of Birth</div>
                    <div class="value"><?= date('d M Y', strtotime($patient['birth_date'])) ?></div>
                </div>
                <div class="content-row-half">
                    <div class="label">Contact Number</div>
                    <div class="value"><?= esc($patient['contact_no'] ?? 'N/A') ?></div>
                </div>
            </div>
        </div>
        
        <!-- Medical Information -->
        <?php if($cert['diagnosis_summary']): ?>
            <div class="description-section">
                <div class="description-label">Diagnosis Summary</div>
                <div class="description-text"><?= nl2br(esc($cert['diagnosis_summary'])) ?></div>
            </div>
        <?php endif; ?>
        
        <?php if($cert['recommendation']): ?>
            <div class="description-section">
                <div class="description-label">Medical Recommendation</div>
                <div class="description-text"><?= nl2br(esc($cert['recommendation'])) ?></div>
            </div>
        <?php endif; ?>
        
        <!-- Validity Period -->
        <div class="validity-badge">
            <div class="validity-label">Valid Period</div>
            <div class="validity-dates">
                From: <strong><?= date('d M Y', strtotime($cert['validity_start'])) ?></strong> 
                To: <strong><?= date('d M Y', strtotime($cert['validity_end'])) ?></strong>
            </div>
        </div>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name"><?= esc($issuer['username'] ?? 'Medical Professional') ?></div>
                <div class="signature-title">Issuing Medical Officer</div>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name"><?= date('d M Y') ?></div>
                <div class="signature-title">Date Issued</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            This certificate is valid only for the period specified above and is issued for medical purposes only.
        </div>
        
        <!-- Certificate ID -->
        <div class="certificate-id">
            Certificate #<?= esc($cert['certificate_id']) ?> | Generated: <?= date('d M Y H:i') ?>
        </div>
    </div>
</body>
</html>
