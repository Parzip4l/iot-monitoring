// dashboard-monitoring.init.js

// === CONFIG API ENDPOINT ===
const API_BASE_URL = "/api/monitoring/realtime";

// === INIT CHARTS & SLIDER ===
let realtimeChart, connectivityChart, trainSwiper;
let selectedTrainId = null; // Simpan ID kereta yang dipilih

document.addEventListener("DOMContentLoaded", function () {
    initRealtimeChart();
    // initConnectivityChart(); // Connectivity chart sepertinya tidak ada di HTML
    initTrainSlider();

    // Ambil data pertama kali untuk "Semua Kereta"
    fetchData();

    // Refresh tiap 10 detik
    setInterval(fetchData, 10000);
});

// === Inisialisasi Slider Kereta ===
function initTrainSlider() {
    trainSwiper = new Swiper('.train-slider', {
        // Optional parameters
        loop: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Listener saat slide berubah
    trainSwiper.on('slideChange', function () {
        const activeSlide = trainSwiper.slides[trainSwiper.activeIndex];
        selectedTrainId = activeSlide.getAttribute('data-train-id');
        
        // Tampilkan loading/spinner
        console.log(`Kereta diganti ke: ${selectedTrainId}. Mengambil data baru...`);

        // Langsung fetch data baru saat slider diganti
        fetchData();
    });

    // Inisialisasi ID kereta pertama
    if (trainSwiper.slides.length > 0) {
       const firstSlide = trainSwiper.slides[0];
       selectedTrainId = firstSlide.getAttribute('data-train-id');
       // Jika ingin defaultnya "Semua", set `selectedTrainId = null;`
    }
}


// === Fetch Data dari API ===
function fetchData() {
    let url = API_BASE_URL;
    // Tambahkan parameter jika ada kereta yang dipilih
    if (selectedTrainId) {
        url += `?train_id=${selectedTrainId}`;
    }

    axios.get(url)
        .then(response => {
            const data = response.data;
            updateSummary(data.summary);
            updateRealtimeChart(data.sensors);
            updateTrainDiagram(data.diagram); // Fungsi baru untuk update diagram
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

// === Update Ringkasan di Card ===
function updateSummary(summaryData) {
    const temperature = parseFloat(summaryData.temperature).toFixed(1);
    const humidity = parseFloat(summaryData.humidity).toFixed(1);
    const noise = parseFloat(summaryData.noise).toFixed(1);
    const alerts = summaryData.alerts || 0;

    // 🔹 Update KPI di sebelah chart
    document.getElementById("temperature-value").innerText = `${temperature}°C`;
    document.getElementById("humidity-value").innerText = `${humidity}%`;
    document.getElementById("noise-value").innerText = `${noise} dB`;

    // 🔹 Update KPI card di bawah
    document.getElementById("temperature-card-value").innerText = `${temperature}°C`;
    document.getElementById("humidity-card-value").innerText = `${humidity}%`;
    document.getElementById("noise-card-value").innerText = `${noise} dB`;
    document.getElementById("anomaly-card-value").innerText = alerts;
}

// === Update Diagram Kereta di Slider ===
function updateTrainDiagram(diagramData) {
    // Loop setiap slide pada swiper
    trainSwiper.slides.forEach(slide => {
        const trainId = slide.getAttribute('data-train-id');
        const trainData = diagramData.find(t => t.id == trainId);
        
        const container = slide.querySelector('.gerbong-indicators-container');
        if (!trainData || !container) return;

        // Kosongkan indikator lama
        container.innerHTML = '';

        // Buat indikator baru berdasarkan data API
        trainData.cars.forEach((car, index) => {
            let statusClass = 'bg-success'; // Default hijau
            let statusText = `Gerbong ${car.car_number}: ${car.online_devices} Online`;

            if (car.offline_devices > 0) {
                statusClass = 'bg-danger';
                statusText = `Gerbong ${car.car_number}: ${car.offline_devices} Offline`;
            } 
            // Tambahkan logika untuk anomali jika ada
            // else if (car.anomaly_devices > 0) {
            //     statusClass = 'bg-warning';
            //     statusText = `Gerbong ${car.car_number}: ${car.anomaly_devices} Anomali`;
            // }

            // Tentukan posisi (ini contoh, sesuaikan dengan gambar Anda)
            const leftPosition = 20 + (index * 50); // Misal: Gerbong 1 di 20%, Gerbong 2 di 70%

            const indicatorHTML = `
                <div class="iot-status position-absolute" style="top:5%; left:${leftPosition}%;">
                    <span class="badge ${statusClass}">${statusText}</span>
                    <div class="line-indicator"></div>
                </div>
            `;
            container.innerHTML += indicatorHTML;
        });
    });
}


// === Inisialisasi Realtime Sensor Chart ===
function initRealtimeChart() {
    let options = {
        chart: { height: 300, type: "line", stacked: false, toolbar: { show: false } },
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
        noData: { // Tampilan jika tidak ada data
            text: "Waiting for data..."
        },
        legend: { position: "top" }
    };

    realtimeChart = new ApexCharts(document.querySelector("#realtime-sensor-chart"), options);
    realtimeChart.render();
}

// === Update Realtime Sensor Chart ===
function updateRealtimeChart(sensorData) {
    if (!sensorData || sensorData.length === 0) {
        realtimeChart.updateSeries([
            { data: [] }, { data: [] }, { data: [] }
        ]);
        return;
    }

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