
<html>
<head>
    <meta charset="utf-8">
    <title>Reinsurance Placement Slip</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 220px; margin-bottom: 10px; }
        .blue-bar {
            width: 100%;
            height: 35px;
            background: #2e3192;
            margin-bottom: 10px;
            border-radius: 0 40px 0 0;
        }
        h2 { text-align: center; text-decoration: underline; margin-bottom: 30px; }
        .section { margin-bottom: 18px; }
        .label { font-weight: bold; width: 170px; display: inline-block; vertical-align: top; }
        .value { display: inline-block; width: 75%; }
        .type-section { margin-bottom: 18px; }
        .type-section .label { width: 70px; }
        .type-section .value { width: auto; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/favion.png') }}" class="logo" alt="Zoom Insurance Logo">
        <div class="blue-bar"></div>
    </div>
    <h2>Reinsurance Placement Slip</h2>
    <div class="type-section">
        <span class="label">TYPE:</span>
        <span class="value">
            <strong>Section A:</strong> Terrorism and/or Sabotage Reinsurance.<br>
            <strong>Section B:</strong> Terrorism and Sabotage Liability Reinsurance.
        </span>
    </div>
    <div class="section">
        <span class="label">ORIGINAL INSURED:</span>
        <span class="value">{{ $quote->insured_name }}</span>
    </div>
    <div class="section">
        <span class="label">INSURED ADDRESS:</span>
        <span class="value">{{ $quote->insured_address }}</span>
    </div>
    <div class="section">
        <span class="label">REINSURED:</span>
        <span class="value">Reliance General Insurance Company Limited</span>
    </div>
    <div class="section">
        <span class="label">REINSURED ADDRESS:</span>
        <span class="value">6th Floor Oberoi Commerz, International Business Park, Oberoi Garden City off, Western Express Highway, Goregaon E Mumbai - 400063</span>
    </div>
    <!-- Add more fields as needed, using $quote->field_name -->
</body>
</html>
