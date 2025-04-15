const scanner = new Html5Qrcode("reader");
const errorElement = document.querySelector(".error-message");
const tryAgainBtn = document.querySelector("button.try-again");
const cameraSelectContainer = document.querySelector(".camera-selection");
const cameraSelect = document.querySelector(".camera-selection select");
const info = document.querySelector(".info");

let devices = [];

const setError = (message) => {
  if (message) {
    errorElement.innerText = message;
    errorElement.style.display = "block";
    tryAgainBtn.style.display = "block";
    info.style.display = "none";
  } else {
    errorElement.innerText = "";
    errorElement.style.display = "none";
    tryAgainBtn.style.display = "none";
    info.style.display = "flex";
  }
};

const loadDevices = async () => {
  try {
    const loaded_devices = await Html5Qrcode.getCameras();
    if (!loaded_devices || loaded_devices.length === 0) {
      throw new Error("No camera found.");
    }
    devices = loaded_devices;

    if (devices.length > 1) {
      cameraSelectContainer.style.display = "flex";
      devices.forEach((device) => {
        const option = document.createElement("option");
        option.value = device.id;
        option.textContent = device.label || `Camera ${device.id}`;
        cameraSelect.appendChild(option);
      });
    } else {
      cameraSelectContainer.style.display = "none";
    }
  } catch (error) {
    const message = error.message || error.toString();
    throw new Error("Error loading devices to scan: " + message);
  }
};

const startScanning = async (deviceId) => {
  try {
    await scanner.clear();
    scanner.start(
      deviceId,
      {
        fps: 10,
        qrbox: { width: 250, height: 250 },
      },
      (decodedText, decodedResult) => {
        scanner.stop().then(() => {
          redirect(decodedText);
        });
      }
    );
  } catch (error) {
    const message = error.message || error.toString();
    throw new Error("Error loading devices to scan: " + message);
  }
};

const run = async () => {
  setError(null);
  try {
    await loadDevices();
    const selectingDeviceId =
      devices.length > 1 ? devices[1].id : devices[0].id;
    await startScanning(selectingDeviceId);
    cameraSelect.value = selectingDeviceId;
  } catch (error) {
    const message = error.message || error.toString();
    setError(message);
  }
};

window.addEventListener("DOMContentLoaded", run);
tryAgainBtn.addEventListener("click", run);
cameraSelect.addEventListener("change", async (event) => {
  setError(null);
  try {
    await scanner.stop();
    await startScanning(event.target.value);
  } catch (error) {
    setError(error.message || error.toString());
  }
});

const redirect = (key) => {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "./start_workout_process.php";
  const input = document.createElement("input");
  input.type = "hidden";
  input.name = "key";
  input.value = key;
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
};
