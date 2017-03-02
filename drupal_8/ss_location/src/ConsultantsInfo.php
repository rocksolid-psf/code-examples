<?php

namespace Drupal\ss_location;

class ConsultantsInfo {

  /**
   * Get consultants info.
   */
  public function getConsultantsInfo() {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Counselors', 'c');
    $query->fields('c', []);
    $values = $query->execute()->fetchAll();

    $consultants = [];
    foreach ($values as $value) {
      $consultants[] = [
        'image' => $value->Image,
        'name' => $value->FirstName . ' ' . $value->LastName,
        'phone' => $value->Telephone,
        'email' => $value->Email
      ];
    }

    return $consultants;
  }

}