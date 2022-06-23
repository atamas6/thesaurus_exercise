<?php


namespace App;


use Illuminate\Support\Facades\Log;

class WeatherData
{
    const MILES_QOEF = 2.23694;
    const REQUIRED_DATA = [
        'weather' => 'Current weather conditions',
        'main/temp' => 'The current temperature, in celsius',
        'main/feels_like' => '"Feels like” temperature, in celsius',
        'main/humidity' => 'Humidity percentage',
        'main/temp_min' => 'Minimum temperature, in celsius',
        'main/temp_max' => 'Maximum temperature, in celsius',
        'wind/speed' => 'Wind speed, in miles per hour',
        'rain/1h' => 'Rain volume for the last hour, in millimeters',
    ];
    private $weatherData;

    /**
     * WeatherData constructor.
     * @param array $weatherData
     */
    public function __construct(array $weatherData)
    {
        $this->weatherData = $weatherData;
    }

    /**
     * @return array
     */
    public function getFormattedData(): array
    {
        $retArray = [];
        foreach (self::REQUIRED_DATA as $key => $value){
            $retArray[] =[
                'label' => $value,
                'content' =>$this->processField($key),
            ];
        }
        return $retArray;
    }

    private function processField(string $key): string
    {
        $keys = explode('/', $key);
        if(!isset($keys[1])){
            return $this->formatMainWeather($key);
        } else {
            switch ($keys[0]){
                case 'main':
                    return $this->formatBasicInfo($keys[1]);
                case 'wind':
                    return $this->convertToMph(isset($this->weatherData['wind'][$keys[1]]) ? $this->weatherData['wind'][$keys[1]] : 0);
                case 'rain':
                    return (isset($this->weatherData[$keys[1]]) ? $this->weatherData[$keys[1]] : 0);
            }
        }
    }

    private function formatMainWeather(string $key): string
    {
        return (isset($this->weatherData[$key]) ? implode(', ', $this->weatherData[$key][0]) : 'NA');
    }

    private function formatBasicInfo(string $key): string
    {
        return (isset($this->weatherData['main'][$key]) ? $this->weatherData['main'][$key] : 'NA');
    }

    private function convertToMph(float $metres): float
    {
        return $metres * self::MILES_QOEF;
    }

    public static function exportUkCities()
    {
        $retArray = [];
        $jsonData = json_decode(file_get_contents(storage_path() . '/city.list.json'), true);
        if ($jsonData !== false) {
            foreach ($jsonData as $data) {
                if(isset($data['country'])
                    && isset($data['name'])
                    && $data['country'] === 'GB'
                ){
                    $retArray[] = $data['name'];
                }
            }
        }

        echo json_encode($retArray);
        dd('done');
    }
}
