<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Expired</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="card" style="max-width:500px;margin:80px auto;">
        <div class="card-body text-center p-5">
            <div class="display-4 mb-3">⏰</div>
            <h3>Survey link expired</h3>
            <p class="text-muted">This survey link is no longer valid. Survey links expire after {{ config('customer-feedback.survey_expiry_days', 7) }} days.</p>
            <p class="text-muted">If you still wish to share feedback, please contact us directly.</p>
        </div>
    </div>
</div>
</body>
</html>
