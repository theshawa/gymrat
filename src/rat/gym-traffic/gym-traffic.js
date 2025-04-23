const meter = document.querySelector(".meter");
const arrow = document.querySelector(".meter .arrow");
const textIncrementer = document.querySelector(".traffic-value");

const animateMeter = (targetValue = 0) => {
  let currentValue = 0;
  const maxValue = 10;

  const animate = () => {
    if (currentValue <= targetValue) {
      const rotation = (currentValue / maxValue) * 180;
      arrow.style.transform = `translateX(-50%) rotate(${rotation}deg)`;

      let color;
      if (currentValue < maxValue / 3) {
        color = `rgb(66, 179, 77)`;
      } else if (currentValue < (2 * maxValue) / 3) {
        color = `rgb(255, 204, 0)`;
      } else {
        color = `rgb(240, 80, 80)`;
      }

      meter.style.backgroundColor = color;
      meter.style.borderColor = color;
      arrow.style.backgroundColor = color;
      document.querySelector(".data .active-users .dot").style.backgroundColor =
        color;

      currentValue += 0.1;
      requestAnimationFrame(animate);
    }
  };

  animate();
};

let currentIncrement = 0;
const incrementCount = () => {
  const intervalSpeed = 1200 / $TRAFFIC / 9;
  const interval = setInterval(() => {
    if (currentIncrement < $TRAFFIC) {
      currentIncrement += 0.1;
      textIncrementer.innerText = `${currentIncrement.toFixed(1)}/10`;
    } else {
      clearInterval(interval);
      textIncrementer.innerText = `${$TRAFFIC.toFixed(1)}/10`;
    }
  }, intervalSpeed);
};

if ($TRAFFIC < 3) {
  animateMeter(10);
  setTimeout(() => {
    animateMeter($TRAFFIC);
    incrementCount();
    setTimeout(() => {
      document.querySelector(".data").style.opacity = "1";
    }, 1500);
  }, 2500);
} else {
  animateMeter($TRAFFIC);
  setTimeout(incrementCount, 500);
  setTimeout(() => {
    document.querySelector(".data").style.opacity = "1";
  }, 1500);
}
