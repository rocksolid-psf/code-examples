<?php

namespace Drupal\ss_location;

use Drupal\Component\Utility\NestedArray;
use Drupal\views\EntityViewsData;

/**
 * Provides a view to override views data for test entity types.
 */
class LocationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $views_data = parent::getViewsData();

    if ($this->entityType->id() != 'ss_location') {
      return $views_data;
    }

    $views_data = NestedArray::mergeDeep($views_data, \Drupal::state()->get('ss_location.views_data', []));

    return $views_data;
  }

}
