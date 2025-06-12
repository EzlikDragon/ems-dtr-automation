<?php
    $videoPath = "video/DEMOVIDEO.mp4";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Video</title>
</head>
<body>
    <h2>Video Playback</h2>
    <video width="640" height="360" controls>
        <source src="<?php echo $videoPath; ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</body>
</html>