{{--
    TitanPWA :: offline
    ─────────────────────────────────────────────────────────────────────────
    Offline fallback page.
    Pre-cached by the service worker during install; returned for navigation
    requests that fail due to network unavailability.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ config('titanpwa.theme_color', '#1a5276') }}">
    <title>Offline — {{ config('titanpwa.name', 'CleanSmartOS') }}</title>

    <link rel="manifest" href="{{ route('titanpwa.manifest') }}">
    <link rel="icon" href="{{ asset('vendor/titanpwa/icons/icon-192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/titanpwa/css/pwa.css') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f4f8;
            color: #2d3748;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .titanpwa-offline-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 48px 32px;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }

        .titanpwa-offline-card img {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin-bottom: 24px;
        }

        .titanpwa-offline-card h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 12px;
            color: #1a5276;
        }

        .titanpwa-offline-card p {
            font-size: 15px;
            color: #718096;
            line-height: 1.6;
            margin: 0 0 28px;
        }

        .titanpwa-offline-card .pending-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef9e7;
            border: 1px solid #f39c12;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #b7770d;
            margin-bottom: 28px;
        }

        .titanpwa-retry-btn {
            display: inline-block;
            background: #1a5276;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .titanpwa-retry-btn:hover { background: #2471a3; }

        .titanpwa-offline-features {
            margin-top: 36px;
            border-top: 1px solid #e8ecf0;
            padding-top: 24px;
            text-align: left;
        }

        .titanpwa-offline-features h2 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #a0aec0;
            margin: 0 0 12px;
        }

        .titanpwa-offline-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .titanpwa-offline-features li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4a5568;
            padding: 6px 0;
        }

        .titanpwa-offline-features li::before {
            content: '✓';
            color: #27ae60;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="titanpwa-offline-card">
        <img src="{{ asset('vendor/titanpwa/icons/icon-192x192.png') }}"
             alt="{{ config('titanpwa.short_name', 'CleanSmartOS') }} icon">

        <h1>You're offline</h1>

        <p>
            No internet connection detected. You can still access your
            cached job cards and check off items — everything will sync
            automatically when you reconnect.
        </p>

        <div class="pending-badge" id="titanpwa-offline-queue-info" style="display:none">
            ⏳ <span id="titanpwa-queue-depth">0</span> action(s) pending sync
        </div>

        <button class="titanpwa-retry-btn"
                onclick="window.location.reload()"
                type="button">
            Try again
        </button>

        <div class="titanpwa-offline-features">
            <h2>Available offline</h2>
            <ul>
                <li>View today's job cards &amp; checklists</li>
                <li>Mark checklist items complete</li>
                <li>Record notes and observations</li>
                <li>Actions sync automatically on reconnect</li>
            </ul>
        </div>
    </div>

    <script>
        // Show queue depth if there are pending items
        (function () {
            if (!window.indexedDB) return;
            var req = indexedDB.open('titanpwa-db', 1);
            req.onsuccess = function (e) {
                var db = e.target.result;
                if (!db.objectStoreNames.contains('sync-queue')) return;
                var tx  = db.transaction('sync-queue', 'readonly');
                var cnt = tx.objectStore('sync-queue').count();
                cnt.onsuccess = function () {
                    if (cnt.result > 0) {
                        document.getElementById('titanpwa-queue-depth').textContent = cnt.result;
                        document.getElementById('titanpwa-offline-queue-info').style.display = 'inline-flex';
                    }
                };
            };
        }());
    </script>
</body>
</html>
