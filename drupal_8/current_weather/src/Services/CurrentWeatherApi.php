<?php

namespace Drupal\current_weather\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Logger\LoggerChannelFactory;

/**
 * Class CurrentWeatherApi.
 */
class CurrentWeatherApi {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * API endpoint.
   *
   * @var array|\Drupal\Core\Config\ImmutableConfig|null
   */
  private $endpoint;

  /**
   * API key.
   *
   * @var array|\Drupal\Core\Config\ImmutableConfig|null
   */
  private $apiKey;

  /**
   * Constructs a CurrentWeatherApi object.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The client.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   Logger Factory.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config Factory.
   */
  public function __construct(ClientInterface $client, LoggerChannelFactory $loggerFactory, ConfigFactoryInterface $config_factory) {
    $this->httpClient = $client;
    $this->loggerFactory = $loggerFactory->get('current_weather');
    $this->config = $config_factory->get('current_weather.config');
    $this->apiKey = $this->config->get('api');
    $this->endpoint = $this->config->get('endpoint');
  }

  /**
   * Provides getting current weather for default location.
   *
   * @param string $city_name
   *   City name.
   * @param string $country_code
   *   Country code.
   *
   * @return array
   *   Return array with weather.
   */
  public function getCurrentWeather($city_name, $country_code = NULL) {
    if (!empty($city_name)) {
      if (!empty($country_code)) {
        $q = $city_name . ',' . $country_code;
      }
      else {
        $q = $city_name;
      }
      try {
        $base_url = $this->endpoint . '/data/2.5/weather?q=' . $q . "&units=metric&appid=" . $this->apiKey;
        $request = $this->httpClient->get($base_url)->getBody();
        $weather = json_decode($request->getContents(), TRUE);
        return $weather;
      }
      catch (RequestException $e) {
        $this->loggerFactory->error($e);
        $weather = [
          'status' => FALSE,
          'data' => $e->getMessage(),
        ];
        return $weather;
      }
    }
    else {
      $error[] = $this->loggerFactory->error('City name not found');
      return $error;
    }
  }

  /**
   * Provides getting current weather for default location.
   *
   * @return array
   *   Return array with weather.
   */
  public function getDefaultCurrentWeather() {
    $default_city_name = '';
    $default_country_code = '';
    if ($this->config) {
      $default_city_name = $this->config->get('default_city_name');
      $default_country_code = $this->config->get('default_country_code');
    }
    $weather = $this->getCurrentWeather($default_city_name, $default_country_code);
    return $weather;
  }

  /**
   * Provides getting current weather for default location.
   *
   * @param string $city
   *   City name.
   *
   * @return array
   *   Return array with weather.
   */
  public function getCurrentWeatherByCity($city) {
    $weather = $this->getCurrentWeather($city);
    return $weather;
  }

  /**
   * Provides getting current weather for default location.
   *
   * @param string $city
   *   City name.
   * @param string $country_code
   *   Country code.
   *
   * @return array
   *   Return array with weather.
   */
  public function getCurrentWeatherByCityAndCountryCode($city, $country_code) {
    $weather = $this->getCurrentWeather($city, $country_code);
    return $weather;
  }

}
