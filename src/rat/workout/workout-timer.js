const runTimer = () => {
  const renderer = document.querySelector(".workout-timer");
  const setTime = () => {
    const timeText = getTimeText($WORKOUT_STARTED_AT);
    renderer.textContent = timeText;
  };

  setTime();
  setInterval(setTime, 1000);
};

const formatNumber = (number) => {
  return number < 10 ? `0${number}` : number;
};

const getTimeText = (from) => {
  const startedAt = new Date(from);
  const now = new Date();
  const diffInSeconds = Math.floor((now - startedAt) / 1000);
  const seconds = diffInSeconds % 60;
  const minutes = Math.floor(diffInSeconds / 60) % 60;
  const hours = Math.floor(diffInSeconds / 3600) % 24;

  return `${formatNumber(hours)}:${formatNumber(minutes)}:${formatNumber(
    seconds
  )}`;
};

if ($WORKOUT_STARTED_AT) {
  runTimer();
}
