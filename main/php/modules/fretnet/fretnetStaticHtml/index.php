<?php
// php will be removed once we go over to vite but since ... we are having some constraints with filesystems im going to just use the build in way
$images = MODULE_URL_fretnet."/fretnetStaticHtml/img";
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- dev only (FOR REAL DONT FORGET IT AGAIN, ITS MY HOT LOADING soo haaawt) -->
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <link rel="stylesheet" href="http://localhost:5173/scss/fretnet/main.scss">
    <title>Fretnet</title>
</head>
<body>
    <div class="wrap-container">
        <div class="header">
            <div class="topline">
                <img src="" alt="" class="logo">
                <nav class="navigation">
                    <ul class="nav_list">
                        <a href="#"><li> Home </li></a>
                        <a href="#"><li> Products </li></a>
                        <a href="#"><li> Contact </li></a>
                    </ul>
                </nav>
            </div>
            <div class="body-container">
                <!-- carousel -->
                <div class="carousel">
                    <ul>
                        <li>
                            <div class="carousel-item" data-accName="card1">
                                <div class="numbertext">1 / 5</div>
                                <img src="<?php echo $images."/car1.jpeg"; ?>" alt="the Boys">
                                <div class="text">Caption Text</div>
                            </div>
                        </li>
                        <li>
                            <div class="carousel-item" data-accName="card2">
                                <div class="numbertext">2 / 5</div>
                                <img src="<?php echo $images."/car2.jpeg"; ?>" alt="the Boys">
                                <div class="text">Caption Text</div>
                            </div>
                        </li>
                        <li>
                            <div class="carousel-item" data-accName="card3">
                                <div class="numbertext">3 / 5</div>
                                <img src="<?php echo $images."/car3.jpeg"; ?>" alt="the Boys">
                                <div class="text">Caption Text</div>
                            </div>
                        </li>
                        <li>
                            <div class="carousel-item" data-accName="card4">
                                <div class="numbertext">4 / 5</div>
                                <img src="<?php echo $images."/car4.jpeg"; ?>" alt="the Boys">
                                <div class="text">Caption Text</div>
                            </div>
                        </li>
                        <li>
                            <div class="carousel-item" data-accName="card5">
                                <div class="numbertext">5 / 5</div>
                                <img src="<?php echo $images."/car5.jpeg"; ?>" alt="the Boys">
                                <div class="text">Caption Text</div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="footer-container">

            </div>
        </div>
    </div>
</body>
</html>
