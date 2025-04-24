const meter = document.querySelector(".meter");
const arrow = document.querySelector(".meter .arrow");
const textIncrementer = document.querySelector(".traffic-value");

const maxValue = 100;

const animateMeter = (targetValue = 0) => {
  let currentValue = 0;

  const animate = () => {
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

    currentValue += 2;
    if (currentValue < targetValue) {
      requestAnimationFrame(animate);
    } else {
      currentValue = targetValue;
      const finalRotation = (currentValue / maxValue) * 180;
      arrow.style.transform = `translateX(-50%) rotate(${finalRotation}deg)`;
    }
  };

  animate();
};

let currentIncrement = 0;
const incrementCount = () => {
  const intervalSpeed = 1200 / $TRAFFIC;
  const interval = setInterval(() => {
    if (currentIncrement < $TRAFFIC) {
      currentIncrement += 2;
      textIncrementer.innerHTML = `${Math.round(
        currentIncrement
      )}% of the gym is full<br/>at the moment`;
    } else {
      clearInterval(interval);
      textIncrementer.innerHTML = `${Math.round(
        $TRAFFIC
      )}% of the gym is full<br/>at the moment`;
    }
  }, intervalSpeed);
};

const run = async () => {
  if ($TRAFFIC < maxValue / 2) {
    animateMeter(maxValue);
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
};

run();
