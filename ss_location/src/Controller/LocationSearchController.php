<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller routines for location search autocomplete.
 */
class LocationSearchController extends ControllerBase {

  public function searchPage($province, $city) {
    $search = NULL;
    $type = NULL;

    if ($city && $province) {
      $search = "$city, $province";
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Postcode'])) {
      $search = $_GET['Postcode'];
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Name'])) {
      $search = $_GET['Name'];
      $type = 'locatienaam';
    }
    $title = t('Zoek je locatie');
    $button = t('Ontdek deze locatie');
    return $this->searchPageContent($title, $button, $search, $type, NULL, 'search', $city);
  }

  public function searchTourPage($province, $city) {
    $search = NULL;
    $type = NULL;

    if ($city && $province) {
      $search = "$city, $province";
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Postcode'])) {
      $search = $_GET['Postcode'];
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Name'])) {
      $search = $_GET['Name'];
      $type = 'locatienaam';
    }
    $title = t('Waar wil je een rondleiding?');
    $button = t('Rondleiding aanvragen');
    return $this->searchPageContent($title, $button, $search, $type, 'tour', 'searchtour', $city);
  }

  public function searchRegistrationPage($province, $city) {
    $search = NULL;
    $type = NULL;

    $type = 'locatienaam';
    if ($city && $province) {
      $search = "$city, $province";
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Postcode'])) {
      $search = $_GET['Postcode'];
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Name'])) {
      $search = $_GET['Name'];
      $type = 'locatienaam';
    }
    $title = t('Waar wil je je inschrijven?');
    $button = t('Meteen inschrijven');
    return $this->searchPageContent($title, $button, $search, $type, 'registration', 'searchregistration', $city);
  }

  private function searchPageContent($title, $button, $search, $type, $route_name = NULL, $page = 'search', $city = NULL) {
    $ad_words = NULL;

    $breadcrumbs = [
      Link::fromTextAndUrl(t('Home'), Url::fromRoute('<front>')),
      $title
    ];

    if (isset($_GET['Content']) || isset($_GET['Campaign'])) {
      $content = isset($_GET['Content']) ? $_GET['Content'] : 1;
      $breadcrumbs = [];
      $ad_words_info = ss_location_adwords($content);

      if ($ad_words_info) {
        $reviews = ss_location_get_location_reviews();

        if (isset($_GET['KDV']) && $_GET['KDV']) {
          $service = 'een kinderdagverblijf';
        }
        elseif (isset($_GET['PSZ']) && $_GET['PSZ']) {
          $service = 'een peuterspeelzaal';
        }
        elseif (isset($_GET['BSO']) && $_GET['BSO']) {
          $service = 'buitenschoolse opvang';
        }
        else {
          $service = 'kinderopvang';
        }

        $ad_words = [
          '#theme' => 'ss_location_search_adwords',
          '#background' => $ad_words_info['Image1'],
          '#banner_title' => t("Op zoek naar @service in @city?", ['@service' => $service, '@city' => ucfirst($city)]),
          '#banner_text' => $ad_words_info['Text1'],
          '#text' => $ad_words_info['Text2'],
          '#reviews' => []
        ];

        foreach ($reviews as $review) {
          $x = round(@$review->Rating * 2) / 2;
          $stars = [];
          for ($i = 1; $i <= 5; $i++) {
            $stars[] = ($x >= $i) ? 'full' : (($x == $i - 0.5) ? 'half' : 'empty');
          }

          $ad_words['#reviews'][] = [
            'image' => $review->Image,
            'stars' => $stars,
            'text' => $review->Text
          ];
        }
      }
    }

    $services = [];
    if (isset($_GET['KDV']) && $_GET['KDV']) {
      $services[] = 'KDV';
    }
    if (isset($_GET['PSZ']) && $_GET['PSZ']) {
      $services[] = 'PSZ';
    }
    if (isset($_GET['BSO']) && $_GET['BSO']) {
      $services[] = 'BSO';
    }
    $location_id = NULL;
    $attached = [];
    $attached['library'][] = 'ss_common/search';
    $attached['drupalSettings']['ss_location']['search']['locations'] = [];
    $attached['drupalSettings']['ss_location']['search']['center'] = [];

    if (isset($_SERVER['HTTP_REFERER']) && !$route_name) {
      $url = parse_url($_SERVER['HTTP_REFERER']);
      $url_object = \Drupal::service('path.validator')->getUrlIfValid($url['path']);

      if (isset($_SERVER['HTTP_REFERER'])) {
        $url = parse_url($_SERVER['HTTP_REFERER']);

        if ($url['path'] == '/kinderdagverblijf' && !in_array('KDV', $services)) {
          $services[] = 'KDV';
        }

        if ($url['path'] == '/peuterspeelzaal' && !in_array('PSZ', $services)) {
          $services[] = 'PSZ';
        }

        if ($url['path'] == '/buitenschoolse-opvang' && !in_array('BSO', $services)) {
          $services[] = 'BSO';
        }
      }

      if ($url_object) {
        $route_name = $url_object->getRouteName();
      }
    }

    switch ($route_name) {
      case 'entity.ss_location.registration':
      case 'registration':
        $referer = 'registration';
        break;
      case 'entity.ss_location.tour':
      case 'tour':
        $referer = 'tour';
        break;
      case 'entity.ss_location.contact':
        $referer = 'contact';
        break;
      default:
        $referer = 'canonical';
        break;
    }

    if (isset($_GET['Content']) || isset($_GET['Campaign'])) {
      $referer = 'tour';
    }

      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
    $last_location_id = ss_location_get_last_location();
    $last_location_link = NULL;
    if ($last_location_id) {
      $location = $location_storage->load($last_location_id);

      if ($location) {
        $last_location_link = Link::fromTextAndUrl($location->getName(), Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $location->getPath()]));
      }
    }

    $switcher_title = ($type == 'locatienaam') ? t('zoek op plaats of postcode') : t('zoek op locatienaam');
    $switcher_action = ($type == 'locatienaam') ? 'locatieadres' : 'locatienaam';

    $results = [];
    $count = 0;
    if (isset($search, $type)) {
      $result = ss_location_search_coords($search, $type, $services);
      $attached['drupalSettings']['ss_location']['search']['locations'] = ss_location_get_location_list($services, $result['Latitude'], $result['Longitude']);
      $count = count($attached['drupalSettings']['ss_location']['search']['locations']);
      $attached['drupalSettings']['ss_location']['search']['button'] = $button;
      $attached['drupalSettings']['ss_location']['search']['Latitude'] = $result['Latitude'];
      $attached['drupalSettings']['ss_location']['search']['Longitude'] = $result['Longitude'];
      $attached['drupalSettings']['ss_location']['search']['referer'] = $referer;
      $attached['drupalSettings']['ss_location']['search']['center']['Latitude'] = $attached['drupalSettings']['ss_location']['search']['locations'][0]['lat'];
      $attached['drupalSettings']['ss_location']['search']['center']['Longitude'] = $attached['drupalSettings']['ss_location']['search']['locations'][0]['lng'];
      $attached['drupalSettings']['ss_location']['search']['center']['Zoom'] = 12;
      $attached['drupalSettings']['ss_location']['search']['center']['services'] = $services;
      $attached['drupalSettings']['ss_location']['search']['center']['count'] = $count;
      $results = $this->nearest($result['Latitude'], $result['Longitude'], $services, $referer, $button, 0, TRUE);
    }

    $form = \Drupal::formBuilder()
      ->getForm('Drupal\ss_location\Form\LocationSearchForm', $referer, $page);

    return [
      '#attached' => $attached,
      '#theme' => 'ss_location_search',
      '#adwords' => $ad_words,
      '#breadcrumbs' => $breadcrumbs,
      '#title' => $title,
      '#form' => render($form),
      '#last_location_label' => t('Laatst bezocht:'),
      '#last_location_link' => $last_location_link,
      '#switcher_title' => $switcher_title,
      '#switcher_action' => $switcher_action,
      '#results' => $results,
      '#count' => $count
    ];
  }

  public function autocomplete() {
    if (!isset($_POST['search'], $_POST['type'])) {
      throw new AccessDeniedHttpException();
    }

    $results = [];

    if ($_POST['type'] == 'locatieadres') {
      $results = ss_location_search_by_address($_POST['search']);
    }

    if ($_POST['type'] == 'locatienaam') {
      $results = ss_location_search_by_name($_POST['search']);
    }

    exit(json_encode($results));
  }

  public function nearest($latitude = NULL, $longitude = NULL, $services = [], $referer = 'canonical', $button = 'Ontdek deze locatie', $page = 0, $return = FALSE) {
    $page = isset($_GET['page']) ? $_GET['page'] : $page;
    $latitude = isset($_GET['Latitude']) ? $_GET['Latitude'] : $latitude;
    $longitude = isset($_GET['Longitude']) ? $_GET['Longitude'] : $longitude;
    $services = isset($_GET['services']) ? $_GET['services'] : $services;
    $referer = isset($_GET['referer']) ? $_GET['referer'] : $referer;
    $button = isset($_GET['button']) ? $_GET['button'] : $button;

    $results = [];
    if ($latitude && $longitude) {
      $results = ss_location_order_by_nearest($latitude, $longitude, $services, $page);
    }

    foreach ($results as &$result) {
      if ($result->distance < 1) {
        $distance = $result->distance * 1000;
        $result->distance_formated = $distance . ' m';
      }
      else {
        $distance = $result->distance;
        $result->distance_formated = $distance . ' km';
      }
      if ($result->ServiceKDV == 1) {
        $result->services[] = LOCATION_SERVICES_NAMES['KDV'];
      }
      if ($result->ServiceBSO == 1) {
        $result->services[] = LOCATION_SERVICES_NAMES['BSO'];
      }
      if ($result->ServicePSZ == 1) {
        $result->services[] = LOCATION_SERVICES_NAMES['PSZ'];
      }
      $loc = $referer == 'contact' ? 'location' : 'ss_location';
      $result->link_title = $button;
      $result->link = Url::fromRoute("entity.ss_location.$referer", [$loc => $result->Permalink])->toString();
      if ($referer == 'canonical') {
        $result->link_tour = Url::fromRoute("entity.ss_location.tour", [$loc => $result->Permalink])
          ->toString();
      }
    }

    $nearest = $page == 0 ? @$results[0] : NULL;

    $build = [];
    if (count($results) > 0) {
      $build = [
        '#theme' => 'ss_location_search_results',
        '#page' => $page,
        '#nearest' => $nearest,
        '#results' => $results
      ];
    }

    if ($return) {
      return $build;
    }
    else {

      $results = \Drupal::service('renderer')->renderPlain($build);
      exit($results);
    }
  }

  public function nearestGenerate() {
    if (!isset($_POST['postcode'], $_POST['house_number'])) {
      throw new AccessDeniedHttpException();
    }

    $response = ss_location_remote_request("https://api.postcode.nl/rest/addresses/{$_POST['postcode']}/{$_POST['house_number']}", false, 'ZeDDm3I6fyNRybNL83J5lyYkOiQNmczIKYEpTJC2S6E:NTKsIgZkCNyZ2kyqZW1OXuS2vmSW9JKExR27KzPltKTttPLlqA');
    $response_decoded = json_decode($response);
    if (isset($response_decoded->exception)) {
      exit($response);
    }
    $latitude = $response_decoded->latitude;
    $longitude = $response_decoded->longitude;
    $services = ss_location_get_campaign_services($_POST['campaign']);
    $results = ss_location_nearest_locations_list($latitude, $longitude, $services);
    $results = json_encode($results);

    exit($results);
  }
}
