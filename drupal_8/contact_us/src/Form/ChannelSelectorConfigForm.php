<?php

namespace Drupal\contact_us\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Contact us channel selector configurations form.
 */
class ChannelSelectorConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_us_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'contact_us.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $settings = $this->config('contact_us.settings');
    $form['#cache'] = [
      'max-age' => 0,
    ];
    $form['open_window'] = [
      '#title' => $this->t('Open window command'),
      '#type' => 'textarea',
      '#default_value' => $settings->get("open_window"),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save configurations.
    $this->config('contact_us.settings')
      ->set('open_window', $form_state->getValue('open_window'))
      ->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
