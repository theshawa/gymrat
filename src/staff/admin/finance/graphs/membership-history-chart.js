const ctx = document.getElementById("membership-history-chart");

if (ctx) {
  const labels = Object.keys($GROUPED_SALES_BY_MONTH);
  const data = Object.values($GROUPED_SALES_BY_MONTH);

  new Chart(ctx, {
    type: "line",
    data: {
      labels,
      datasets: [
        {
          label: "Membership Plan Purchases",
          data,
          borderColor: "rgba(68, 0, 153, 1)",
          backgroundColor: "rgba(68, 0, 153, 0.2)",
          borderWidth: 2,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: "bottom",
        },
        tooltip: {
          callbacks: {
            label: (context) => `${context.label}: ${context.raw} purchases`,
          },
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Time (Year-Month)",
          },
        },
        y: {
          title: {
            display: true,
            text: "Number of Purchases",
          },
          beginAtZero: true,
        },
      },
    },
  });
}
