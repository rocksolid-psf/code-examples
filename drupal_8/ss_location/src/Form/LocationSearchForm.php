<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LocationSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $referer = 'canonical', $page = 'search') {
    $theme_path = drupal_get_path('theme', 'smallsteps');

    $search = NULL;
    $type = 'locatieadres';
    $params = \Drupal::routeMatch()->getRawParameters();
    $city = $params->get('city');
    $province = $params->get('province');
    if ($city && $province) {
      $search = "$city, $province";
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Postcode'])) {
      $search = $_GET['Postcode'];
      $type = 'locatieadres';
    }
    elseif (isset($_GET['Name'])) {
      $search = $_GET['Name'];
      $type = 'locatienaam';
    }
    elseif ($referer == 'registration') {
      $type = 'locatienaam';
    }

    $services = [];
    if (isset($_GET['KDV']) && $_GET['KDV']) {
      $services[] = 'KDV';
    }
    if (isset($_GET['PSZ']) && $_GET['PSZ']) {
      $services[] = 'PSZ';
    }
    if (isset($_GET['BSO']) && $_GET['BSO']) {
      $services[] = 'BSO';
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
      $url = parse_url($_SERVER['HTTP_REFERER']);

      if ($url['path'] == '/kinderdagverblijf' && !in_array('KDV', $services)) {
        $services[] = 'KDV';
      }

      if ($url['path'] == '/peuterspeelzaal' && !in_array('PSZ', $services)) {
        $services[] = 'PSZ';
      }

      if ($url['path'] == '/buitenschoolse-opvang' && !in_array('BSO', $services)) {
        $services[] = 'BSO';
      }
    }

    $form['searchtype'] = [
      '#type' => 'hidden',
      '#default_value' => $type
    ];

    $form['referer'] = [
      '#type' => 'hidden',
      '#default_value' => isset($_GET['referer']) ? $_GET['referer'] : $referer
    ];

    $form['page'] = [
      '#type' => 'value',
      '#default_value' => $page
    ];

    $services_options = [
      'KDV' => t('<b>@service</b> (@age jaar)', ['@service' => LOCATION_SERVICES_NAMES['KDV'], '@age' => LOCATION_SERVICES_AGE['KDV']]),
      'PSZ' => t('<b>@service</b> (@age jaar)', ['@service' => LOCATION_SERVICES_NAMES['PSZ'], '@age' => LOCATION_SERVICES_AGE['PSZ']]),
      'BSO' => t('<b>@service</b> (@age jaar)', ['@service' => LOCATION_SERVICES_NAMES['BSO'], '@age' => LOCATION_SERVICES_AGE['BSO']])
    ];

    $form['services'] = [
      '#title' => NULL,
      '#type' => 'checkboxes',
      '#options' => $services_options,
      '#default_value' => $services
    ];

    $form['search'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => ($type == 'locatienaam') ? t('zoek op locatienaam') : t('zoek op plaats of postcode'),
      ],
      '#default_value' => $search
    ];

    $form['submit'] = [
      '#type' => 'image_button',
      '#src' => $theme_path . '/images/search2.png',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $services = $form_state->getValue('services');
    $search = $form_state->getValue('search');

    $query = [];
    foreach ($services as $service) {
      if ($service) {
        $query[$service] = 1;
      }
    }

    $args = [];
    if (strpos($search, ',') !== FALSE) {
      list($city, $province) = explode(',', $search);
      $city = strtolower($city);
      $province = strtolower($province);
      $args['city'] = trim($city);
      $args['province'] = trim($province);
    }
    elseif (preg_match('/^\d{4}/', $search)) {
      $search = urldecode(preg_replace('/^(\d{4})\s*?(\w{2})(.*)/', '$1 $2', strtoupper($search)));
      $query['Postcode'] = $search;
    }
    elseif (strpos($search, '-') !== FALSE) {
      list($location) = explode('-', $search);
      $query['Name'] = trim($location);
    }
    else {
      $query['Name'] = trim($search);
    }
    $page = $form_state->getValue('page');
    $form_state->setRedirect("entity.ss_location.$page", $args, ['query' => $query]);
  }
}