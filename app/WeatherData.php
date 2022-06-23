<?php


namespace App;


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

    /**
     * @param string $key
     * @return string
     */
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

    /**
     * Main weather information
     *
     * @param string $key
     * @return string
     */
    private function formatMainWeather(string $key): string
    {
        if (isset($this->weatherData[$key])){
            $data = $this->weatherData[$key][0];
            return $data['main'] . ': ' . $data['description'] . ' ' . '<img src="http://openweathermap.org/img/wn/'.$data['icon'].'@2x.png" width="25px" height="20px">';
        }

        return 'NA';
    }

    /**
     * Basic weather information
     *
     * @param string $key
     * @return string
     */
    private function formatBasicInfo(string $key): string
    {
        return (isset($this->weatherData['main'][$key]) ? $this->weatherData['main'][$key] : 'NA');
    }

    /**
     * Convert speed from m/s to mph
     *
     * @param float $metres
     * @return float
     */
    private function convertToMph(float $metres): float
    {
        return round($metres * self::MILES_QOEF, 2);
    }
}
