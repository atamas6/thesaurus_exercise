<?php

namespace App\Http\Controllers;

use App\WeatherData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class WeatherController extends Controller
{
    const WEATHER_URL = 'https://api.openweathermap.org/data/2.5/weather';

    public function getWeather(string $city): View
    {
//        WeatherData::exportUkCities();
        $query = [
            'q' => $city,
            'units' => 'metric',
            'appid' => env('OPEN_WEATHER_API_KEY'),
        ];
        $weatherData = Http::get(self::WEATHER_URL . '?' . http_build_query($query));
        $retArray = $weatherData->json();
        if($weatherData->ok()){
            $dataFormatter = new WeatherData($retArray);
            $formattedData = $dataFormatter->getFormattedData();
        } else {
            $formattedData = ['error' => $retArray['message']??'Failed to retrieve data'];
        }

        return \view('home', ['data' => $formattedData]);
    }
}
