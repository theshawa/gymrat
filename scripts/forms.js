console.log("forms.js loaded");

// password field toggle
const passwordInputs = document.querySelectorAll(".password-field");
const EYE_ICON_SVG = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.69605 10.2687C1.63848 10.0959 1.63843 9.90895 1.69589 9.73619C2.85286 6.2581 6.13375 3.75 10.0004 3.75C13.8653 3.75 17.1449 6.25577 18.3034 9.73134C18.3609 9.90406 18.361 10.0911 18.3035 10.2638C17.1465 13.7419 13.8657 16.25 9.99897 16.25C6.13409 16.25 2.85445 13.7442 1.69605 10.2687Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.4998 10C12.4998 11.3807 11.3805 12.5 9.99976 12.5C8.61904 12.5 7.49976 11.3807 7.49976 10C7.49976 8.61929 8.61904 7.5 9.99976 7.5C11.3805 7.5 12.4998 8.61929 12.4998 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>`;
const EYE_SLASH_ICON_SVG = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.31646 6.85214C2.54721 7.76152 1.9602 8.83001 1.61182 10.0012C2.6879 13.615 6.03578 16.25 9.9991 16.25C10.8262 16.25 11.6266 16.1352 12.3851 15.9207M5.18954 5.18969C6.56976 4.27965 8.22294 3.75 9.99984 3.75C13.9632 3.75 17.311 6.38504 18.3871 9.99877C17.7939 11.9932 16.5087 13.6898 14.8099 14.81M5.18954 5.18969L2.49985 2.5M5.18954 5.18969L8.23209 8.23223M14.8099 14.81L17.4999 17.5M14.8099 14.81L11.7676 11.7678M11.7676 11.7678C12.22 11.3154 12.4999 10.6904 12.4999 10C12.4999 8.61929 11.3806 7.5 9.99985 7.5C9.3095 7.5 8.6845 7.77982 8.23209 8.23223M11.7676 11.7678L8.23209 8.23223" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>`;
passwordInputs.forEach((input) => {
  const btn = document.createElement("button");
  input.appendChild(btn);
  btn.innerHTML = EYE_ICON_SVG;
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    if (input.querySelector("input").type === "password") {
      input.querySelector("input").type = "text";
      btn.innerHTML = EYE_SLASH_ICON_SVG;
    } else {
      input.querySelector("input").type = "password";
      btn.innerHTML = EYE_ICON_SVG;
    }
  });
  const field = input.querySelector("input");
  [field, btn].forEach((el) => {
    el.addEventListener("focus", () => {
      input.classList.add("focused");
      if (el === btn) {
        field.focus();
      }
    });
    el.addEventListener("blur", () => {
      input.classList.remove("focused");
    });
  });
});
