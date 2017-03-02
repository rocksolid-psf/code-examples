<?php

namespace Drupal\ss_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'Location Registration Thank You' block.
 *
 * @Block(
 *   id = "ss_location_registration_thank_you",
 *   admin_label = @Translation("Location Registration Thank You"),
 *   category = @Translation("Smallsteps")
 * )
 */
class RegistrationThankYou extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $params = \Drupal::routeMatch()->getRawParameters();
    $location = $params->get('ss_location');

    $child_name = [
      $_SESSION['location_reg_data']['ChildNameFirst'],
      $_SESSION['location_reg_data']['ChildNameLast'],
    ];
    $child_name = implode(' ', $child_name);
    $child_name = trim($child_name);
    $title = t('De aanmelding voor @child_name is verstuurd', ['@child_name' => $child_name]);
    $text = gc('LocationFormRegistrationThankYouText');
    $link = Link::fromTextAndUrl(t('Afsluiten'), Url::fromRoute('entity.ss_location.canonical', ['ss_location' => $location]));

    if (isset($_GET['additional']) && $_GET['additional'] == 1 && isset($_SESSION['location_reg_data']['ChildNameFirst']) && $location) {
      $text = gc('LocationFormRegistrationThankYouAddChildText');
      $link = Link::fromTextAndUrl(t('Volgende kind aanmelden'), Url::fromRoute('entity.ss_location.registration', ['ss_location' => $location], ['query' => ['additional' => 1]]));
    }

    $build = [
      'title' => $title,
      'text' => $text,
      'button' => $link,
    ];

    return $build;
  }
}