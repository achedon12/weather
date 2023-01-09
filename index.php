<?php

require_once "WeatherAPI.php";

$key = "a17559bf9b5ffc09a39dc6fb1f02aaae";
$url = "https://api.openweathermap.org/data/2.5/weather?q={city}&lang=fr&units=metric&APPID={key}";
$defaultCity = "Valence";

$weatherAPI = new WeatherAPI($url,$key,$defaultCity);

if(empty($_POST))
{
    $_POST["city"] = $defaultCity;
}

$city = $weatherAPI->getWeather($_POST["city"] ?? null);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Weather</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <main>
        <form method="post">
            <div class="form-group">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Valence" name="city" required>
                    <label for="floatingInput">Ville...</label>
                </div>
            </div>
            <input type="submit" value="rechercher la météo" name="shearch" class="btn btn-outline-primary">
        </form>
        <?php if(is_array($city)){
            $weatherAPI->transform($city);
        }else{ ?>
        <div class="card text-white bg-danger mb-3" style="max-width: 20rem;">
            <div class="card-header">Erreur</div>
            <div class="card-body">
                <h4 class="card-title">La ville n'existe pas</h4>
            </div>
        </div>
        <?php } ?>
    </main>
    </body>
</html>