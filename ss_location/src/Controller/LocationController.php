<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;

/**
 * Controller for location entity view.
 */
class LocationController extends ControllerBase {

  /**
   * Checks access for a specific request.
   */
  public function access(AccountInterface $account) {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');
    $access = $account->hasPermission('view location entity');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
      $access = $account->hasPermission('view location entity') && ($account->hasPermission('view unpublished location entity') || $location->getStatus());
    }

    return AccessResult::allowedIf($access);
  }

  public function getLocationTitle() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $name = $location->getName();
    $city = $location->getCity();
    $title = [
      "Kinderopvang {$city}",
      $name
    ];

    return implode(' | ', $title);
  }

  public function getLocationServiceKDVTitle() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $name = $location->getName();
    $city = $location->getCity();
    $title = [
      "Kinderdagverblijf {$city}",
      $name
    ];

    return implode(' | ', $title);
  }

  public function getLocationServicePSZTitle() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $name = $location->getName();
    $city = $location->getCity();
    $title = [
      "Peuterspeelzaal {$city}",
      $name
    ];

    return implode(' | ', $title);
  }

  public function getLocationServiceBSOTitle() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $name = $location->getName();
    $city = $location->getCity();
    $title = [
      "BSO {$city}",
      $name
    ];

    return implode(' | ', $title);
  }

  public function getLocationOurLocationTitle() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location_id = $params->get('ss_location');

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $name = $location->getName();
    $title = [
      "Kinderopvang {$name}"
    ];

    return implode(' | ', $title);
  }

  public function overviewPage() {
    $query = \Drupal::entityQuery('ss_location');
    $entity_ids = $query->execute();
    $locations = \Drupal::entityTypeManager()->getStorage('ss_location')->loadMultiple($entity_ids);

    $locations_info = [];
    foreach ($locations as $location) {
      if ($location->getStatus() == 1) {
        $services = [];
        if ($location->getServiceKDV() == 1) {
          $services['KDV'] = LOCATION_SERVICES_NAMES['KDV'];
        }
        if ($location->getServicePSZ() == 1) {
          $services['PSZ'] = LOCATION_SERVICES_NAMES['PSZ'];
        }
        if ($location->getServiceBSO() == 1) {
          $services['BSO'] = LOCATION_SERVICES_NAMES['BSO'];
        }

        $locations_info[$location->getName() . $location->getCity() . $location->getId()] = [
          'link' => Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $location->getPath()])->toString(),
          'title' => t('Smallsteps @name', ['@name' => $location->getName()]),
          'name' => t('Smallsteps @name - @street - @postcode - @city', ['@name' => $location->getName(), '@street' => $location->getStreetAddress(), '@postcode' => $location->getPostCode(), '@city' => $location->getCity()]),
          'services' => t('Diensten: @services', ['@services' => implode(', ', $services)])
        ];
      }
    }

    ksort($locations_info);

    return [
      '#theme' => 'ss_location_overview_page',
      '#locations' => $locations_info
    ];
  }
}