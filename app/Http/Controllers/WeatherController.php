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
    const WETHER_PARAMS = [
        'units' => 'metric',
        'country' => 'uk',
    ];

    /**
     * Display weather information for provided city
     *
     * @param Request $request
     * @return View
     */
    public function getWeather(Request $request): View
    {
        $query = [
            'q' => $request['city'] . ',' . self::WETHER_PARAMS['country'],
            'units' => self::WETHER_PARAMS['units'],
            'appid' => env('OPEN_WEATHER_API_KEY'),
        ];
        $weatherData = Http::get(self::WEATHER_URL . '?' . http_build_query($query));
        $retArray = $weatherData->json();
        if($weatherData->ok()){
            $dataFormatter = new WeatherData($retArray);
            $formattedData = array_merge([['label' => 'City', 'content' => ucwords($request['city'])]], $dataFormatter->getFormattedData());
        } else {
            $formattedData = ['error' => $retArray['message']??'Failed to retrieve data'];
        }

        return \view('show', ['data' => $formattedData]);
    }
}
