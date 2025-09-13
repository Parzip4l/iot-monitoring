// dashboard-monitoring.init.js
const API_URL = "/api/monitoring/realtime";

// Chart global
let realtimeChart;

// Waktu filter saat ini
let currentFilter = "realtime";

document.addEventListener("DOMContentLoaded", function () {
    initRealtimeChart();
    fetchData(); 
    setInterval(fetchData, 10000); // refresh tiap 10 detik

    // Handle klik filter waktu
    document.querySelectorAll(".nav-pills .nav-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelectorAll(".nav-pills .nav-link").forEach(a => a.classList.remove("active"));
            this.classList.add("active");   
            currentFilter = this.textContent.trim().toLowerCase();
            fetchData();
        });
    });
});

function fetchData() {
    axios.get(API_URL, { params: { filter: currentFilter } })
        .then(response => {
            const data = response.data;
            updateSummary(data);
            updateRealtimeChart(data.sensors);
        })
        .catch(err => console.error("Error fetching data:", err));
}

// Update card summary
function updateSummary(data) {
    const tempEl = document.getElementById("temperature-value");
    const humEl = document.getElementById("humidity-value");
    const noiseEl = document.getElementById("noise-value");
    const alertEl = document.querySelector(".card h5.text-danger");

    if (tempEl) tempEl.innerText = data.temperature + "°C";
    if (humEl) humEl.innerText = data.humidity + "%";
    if (noiseEl) noiseEl.innerText = data.noise + " dB";
    if (alertEl) alertEl.innerText = data.alerts;
}

// Inisialisasi chart
function initRealtimeChart() {
    const options = {
        chart: { height: 300, type: "line", toolbar: { show: false } },
        colors: ["#f46a6a", "#34c38f", "#f1b44c"],
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: 2 },
        series: [
            { name: "Temperature (°C)", data: [] },
            { name: "Humidity (%)", data: [] },
            { name: "Noise (dB)", data: [] }
        ],
        xaxis: { categories: [], title: { text: "Time" } },
        legend: { position: "top" }
    };

    realtimeChart = new ApexCharts(document.querySelector("#realtime-sensor-chart"), options);
    realtimeChart.render();
}

function updateRealtimeChart(sensors) {
    if (!realtimeChart) return;
    const times = sensors.map(d => d.time);
    const temp = sensors.map(d => d.temperature);
    const hum = sensors.map(d => d.humidity);
    const noise = sensors.map(d => d.noise);

    realtimeChart.updateOptions({ xaxis: { categories: times } });
    realtimeChart.updateSeries([
        { name: "Temperature (°C)", data: temp },
        { name: "Humidity (%)", data: hum },
        { name: "Noise (dB)", data: noise }
    ]);
}
