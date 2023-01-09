<?php

class WeatherAPI
{
    private string $url;

    private string $key;

    private string $defaultCity;

    /**
     * @param string $url
     * @param string $key
     * @param string $defaultCity
     */
    public function __construct(string $url, string $key, string $defaultCity = "Valence")
    {
        $this->url = $url;
        $this->key = $key;
        $this->defaultCity = $defaultCity;
    }

    public function getWeather(string $city = null): array|false
    {
        if($city === null){
            return false;
        }

        if (isset($_POST["shearch"])) {
            if (isset($_POST["city"])) {
                if (!ctype_space($_POST["city"])) {
                    if (preg_match("/^[a-zA-Z ]*$/", $_POST["city"])) {
                        $city = $_POST["city"];
                    }
                }
            }
        }

        $url = str_replace(["{city}", "{key}"], [$city, $this->key], $this->url);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($result);

        if ($json->cod == 404) {
            return false;
        } else {
            return [
                "error" => false,
                "name" => $json->name,
                "weather" => $json->weather[0]->main,
                "description" => $json->weather[0]->description,
                "humidity" => $json->main->humidity,
                "wind" => $json->wind->speed,
                "timezone" => $json->timezone,
            ];
        }
    }

    public function transform(array $data): void{

        $wind = $data["wind"];
        $humidity = $data["humidity"];
        $description = $data["description"];
        $name = $data["name"];
        $timezone = date("H:m:s",time() + $data["timezone"]);
        echo '<div class="card text-white bg-success mb-3" style="max-width: 20rem;">
            <div class="card-header">Météo - '.$timezone.'</div>
            <div class="card-body">
                <h4 class="card-title">'.$name.' - '.$description.'</h4>
                Le vent est de '.$wind.' km/h.</p><p> L\'humidité est de '.$humidity.' %.</p>
            </div>
            </div>';
    }
}