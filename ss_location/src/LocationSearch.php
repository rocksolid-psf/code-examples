<?php

namespace Drupal\ss_location;

use Drupal\Core\Link;
use Drupal\Core\Url;

class LocationSearch {

  /**
   * Search location by name.
   * @param string $name - location name
   * @return array with found locations
   */
  public function getLocationByName($name) {
    $account = \Drupal::currentUser();

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', ['Permalink']);
    $query->addField('l', 'Name', 'Location');
    $query->addField('c', 'Name', 'City');
    if (isset($name)) {
      $or_condition = $query->orConditionGroup();
      $or_condition->condition('l.Name', $query->escapeLike($name) . '%', 'LIKE');

      $prefixes = ['het', '\'t', 'de', 'een'];
      foreach ($prefixes as $prefix) {
        $or_condition->condition('l.Name', $query->escapeLike($prefix . ' ' . $name) . '%', 'LIKE');
      }

      $query->condition($or_condition);
    }
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    $query->orderBy('l.Name', 'ASC');
    $query->orderBy('c.Name', 'ASC');
    $query->range(0, 20);
    $results = $query->execute()->fetchAll();

    return $this->formatResults($results);
  }

  /**
   * Search location by name.
   * @param string $address - location name
   * @return array with found locations
   */
  public function getLocationByAddress($address) {
    $search_results = [];

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Postcodes', 'p');
    $query->fields('p', ['Id']);
    $query->condition('p.Id', $query->escapeLike($address) . '%', 'LIKE');
    $query->orderBy('p.Id', 'ASC');
    $query->range(0, 20);
    $results = $query->execute()->fetchCol();

    foreach ($results as $result) {
      $search_results[] = ['value' => $result];
    }

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Cities', 'c');
    $query->leftJoin('channela_ss_dashboard.Provinces', 'p', 'p.Id = c.Province');
    $query->addField('c', 'Name', 'city');
    $query->addField('p', 'Name', 'province');
    $query->condition('c.Name', $query->escapeLike($address) . '%', 'LIKE');
    $query->orderBy('c.Name', 'ASC');
    $query->orderBy('p.Name', 'ASC');
    $query->range(0, 20);
    $results = $query->execute()->fetchAll();

    foreach ($results as $result) {
      $search_results[] = ['value' => $result->city . ', ' . $result->province];
    }

    return $search_results;
  }

  private function formatResults($results) {
    $location_list = [];
    foreach ($results as $result) {
      $location_list[] = [
        'value' => $result->Location . ' - ' . $result->City,
        'id' => $result->Permalink,
        'url' => Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $result->Permalink])->toString(),
        'tour_url' => Url::fromRoute('entity.ss_location.tour', ['ss_location' => $result->Permalink])->toString(),
        'registration_url' => Url::fromRoute('entity.ss_location.registration', ['ss_location' => $result->Permalink])->toString(),
        'contact_url' => Url::fromRoute('entity.ss_location.contact', ['location' => $result->Permalink], ['fragment' => 'contact'])->toString(),
        'existing_customers_url' => Url::fromRoute('entity.ss_location.existingcustomers', ['location' => $result->Permalink])->toString(),
      ];
    }

    return $location_list;
  }

  public function getNearestLocationsInfo($lat, $lng, $services, $page) {
    $account = \Drupal::currentUser();

    $range = 5;
    $page = isset($page) ? $page : 0;
    $from = $page == 0 ? 0 : $page*$range+1;
    $count = $page == 0 ? $range+1 : $range;

    $location_fields = [
      'Id',
      'Name',
      'Permalink',
      'StreetAddress',
      'Postcode',
      'Telephone',
      'ServiceKDV',
      'ServiceBSO',
      'ServicePSZ',
      'Latitude',
      'Longitude'
    ];

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', $location_fields);
    $query->addField('c', 'Name', 'City');
    $query->addExpression(
      'ROUND( 6371 * acos(cos(radians(:lat)) * cos(radians(l.Latitude)) * cos(radians(l.Longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(l.Latitude))), 1 )',
      'distance',
      array(':lat' => $lat, ':lng' => $lng)
    );
    $query->condition('l.Status', 1, '=');
    if (count($services) > 0) {
      $or_condition = $query->orConditionGroup();
      foreach ($services as $service) {
        $or_condition->condition("l.Service$service", 1, '=');
      }
      $query->condition($or_condition);
    }
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    $query->orderBy('distance', 'ASC');
    $query->range($from, $count);
    $result = $query->execute()->fetchAll();

    return $result;
  }

  public function getNearestLocationsList($lat, $lng, $services) {
    $account = \Drupal::currentUser();

    $range = 10;

    $location_fields = [
      'Id',
      'Name',
      'Permalink'
    ];

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', $location_fields);
    $query->addField('c', 'Name', 'City');
    $query->addExpression(
      'ROUND( 6371 * acos(cos(radians(:lat)) * cos(radians(l.Latitude)) * cos(radians(l.Longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(l.Latitude))), 1 )',
      'distance',
      array(':lat' => $lat, ':lng' => $lng)
    );
    $query->condition('l.Status', 1, '=');
    if (count($services) > 0) {
      $or_condition = $query->orConditionGroup();
      foreach ($services as $service) {
        $or_condition->condition("l.Service$service", 1, '=');
      }
      $query->condition($or_condition);
    }
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    $query->orderBy('distance', 'ASC');
    $query->range(0, $range);
    $results = $query->execute()->fetchAll();

    $list = [];
    foreach ($results as $result) {
      $list[] = [
        'value' => $result->Id,
        'label' => $result->City . ' - ' . $result->Name . ' - ' . $result->distance . ' km'
      ];
    }

    return $list;
  }

  public function getLocationsListSimple() {
    $account = \Drupal::currentUser();

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', ['Id', 'Name']);
    $query->addField('c', 'Name', 'City');
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    $query->orderBy('l.Name', 'ASC');
    $query->orderBy('c.Name', 'ASC');
    $results = $query->execute()->fetchAllAssoc('Id');

    $list = [];
    foreach ($results as $id => $result) {
      $list[$id] = $result->Name . ' - ' . $result->City;
    }

    return $list;
  }

  public function getLocationsCityList($campaign) {
    $account = \Drupal::currentUser();

    $services = ss_location_get_campaign_services($campaign);

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', ['Id', 'Name']);
    $query->addField('c', 'Name', 'City');
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    if (count($services) > 0) {
      $or_condition = $query->orConditionGroup();
      foreach ($services as $service) {
        $or_condition->condition("l.Service$service", 1, '=');
      }
      $query->condition($or_condition);
    }
    $query->orderBy('c.Name', 'ASC');
    $query->orderBy('l.Name', 'ASC');
    $results = $query->execute()->fetchAllAssoc('Id');

    $list = [];
    foreach ($results as $id => $result) {
      $list[$id] = $result->City . ' - ' . $result->Name;
    }

    return $list;
  }

  public function getLocationsList($services = [], $lat = 0, $lng = 0) {
    $account = \Drupal::currentUser();

    global $base_url;

    $icon = $base_url . '/' . \Drupal::theme()->getActiveTheme()->getPath() . '/images/search-pin.png';

    $location_fields = [
      'Id',
      'Name',
      'Permalink',
      'StreetAddress',
      'Postcode',
      'Latitude',
      'Longitude'
    ];

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Locations', 'l');
    $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
    $query->fields('l', $location_fields);
    $query->addField('c', 'Name', 'City');
    $query->condition('l.Status', 1, '=');
    $query->condition('l.Latitude', 0, '<>');
    $query->condition('l.Longitude', 0, '<>');
    if (!$account->hasPermission('view unpublished location entity')) {
      $query->condition('l.StatusSite', 1);
    }
    if (count($services) > 0) {
      $or_condition = $query->orConditionGroup();
      foreach ($services as $service) {
        $or_condition->condition("l.Service$service", 1, '=');
      }
      $query->condition($or_condition);
    }
    if ($lat && $lng) {
      $query->addExpression(
        'ROUND( 6371 * acos(cos(radians(:lat)) * cos(radians(l.Latitude)) * cos(radians(l.Longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(l.Latitude))), 1 )',
        'distance',
        array(':lat' => $lat, ':lng' => $lng)
      );
      $query->orderBy('distance', 'ASC');
    }
    $results = $query->execute()->fetchAllAssoc('Id');

    $map_data = [];
    foreach ($results as $result) {
      $link = Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $result->Permalink])->toString();

      $info = [
        '<strong>' . t('Smallsteps') . ' ' . $result->Name . '</strong>',
        $result->StreetAddress,
        $result->Postcode . ' ' . $result->City,
        '<a target="_blank" href="' . $link . '">' . t('ontdek deze locatie') . '</a>'
      ];

      $map_data[] = [
        'lat' => $result->Latitude,
        'lng' => $result->Longitude,
        'info' => sprintf(implode('<br />', $info)),
        'icon' => $icon
      ];

      $icon = $base_url . '/' . \Drupal::theme()->getActiveTheme()->getPath() . '/images/search-pin-other.png';
    }

    return $map_data;
  }

  public function getLocationBySearch($search, $type, $services = []) {
    $account = \Drupal::currentUser();

    $result = NULL;
    $args = explode(' - ', $search);

    if (count($args) == 2) {
      $name = trim($args[0]);
      $address = trim($args[1]);

      $query = \Drupal::database()
        ->select('channela_ss_dashboard.Locations', 'l');
      $query->leftJoin('channela_ss_dashboard.Cities', 'c', 'c.Id = l.City');
      $query->fields('l', ['Permalink', 'Latitude', 'Longitude']);
      $or_condition = $query->orConditionGroup()
        ->condition('l.Postcode', $query->escapeLike($address), 'LIKE')
        ->condition('c.Name', $query->escapeLike($address), 'LIKE');
      $query->condition($or_condition);
      if (!$account->hasPermission('view unpublished location entity')) {
        $query->condition('l.StatusSite', 1);
      }
      if (count($services) > 0) {
        $or_condition = $query->orConditionGroup();
        foreach ($services as $service) {
          $or_condition->condition("l.Service$service", 1, '=');
        }
        $query->condition($or_condition);
      }
      $query->condition('l.Name', $query->escapeLike($name), 'LIKE');
      $location = $query->execute()->fetchAll();
      $result = array_pop($location);
      $result = $result ? (array)$result : NULL;
    }
    elseif ($type == 'locatieadres') {
      $address = trim($search);
      if (preg_match('/^\d{4}/', $address)) {
        $address = urldecode(preg_replace('/^(\d{4})\s*?(\w{2})(.*)/', '$1 $2', strtoupper($address)));

        \Drupal::database()
          ->update('channela_ss_dashboard.Postcodes')
          ->expression('Hits', 'Hits + 1')
          ->condition('Id', $address, 'LIKE')
          ->execute();

        $query = \Drupal::database()
          ->select('channela_ss_dashboard.Postcodes', 'p');
        $query->fields('p', ['Latitude', 'Longitude']);
        $query->condition('p.Id', $query->escapeLike($address), 'LIKE');
        $postcode = $query->execute()->fetchAll();

        $result = array_pop($postcode);
        $result = $result ? (array)$result : NULL;
      }
      elseif (stripos($address, ',') !== FALSE) {
        list($city, $province) = explode(',', $address);
        $city = trim($city);
        $province = trim($province);
        $query = \Drupal::database()
          ->select('channela_ss_dashboard.Cities', 'c');
        $query->leftJoin('channela_ss_dashboard.Provinces', 'p', 'p.Id = c.Province');
        $query->fields('c', ['Id', 'Latitude', 'Longitude']);
        $query->condition('c.Name', $query->escapeLike($city), 'LIKE');
        $query->condition('p.Name', $query->escapeLike($province), 'LIKE');
        $found_city = $query->execute()->fetchAll();
        $result = array_pop($found_city);
        $result = $result ? (array)$result : NULL;

        if ($result && $result['Latitude'] == 0 && $result['Longitude'] == 0) {
          $request = ss_location_remote_request('http://maps.googleapis.com/maps/api/geocode/json?sensor=false&region=nl&address=' . urlencode("$city, $province, The Netherlands"));
          $response = json_decode($request, TRUE);

          if (is_array($response) && $response['status'] == 'OK' && is_array($response['results'])) {
            $responseresult = reset($response['results']);

            \Drupal::database()
              ->update('channela_ss_dashboard.Cities')
              ->fields([
                'Latitude' => $responseresult['geometry']['location']['lat'],
                'Longitude' => $responseresult['geometry']['location']['lng']
              ])
              ->condition('Id', $result['Id'], '=')
              ->execute();

            $result['Latitude'] = $responseresult['geometry']['location']['lat'];
            $result['Longitude'] = $responseresult['geometry']['location']['lng'];
          }
        }
      }
      else {
        $query = \Drupal::database()
          ->select('channela_ss_dashboard.Cities', 'c');
        $query->fields('c', ['Latitude', 'Longitude']);
        $query->condition('c.Name', $query->escapeLike($address), 'LIKE');
        $city = $query->execute()->fetchAll();
        $result = array_pop($city);
        $result = $result ? (array)$result : NULL;
      }
    }
    elseif ($type == 'locatienaam') {
      $name = trim($search);

      $query = \Drupal::database()
        ->select('channela_ss_dashboard.Locations', 'l');
      $query->fields('l', ['Permalink', 'Latitude', 'Longitude']);
      $query->condition('l.Name', $query->escapeLike($name), 'LIKE');
      if (count($services) > 0) {
        $or_condition = $query->orConditionGroup();
        foreach ($services as $service) {
          $or_condition->condition("l.Service$service", 1, '=');
        }
        $query->condition($or_condition);
      }
      if (!$account->hasPermission('view unpublished location entity')) {
        $query->condition('l.StatusSite', 1);
      }
      $location = $query->execute()->fetchAll();
      $result = array_pop($location);
      $result = $result ? (array)$result : NULL;
    }

    return $result;
  }
}
