// progress_analytics.js - Chart configuration and initialization
document.addEventListener('DOMContentLoaded', function () {
    // Configure chart.js global options
    Chart.defaults.color = '#a1a1aa';
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    
    // Initialize trainer assessment progress chart
    initProgressChart();
    
    // Initialize BMI chart
    initBMIChart();

    // Add animation to the success rate percentage display in summary card
    animateSuccessRate();
    
    // Set up expandable cards
    initExpandableCards();
});

function initExpandableCards() {
    const expandButtons = document.querySelectorAll('.expand-btn');
    
    expandButtons.forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.expandable-card');
            card.classList.toggle('expanded');
            
            // Change button icon based on expanded state
            const isExpanded = card.classList.contains('expanded');
            
            if (isExpanded) {
                button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minimize"><path d="M8 3v3a2 2 0 0 1-2 2H3"/><path d="M21 8h-3a2 2 0 0 1-2-2V3"/><path d="M3 16h3a2 2 0 0 1 2 2v3"/><path d="M16 21v-3a2 2 0 0 1 2-2h3"/></svg>`;
                
                // Update chart size when expanded
                const chartCanvas = card.querySelector('canvas');
                if (chartCanvas && chartCanvas.chart) {
                    setTimeout(() => {
                        chartCanvas.chart.resize();
                    }, 300);
                }
            } else {
                button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-expand"><path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8"/><path d="M3 16.2V21m0 0h4.8M3 21l6-6"/><path d="M21 7.8V3m0 0h-4.8M21 3l-6 6"/><path d="M3 7.8V3m0 0h4.8M3 3l6 6"/></svg>`;
                
                // Update chart size when collapsed
                const chartCanvas = card.querySelector('canvas');
                if (chartCanvas && chartCanvas.chart) {
                    setTimeout(() => {
                        chartCanvas.chart.resize();
                    }, 300);
                }
            }
        });
    });
}

function initProgressChart() {
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
}

function initBMIChart() {
    // Check if bmi chart element exists
    const bmiChartCanvas = document.getElementById('bmiChart');
    if (!bmiChartCanvas) return;

    // Check if bmi chart data exists (was passed from PHP)
    if (typeof bmiChartData === 'undefined' || !bmiChartData.values.length) return;

    const bmiCtx = bmiChartCanvas.getContext('2d');
    
    // Create gradient for BMI chart
    const bmiGradient = bmiCtx.createLinearGradient(0, 0, 0, 250);
    bmiGradient.addColorStop(0, 'rgba(121, 103, 238, 0.2)');
    bmiGradient.addColorStop(1, 'rgba(121, 103, 238, 0)');
    
    // Format dates for display
    const formattedDates = bmiChartData.labels.map(date => {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    });
    
    const bmiChart = new Chart(bmiChartCanvas, {
        type: 'line',
        data: {
            labels: bmiChartData.labels,
            datasets: [
                {
                    label: 'BMI',
                    data: bmiChartData.values,
                    borderColor: '#7967EE',
                    backgroundColor: bmiGradient,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: function(context) {
                        const value = context.raw;
                        if (value < 18.5) return '#ffcc00'; // Underweight
                        if (value < 25) return '#42b34d';   // Normal
                        if (value < 30) return '#ff9900';   // Overweight
                        return '#ff3300';                   // Obese
                    },
                    pointBorderColor: '#ffffff',
                    pointRadius: function(context) {
                        // Only show points at start, end, and major changes
                        const dataIndex = context.dataIndex;
                        const dataLength = context.dataset.data.length;
                        
                        // Always show first and last point
                        if (dataIndex === 0 || dataIndex === dataLength - 1) {
                            return 5;
                        }
                        
                        // For datasets with more than 5 points, show fewer points
                        if (dataLength > 5) {
                            // Show approximately every Nth point
                            const interval = Math.max(Math.floor(dataLength / 5), 2);
                            return dataIndex % interval === 0 ? 5 : 0;
                        }
                        
                        return 5;
                    },
                    pointHoverRadius: 7,
                    yAxisID: 'y',
                    order: 0
                },
                {
                    label: 'Weight (kg)',
                    data: bmiChartData.weights,
                    borderColor: '#64748b',
                    backgroundColor: 'rgba(100, 116, 139, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.3,
                    pointBackgroundColor: '#64748b',
                    pointBorderColor: '#ffffff',
                    pointRadius: function(context) {
                        // Match the same pattern as the BMI data points
                        const dataIndex = context.dataIndex;
                        const dataLength = context.dataset.data.length;
                        
                        // Always show first and last point
                        if (dataIndex === 0 || dataIndex === dataLength - 1) {
                            return 4;
                        }
                        
                        // For datasets with more than 5 points, show fewer points
                        if (dataLength > 5) {
                            // Show approximately every Nth point
                            const interval = Math.max(Math.floor(dataLength / 5), 2);
                            return dataIndex % interval === 0 ? 4 : 0;
                        }
                        
                        return 4;
                    },
                    pointHoverRadius: 6,
                    yAxisID: 'y1',
                    order: 1
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
                    position: 'nearest',
                    callbacks: {
                        title: function(context) {
                            // Format the date for the tooltip title
                            return new Date(context[0].label).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                        },
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            
                            if (context.dataset.label === 'BMI') {
                                const bmi = context.parsed.y;
                                let category = '';
                                
                                if (bmi < 18.5) category = '(Underweight)';
                                else if (bmi < 25) category = '(Normal)';
                                else if (bmi < 30) category = '(Overweight)';
                                else category = '(Obese)';
                                
                                // Add visible/hidden marker for data points that aren't shown
                                const index = context.dataIndex;
                                const shouldShow = index === 0 || 
                                                 index === context.dataset.data.length - 1 || 
                                                 (context.dataset.data.length > 5 && 
                                                  index % Math.max(Math.floor(context.dataset.data.length / 5), 2) === 0);
                                
                                label += bmi.toFixed(1) + ' ' + category;
                                if (!shouldShow) {
                                    label += ' ○'; // indicate hidden point
                                }
                            } else {
                                label += context.parsed.y + ' kg';
                            }
                            
                            return label;
                        }
                    }
                },
                annotation: {
                    annotations: {
                        underweightLine: {
                            type: 'line',
                            yMin: 18.5,
                            yMax: 18.5,
                            borderColor: '#ffcc00',
                            borderWidth: 1,
                            borderDash: [3, 3],
                            label: {
                                display: true,
                                content: 'Underweight',
                                position: 'start',
                                backgroundColor: 'rgba(255, 204, 0, 0.7)',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        normalLine: {
                            type: 'line',
                            yMin: 25,
                            yMax: 25,
                            borderColor: '#42b34d',
                            borderWidth: 1,
                            borderDash: [3, 3],
                            label: {
                                display: true,
                                content: 'Normal',
                                position: 'start',
                                backgroundColor: 'rgba(66, 179, 77, 0.7)',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        overweightLine: {
                            type: 'line',
                            yMin: 30,
                            yMax: 30,
                            borderColor: '#ff3300',
                            borderWidth: 1,
                            borderDash: [3, 3],
                            label: {
                                display: true,
                                content: 'Overweight',
                                position: 'start',
                                backgroundColor: 'rgba(255, 51, 0, 0.7)',
                                font: {
                                    size: 10
                                }
                            }
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
                        callback: function(value, index) {
                            const date = new Date(this.getLabelForValue(value));
                            return date.toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    }
                },
                y: {
                    beginAtZero: false,
                    suggestedMin: Math.max(10, Math.min(...bmiChartData.values) - 5),
                    suggestedMax: Math.min(40, Math.max(...bmiChartData.values) + 5),
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    },
                    ticks: {
                        padding: 10,
                        precision: 1
                    },
                    title: {
                        display: true,
                        text: 'BMI Value',
                        color: '#a1a1aa',
                        font: {
                            size: 12
                        }
                    },
                    position: 'left'
                },
                y1: {
                    beginAtZero: false,
                    suggestedMin: Math.max(30, Math.min(...bmiChartData.weights) - 10),
                    suggestedMax: Math.min(150, Math.max(...bmiChartData.weights) + 10),
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        padding: 10,
                        callback: function(value) {
                            return value + ' kg';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Weight (kg)',
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
                point: {
                    hitRadius: 8 // Increase hit area for points to make them easier to hover over
                }
            }
        }
    });
    
    // Store chart instance on canvas element for resize access
    bmiChartCanvas.chart = bmiChart;
}

function animateSuccessRate() {
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
}