import './bootstrap';

import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.start();

// Initialize charts after DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Ensure injected variables exist
    if (typeof drugStock !== 'undefined') {
        const pieOptions = {
            chart: { type: 'pie', height: 300 },
            series: [
                drugStock.inStock,
                drugStock.outOfStock,
                drugStock.expired,
                drugStock.reserved
            ],
            labels: ['In Stock', 'Out of Stock', 'Expired', 'Reserved'],
            colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1']
        };
        const pieChart = document.querySelector("#pieChart");
        if (pieChart) new ApexCharts(pieChart, pieOptions).render();
    }

    if (typeof patientsTrend !== 'undefined') {
        const lineOptions = {
            chart: { type: 'line', height: 300 },
            series: [{
                name: 'Patients',
                data: Object.values(patientsTrend)
            }],
            xaxis: {
                categories: Object.keys(patientsTrend)
            },
            colors: ['#3b82f6']
        };
        const lineChart = document.querySelector("#lineChart");
        if (lineChart) new ApexCharts(lineChart, lineOptions).render();
    }

    if (typeof prescriptionsData !== 'undefined') {
        const barOptions = {
            chart: { type: 'bar', height: 300 },
            series: [{
                name: 'Prescriptions',
                data: Object.values(prescriptionsData)
            }],
            xaxis: {
                categories: Object.keys(prescriptionsData)
            },
            colors: ['#8b5cf6']
        };
        const barChart = document.querySelector("#barChart");
        if (barChart) new ApexCharts(barChart, barOptions).render();
    }
});
