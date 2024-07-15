
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
    exit(0);
}

require_once("modules/weather.php");

$weather = new Weather();

if (isset($_REQUEST['request'])) {
    $request = explode('/', urldecode($_REQUEST['request']));
} else {
    http_response_code(404);
    var_dump($_REQUEST);
    echo json_encode(["error" => "Not Found"]);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($request[0]) {
            case 'getweather':
                if (isset($_POST['city'])) {
                    $city = $_POST['city'];
                    $weatherResponse = $weather->getWeatherCity($city);
                    unset($weatherResponse['coord']);
                    echo json_encode($weatherResponse);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Enter a valid city name"]);
                }
                break;
            case 'getweatherbycoordinates':
                if (isset($_POST['lat']) && isset($_POST['lon'])) {
                        $lat = $_POST['lat'];
                        $lon = $_POST['lon'];
                        echo json_encode($weather->getWeatherByCoordinates($lat, $lon));
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "Enter valid coordinates"]);
                    }
                    break;
            case 'fivedaysweather':
                if (isset($_POST['city'])) {
                    $city = $_POST['city'];
                    $weatherResponse = $weather->get5daysforecast($city);
                    echo json_encode($weatherResponse);
                    
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Enter a valid city name"]);
                }
                break;
            case 'fivedaysweatherbycoordinates':
                if (isset($_POST['lat']) && isset($_POST['lon'])) {
                        $lat = $_POST['lat'];
                        $lon = $_POST['lon'];
                        echo json_encode($weather->get5daysForecastByCoordinates($lat, $lon));
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "Enter valid coordinates"]);
                    }
                    break;
            default:
                http_response_code(403);
                echo json_encode(["error" => "Forbidden"]);
                break;
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
        break;
}
?>
