<?php

/**
 * @file
 * Provides hooks for rules_curl_http_post module.
 */

/**
 * Implements hook_rules_action_info().
 */
function rules_curl_http_post_rules_action_info() {
  $items['request_url'] = array(
    'label' => t('Request HTTP data'),
    'base' => 'rules_curl_http_post_request_url',
    'group' => t('Data'),
    'parameter' => array(
      'url' => array(
        'type' => 'text',
        'label' => 'URL',
        'description' => t("The request url."),
      ),
      'headers' => array(
        'type' => 'text',
        'label' => t('Headers'),
        'description' => t('Request headers to send as "name: value" pairs, one per line (e.g., Content-Type: application/json). See <a href="https://www.wikipedia.org/wiki/List_of_HTTP_header_fields" target="_blank">wikipedia.org/wiki/List_of_HTTP_header_fields</a> for more information.'),
        'optional' => TRUE,
      ),
      'authorization_login' => array(
        'type' => 'text',
        'label' => t('Basic Auth Username'),
        'optional' => TRUE,
      ),
      'authorization_pass' => array(
        'type' => 'text',
        'label' => t('Basic Auth Password'),
        'optional' => TRUE,
      ),
      'method' => array(
        'type' => 'text',
        'label' => t('Method'),
        'description' => t('The HTTP request method, GET or POST.'),
        'options list' => 'rules_curl_http_post_options_method',
        'optional' => TRUE,
      ),
      'data' => array(
        'type' => 'text',
        'label' => t('Data'),
        'description' => t("The request body."),
        'optional' => TRUE,
      ),
      'max_redirects' => array(
        'type' => 'integer',
        'label' => t('Max redirects'),
        'description' => t('How many times a redirect may be followed.'),
        'default value' => 3,
        'optional' => TRUE,
      ),
      'timeout' => array(
        'type' => 'decimal',
        'label' => t('Timeout'),
        'description' => t('The maximum number of seconds the request may take.'),
        'default value' => 30,
        'optional' => TRUE,
      ),
    ),
    'provides' => array(
      'http_response' => array(
        'type' => 'text',
        'label' => t('HTTP data'),
      ),
    ),
  );

  return $items;
}

/**
 * Options list callback for 'Method' parameter.
 */
function rules_curl_http_post_options_method() {
  return array(
    'GET' => 'GET',
    'HEAD' => 'HEAD',
    'POST' => 'POST',
    'PUT' => 'PUT',
    'DELETE' => 'DELETE',
    'TRACE' => 'TRACE',
    'OPTIONS' => 'OPTIONS',
    'CONNECT' => 'CONNECT',
    'PATCH' => 'PATCH',
  );
}

/**
 * Callback for 'request_url' rules action.
 */
function rules_curl_http_post_request_url($url, $headers = '', $username = NULL, $password = NULL, $method = 'GET', $data = NULL, $max_redirects = 3, $timeout = 30) {
  // Headers.
  $headers = explode("\r\n", $headers);

  // Method.
  $method = drupal_strtoupper($method);

  // Max redirects.
  $max_redirects = empty($max_redirects) ? 3 : $max_redirects;

  // Timeout.
  $timeout = empty($timeout) ? 30 : $timeout;

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => $max_redirects,
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_USERPWD => "$username:$password",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => $headers,
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);
  if ($err) {
    watchdog('rules_curl_http_post', $err);
    return;
  }
  else {
    return array('http_response' => $response->data);
  }
}
