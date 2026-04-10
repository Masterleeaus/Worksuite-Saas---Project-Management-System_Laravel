<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - FixitPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 2rem;
            position: relative;
            z-index: 10;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .bg-shapes div {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: rgba(59, 130, 246, 0.15);
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            width: 400px;
            height: 400px;
            background: rgba(139, 92, 246, 0.1);
            bottom: -150px;
            left: -150px;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>

    <div class="glass-card floating">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">FixitPro</h1>
            <p class="text-blue-400 text-sm font-medium">ADMIN PORTAL</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="input-field w-full px-4 py-3 rounded-xl block"
                        placeholder="admin@fixitpro.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="input-field w-full px-4 py-3 rounded-xl block"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="rounded border-gray-700 bg-gray-800 text-blue-600 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-400">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-4 rounded-xl text-white font-semibold">
                    Sign In to Dashboard
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} FixitPro. All rights reserved.
        </p>
    </div>
</body>
</html>
