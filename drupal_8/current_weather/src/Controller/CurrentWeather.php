<?php

namespace Drupal\current_weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\current_weather\Services\CurrentWeatherApi;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CurrentWeather.
 */
class CurrentWeather extends ControllerBase {

  /**
   * Current Weather service.
   *
   * @var \Drupal\current_weather\Services\CurrentWeatherApi
   */
  protected $currentWeatherApi;

  /**
   * Class constructor.
   *
   * @param \Drupal\current_weather\Services\CurrentWeatherApi $currentWeatherApi
   *   Current Weather service.
   */
  public function __construct(CurrentWeatherApi $currentWeatherApi) {
    $this->currentWeatherApi = $currentWeatherApi;
  }

  /**
   * Create.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container.
   *
   * @return \Drupal\current_weather\Controller\CurrentWeather
   *   Return service.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_weather.api')
    );
  }

  /**
   * Return current weather.
   */
  public function currentWeather() {
    $weather = $this->currentWeatherApi->getDefaultCurrentWeather();
    return [
      '#theme' => 'weather_template',
      '#rows' => $weather,
    ];
  }

  /**
   * Return current weather.
   *
   * @param string $city
   *   City name from URL.
   *
   * @return array
   *   Return template.
   */
  public function currentWeatherByCity($city) {
    $weather = $this->currentWeatherApi->getCurrentWeatherByCity($city);
    return [
      '#theme' => 'weather_city_template',
      '#rows' => $weather,
    ];
  }

  /**
   * Return current weather.
   *
   * @param string $city
   *   City name from URL.
   * @param string $country_code
   *   Country code from URL.
   *
   * @return array
   *   Return template.
   */
  public function currentWeatherByCityAndCountryCode($city, $country_code) {
    $weather = $this->currentWeatherApi->getCurrentWeatherByCityAndCountryCode($city, $country_code);
    return [
      '#theme' => 'weather_city_country_template',
      '#rows' => $weather,
    ];
  }

}
