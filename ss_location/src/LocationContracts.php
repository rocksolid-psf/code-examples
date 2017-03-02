<?php

namespace Drupal\ss_location;

class LocationContracts {

  /**
   * Get contracts by location id.
   */
  public function getContracts($lid, $limit = 0) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Prices', 'c');
    $query->innerJoin('channela_ss_dashboard.CostCenters', 'cc', 'cc.Id = c.LocationCostCenter');
    $query->fields('c', ['ContractPackageName', 'ContractPackageText']);
    $query->groupBy('c.ContractPackageName, c.ContractPackageText, c.ContractPackageOrder');
    $query->condition('cc.Location', $lid, '=');
    $query->condition('c.ContractStatus', 1, '=');
    if ($limit > 0) {
      $query->range(0, $limit);
    }
    $query->orderBy('c.ContractPackageOrder', 'ASC');
    $value = $query->execute()->fetchAll();

    return $value;
  }

  /**
   * Get service contracts by location id and service name.
   */
  public function getServiceContracts($lid, $service) {
    $query = \Drupal::database()
      ->select('channela_ss_dashboard.Prices', 'c');
    $query->innerJoin('channela_ss_dashboard.CostCenters', 'cc', 'cc.Id = c.LocationCostCenter');
    $query->fields('c', ['ContractPackageName', 'PriceFinal', 'ContractCode']);
    $query->condition('cc.Location', $lid, '=');
    $query->condition('c.LocationService', $service, '=');
    $query->condition('c.PriceStatus', 1, '=');
    $query->orderBy('c.ContractPackageOrder', 'ASC');
    $value = $query->execute()->fetchAll();

    return $value;
  }

}