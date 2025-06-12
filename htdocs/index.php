<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/webicon.png">
    <title>Internship - (HR) City Hall</title>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-B4KGNFS231"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-B4KGNFS231');
    </script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MZ4QDWZK');</script>
    <!-- End Google Tag Manager -->
    
    <style>
        .hero {
            position: fixed;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background: black;
            transition: opacity 1s ease-out;
            z-index: 999;
        }

        .hero video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .loginpage {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .footer {
            width: 100%;
            text-align: center;
            padding: 15px 10px;
            background: rgba(255, 182, 212, 0.8);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MZ4QDWZK" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <div class="hero">
        <video autoplay muted playsinline>
            <source src="video/video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <section class="loginpage">
        <ul>
            <?php include 'login.php'; ?>
        </ul>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let hero = document.querySelector(".hero");
            let video = document.querySelector(".hero video");
            let loginPage = document.querySelector(".loginpage");

            // Set playback speed
            video.playbackRate = 0.8; 
            
            // Ensure the video plays
            video.play().catch(error => console.log("Video play error: ", error));

            // Fade-out effect after video plays for 3.5 seconds
            setTimeout(() => {
                hero.style.opacity = '0';
                setTimeout(() => {
                    hero.style.display = 'none';
                    loginPage.style.display = 'block';
                }, 1000); // Wait for fade-out to complete
            }, 3500);
        });
    </script>
</body>
</html>
