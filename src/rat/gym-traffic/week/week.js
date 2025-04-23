const ctx = document.getElementById("week-chart");

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
          fill: true,
          cubicInterpolationMode: "monotone",
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
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      elements: {
        line: {
          tension: 0.5,
        },
      },
      scales: {
        y: {
          ticks: {
            stepSize: 1,
            min: 0,
            max: 10,
          },
        },
        x: {
          ticks: {
            maxTicksLimit: 9,
            callback: function (value, index, ticks) {
              const time = labels[index];

              return time > 12 ? `${time - 12} PM` : `${time} AM`;
            },
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
        },
      },
    },
  });
};

const data = $DATA.sort((a, b) => a.from - b.from);

const labels = data.map((item) => item.from);

const values = data.map((item) => (item.rats / $MAX_RATS) * 10);

loadChart(labels, values);
