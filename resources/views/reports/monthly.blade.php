@extends('layouts.app')

@section('title', 'Laporan Bulanan Sensor')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">📆 Laporan Bulanan Sensor</h1>

        <form method="GET" action="{{ route('reports.monthly') }}" class="flex items-center space-x-3">
            <input type="month" name="month" value="{{ $month }}" class="border rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-blue-300">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="bg-white shadow rounded-2xl p-4 mb-6">
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    <div class="bg-white shadow rounded-2xl p-4 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-600">
            <thead class="bg-blue-50 text-gray-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Device</th>
                    <th class="px-4 py-3 text-left">Temperature (°C)</th>
                    <th class="px-4 py-3 text-left">Humidity (%)</th>
                    <th class="px-4 py-3 text-left">Noise (dB)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($log->timestamp)->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $log->device_id }}</td>
                    <td class="px-4 py-3">{{ number_format($log->temperature, 1) }}</td>
                    <td class="px-4 py-3">{{ number_format($log->humidity, 1) }}</td>
                    <td class="px-4 py-3">{{ number_format($log->noise, 1) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data untuk bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($logs->pluck('timestamp')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))),
        datasets: [
            {
                label: 'Temperature (°C)',
                data: @json($logs->pluck('temperature')),
                backgroundColor: '#f87171'
            },
            {
                label: 'Humidity (%)',
                data: @json($logs->pluck('humidity')),
                backgroundColor: '#60a5fa'
            },
            {
                label: 'Noise (dB)',
                data: @json($logs->pluck('noise')),
                backgroundColor: '#34d399'
            },
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            x: { title: { display: true, text: 'Tanggal' } },
            y: { title: { display: true, text: 'Nilai Sensor' } }
        }
    }
});
</script>
@endsection
