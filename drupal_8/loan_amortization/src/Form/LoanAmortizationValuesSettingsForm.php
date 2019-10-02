<?php

namespace Drupal\loan_amortization\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @file
 * Contains \Drupal\loan_amortization\Form\LoanAmortizationValuesSettingsForm.
 */

/**
 * Configure example settings for this site.
 */
class LoanAmortizationValuesSettingsForm extends ConfigFormBase {
  /**
   * Configuration data.
   *
   * @var string Config settings.
   */
  const SETTINGS = 'loan_amortization_values.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'loan_amortization_values_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $languages = \Drupal::languageManager()->getLanguages();
    foreach ($languages as $language) {
      $lang_ids[$language->getName()] = $language->getId();
    }
    foreach ($lang_ids as $language => $lang) {

      $form['loan_amount' . $lang] = [
        '#type' => 'number',
        '#title' => $this->t('Loan Amount'),
        '#default_value' => $config->get($lang)['loan_amount'],
        '#prefix' => '<h1>' . $language . '</h1>',
      ];

      $form['annual_rate' . $lang] = [
        '#type' => 'number',
        '#title' => $this->t('Annual Interest Rate'),
        '#default_value' => $config->get($lang)['annual_rate'],
      ];

      $form['loan_period' . $lang] = [
        '#type' => 'number',
        '#title' => $this->t('Loan Period in Year'),
        '#default_value' => $config->get($lang)['loan_period'],
      ];

      $form['annual_payments' . $lang] = [
        '#type' => 'number',
        '#title' => $this->t('Number of Payments Per Year'),
        '#default_value' => $config->get($lang)['annual_payments'],
      ];

      $form['start_of_loan' . $lang] = [
        '#type' => 'date',
        '#title' => $this->t('Start Date of Loan'),
        '#default_value' => $config->get($lang)['start_of_loan'],
      ];

      $form['extra_payment' . $lang] = [
        '#type' => 'number',
        '#title' => $this->t('Optional Extra Payments'),
        '#default_value' => $config->get($lang)['extra_payment'],
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $languages = \Drupal::languageManager()->getLanguages();
    foreach ($languages as $language) {
      $lang_ids[$language->getName()] = $language->getId();
    }
    // Retrieve the configuration.
    foreach ($lang_ids as $lang) {
      $config_data[$lang] = [
        'loan_amount' => $form_state->getValue('loan_amount' . $lang),
        'annual_rate' => $form_state->getValue('annual_rate' . $lang),
        'loan_period' => $form_state->getValue('loan_period' . $lang),
        'annual_payments' => $form_state->getValue('annual_payments' . $lang),
        'start_of_loan' => $form_state->getValue('start_of_loan' . $lang),
        'extra_payment' => $form_state->getValue('extra_payment' . $lang),
      ];
    }

    $this->configFactory->getEditable(static::SETTINGS)->setData($config_data)
      ->save();
    parent::submitForm($form, $form_state);
  }
}