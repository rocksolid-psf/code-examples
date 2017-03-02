<?php

namespace Drupal\ss_location;

class LocationFacebookPage {

  /**
   * Get facebook page info by location SocialFacebookId.
   */
  public function getFacebookPage($fid) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.FacebookPages', 'fp');
    $query->fields('fp', ['RatingOverall', 'RatingCount']);
    $or_condition = $query->orConditionGroup()
      ->condition('fp.Id', $fid, '=')
      ->condition('fp.Username', $fid, '=');
    $query->condition($or_condition);
    $value = $query->execute()->fetchAssoc();

    return $value;
  }

}