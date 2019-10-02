<?php

namespace Drupal\loan_amortization\Plugin\rest\resource;

/**
 * @file
 * Contains Drupal\loan_amortization\Plugin\rest\resource\LoanSummaryResource.
 */

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a resource to get loan summary array.
 *
 * @RestResource(
 *   id = "loan_summary_resource",
 *   authenticationTypes = TRUE,
 *   label = @Translation("Loan summary"),
 *   uri_paths = {
 *     "create" = "/api/loan-summary",
 *   }
 * )
 */
class LoanSummaryResource extends ResourceBase {

  /**
   * Responds to POST requests.
   *
   * Returns a list of bundles for specified entity.
   */
  public function post($data) {
    $loan_service = \Drupal::service('LoanSummaryService');
    $validation_messages = $loan_service->loanApiValidationMessages($data);
    if (empty($validation_messages)) {

      $loan_amount = $data['loan_amount'];
      $annual_rate = $data['annual_rate'] * 0.01;
      $loan_period = $data['loan_period'];
      $annual_payments = $data['annual_payments'];
      $start_of_loan = $data['start_of_loan'];
      $extra_payment = $data['extra_payment'];

      $loan_summary = $loan_service->loanSummary(
        $loan_amount, $annual_rate, $loan_period,
        $annual_payments, $start_of_loan, $extra_payment
      );

      return new ResourceResponse($loan_summary);
    }
    else {
      return new ResourceResponse($validation_messages, 400);
    }
  }

}
