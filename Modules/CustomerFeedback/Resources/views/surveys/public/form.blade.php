<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Feedback Survey</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .survey-card { max-width: 600px; margin: 40px auto; }
        .nps-btn { width: 48px; height: 48px; border-radius: 50%; font-weight: bold; }
        .star-group label { cursor: pointer; font-size: 1.5rem; color: #ccc; }
        .star-group input:checked ~ label,
        .star-group label:hover,
        .star-group label:hover ~ label { color: #f5a623; }
    </style>
</head>
<body>
<div class="container">
    <div class="card survey-card shadow-sm">
        <div class="card-body p-4">
            <h3 class="mb-1">How did we do?</h3>
            <p class="text-muted mb-4">Your feedback helps us improve. This should take less than a minute.</p>

            <form method="POST" action="{{ route('survey.submit', $survey->survey_token) }}">
                @csrf

                {{-- NPS Score 0-10 --}}
                <div class="form-group">
                    <label class="font-weight-bold">How likely are you to recommend us to a friend or colleague? <span class="text-danger">*</span></label>
                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        @for ($i = 0; $i <= 10; $i++)
                            <div class="text-center">
                                <input type="radio" name="nps_score" id="nps_{{ $i }}" value="{{ $i }}" class="d-none" required>
                                <label for="nps_{{ $i }}" class="btn btn-outline-secondary nps-btn">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Not likely</small>
                        <small class="text-muted">Extremely likely</small>
                    </div>
                    @error('nps_score')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Service Quality --}}
                <div class="form-group">
                    <label class="font-weight-bold">Service quality</label>
                    <div class="d-flex star-group flex-row-reverse justify-content-end">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="service_rating" id="sr_{{ $i }}" value="{{ $i }}" class="d-none">
                            <label for="sr_{{ $i }}" title="{{ $i }} star">&#9733;</label>
                        @endfor
                    </div>
                </div>

                {{-- Cleaner Rating --}}
                <div class="form-group">
                    <label class="font-weight-bold">Cleaner / technician rating</label>
                    <div class="d-flex star-group flex-row-reverse justify-content-end">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="cleaner_rating" id="cr_{{ $i }}" value="{{ $i }}" class="d-none">
                            <label for="cr_{{ $i }}" title="{{ $i }} star">&#9733;</label>
                        @endfor
                    </div>
                </div>

                {{-- Punctuality --}}
                <div class="form-group">
                    <label class="font-weight-bold">Punctuality</label>
                    <div class="d-flex star-group flex-row-reverse justify-content-end">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="punctuality_rating" id="pr_{{ $i }}" value="{{ $i }}" class="d-none">
                            <label for="pr_{{ $i }}" title="{{ $i }} star">&#9733;</label>
                        @endfor
                    </div>
                </div>

                {{-- Comments --}}
                <div class="form-group">
                    <label class="font-weight-bold">Any additional comments?</label>
                    <textarea name="comments" class="form-control" rows="3" maxlength="2000" placeholder="Tell us more...">{{ old('comments') }}</textarea>
                    @error('comments')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">Submit Feedback</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Highlight selected NPS button
    document.querySelectorAll('input[name="nps_score"]').forEach(function(el) {
        el.addEventListener('change', function() {
            document.querySelectorAll('.nps-btn').forEach(function(btn) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
            });
            this.nextElementSibling.classList.remove('btn-outline-secondary');
            this.nextElementSibling.classList.add('btn-primary');
        });
    });
</script>
</body>
</html>
