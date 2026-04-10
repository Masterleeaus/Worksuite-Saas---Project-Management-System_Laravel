<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - FixitPro</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Simplified style block to avoid @apply issues with CDN */
        .nav-btn-base {
            display: flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }

        .nav-active {
            color: #ffffff;
            background: rgba(59, 130, 246, 0.2);
            border-bottom: 2px solid #60a5fa;
        }

        .nav-inactive {
            color: rgba(255, 255, 255, 0.85);
        }

        .nav-inactive:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        @keyframes slideIn {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }

        .glass-effect {
            background: linear-gradient(135deg, #0A2647 0%, #06162d 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-glow {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
        }

        .dropdown-glow {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 0 0 20px rgba(59, 130, 246, 0.05);
        }

        /* Google Maps Autocomplete Dropdown fix */
        .pac-container {
            z-index: 99999 !important;
            border-radius: 1.25rem;
            margin-top: 5px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
            border: 1px solid #e5e7eb !important;
            font-family: 'Outfit', sans-serif !important;
        }

        .pac-item {
            padding: 12px 16px;
            cursor: pointer;
            border-top: 1px solid #f9fafb;
            display: flex;
            align-items: center;
        }

        .pac-item:hover {
            background-color: #f3f4f6;
        }

        .pac-item-query {
            font-size: 14px;
            color: #111827;
            font-weight: 700;
        }

        .pac-matched {
            color: #3b82f6;
        }

        /* Premium SweetAlert Success Styling */
        .premium-swal-success {
            border-radius: 2.5rem !important;
            padding: 3rem !important;
            box-shadow: 0 25px 50px -12px rgba(5, 150, 105, 0.25) !important;
        }

        .premium-swal-success-icon {
            border-color: #065f46 !important;
            /* Dark Green Circle */
            background-color: #065f46 !important;
        }

        .premium-swal-success-icon [class^='swal2-success-line'] {
            background-color: #ffffff !important;
            /* White Tick */
        }

        .premium-swal-success-icon .swal2-success-ring {
            border: 4px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1) !important;
        }

        .premium-swal-title {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 900 !important;
            color: #065f46 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 1.5rem !important;
            margin-top: 1rem !important;
        }

        .premium-swal-popup {
            border: 1px solid #e5e7eb !important;
        }

        /* Select2 Premium Styling */
        .select2-container--default .select2-selection--single {
            background-color: #F9FAFB !important;
            border: 1px solid #E5E7EB !important;
            border-radius: 1rem !important;
            height: 52px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 40px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
            background-color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 15px !important;
        }

        .select2-dropdown {
            border-radius: 1.25rem !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important;
            overflow: hidden !important;
            margin-top: 5px !important;
        }

        .select2-results__option {
            padding: 12px 20px !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            color: #4B5563 !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #3B82F6 !important;
        }

        /* DataTables Ultra-Premium Overhaul */
        .premium-table-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            margin: 0 !important;
            padding: 24px 32px !important;
            width: auto !important;
            position: relative !important;
        }

        .dataTables_wrapper .dataTables_filter label {
            position: relative !important;
            display: block !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1.5px solid #f1f5f9 !important;
            border-radius: 1.25rem !important;
            padding: 12px 16px 12px 48px !important;
            background-color: #f8fafc !important;
            font-weight: 600 !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 0.875rem !important;
            width: 360px !important;
            color: #1e293b !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            background-color: #ffffff !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1) !important;
            width: 400px !important;
        }

        .dataTables_wrapper .dataTables_filter label::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
            z-index: 10;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_length {
            float: right !important;
            margin: 0 !important;
            padding: 24px 32px !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            color: #64748b !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1.5px solid #f1f5f9 !important;
            border-radius: 0.875rem !important;
            padding: 8px 12px !important;
            background-color: #f8fafc !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            cursor: pointer;
            transition: all 0.2s ease !important;
        }

        .dataTables_wrapper .dataTables_length select:hover {
            border-color: #cbd5e1 !important;
        }

        table.dataTable thead th {
            background: #f8fafc;
            border-bottom: 1.5px solid #f1f5f9 !important;
            padding: 20px 32px !important;
            color: #64748b !important;
            font-weight: 800 !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
        }

        table.dataTable tbody tr {
            transition: all 0.2s ease !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #fcfdfe !important;
            box-shadow: inset 4px 0 0 #3b82f6 !important;
        }

        table.dataTable tbody td {
            padding: 24px 32px !important;
            border-bottom: 1px solid #f8fafc !important;
            vertical-align: middle !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 24px 32px !important;
            color: #64748b !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 24px 32px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: none !important;
            background: #f8fafc !important;
            border-radius: 0.875rem !important;
            padding: 10px 18px !important;
            font-weight: 800 !important;
            font-size: 0.85rem !important;
            color: #64748b !important;
            margin: 0 4px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3) !important;
            transform: translateY(-2px);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
            color: #1e293b !important;
            transform: translateY(-1px);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
        }
    </style>
    @if (config('services.google_maps.key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places">
        </script>
    @endif
</head>
