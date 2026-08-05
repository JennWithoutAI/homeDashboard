<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <link rel="stylesheet" href="http://localhost:5173/scss/fretnet/fretCarousel.scss">
    <title>Document</title>
</head>
<body>


<h1>NetFret Carousel test</h1>
<div class="cards">
    <input type="radio" name="card" id="card-1" checked>
    <input type="radio" name="card" id="card-2">
    <input type="radio" name="card" id="card-3">
    <input type="radio" name="card" id="card-4">
    <input type="radio" name="card" id="card-5">
    <input type="radio" name="card" id="card-6">
    <input type="radio" name="card" id="card-7">
    <input type="radio" name="card" id="card-8">



    <div class="circle-container">

        <div><img src="<?php echo MODULE_URL_fretnet."/fretnetStaticHtml/img/car/car1.jpeg"?>" alt="1"></div>
        <div><img src="<?php echo MODULE_URL_fretnet."/fretnetStaticHtml/img/car/car2.png"?>" alt="2"></div>
        <div><img src="<?php echo MODULE_URL_fretnet."/fretnetStaticHtml/img/car/car3.png"?>" alt="3"></div>
        <div><img src="<?php echo MODULE_URL_fretnet."/fretnetStaticHtml/img/car/car4.png"?>" alt="4"></div>
        <div><img src="<?php echo MODULE_URL_fretnet."/fretnetStaticHtml/img/car/car5.png"?>" alt="5"></div>
    </div>
    <div class="contents">

        <!-- card 1 -->
        <article>
            <h2>Veiligheid van Data</h2>
            <ul>
                <li><span>Opsporen:</span> Wij scannen 24/7 problemen op om uw data te beschermen!</li>
                <li><span>Privacy:</span> Uw data is altijd encrypted, niemand kan dit lezen!</li>
                <li><span>Veiligheid</span> Onze veiligheid specialisten houden alles 24/7 in de gaten!</li>
            </ul>
            <!--<a href="#">more info</a>-->
            <div class="buttons">
                <label for="card-X" title="88 Butterfly" disabled>&#10094;</label>
                <label for="card-2" title="Monarch Butterfly">&#10095;</label>
            </div>
        </article>

        <!-- card 2 -->
        <article>
            <h2>Monarch Butterfly</h2>
            <ul>
                <li><span>Scientific Name:</span> Danaus plexippus</li>
                <li><span>Region:</span> North America (migrates to Mexico)</li>
                <li><span>Fact:</span> Known for its epic annual migration of up to 3,000 miles.</li>
            </ul>
            <div class="buttons">
                <label for="card-1" title="Blue Morpho">&#10094;</label>
                <label for="card-3" title="Peacock Butterfly">&#10095;</label>
            </div>
        </article>

        <!-- card 3 -->
        <article>
            <h2>Peacock Butterfly</h2>
            <ul>
                <li>Scientific Name:</span> Aglais io</li>
                <li>Region:</span> Europe and parts of Asia</li>
                <li>Fact:</span> Has large eye-like spots to scare off birds and predators.</li>
            </ul>
            <div class="buttons">
                <label for="card-2" title="Monarch Butterfly">&#10094;</label>
                <label for="card-4" title="Ulysses Butterfly">&#10095;</label>
            </div>
        </article>

        <!-- card 4 -->
        <article>
            <h2>Ulysses Butterfly</h2>
            <ul>
                <li><span>Scientific Name:</span>  Papilio ulysses</li>
                <li><span>Region:</span>  Australia (especially Queensland)</li>
                <li><span>Fact:</span>  Its vivid electric-blue wings make it a tourist icon.</li>
            </ul>
            <div class="buttons">
                <label for="card-3" title="Peacock Butterfly">&#10094;</label>
                <label for="card-5" title="Glasswing Butterfly">&#10095;</label>
            </div>
        </article>

        <!-- card 5 -->
        <article>
            <h2>Glasswing Butterfly</h2>
            <ul>
                <li><span>Scientific Name:</span> Greta oto</li>
                <li><span>Region:</span> Central America</li>
                <li><span>Fact:</span> Its transparent wings act like camouflage in the forest.</li>
            </ul>
            <div class="buttons">
                <label for="card-4" title="Ulysses Butterfly">&#10094;</label>
                <label for="card-6" title="Painted Lady">&#10095;</label>
            </div>
        </article>

        <!-- card 6 -->
        <article>
            <h2>Painted Lady</h2>
            <ul>
                <li><span>Scientific Name:</span> Vanessa cardui</li>
                <li><span>Region:</span> Found on every continent except Antarctica</li>
                <li><span>Fact:</span> One of the most widespread butterfly species in the world.</li>
            </ul>
            <div class="buttons">
                <label for="card-5" title="Glasswing">&#10094;</label>
                <label for="card-7" title="Rajah Brooke’s Birdwing">&#10095;</label>
            </div>
        </article>

        <!-- card 7 -->
        <article>
            <h2>Vangen en behandelen</h2>
            <ul>
                <li><span>Scientific Name:</span> Trogonoptera brookiana</li>
                <li><span>Region:</span> Borneo, Malaysia</li>
                <li><span>Fact:</span> Lorem ipsum dolor sit amet, consectetur adipisicing .</li>
            </ul>
            <div class="buttons">
                <label for="card-6" title="Painted Lady">&#10094;</label>
                <label for="card-8" title="88 Butterfly">&#10095;</label>
            </div>
        </article>

        <!-- card 8 -->
        <article>
            <h2>88 Butterfly</h2>
            <ul>
                <li><span>Scientific Name:</span> Diaethria anna</li>
                <li><span>Region:</span> Central and South America</li>
                <li><span>Fact:</span> Named for the “88” pattern on its hindwings—naturally formed!</li>
            </ul>
            <div class="buttons">
                <label for="card-7" title="Rajah Brooke’s Birdwing">&#10094;</label>
                <label for="card-X" title="Blue Morpho" disabled>&#10095;</label>
            </div>
        </article>
        </d>
    </div>
</body>
</html>
