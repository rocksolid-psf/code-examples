<?php

namespace Drupal\loan_amortization\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class LoanAmortizationLabelsSettingsForm extends ConfigFormBase {

  const SETTINGS = 'loan_amortization_labels.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'loan_amortization_labels_admin_settings';
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
      $form['enter_values' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Enter Values'),
        '#default_value' => $config->get($lang)['enter_values'],
        '#prefix' => '<h1>' . $language . '</h1>',
      ];

      $form['loan_amount' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Loan Amount'),
        '#default_value' => $config->get($lang)['loan_amount'],
      ];

      $form['annual_rate' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Annual Interest Rate'),
        '#default_value' => $config->get($lang)['annual_rate'],
      ];

      $form['loan_period' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Loan Period in Year'),
        '#default_value' => $config->get($lang)['loan_period'],
      ];

      $form['annual_payments' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Number of Payments Per Year'),
        '#default_value' => $config->get($lang)['annual_payments'],
      ];

      $form['start_of_loan' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Start Date of Loan'),
        '#default_value' => $config->get($lang)['start_of_loan'],
      ];

      $form['optional_extra_payments' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Optional Extra Payments'),
        '#default_value' => $config->get($lang)['extra_payment'],
      ];

      $form['loan_summary' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Loan Summary'),
        '#default_value' => $config->get($lang)['loan_summary'],
      ];

      $form['scheduled_payment' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Scheduled Payment'),
        '#default_value' => $config->get($lang)['scheduled_payment'],
      ];

      $form['scheduled_number_of_payments' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Scheduled Number of Payments'),
        '#default_value' => $config->get($lang)['scheduled_number_of_payments'],
      ];

      $form['actual_number_of_payments' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Actual Number of Payments'),
        '#default_value' => $config->get($lang)['actual_number_of_payments'],
      ];

      $form['total_early_payments' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Total Early Payments'),
        '#default_value' => $config->get($lang)['total_early_payments'],
      ];

      $form['total_interest' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Total Interest'),
        '#default_value' => $config->get($lang)['total_interest'],
      ];

      $form['pmt_no' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('PmtNo.'),
        '#default_value' => $config->get($lang)['pmt_no'],
      ];

      $form['payment_date' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Payment Date'),
        '#default_value' => $config->get($lang)['payment_date'],
      ];

      $form['beginning_balance' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Beginning Balance'),
        '#default_value' => $config->get($lang)['beginning_balance'],
      ];

      $form['scheduled_payment' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Scheduled Payment'),
        '#default_value' => $config->get($lang)['scheduled_payment'],
      ];

      $form['extra_payment' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Extra Payment'),
        '#default_value' => $config->get($lang)['extra_payment'],
      ];

      $form['total_payment' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Total Payment'),
        '#default_value' => $config->get($lang)['total_payment'],
      ];

      $form['principal' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Principal'),
        '#default_value' => $config->get($lang)['principal'],
      ];

      $form['interest' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Interest'),
        '#default_value' => $config->get($lang)['interest'],
      ];

      $form['ending_balance' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Ending Balance'),
        '#default_value' => $config->get($lang)['ending_balance'],
      ];

      $form['cumulative_interest' . $lang] = [
        '#type' => 'textfield',
        '#title' => $this->t('Cumulative Interest'),
        '#default_value' => $config->get($lang)['cumulative_interest'],
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
    $config_data = [];
    foreach ($lang_ids as $lang) {
      $config_data[$lang] = [
        'enter_values' => $form_state->getValue('enter_values' . $lang),
        'loan_amount' => $form_state->getValue('loan_amount' . $lang),
        'annual_rate' => $form_state->getValue('annual_rate' . $lang),
        'loan_period' => $form_state->getValue('loan_period' . $lang),
        'annual_payments' => $form_state->getValue('annual_payments' . $lang),
        'start_of_loan' => $form_state->getValue('start_of_loan' . $lang),
        'optional_extra_payments' => $form_state->getValue('optional_extra_payments' . $lang),
        'loan_summary' => $form_state->getValue('loan_summary' . $lang),
        'scheduled_payment' => $form_state->getValue('scheduled_payment' . $lang),
        'scheduled_number_of_payments' => $form_state->getValue('scheduled_number_of_payments' . $lang),
        'actual_number_of_payments' => $form_state->getValue('actual_number_of_payments' . $lang),
        'total_early_payments' => $form_state->getValue('total_early_payments' . $lang),
        'total_interest' => $form_state->getValue('total_interest' . $lang),
        'pmt_no' => $form_state->getValue('pmt_no' . $lang),
        'payment_date' => $form_state->getValue('payment_date' . $lang),
        'beginning_balance' => $form_state->getValue('beginning_balance' . $lang),
        'extra_payment' => $form_state->getValue('extra_payment' . $lang),
        'total_payment' => $form_state->getValue('total_payment' . $lang),
        'principal' => $form_state->getValue('principal' . $lang),
        'interest' => $form_state->getValue('interest' . $lang),
        'ending_balance' => $form_state->getValue('ending_balance' . $lang),
        'cumulative_interest' => $form_state->getValue('cumulative_interest' . $lang),
      ];
    }

    $this->configFactory->getEditable(static::SETTINGS)->setData($config_data)
      ->save();
    parent::submitForm($form, $form_state);
  }

}