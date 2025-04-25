// progress_analytics.js - Chart configuration and initialization
document.addEventListener('DOMContentLoaded', function () {
    // Check if chart element exists
    const chartCanvas = document.getElementById('progressChart');
    if (!chartCanvas) return;

    // Check if chart data exists (was passed from PHP)
    if (typeof progressChartData === 'undefined') return;

    // Create the progress chart
    const ctx = chartCanvas.getContext('2d');

    // Set up gradient for the "Well Done" area
    const wellDoneGradient = ctx.createLinearGradient(0, 0, 0, 250);
    wellDoneGradient.addColorStop(0, 'rgba(66, 179, 77, 0.2)');
    wellDoneGradient.addColorStop(1, 'rgba(66, 179, 77, 0)');

    // Set up gradient for the "Try Harder" area
    const tryHarderGradient = ctx.createLinearGradient(0, 0, 0, 250);
    tryHarderGradient.addColorStop(0, 'rgba(255, 204, 0, 0.2)');
    tryHarderGradient.addColorStop(1, 'rgba(255, 204, 0, 0)');

    // Configure chart.js global options
    Chart.defaults.color = '#a1a1aa';
    Chart.defaults.font.family = "'DM Sans', sans-serif";

    // Create the chart
    const progressChart = new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: progressChartData.labels,
            datasets: [
                {
                    label: 'Success Rate (%)',
                    data: progressChartData.ratio,
                    borderColor: '#7967EE',
                    backgroundColor: 'rgba(121, 103, 238, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#7967EE',
                    pointBorderColor: '#7967EE',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1',
                    type: 'line',
                    order: 0
                },
                {
                    label: 'Well Done',
                    data: progressChartData.well_done,
                    borderColor: '#42b34d',
                    backgroundColor: wellDoneGradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#42b34d',
                    pointBorderColor: '#42b34d',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    yAxisID: 'y',
                    order: 1
                },
                {
                    label: 'Try Harder',
                    data: progressChartData.try_harder,
                    borderColor: '#ffcc00',
                    backgroundColor: tryHarderGradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#ffcc00',
                    pointBorderColor: '#ffcc00',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    yAxisID: 'y',
                    order: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'start',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#27272A',
                    padding: 12,
                    titleColor: '#ffffff',
                    bodyColor: '#d4d4d8',
                    borderColor: '#3f3f46',
                    borderWidth: 1,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    displayColors: true,
                    callbacks: {
                        title: function (context) {
                            return context[0].label;
                        },
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.dataset.label === 'Success Rate (%)') {
                                label += context.parsed.y + '%';
                            } else {
                                label += context.parsed.y;
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    },
                    ticks: {
                        padding: 10,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    },
                    ticks: {
                        padding: 10,
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Number of Assessments',
                        color: '#a1a1aa',
                        font: {
                            size: 12
                        }
                    },
                    position: 'left'
                },
                y1: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        padding: 10,
                        callback: function (value) {
                            return value + '%';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Success Rate',
                        color: '#a1a1aa',
                        font: {
                            size: 12
                        }
                    },
                    position: 'right'
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            elements: {
                line: {
                    borderWidth: 2
                },
                point: {
                    radius: 0,
                    hoverRadius: 6
                }
            }
        }
    });

    // Add animation to the success rate percentage display in summary card
    const successRateElement = document.querySelector('.summary-value[class*="color"]');
    if (successRateElement) {
        const targetValue = parseInt(successRateElement.textContent);
        let currentValue = 0;
        const duration = 1500; // milliseconds
        const frameRate = 60;
        const totalFrames = duration / (1000 / frameRate);
        const increment = targetValue / totalFrames;

        successRateElement.textContent = '0%';

        let frame = 0;
        const counter = setInterval(() => {
            frame++;
            currentValue += increment;

            if (currentValue >= targetValue) {
                clearInterval(counter);
                currentValue = targetValue;
            }

            successRateElement.textContent = Math.floor(currentValue) + '%';

            if (frame >= totalFrames) {
                clearInterval(counter);
            }
        }, 1000 / frameRate);
    }
});