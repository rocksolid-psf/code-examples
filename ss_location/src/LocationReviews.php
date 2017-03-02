<?php

namespace Drupal\ss_location;

class LocationReviews {

  /**
   * Get location ad words.
   */
  public function getLocationReviews($limit = 3) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Reviews', 'r');
    $query->fields('r', []);
    $query->range(0, $limit);
    $results = $query->execute()->fetchAll();

    return $results;
  }
}