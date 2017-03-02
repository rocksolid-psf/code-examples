<?php

namespace Drupal\ss_location;

class CampaignsInfo {

  /**
   * Get campaigns list.
   */
  public function getCampaigns() {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Campaigns', 'c');
    $query->fields('c', ['Id', 'Name']);
    $query->condition('c.Status', 1, '=');
    $query->orderBy('c.Id', 'ASC');
    $values = $query->execute()->fetchAll();

    $campaigns = [];
    foreach ($values as $value) {
      $campaigns[$value->Id] = $value->Name;
    }

    return $campaigns;
  }

  /**
   * Get campaign services list.
   */
  public function getCampaignServices($cid) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.CampaignsRel', 'cr');
    $query->leftJoin('channela_ss_dashboard.Services', 's', 's.Id = cr.Rel');
    $query->fields('s', ['Name']);
    $query->condition('cr.Campaign', $cid, '=');
    $query->condition('cr.Type', 1);
    $services = $query->execute()->fetchCol();

    return $services;
  }
}