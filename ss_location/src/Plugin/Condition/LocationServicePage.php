<?php

/**
 * @file
 * Contains \Drupal\ss_location\Plugin\Condition\LocationServicePage.
 */

namespace Drupal\ss_location\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides access to the location service page based on neighbourhood and service condition.
 *
 * @Condition(
 *   id = "location_service_page",
 *   label = @Translation("Location Service Page"),
 *   context = {
 *     "ss_location" = @ContextDefinition("entity:ss_location", required = TRUE)
 *   }
 * )
 *
 */
class LocationServicePage extends ConditionPluginBase {
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a new ExampleCondition instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate() {
    $location = $this->getContextValue('ss_location');

    $params = \Drupal::routeMatch()->getRawParameters();
    $service = $params->get('service');

    if (!in_array($service, LOCATION_SERVICES)) {
      return FALSE;
    }

    $service_code = array_search($service, LOCATION_SERVICES);

    if ($service_code == 'KDV' && $location->getServiceKDV() != 1) {
      return FALSE;
    }

    if ($service_code == 'BSO' && $location->getServiceBSO() != 1) {
      return FALSE;
    }

    if ($service_code == 'PSZ' && $location->getServicePSZ() != 1) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    return $this->t('Location Service Page');
  }
}