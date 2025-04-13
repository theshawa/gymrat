const slider = document.querySelector(".height input");
const valueField = document.querySelector(".height span");

const setValue = () => {
  valueField.innerHTML = slider.value;
};
slider.addEventListener("input", setValue);

slider.value = 175;
setValue();

const btns = document.querySelectorAll(".btns button");
btns.forEach((btn) => {
  const target = btn.getAttribute("data-target");
  const op = btn.getAttribute("data-op");

  const input = document.querySelector(`input[name="${target}"]`);
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    if (op === "-") {
      input.value = String(parseFloat(input.value) - 1);
    } else {
      input.value = String(parseFloat(input.value) + 1);
    }
  });
});
