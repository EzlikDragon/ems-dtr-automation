<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js/dist/video-js.css">
    <link rel="stylesheet" href="dash.css">
    <script src="https://cdn.jsdelivr.net/npm/video.js/dist/video.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
            background: rgba(255, 182, 212, 0.8);
            position: relative;
        }
        #videoContainer {
            display: none;
            position: relative;
            z-index: 2;
        }
        #my-video {
            width: 860px;
            height: 440px;
        }
        #logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        #watchButton {
            background: linear-gradient(45deg, rgb(43, 41, 217), rgb(72, 70, 255));
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(43, 41, 217, 0.4);
        }
        #watchButton:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(43, 41, 217, 0.6);
            background: yellow;
            color: red;
        }
        #watchButton:active {
            transform: scale(0.95);
            box-shadow: 0 2px 10px rgba(43, 41, 217, 0.4);
        }
        .side-image {
            position: absolute;
            width: 50px;
            height: auto;
            top: 50%;
            transform: translateY(-50%) scale(0.1);
            opacity: 0;
            transition: transform 1.5s ease, opacity 1.5s ease;
            z-index: 1;
        }
        #leftImage {
            left: 20%;
        }
        #rightImage {
            right: 20%;
        }
        .show-image {
            transform: translateY(-50%) scale(1);
            opacity: 1;
        }
        #feedbackSection {
            margin-top: 20px;
            width: 50%;
            text-align: center;
        }
        textarea {
            width: 100%;
            height: 60px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button#submitFeedback {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button#submitFeedback:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function showVideo() {
            document.getElementById('watchButton').style.display = 'none';
            document.getElementById('videoContainer').style.display = 'block';
            document.getElementById('leftImage').classList.add('show-image');
            document.getElementById('rightImage').classList.add('show-image');
            var player = videojs('my-video');
            player.play();
        }
        function submitFeedback() {
            let feedback = document.getElementById('feedbackText').value;
            if (feedback.trim() === "") {
                alert("Please enter your feedback before submitting.");
                return;
            }
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "save_feedback.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Thank you for your feedback!");
                    document.getElementById('feedbackText').value = '';
                    window.location.href = 'index.php'; // Redirect to index.php
                }
            };
            xhr.send("feedback=" + encodeURIComponent(feedback));
        }
    </script>
</head>
<body>
    <img id="logo" src="images/MaasinSeal.png" alt="Maasin Seal">
    <h2>Maasin City Hall - Employee Management System</h2>
    <br>
    <br>
    <br>
    <button id="watchButton" onclick="showVideo()">WATCH DEMO</button>
    <div id="videoContainer">
        <video id="my-video" class="video-js" controls preload="auto">
            <source src="video/samplevideo1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    
    <div id="feedbackSection">
        <h3>Provide Your Feedback</h3>
        <textarea id="feedbackText" placeholder="Write your feedback or suggestions here..."></textarea>
        <button id="submitFeedback" onclick="submitFeedback()">Submit Feedback</button>
    </div>
</body>
   <br>
    <br>
    <br>
<?php include 'footer.php'; ?>
</html>
