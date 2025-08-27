@extends('layouts.dashboard')

@section('content')
    <div class="px-4 pt-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-gray-600 dark:text-gray-400">Ringkasan aktivitas dan statistik sistem.</p>
        </div>

        <!-- Statistik Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <div class="min-w-0 rounded-lg shadow-xs overflow-hidden bg-white dark:bg-gray-800 p-4">
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Users
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    1,257
                </p>
            </div>

            <div class="min-w-0 rounded-lg shadow-xs overflow-hidden bg-white dark:bg-gray-800 p-4">
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Orders
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    4,320
                </p>
            </div>

            <div class="min-w-0 rounded-lg shadow-xs overflow-hidden bg-white dark:bg-gray-800 p-4">
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Revenue
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Rp 75,200,000
                </p>
            </div>

            <div class="min-w-0 rounded-lg shadow-xs overflow-hidden bg-white dark:bg-gray-800 p-4">
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Pending
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    89
                </p>
            </div>
        </div>

        <!-- Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Sales Overview</h2>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Users Growth</h2>
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Latest Orders</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Customer</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">#1023</td>
                            <td class="px-4 py-3">John Doe</td>
                            <td class="px-4 py-3"><span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">Completed</span></td>
                            <td class="px-4 py-3">Rp 1,250,000</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">#1024</td>
                            <td class="px-4 py-3">Jane Smith</td>
                            <td class="px-4 py-3"><span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded">Pending</span></td>
                            <td class="px-4 py-3">Rp 980,000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">#1025</td>
                            <td class="px-4 py-3">Michael Lee</td>
                            <td class="px-4 py-3"><span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded">Cancelled</span></td>
                            <td class="px-4 py-3">Rp 2,100,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1900, 3000, 2500, 3200, 4000],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                tension: 0.4,
                fill: true
            }]
        }
    });

    // Users Chart
    new Chart(document.getElementById('usersChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'New Users',
                data: [50, 70, 100, 120, 150, 200],
                backgroundColor: 'rgb(59, 130, 246)'
            }]
        }
    });
</script>
@endpush
