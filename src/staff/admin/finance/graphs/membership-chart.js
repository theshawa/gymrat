const ctx = document.getElementById("membership-chart");


if (ctx) {
    const labels = Object.keys($GROUPED_SALES).map(
        (id) => $MEMBERSHIP_TITLES[id] || `Plan ${id}`
    );
    const data = Object.values($GROUPED_SALES);

    ctx.width = 2; 
    ctx.height = 1;

    new Chart(ctx, {
        type: "bar",
        data: {
            labels,
            datasets: [
                {
                    label: "Number of Purchases",
                    data,
                    backgroundColor: "rgba(103, 0, 230, 0.5)",
                    borderColor: "rgba(103, 0, 230, 1)",
                    borderWidth: 2,
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
                        label: (context) =>
                            `${context.label}: ${context.raw} purchases`,
                    },
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Membership Plans",
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
