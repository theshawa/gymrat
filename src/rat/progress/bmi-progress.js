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
  if (!ctx) return;
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
          pointBackgroundColor: function (context) {
            const value = context.parsed.y; // y-value for line chart
            if (value < 18.5) return "#ffcc00"; // yellow for underweight
            if (value < 25) return "#42b34d"; // green for normal
            if (value < 30) return "#ff9900"; // orange for overweight
            return "#ff3300"; // red for obese
          },
          pointBorderWidth: Config.dotSize,
          pointHoverRadius: Config.dotSize,
        },
      ],
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: "Saved Date",
            color: Config.colors.text,
            font: {
              size: 13,
              weight: "500",
              family: '"DM Sans", sans-serif',
            },
          },
          ticks: {
            display: false,
          },
        },
        y: {
          title: {
            display: true,
            text: "BMI Value",
            color: Config.colors.text,
            font: {
              size: 13,
              weight: "500",
              family: '"DM Sans", sans-serif',
            },
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          enabled: true,
          backgroundColor: Config.colors.border,
          titleColor: function (context) {
            // For line charts, context.tooltip.dataPoints[0] gives the hovered point
            const point = context.tooltip.dataPoints[0];
            const bmi = point.parsed.y;
            if (bmi < 18.5) return "#ffcc00"; // Underweight (yellow)
            if (bmi < 25) return "#42b34d"; // Normal (green)
            if (bmi < 30) return "#ff9900"; // Overweight (orange)
            return "#ff3300"; // Obese (red)
          },
          bodyColor: Config.colors.text,
          displayColors: false,
          padding: 10,
          titleAlign: "center",
          bodyAlign: "center",
          titleMarginBottom: 2,
          titleFont: {
            size: 15,
            weight: 600,
          },
          bodyFont: {
            size: Config.fontSize,
          },
          callbacks: {
            label: function (context) {
              return (
                "Saved at " +
                Intl.DateTimeFormat("en-US", {
                  dateStyle: "medium",
                  timeStyle: "short",
                }).format(new Date(context.label))
              );
            },
            title: function (context) {
              const bmi = context[0].parsed.y;
              let bmiClass = "";
              if (bmi < 18.5) {
                bmiClass = "Underweight";
              } else if (bmi < 25) {
                bmiClass = "Normal";
              } else if (bmi < 30) {
                bmiClass = "Overweight";
              } else {
                bmiClass = "Obese";
              }
              return (
                "BMI: " +
                parseFloat(context[0].raw).toFixed(2) +
                ` (${bmiClass})`
              );
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

loadChart(
  $LABELS.map((dobj) =>
    Intl.DateTimeFormat("en-US", {
      dateStyle: "medium",
      timeStyle: "short",
    }).format(new Date(dobj.date))
  ),
  $VALUES
);

const select = document.querySelector(".filter select");

if (select) {
  select.addEventListener("change", (e) => {
    e.currentTarget.parentElement.submit();
  });
}

document.querySelectorAll(".list .item form").forEach((item) => {
  item.addEventListener("submit", (e) => {
    e.stopPropagation();
    if (confirm("Are you sure want to delete this record?")) e.target.submit();
  });
});
