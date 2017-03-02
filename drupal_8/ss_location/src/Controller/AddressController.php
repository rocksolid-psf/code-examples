<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller for full address generation.
 */
class AddressController extends ControllerBase {

  public function generate() {
    if (!isset($_POST['postcode'], $_POST['house_number'])) {
      throw new AccessDeniedHttpException();
    }

    $response = ss_location_remote_request("https://api.postcode.nl/rest/addresses/{$_POST['postcode']}/{$_POST['house_number']}", false, 'ZeDDm3I6fyNRybNL83J5lyYkOiQNmczIKYEpTJC2S6E:NTKsIgZkCNyZ2kyqZW1OXuS2vmSW9JKExR27KzPltKTttPLlqA');

    exit($response);
  }
}