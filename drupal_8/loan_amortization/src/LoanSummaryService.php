<?php

namespace Drupal\loan_amortization;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * LoanSummaryService implements loanSummary, loanPayments, pmt functions.
 */
class LoanSummaryService {

  const SETTINGS_LABELS = 'loan_amortization_labels.settings';

  /**
   * Returns loan_summary array.
   */
  public function loanSummary(
    $loan_amount,
    $annual_rate,
    $loan_period,
    $annual_payments,
    $start_of_loan,
    $optional_payment
  ) {
    $rate = $annual_rate / $annual_payments;
    $nper = $loan_period * $annual_payments;
    $pv = $loan_amount;
    $scheduled_payment = -($this->pmt($rate, $nper, $pv));
    $scheduled_number_of_payments = $loan_period * $annual_payments;

    // Table data.
    $beginning_balance[1] = $loan_amount;
    $cumulative_interest[0] = NULL;
    for ($i = 1; $i <= $scheduled_number_of_payments; $i++) {
      $pmt_no[$i] = $i;
      $diff_months = 12 * 1 / $annual_payments;
      $payment_date[$i] = strtotime($start_of_loan . '+' . $diff_months . ' month');
      if ($scheduled_payment + $optional_payment < $beginning_balance[$i]) {
        $extra_payment[$i] = $optional_payment;
      }
      elseif (($beginning_balance[$i] - $scheduled_payment) > 0) {
        $extra_payment[$i] = $beginning_balance[$i] - $scheduled_payment;
      }

      if (($scheduled_payment + $extra_payment[$i]) < $beginning_balance[$i]) {
        $total_payment[$i] = $scheduled_payment + $extra_payment[$i];
      }
      else {
        $total_payment[$i] = $beginning_balance[$i];
      }

      $interest[$i] = $beginning_balance[$i] * ($annual_rate / $annual_payments);
      $principal[$i] = $total_payment[$i] - $interest[$i];
      if (($scheduled_payment + $extra_payment[$i]) < $beginning_balance[$i]) {
        $ending_balance[$i] = $beginning_balance[$i] - $principal[$i];
      }
      else {
        $ending_balance[$i] = 0;
      }

      $beginning_balance[$i + 1] = $ending_balance[$i];
      $cumulative_interest[$i] = $cumulative_interest[$i - 1] ?
        $cumulative_interest[$i - 1] + $interest[$i] : $interest[$i];
      if ($ending_balance[$i] === 0) {
        break;
      }
    }

    $loan_values = [
      'scheduled_payment' => number_format((float) $scheduled_payment, 2, '.', ''),
      'scheduled_number_of_payments' => $scheduled_number_of_payments,
      'actual_number_of_payments' => end($pmt_no) ,
      'total_early_payments' => number_format((float) array_sum($extra_payment), 2, '.', ''),
      'total_interest' => number_format((float) array_sum($interest), 2, '.', ''),
    ];

    $config_labels = \Drupal::config(static::SETTINGS_LABELS);
    $currentLanguageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $defaultLabels = $config_labels->get('en');
    $currentLabels = $config_labels->get($currentLanguageId);

    foreach ($loan_values as $key => $value) {
      $loan_summary[$key]['label'] = $currentLabels[$key] ? $currentLabels[$key] : $defaultLabels[$key];
      $loan_summary[$key]['data'] = $loan_values[$key];
    }

    return $loan_summary;
  }

  /**
   * Returns array for build table of payments.
   */
  public function loanPayments(
    $loan_amount,
    $annual_rate,
    $loan_period,
    $annual_payments,
    $start_of_loan,
    $optional_payment
  ) {
    $rate = $annual_rate / $annual_payments;
    $nper = $loan_period * $annual_payments;
    $pv = $loan_amount;
    $scheduled_payment = -($this->pmt($rate, $nper, $pv));
    $scheduled_number_of_payments = $loan_period * $annual_payments;

    // Table data loan_payments.
    $loan_payments = [];
    $beginning_balance[1] = $loan_amount;
    $cumulative_interest[1] = NULL;
    for ($i = 1; $i <= $scheduled_number_of_payments; $i++) {
      $pmt_no[$i] = $i;

      $diff_months = (int) (12 * $pmt_no[$i] / $annual_payments);
      $data_of_payment = strtotime($start_of_loan . '+' . $diff_months . ' month');
      $payment_date[$i] = DrupalDateTime::createFromTimestamp($data_of_payment)->format('m/d/Y');

      $scheduled_payment_data[$i] = number_format((float) $scheduled_payment, 2, '.', '');
      if ($scheduled_payment + $optional_payment < $beginning_balance[$i]) {
        $extra_payment_exact = $optional_payment;
        $extra_payment[$i] = number_format((float) $extra_payment_exact, 2, '.', '');
      }
      elseif (($beginning_balance[$i] - $scheduled_payment) > 0) {
        $extra_payment_exact = $beginning_balance[$i] - $scheduled_payment;
        $extra_payment[$i] = number_format((float) $extra_payment_exact, 2, '.', '');
      }

      if (($scheduled_payment + $extra_payment[$i]) < $beginning_balance[$i]) {
        $total_payment_exact = $scheduled_payment + $extra_payment[$i];
        $total_payment[$i] = number_format((float) $total_payment_exact, 2, '.', '');
      }
      else {
        $total_payment_exaxt = $beginning_balance[$i];
        $total_payment[$i] = number_format((float) $total_payment_exaxt, 2, '.', '');
      }

      $interest_exact = $beginning_balance[$i] * ($annual_rate / $annual_payments);
      $interest[$i] = number_format((float) $interest_exact, 2, '.', '');

      $principal_exact = $total_payment[$i] - $interest[$i];
      $principal[$i] = number_format((float) $principal_exact, 2, '.', '');

      if (($scheduled_payment + $extra_payment[$i]) < $beginning_balance[$i]) {
        $ending_balance_exact = $beginning_balance[$i] - $principal[$i];
        $ending_balance[$i] = number_format((float) $ending_balance_exact, 2, '.', '');
      }
      else {
        $ending_balance[$i] = 0;
      }
      $cumulative_interest[$i] = $cumulative_interest[$i - 1] ?
        $cumulative_interest[$i - 1] + $interest[$i] : $interest[$i];

      $beginning_balance[$i + 1] = $ending_balance[$i];

      $loan_payments[$i] = [
        'pmt_no' => $pmt_no[$i],
        'payment_date' => $payment_date[$i],
        'beginning_balance' => $beginning_balance[$i],
        'scheduled_payment' => $scheduled_payment_data[$i],
        'extra_payment' => $extra_payment[$i],
        'total_payment' => $total_payment[$i],
        'principal' => $principal[$i],
        'interest' => $interest[$i],
        'ending_balance' => $ending_balance[$i],
        'cumulative_interest' => $cumulative_interest[$i],
      ];
      if ($ending_balance[$i] === 0) {
        break;
      }
    }

    $payment_labels = [
      'pmt_no',
      'payment_date',
      'beginning_balance',
      'scheduled_payment',
      'extra_payment',
      'total_payment',
      'principal',
      'interest',
      'ending_balance',
      'cumulative_interest',
    ];

    $config_labels = \Drupal::config(static::SETTINGS_LABELS);
    $currentLanguageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $defaultLabels = $config_labels->get('en');
    $currentLabels = $config_labels->get($currentLanguageId);

    foreach ($payment_labels as $key) {
      $labels[$key] = $currentLabels[$key] ? $currentLabels[$key] : $defaultLabels[$key];
    }

    $build = [
      '#theme' => 'loan_payments',
      '#loan_payments' => $loan_payments,
      '#payment_labels' => $labels,
    ];

    return $build;
  }

  /**
   * Returns a value of a periodic payment for a loan.
   *
   * Number $rate
   * The interest rate for the loan.
   * number $nper
   * The total number of payments for the loan.
   * number $pv
   * The present value, or total value of all loan payments now.
   * bool $fv
   * [optional] The future value, or a cash balance you want after
   * the last payment is made. Defaults to 0 (zero).
   * bool $type
   * [optional] When payments are due. 0 = end of period.
   * 1 = beginning of period. Default is 0.
   */
  public function pmt($rate, $nper, $pv, $fv = 0, $type = 0) {
    if ($rate === 0) {
      return -($pv + $fv) / $nper;
    }
    $pvif = pow(1 + $rate, $nper);
    $pmt = -$rate * $pv * ($pvif + $fv) / ($pvif - 1);

    if ($type === 1) {
      $pmt /= 1 + $rate;
    }

    return $pmt;
  }

  /**
   * Returns validation message.
   */
  public function loanApiValidationMessages(array $data) {
    // Validation.
    $config_labels = \Drupal::config(static::SETTINGS_LABELS);
    $defaultLabels = $config_labels->get('en');

    if (isset($data['loan_amount'])) {
      $loan_amount = $data['loan_amount'];
      if ($loan_amount < 0) {
        $message['loan_amount'] = 'The amount should be positive number.';
      }
    }
    else {
      $message['loan_amount'] = $defaultLabels['loan_amount'] . ' is required.';
    }

    if (isset($data['annual_rate'])) {
      $annual_rate = $data['annual_rate'] * 0.01;
      $cond_annual_rate = $annual_rate < 0 or $annual_rate > 100;
      if ($cond_annual_rate) {
        $message['annual_rate'] = 'The rate should be written in percents.';
      }
    }
    else {
      $message['annual_rate'] = $defaultLabels['annual_rate'] . ' is required.';
    }

    if (isset($data['loan_period'])) {
      $loan_period = $data['loan_period'];
      $cond_loan_period = $loan_period < 0;
      if ($cond_loan_period) {
        $message['loan_period'] = 'The period should be written in positive integer number format.';
      }
    }
    else {
      $message['loan_period'] = $defaultLabels['loan_period'] . ' is required.';
    }

    if (isset($data['annual_payments'])) {
      $annual_payments = $data['annual_payments'];
      $cond_annual_payments = $annual_payments < 0;
      if ($cond_annual_payments) {
        $message['annual_payments'] = 'The quantity of payments should be positive number.';
      }
    }
    else {
      $message['annual_payments'] = $defaultLabels['annual_payments'] . ' is required.';
    }

    return $message;

  }

}
