<?php

namespace Drupal\ss_location;

class LocationTour {

  /**
   * Book location tour by tour id.
   */
  public function setLocationTourBook($tid, $lid) {
    \Drupal::database()
      ->update('channela_ss_dashboard.Tours')
      ->fields(['Booked' => 1, 'Lead' => $lid])
      ->condition('Id', $tid, '=')
      ->execute();
  }

  /**
   * Get location tour schedules by location id.
   */
  public function getLocationTourSchedules($lid) {
    $from = date('Y-m-d H:i:s');
    $till = date('Y-m-d H:i:s', strtotime("+3 month"));

    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Tours', 't');
    $query->fields('t', ['Id', 'TimeSlot']);
    $query->condition('t.Location', $lid, '=');
    $query->condition('t.Booked', 0, '=');
    $query->condition('t.TimeSlot', $from, '>=');
    $query->condition('t.TimeSlot', $till, '<');
    $query->orderBy('t.TimeSlot', 'ASC');
    $values = $query->execute()->fetchAll();

    $dates = [];
    foreach ($values as $value) {
      $timestamp = $value->TimeSlot;
      $date = date('Y-m-d', strtotime($timestamp));
      $time = date('H:i', strtotime($timestamp));
      $dates[$date][$value->Id] = $time;
    }

    return $dates;
  }

}