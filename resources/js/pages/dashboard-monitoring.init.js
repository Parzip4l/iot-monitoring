// dashboard-monitoring.init.js

// === CONFIG API ENDPOINT ===
// Sesuaikan dengan endpoint Laravel kamu
const API_URL = "/api/monitoring/realtime";

// === INIT CHARTS ===
let realtimeChart, connectivityChart;

document.addEventListener("DOMContentLoaded", function () {
    initRealtimeChart();
    initConnectivityChart();

    // Ambil data pertama kali (Dummy)
    // fetchDummyData();

    // // Refresh tiap 10 detik
    // setInterval(fetchDummyData, 10000);

    // === KONEK API (NANTI BACKEND READY, UNCOMMENT BAGIAN INI) ===
    
    fetchData(); 
    setInterval(fetchData, 10000);
    
});

// === Fetch Data dari API (REAL) ===
function fetchData() {
    axios.get(API_URL)
        .then(response => {
            const data = response.data;
            updateSummary(data);
            updateRealtimeChart(data.sensors);
            updateConnectivityChart(data.connectivity);
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

// === Fetch Data Dummy (Sementara) ===
// function fetchDummyData() {
//     const data = {
//         temperature: getRandomInt(24, 36),
//         humidity: getRandomInt(50, 90),
//         noise: getRandomInt(60, 100),
//         devices: getRandomInt(10, 20),
//         alerts: getRandomInt(0, 5),
//         connectivity: getRandomInt(70, 99),
//         sensors: [
//             { time: "12:00", temperature: 28, humidity: 65, noise: 78 },
//             { time: "12:10", temperature: 29, humidity: 67, noise: 80 },
//             { time: "12:20", temperature: 30, humidity: 64, noise: 76 },
//             { time: "12:30", temperature: 32, humidity: 68, noise: 82 },
//             { time: "12:40", temperature: 31, humidity: 70, noise: 85 }
//         ]
//     };

//     updateSummary(data);
//     updateRealtimeChart(data.sensors);
//     updateConnectivityChart(data.connectivity);
// }

// === Helper Dummy Randomizer ===
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}

// === Update Ringkasan di Card ===
function updateSummary(data) {
    document.getElementById("temperature-value").innerText = data.temperature + "°C";
    document.getElementById("humidity-value").innerText = data.humidity + "%";
    document.getElementById("noise-value").innerText = data.noise + " dB";

    // kalau ada card total device & alerts → juga kasih id
    let deviceCard = document.getElementById("device-count");
    if (deviceCard) deviceCard.innerText = data.devices;

    let alertCard = document.getElementById("alert-count");
    if (alertCard) alertCard.innerText = data.alerts;
}

// === Inisialisasi Realtime Sensor Chart ===
function initRealtimeChart() {
    let options = {
        chart: {
            height: 300,
            type: "line",
            stacked: false,
            toolbar: { show: false }
        },
        colors: ["#f46a6a", "#34c38f", "#f1b44c"],
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: 2 },
        series: [
            { name: "Temperature (°C)", data: [] },
            { name: "Humidity (%)", data: [] },
            { name: "Noise (dB)", data: [] }
        ],
        xaxis: {
            categories: [],
            title: { text: "Time" }
        },
        legend: { position: "top" }
    };

    realtimeChart = new ApexCharts(
        document.querySelector("#realtime-sensor-chart"),
        options
    );
    realtimeChart.render();
}

// === Update Realtime Sensor Chart ===
function updateRealtimeChart(sensorData) {
    const times = sensorData.map(d => d.time);
    const temp = sensorData.map(d => d.temperature);
    const hum = sensorData.map(d => d.humidity);
    const noise = sensorData.map(d => d.noise);

    realtimeChart.updateOptions({
        xaxis: { categories: times }
    });

    realtimeChart.updateSeries([
        { name: "Temperature (°C)", data: temp },
        { name: "Humidity (%)", data: hum },
        { name: "Noise (dB)", data: noise }
    ]);
}

// === Inisialisasi Connectivity Chart ===
function initConnectivityChart() {
    let options = {
        chart: { height: 200, type: "radialBar" },
        plotOptions: {
            radialBar: {
                hollow: { size: "60%" },
                dataLabels: {
                    name: { show: false },
                    value: {
                        fontSize: "20px",
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        colors: ["#556ee6"],
        series: [0],
    };

    connectivityChart = new ApexCharts(
        document.querySelector("#connectivity-chart"),
        options
    );
    connectivityChart.render();
}

// === Update Connectivity Chart ===
function updateConnectivityChart(connectivity) {
    connectivityChart.updateSeries([connectivity]);
}
