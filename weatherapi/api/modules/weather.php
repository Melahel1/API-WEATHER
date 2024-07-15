<?php
class Weather {
    private $geoApiUrl = 'http://api.openweathermap.org/geo/1.0/direct';
    private $weatherHourly = 'https://api.openweathermap.org/data/2.5/weather';
    private $weather5days = 'https://api.openweathermap.org/data/2.5/forecast';
    private $apiKey = '4c1f4d5660d2311c4c1376f0e6ac22ae';

public function getWeatherCity($city) {
    $geoResponse = $this->makeApiRequest($this->geoApiUrl, [
        'q' => $city,
        'limit' => 1,
        'appid' => $this->apiKey
    ]);

    if (!$geoResponse || count($geoResponse) === 0) {
        return ["error" => "City not found"];
    }

    $lat = $geoResponse[0]['lat'];
    $lon = $geoResponse[0]['lon'];
    $state = isset($geoResponse[0]['state']) ? $geoResponse[0]['state'] : '';

    $weatherResponse = $this->makeApiRequest($this->weatherHourly, [
        'lat' => $lat,
        'lon' => $lon,
        'units' => 'metric',
        'appid' => $this->apiKey
    ]);

    if (!$weatherResponse) {
        return ["error" => "Failed to retrieve data"];
    }

    $weatherResponse['state'] = $state;

    return $weatherResponse;
}

    public function get5daysforecast($city) {

        $geoResponse = $this->makeApiRequest($this->geoApiUrl, [
            'q' => urlencode($city),
            'limit' => 1,
            'appid' => $this->apiKey
        ]);

        if (!$geoResponse || count($geoResponse) === 0) {
            return ["error" => "City not found"];
        }

        $lat = $geoResponse[0]['lat'];
        $lon = $geoResponse[0]['lon'];

        $forecastResponse = $this->makeApiRequest($this->weather5days, [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $this->apiKey
        ]);

        if (!$forecastResponse) {
            return ["error" => "Failed to retrieve data"];
        }

        return $forecastResponse;
    }


    public function getWeatherByCoordinates($lat, $lon) {
        $weatherResponse = $this->makeApiRequest($this->weatherHourly, [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $this->apiKey
        ]);

        if (!$weatherResponse) {
            return ["error" => "Failed to retrieve data"];
        }

        return $weatherResponse;
    }

    public function get5daysForecastByCoordinates($lat, $lon) {
        $forecastResponse = $this->makeApiRequest($this->weather5days, [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $this->apiKey
        ]);

        if (!$forecastResponse) {
            return ["error" => "Failed to retrieve data"];
        }

        return $forecastResponse;
    }

    
    private function makeApiRequest($url, $params) {
        $query = http_build_query($params);
        $fullUrl = $url . '?' . $query;
        $response = file_get_contents($fullUrl);
        return json_decode($response, true);
    }    
}
?>
