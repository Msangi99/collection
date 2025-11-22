<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a6c 0%, #2a5298 100%);
            color: #fff;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1000px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }
        
        #video {
            width: 100%;
            border-radius: 10px;
            transform: scaleX(-1);
            display: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        #canvas {
            display: none;
        }
        
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px solid #fff;
            border-radius: 10px;
            box-shadow: 0 0 0 4000px rgba(0, 0, 0, 0.3);
        }
        
        .scan-line {
            position: absolute;
            height: 2px;
            width: 100%;
            background: #4cd137;
            box-shadow: 0 0 10px #4cd137;
            animation: scan 2s linear infinite;
        }
        
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
        
        .placeholder {
            width: 100%;
            height: 300px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        
        .placeholder i {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 20px 0;
            width: 100%;
        }
        
        button {
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            background: #3498db;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        button:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        button:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
        }
        
        #scan-btn {
            background: #27ae60;
        }
        
        #scan-btn:hover {
            background: #219653;
        }
        
        .result-container {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        .result-container h2 {
            margin-bottom: 15px;
            text-align: center;
        }
        
        #result {
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            min-height: 100px;
        }
        
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .result-table th, .result-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .result-table th {
            background: rgba(255, 255, 255, 0.15);
            font-weight: bold;
        }
        
        .status {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        .success {
            color: #27ae60;
        }
        
        .error {
            color: #e74c3c;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 20px;
            }
            
            .placeholder {
                height: 250px;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 1.8rem;
            }
            
            button {
                padding: 10px 20px;
            }
            
            .placeholder {
                height: 200px;
            }
            
            .result-table th, .result-table td {
                font-size: 0.9rem;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>QR Code Scanner</h1>
        <p>Scan QR codes and send data to server</p>
    </header>
    
    <div class="container">
        <div class="scanner-container">
            <div class="placeholder" id="placeholder">
                <i>📷</i>
                <p>Camera access required to start scanning</p>
            </div>
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas"></canvas>
            <div class="scanner-overlay" id="scanner-overlay" style="display: none;">
                <div class="scan-line"></div>
            </div>
        </div>
        
        <div class="controls">
            <button id="start-btn">Start Scanner</button>
            <button id="scan-btn" disabled>Scan QR Code</button>
            <button id="switch-btn" disabled>Switch Camera</button>
        </div>
        
        <div class="result-container">
            <h2>Scan Result</h2>
            <div id="result">No QR code scanned yet</div>
            <div id="status" class="status"></div>
        </div>
    </div>
    
    <div class="footer">
        <p>QR Code Scanner App &copy; {{ \Carbon\Carbon::now()->year }} | {{ env('APP_NAME') }}</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const startBtn = document.getElementById('start-btn');
            const scanBtn = document.getElementById('scan-btn');
            const switchBtn = document.getElementById('switch-btn');
            const placeholder = document.getElementById('placeholder');
            const scannerOverlay = document.getElementById('scanner-overlay');
            const resultDiv = document.getElementById('result');
            const statusDiv = document.getElementById('status');
            
            let stream = null;
            let scanningInterval = null;
            let facingMode = 'environment';
            
            startBtn.addEventListener('click', initCamera);
            scanBtn.addEventListener('click', toggleScanning);
            switchBtn.addEventListener('click', switchCamera);
            
            async function initCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: facingMode },
                        audio: false
                    });
                    
                    video.srcObject = stream;
                    video.style.display = 'block';
                    placeholder.style.display = 'none';
                    scannerOverlay.style.display = 'block';
                    
                    startBtn.disabled = true;
                    scanBtn.disabled = false;
                    switchBtn.disabled = false;
                    
                    video.addEventListener('loadedmetadata', function() {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                    });
                    
                    toggleScanning();
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    statusDiv.textContent = 'Unable to access camera. Please check permissions and try again.';
                    statusDiv.className = 'status error';
                }
            }
            
            function toggleScanning() {
                if (scanningInterval) {
                    clearInterval(scanningInterval);
                    scanningInterval = null;
                    scanBtn.textContent = 'Scan QR Code';
                    statusDiv.textContent = 'Scanning stopped';
                    statusDiv.className = 'status';
                } else {
                    scanBtn.textContent = 'Stop Scanning';
                    statusDiv.textContent = 'Scanning for QR codes...';
                    statusDiv.className = 'status';
                    scanningInterval = setInterval(scanQRCode, 100);
                }
            }
            
            function scanQRCode() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    
                    if (code) {
                        drawQRBounds(code.location);
                        sendDataToServer(code.data);
                    }
                }
            }
            
            function drawQRBounds(location) {
                context.strokeStyle = '#27ae60';
                context.lineWidth = 5;
                context.beginPath();
                context.moveTo(location.topLeftCorner.x, location.topLeftCorner.y);
                context.lineTo(location.topRightCorner.x, location.topRightCorner.y);
                context.lineTo(location.bottomRightCorner.x, location.bottomRightCorner.y);
                context.lineTo(location.bottomLeftCorner.x, location.bottomLeftCorner.y);
                context.closePath();
                context.stroke();
            }
            
            async function sendDataToServer(qrData) {
                statusDiv.textContent = 'Sending data to server...';
                statusDiv.className = 'status';
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch('/qr-scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ qr_data: qrData })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                    }
                    
                    const result = await response.json();
                    const data = result['data'];
                    
                    // Display specific fields in a table
                    resultDiv.innerHTML = `
                        <table class="result-table">
                            <tr>
                                <th>Bus</th>
                                <td>${data.bus.campany.name || 'N/A'} || ${data.bus.bus_number || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>${data.customer_name || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Customer Email</th>
                                <td>${data.customer_email || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Customer Phone</th>
                                <td>${data.customer_phone || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Seat</th>
                                <td>${data.seat || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>${data.payment_status || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>${data.payment_method || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Travel Date</th>
                                <td>${data.travel_date || 'N/A'} || ${data.route.schedule.start}</td>
                            </tr>
                        </table>
                    `;
                    
                    statusDiv.textContent = 'Data sent successfully!';
                    statusDiv.className = 'status success';
                    
                    clearInterval(scanningInterval);
                    scanningInterval = null;
                    scanBtn.textContent = 'Scan QR Code';
                    
                } catch (error) {
                    console.error('Error sending data to server:', error);
                    resultDiv.textContent = `Error: ${error.message}`;
                    statusDiv.textContent = 'Error sending data to server';
                    statusDiv.className = 'status error';
                }
            }
            
            async function switchCamera() {
                if (stream) {
                    clearInterval(scanningInterval);
                    scanningInterval = null;
                    stream.getTracks().forEach(track => track.stop());
                    facingMode = facingMode === 'user' ? 'environment' : 'user';
                    await initCamera();
                }
            }
        });
    </script>
</body>
</html>