<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite('resources/css/app.css') {{-- pastikan sudah pakai Vite di project Laravel 11 --}}
</head>
<body class="bg-emerald-500 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Admin Login</h2>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-emerald-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember Me</label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none">
                Login
            </button>
        </form>
    </div>

</body>
</html>
