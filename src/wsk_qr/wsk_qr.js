const evtSource = new EventSource("sse.php");

const qrcode_element = document.querySelector("#qrcode");

var qrcode = new QRCode(qrcode_element, {
  width: 300,
  height: 300,
  colorDark: "black",
  colorLight: "white",
  correctLevel: QRCode.CorrectLevel.H,
});
qrcode.clear();

const showQrCode = (key) => {
  qrcode.clear();
  qrcode.makeCode(key);
};

evtSource.addEventListener("connected", (event) => {
  console.log("connected", event.data);
  if (event.data) showQrCode(event.data);
});

evtSource.addEventListener("qr_code_changed", (event) => {
  console.log("qr_code_changed", event.data);

  if (event.data) showQrCode(event.data);
});

evtSource.addEventListener("error", (event) => {
  console.log("error", event.data);
});

evtSource.onerror = (error) => {
  console.error("Error:", error);
};
