<?php

namespace Drupal\ss_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'Location Tour Thank You' block.
 *
 * @Block(
 *   id = "ss_location_tour_thank_you",
 *   admin_label = @Translation("Location Tour Thank You"),
 *   category = @Translation("Smallsteps")
 * )
 */
class TourThankYou extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $title = t('Rondleidingformulier');

    $params = \Drupal::routeMatch()->getRawParameters();
    $location = $params->get('ss_location');

    $link = NULL;
    if ($location) {
      $link = Link::fromTextAndUrl(t('Afsluiten'), Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $location]));
    }

    $build = [
      'title' => $title,
      'text' => gc('LocationFormTourThankYouText'),
      'button' => $link,
    ];

    return $build;
  }
}