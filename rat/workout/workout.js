document.querySelectorAll(".category").forEach((category) => {
  const toggler = category.querySelector("button");

  toggler.addEventListener("click", () => {
    category.classList.toggle("hidden");
  });
});
