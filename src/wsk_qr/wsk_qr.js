const evtSource = new EventSource("sse.php");

const qrcode_element = document.querySelector("#qrcode");

var qrcode = new QRCode(qrcode_element, {
  width: 300,
  height: 300,
  colorDark: "#18181b", // Matching Tailwind zinc-900
  colorLight: "#ffffff",
  correctLevel: QRCode.CorrectLevel.H,
});

qrcode.clear();

const showQrCode = (key) => {
  qrcode.clear();
  qrcode.makeCode(key);

  qrcode_element.classList.add("highlight");
  setTimeout(() => {
    qrcode_element.classList.remove("highlight");
  }, 1000);
};

evtSource.addEventListener("connected", (event) => {
  console.log("Connected to server", event.data);
  if (event.data) showQrCode(event.data);
});

evtSource.addEventListener("qr_code_changed", (event) => {
  console.log("QR code changed", event.data);
  if (event.data) showQrCode(event.data);
});

evtSource.addEventListener("error", (event) => {
  console.log("Error event", event.data);
});

evtSource.onerror = (error) => {
  console.error("SSE Connection Error:", error);

  setTimeout(() => {
    console.log("Attempting to reconnect...");
  }, 5000);
};

const greetingElement = document.querySelector(".greeting");
const currentHour = new Date().getHours();

let greetingMessage = "Good Morning! 👋";
if (currentHour >= 12 && currentHour < 18) {
  greetingMessage = "Good Afternoon! 👋";
} else if (currentHour >= 18) {
  greetingMessage = "Good Evening! 👋";
}

greetingElement.textContent = greetingMessage;
