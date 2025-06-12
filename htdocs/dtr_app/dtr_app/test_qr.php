<!DOCTYPE html>
<html>
<head>
  <title>Test QR Scanner</title>
  <script src="js/html5-qrcode/html5-qrcode.min.js"></script>
</head>
<body>
  <h3>Scanner Test</h3>
  <div id="reader" style="width: 300px;"></div>

  <script>
  function onScanSuccess(decodedText, decodedResult) {
      alert("Scanned: " + decodedText);
  }

  new Html5Qrcode("reader").start(
      { facingMode: "environment" },
      { fps: 10, qrbox: 250 },
      onScanSuccess,
      error => {}
  );
  </script>
</body>
</html>
