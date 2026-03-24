import Chart from 'chart.js/auto';

document.addEventListener('alpine:init', () => {
    Alpine.data('salesCharts', (hourlyData, monthlyData) => ({
        primaryColor: '#738D56',
        secondaryColor: 'rgba(115, 141, 86, 0.1)',

        init() {
            this.initTodayChart(hourlyData);
            this.initMonthlyChart(monthlyData);
        },

        initTodayChart(data) {
            const ctx = document.getElementById('todaySalesChart').getContext('2d');
            
            const labels = data.map(h => {
                const hour = h.hour;
                return hour === 0 ? '12 AM' : (hour > 12 ? (hour - 12) + ' PM' : (hour === 12 ? '12 PM' : hour + ' AM'));
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data.map(h => h.total),
                        borderColor: this.primaryColor,
                        backgroundColor: this.secondaryColor,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: this.primaryColor
                    }]
                },
                options: this.getChartOptions('Revenue')
            });
        },

        initMonthlyChart(data) {
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(m => m.month),
                    datasets: [{
                        data: data.map(m => m.total),
                        backgroundColor: this.primaryColor,
                        borderRadius: 8,
                        maxBarThickness: 30
                    }]
                },
                options: this.getChartOptions('Total')
            });
        },

        getChartOptions(labelPrefix) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${labelPrefix}: ₱${new Intl.NumberFormat('en-PH').format(context.raw)}`
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { display: false }, 
                        ticks: { font: { size: 10 }, callback: (v) => '₱' + v } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { size: 10 }, maxRotation: 0 } 
                    }
                }
            };
        }
    }));
});