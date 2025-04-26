/**
 * GYMRAT Workout Session Key QR Code Generator
 * File: src/wsk_qr/wsk_qr.js
 */

// Set up SSE connection for real-time QR code updates
const evtSource = new EventSource("sse.php");

// Get QR code element 
const qrcode_element = document.querySelector("#qrcode");

// Initialize QR code generator
var qrcode = new QRCode(qrcode_element, {
    width: 240,
    height: 240,
    colorDark: "#18181b", // Matching Tailwind zinc-900
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Clear any existing QR code
qrcode.clear();

// Function to display QR code
const showQrCode = (key) => {
    qrcode.clear();
    qrcode.makeCode(key);
    
    // Add highlight effect when QR code updates
    qrcode_element.classList.add("highlight");
    setTimeout(() => {
        qrcode_element.classList.remove("highlight");
    }, 1000);
};

// Handle server connection event
evtSource.addEventListener("connected", (event) => {
    console.log("Connected to server", event.data);
    if (event.data) showQrCode(event.data);
});

// Handle QR code update event
evtSource.addEventListener("qr_code_changed", (event) => {
    console.log("QR code changed", event.data);
    if (event.data) showQrCode(event.data);
});

// Handle error event
evtSource.addEventListener("error", (event) => {
    console.log("Error event", event.data);
});

// Handle connection errors
evtSource.onerror = (error) => {
    console.error("SSE Connection Error:", error);
    
    // Attempt to reconnect after a delay
    setTimeout(() => {
        console.log("Attempting to reconnect...");
        // The browser will automatically attempt to reconnect
    }, 5000);
};