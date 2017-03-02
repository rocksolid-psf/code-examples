<?php

namespace Drupal\ss_location;

class LocationManager {

  /**
   * Get manager info by manager id.
   */
  public function getManagerInfo($mid) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.LocationManagers', 'lm');
    $query->fields('lm', ['FirstName', 'LastName', 'Image', 'Email', 'Telephone']);
    $query->condition('lm.Id', $mid, '=');
    $value = $query->execute()->fetchObject();

    return $value;
  }

}