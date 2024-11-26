const ctx = document.getElementById("progress-chart");

const Config = {
  colors: {
    primary: "#6700e6",
    text: "#a1a1aa",
    textLight: "#f4f4f5",
    border: "#18181b",
    background: "#09090b",
  },
  dotSize: 8,
  fontSize: 12,
};

Chart.defaults.backgroundColor = Config.colors.background;
Chart.defaults.borderColor = Config.colors.border;
Chart.defaults.color = Config.colors.text;
Chart.defaults.font.size = Config.fontSize;
Chart.defaults.font.family = `"DM Sans", sans-serif`;

const loadChart = (labels, data) => {
  new Chart(ctx, {
    type: "line",
    lineAtIndex: [24],
    data: {
      labels,
      datasets: [
        {
          label: "BMI",
          fill: true,
          borderColor: Config.colors.primary,
          data,
          backgroundColor: () => {
            const c = ctx.getContext("2d");
            var gradientFill = c.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgb(103, 0, 230)");
            gradientFill.addColorStop(1, "rgba(103, 0, 230, 0.1)");
            return gradientFill;
          },
          pointBackgroundColor: Config.colors.background,
          pointBorderWidth: Config.dotSize,
          pointHoverRadius: Config.dotSize,
        },
      ],
    },
    options: {
      scales: {
        x: {
          ticks: {
            display: false,
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: Config.colors.border,
          titleColor: Config.colors.textLight,
          bodyColor: Config.colors.text,
          displayColors: false,
          padding: 10,
          titleAlign: "center",
          bodyAlign: "center",
          titleMarginBottom: 2,
          titleFont: {
            size: 14,
            weight: 500,
          },
          bodyFont: {
            size: Config.fontSize,
          },
          callbacks: {
            label: function (context) {
              return Intl.DateTimeFormat("en-US", {
                dateStyle: "medium",
                timeStyle: "short",
              }).format(new Date(context.label));
            },
            title: function (context) {
              return "BMI: " + parseFloat(context[0].raw).toFixed(2);
            },
          },
        },
        annotation: {
          annotations: {
            line1: {
              type: "line",
              yMin: 18.5,
              yMax: 18.5,
              borderColor: "#42b34d",
              borderWidth: 2,
            },
            line2: {
              type: "line",
              yMin: 24.9,
              yMax: 24.9,
              borderColor: "#42b34d",
              borderWidth: 2,
            },
          },
        },
      },
    },
  });
};

loadChart($LABELS, $VALUES);

document.querySelector(".filter select").addEventListener("change", (e) => {
  e.currentTarget.parentElement.submit();
});

document.querySelectorAll(".list .item form").forEach((item) => {
  item.addEventListener("submit", (e) => {
    e.stopPropagation();
    if (confirm("Are you sure want to delete this record?")) e.target.submit();
  });
});
