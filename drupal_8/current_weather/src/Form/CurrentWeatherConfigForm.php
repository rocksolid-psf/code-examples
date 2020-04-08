<?php

namespace Drupal\current_weather\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for current_weather module.
 */
class CurrentWeatherConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'current_weather_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['current_weather.config'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('current_weather.config');

    $form['endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Endpoint'),
      '#default_value' => $config->get('endpoint') ?: '',
      '#description' => $this->t('Endpoint'),
    ];
    $form['api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#default_value' => $config->get('api') ?: '',
      '#description' => $this->t('Current weather API key'),
    ];

    $form['default_city_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default city name'),
      '#default_value' => $config->get('default_city_name') ?: '',
      '#description' => $this->t('Default city name'),
    ];

    $form['default_country_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default country code'),
      '#default_value' => $config->get('default_country_code') ?: '',
      '#description' => $this->t('Default country code'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable('current_weather.config')
    // Set the submitted configuration setting.
      ->set('endpoint', $form_state->getValue('endpoint'))
      ->set('api', $form_state->getValue('api'))
      ->set('default_city_name', $form_state->getValue('default_city_name'))
      ->set('default_country_code', $form_state->getValue('default_country_code'))
      ->save();
    // Call parent submit handler.
    parent::submitForm($form, $form_state);
  }

}
