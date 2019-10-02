<?php

namespace Drupal\loan_amortization\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for calculating payments to repay the loan.
 */
class LoanAmortizationForm extends FormBase {
  const SETTINGS_LABELS = 'loan_amortization_labels.settings';
  const SETTINGS_VALUES = 'loan_amortization_values.settings';

  /**
   * ID of form is 'loan_amortization_theme'.
   */
  public function getFormId() {
    return 'loan_amortization_theme';
  }

  /**
   * Building a form of entering loan data, using data from config file.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config_labels = $this->config(static::SETTINGS_LABELS);
    $currentLanguageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $defaultLabels = $config_labels->get('en');
    $currentLabels = $config_labels->get($currentLanguageId);
    foreach ($currentLabels as $key => $label) {
      $labels[$key] = $label ? $label : $defaultLabels[$key];
    }

    $config_values = $this->config(static::SETTINGS_VALUES);
    $currentValues = $config_values->get($currentLanguageId);
    $values = $currentValues;

    $form['loan_amount'] = [
      '#type' => 'number',
      '#title' => $labels['loan_amount'],
      '#default_value' => $values['loan_amount'],
      '#prefix' => '<div class="validation-loan-amount"></div>',
    ];

    $form['annual_rate'] = [
      '#type' => 'number',
      '#title' => $labels['annual_rate'],
      '#default_value' => $values['annual_rate'],
      '#prefix' => '<div class="validation-annual-rate"></div>',
    ];

    $form['loan_period'] = [
      '#type' => 'number',
      '#title' => $labels['loan_period'],
      '#default_value' => $values['loan_period'],
      '#prefix' => '<div class="validation-loan-period"></div>',
    ];

    $form['annual_payments'] = [
      '#type' => 'number',
      '#title' => $labels['annual_payments'],
      '#default_value' => $values['annual_payments'],
      '#prefix' => '<div class="validation-annual-payments"></div>',
    ];

    $form['start_of_loan'] = [
      '#type' => 'date',
      '#title' => $labels['start_of_loan'],
      '#default_value' => $values['start_of_loan'],
      '#prefix' => '<div class="validation-start-of-loan"></div>',
    ];

    $form['extra_payment'] = [
      '#type' => 'number',
      '#title' => $labels['extra_payment'],
      '#default_value' => $values['extra_payment'],
      '#prefix' => '<div class="validation-extra-payment"></div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    ];

    $form['loan_summary'] = [
      '#markup' => '<div id="loan-summary"></div>',
    ];

    $form['loan_payments'] = [
      '#markup' => '<div id="loan-payments"></div>',
    ];

    return $form;
  }

  /**
   * Validation is realized in ajaxSubmitCallback function.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * In ajaxSubmitCallback function we're get from $form_state entered data.
   *
   * If there is GET parameter in URL, the variable is rewriting.
   * Calculating is executing by Drupal service. The result is inserted in
   * corresponding block by AjaxResponse.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();

    $loan_amount = $form_state->getValue('loan_amount');
    $annual_rate = $form_state->getValue('annual_rate') * 0.01;
    $loan_period = $form_state->getValue('loan_period');
    $annual_payments = $form_state->getValue('annual_payments');
    $start_of_loan = $form_state->getValue('start_of_loan');
    $extra_payment = $form_state->getValue('extra_payment');

    // GET parameters.
    $loan_amount_get = \Drupal::request()->query->get('loan_amount');
    $annual_rate_get = \Drupal::request()->query->get('annual_rate');
    $loan_period_get = \Drupal::request()->query->get('loan_period');
    $annual_payments_get = \Drupal::request()->query->get('annual_payments');
    $start_of_loan_get = \Drupal::request()->query->get('start_of_loan');
    $extra_payment_get = \Drupal::request()->query->get('extra_payment');

    if ($loan_amount_get) {
      $loan_amount = $loan_amount_get;
    }
    if ($annual_rate_get) {
      $annual_rate = $annual_rate_get * 0.01;
    }
    if ($loan_period_get) {
      $loan_period = $loan_period_get;
    }
    if ($annual_payments_get) {
      $annual_payments = $annual_payments_get;
    }
    if ($start_of_loan_get) {
      $start_of_loan = $start_of_loan_get;
    }
    if ($extra_payment_get) {
      $extra_payment = $extra_payment_get;
    }

    $set_required = ($loan_amount != 0) and
      ($annual_rate != 0) and
      ($loan_period != 0) and
      ($annual_payments != 0) and
      ($start_of_loan != 0);

    // Validation.
    $cond_loan_amount = $loan_amount < 0;
    $message_loan_amount = 'The amount should be positive number.';
    $selector_loan_amount = '.validation-loan-amount';
    $this->validateMessage($loan_amount, $cond_loan_amount,
      $message_loan_amount, $ajax_response, $selector_loan_amount);

    $cond_annual_rate = $annual_rate < 0 or $annual_rate > 100;
    $message_annual_rate = 'The rate should be written in percents.';
    $selector_annual_rate = '.validation-annual-rate';
    $this->validateMessage($annual_rate, $cond_annual_rate,
      $message_annual_rate, $ajax_response, $selector_annual_rate
    );

    $cond_loan_period = $loan_period < 0;
    $message_loan_period = 'The period should be written in positive integer number format.';
    $selector_loan_period = '.validation-loan-period';
    $this->validateMessage($loan_period, $cond_loan_period,
      $message_loan_period, $ajax_response, $selector_loan_period);

    $cond_annual_payments = $annual_payments < 0;
    $message_annual_payments = 'The quantity of payments should be positive number.';
    $selector_annual_payments = '.validation-annual-payments';
    $this->validateMessage($annual_payments, $cond_annual_payments,
      $message_annual_payments, $ajax_response, $selector_annual_payments);

    if ($set_required and (!($cond_loan_amount or $cond_annual_rate
        or $cond_loan_period or $cond_annual_payments))) {

      // Calculating.
      $loan_service = \Drupal::service('LoanSummaryService');

      $loan_summary = $loan_service->loanSummary($loan_amount, $annual_rate, $loan_period,
                                                 $annual_payments, $start_of_loan, $extra_payment);
      $loan_summary_build = [
        '#theme' => 'loan_summary',
        '#loan_summary' => $loan_summary,
      ];
      $ajax_response->addCommand(new HtmlCommand('#loan-summary', $loan_summary_build));

      $loan_payments_build = $loan_service->loanPayments($loan_amount,
        $annual_rate, $loan_period, $annual_payments, $start_of_loan, $extra_payment);
      $ajax_response->addCommand(new HtmlCommand('#loan-payments', $loan_payments_build));

      // Getting labels for saving of logs.
      $config_labels = $this->config(static::SETTINGS_LABELS);
      $currentLanguageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $defaultLabels = $config_labels->get('en');
      $currentLabels = $config_labels->get($currentLanguageId);
      foreach ($currentLabels as $key => $label) {
        $labels[$key] = $label ? $label : $defaultLabels[$key];
      }

      $enteredData = [
        'loan_amount' => $loan_amount,
        'annual_rate' => $annual_rate * 100,
        'loan_period' => $loan_period,
        'annual_payments' => $annual_payments,
        'start_of_loan' => $start_of_loan,
        'extra_payment' => $extra_payment,
      ];

      // Drupal logs.
      $clientIP = \Drupal::request()->getClientIp();
      $currentAccount = \Drupal::currentUser();
      $userName = $currentAccount->getDisplayName();
      $log_message = 'IP: ' . $clientIP . '; Name: ' . $userName;
      $log_message .= '. Entered data. ';
      foreach ($enteredData as $key => $val) {
        $log_message .= $labels[$key] . ': ' . $val . '; ';
      }
      $log_message .= ' Loan Summary. ';
      foreach ($loan_summary as $key => $val) {
        $log_message .= $val['label'] . ': ' . $val['data'] . '; ';
      }
      $this->logger('loan_amortization')->notice($log_message);
    }

    return $ajax_response;
  }

  /**
   * AjaxSubmitCallback function for validation .
   */
  public function validateMessage($var, $check, $message, AjaxResponse $ajax_response, $selector) {
    if ($var == 0) {
      $this->messenger()->addError($this->t('The field is required'));
      $status_message = [
        '#theme' => 'status_messages',
        '#message_list' => $this->messenger()->deleteAll(),
        '#status_headings' => [
          'status' => t('Status message'),
          'error' => t('Error message'),
          'warning' => t('Warning message'),
        ],
      ];
      $messages = \Drupal::service('renderer')->render($status_message);
      $ajax_response->addCommand(new HtmlCommand($selector, $messages));
    }
    elseif ($check) {
      $this->messenger()->addError($message);
      $status_message = [
        '#theme' => 'status_messages',
        '#message_list' => $this->messenger()->deleteAll(),
        '#status_headings' => [
          'error' => t('Error message'),
        ],
      ];
      $messages = \Drupal::service('renderer')->render($status_message);
      $ajax_response->addCommand(new HtmlCommand($selector, $messages));
    }
    else {
      $ajax_response->addCommand(new HtmlCommand($selector, ''));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
