<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LocationTourForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_tour_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $location = NULL) {
    $form['#attached']['library'][] = 'ss_location/tour_form';
    $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_dates'] = NULL;
    $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_date_hours'] = NULL;

    $services_options = [];
    if ($location->getServiceKDV() == 1) {
      $services_options['KDV'] = t('@service<br />(@age jaar)', ['@service' => $location->getMainServiceKDVTitle(), '@age' => LOCATION_SERVICES_AGE['KDV']]);
    }

    if ($location->getServicePSZ() == 1) {
      $services_options['PSZ'] = t('@service<br />(@age jaar)', ['@service' => $location->getMainServicePSZTitle(), '@age' => LOCATION_SERVICES_AGE['PSZ']]);
    }

    if ($location->getServiceBSO() == 1) {
      $services_options['BSO'] = t('@service<br />(@age jaar)', ['@service' => $location->getMainServiceBSOTitle(), '@age' => LOCATION_SERVICES_AGE['BSO']]);
    }

    $location_services_count = count($services_options);

    $form['Location'] = [
      '#type' => 'hidden',
      '#value' => $location->getPath()
    ];

    $form['LocationId'] = [
      '#type' => 'hidden',
      '#value' => $location->getId()
    ];

    $form['ContactReason'] = [
      '#type' => 'hidden',
      '#value' => 'Tour'
    ];

    $campaign = NULL;
    if (!empty($_GET['Campaign'])) {
      $campaign = $_GET['Campaign'];
    }
    elseif (!empty($_COOKIE['Campaign'])) {
      $campaign = $_COOKIE['Campaign'];
    }

    $form['CampaignId'] = [
      '#type' => 'hidden',
      '#value' => $campaign
    ];

    $language = \Drupal::languageManager()->getCurrentLanguage()->getName();

    $form['Language'] = [
      '#type' => 'hidden',
      '#value' => $language
    ];

    $form['Source'] = [
      '#type' => 'hidden',
      '#value' => 1
    ];

    $form['TourDate'] = [
      '#type' => 'hidden',
    ];

    $form['TourTime'] = [
      '#type' => 'hidden',
    ];

    $form['TourId'] = [
      '#type' => 'hidden',
    ];

    $form_state->setCached(FALSE);

    if ($location_services_count > 1) {
      $form['services_wrapper']['subtitle'] = [
        '#type' => 'markup',
        '#markup' => t('Waar ben je naar op zoek?')
      ];

      $form['services_wrapper']['LocationServices'] = [
        '#title' => NULL,
        '#type' => 'checkboxes',
        '#options' => $services_options,
      ];
    }

    if ($location_services_count == 1) {
      $form['services_wrapper']['LocationServices'] = [
        '#type' => 'value',
        '#value' => [key($services_options)]
      ];
    }

    $form['preferences_wrapper']['title'] = [
      '#type' => 'markup',
      '#markup' => t('Wanneer wil je komen kijken?')
    ];

    $tour_schedules = $location->getTourSchedules();
    if (count($tour_schedules) > 0) {
      $days = [];
      $hidden = NULL;
      foreach ($tour_schedules as $day => $hours) {
        $days[] = $day;
        $default = $hours;
        reset($default);
        $default = key($default);
        $form['preferences_wrapper']['tour_schedules_hours']['tour_schedules_hours_' . $day] = [
          '#type' => 'radios',
          '#options' => $hours,
          '#default_value' => $default,
          '#prefix' => '<div class="schedules-hours hidden schedules-hours-' . $day . '">',
          '#suffix' => '</div>'
        ];
      }

      $days = json_encode($days);
      $tour_schedules = json_encode($tour_schedules);

      $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_dates'] = $days;
      $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_date_hours'] = $tour_schedules;

      $form['preferences_wrapper']['TourScheduleOptout'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Je kan geen geschikte datum en/of tijd vinden. Ga hieronder verder.'),
      ];
    }
    else {
      $days = [
        'Monday' => $this->t('Maandag'),
        'Tuesday' => $this->t('Dinsdag'),
        'Wednesday' => $this->t('Woensdag'),
        'Thursday' => $this->t('Donderdag'),
        'Friday' => $this->t('Vrijdag'),
      ];

      $hours = [
        '07:30 - 11:00' => $this->t('@time uur', ['@time' => '07:30 - 11:00']),
        '11:00 - 14:00' => $this->t('@time uur', ['@time' => '11:00 - 14:00']),
        '14:00 - 18:00' => $this->t('@time uur', ['@time' => '14:00 - 18:00']),
        0 => $this->t('Geen voorkeur')
      ];

      $form['preferences_wrapper']['PreferredDays'] = [
        '#type' => 'checkboxes',
        '#options' => $days,
      ];

      foreach ($days as $key => $day) {
        $form['preferences_wrapper']['preferred_times'][$key] = [
          '#type' => 'radios',
          '#name' => 'PreferredTimes',
          '#options' => $hours,
          '#prefix' => '<div class="preferences-day-hours day-' . $key . '-hours hidden">',
          '#suffix' => '</div>',
        ];
      }

      $form['preferences_wrapper']['no_preferences_day'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Ik heb geen voorkeur.'),
      ];
    }

    $form['address_wrapper']['title'] = [
      '#type' => 'markup',
      '#markup' => t('Zou je je gegevens willen invullen, zodat we contact met je kunnen opnemen?')
    ];

    $form['address_wrapper']['NameTitle'] = [
      '#type' => 'radios',
      '#options' => [
        'mevrouw' => t('Mevrouw'),
        'heer' => t('Meneer')
      ],
      '#required' => TRUE
    ];

    $form['address_wrapper']['NameFirst'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Voornaam'),
      ]
    ];

    $form['address_wrapper']['NameMiddle'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Tussenvoegsel'),
      ]
    ];

    $form['address_wrapper']['NameLast'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Achternaam'),
      ]
    ];

    $form['address_wrapper']['AddressPostcode'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Postcode'),
      ]
    ];

    $form['address_wrapper']['AddressHouseNumber'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Huisnummer'),
      ]
    ];

    $form['address_wrapper']['AddressAdditional'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Toevoeging'),
      ]
    ];

    $form['address_wrapper']['AddressStreet'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Straatnaam'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ]
    ];

    $form['address_wrapper']['AddressCity'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Woonplaats'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ]
    ];

    $form['address_wrapper']['ContactEmail'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('E-mail'),
        'data-rule-required' => 'true'
      ]
    ];

    $form['address_wrapper']['ContactPhone'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Telefoonnummer'),
        'pattern' => ".{8,12}"
      ]
    ];

    $form['address_wrapper']['more'] = [
      '#type' => 'checkbox',
      '#title' => t('Ik neem iemand mee.'),
    ];

    $form['address_wrapper']['Remarks'] = [
      '#type' => 'textarea',
      '#attributes' => [
        'placeholder' => t('Heb je wensen, vragen of is er iets anders dat je alvast wilt laten weten?')
      ]
    ];

    $form['address_wrapper']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Rondleiding aanvragen')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $tour_id = $form_state->getValue('TourScheduleOptout') == 1 ? NULL : $form_state->getValue('TourId');
    if ($tour_id) {
      $tour_schedules = ss_location_tour_schedules($form_state->getValue('LocationId'));

      $tour_valid = FALSE;
      foreach ($tour_schedules as $tour_schedule) {
        if (isset($tour_schedule[$tour_id])) {
          $tour_valid = TRUE;
        }
      }

      if ($tour_valid == FALSE) {
        $form_state->setErrorByName('tour_schedules', t('Application error. Tour cannot be booked.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::state();
    $crm_url = $config->get('services.crm');

    $campaign = $form_state->getValue('CampaignId');

    $services = $form_state->getValue('LocationServices');
    $LocationServices = [];
    foreach ($services as $service) {
      if ($service) {
        $LocationServices[] = $service;
      }
    }
    $PreferredDays = $form_state->getValue('PreferredDays');
    $PreferredDaysValues = $PreferredTimes = [];
    if ($PreferredDays) {
      foreach ($PreferredDays as $PreferredDay) {
        if ($PreferredDay) {
          $PreferredDaysValues[] = $PreferredDay;
          $PreferredTimes[$PreferredDay] = ($form_state->getValue($PreferredDay) != 0) ? $form_state->getValue($PreferredDay) : '';
        }
      }
    }
    $postcode = $form_state->getValue('AddressPostcode');
    $postcode = str_replace(' ', '', $postcode);
    $postcode = strtoupper($postcode);

    $collected_data = [
      'LocationId' => $form_state->getValue('LocationId'),
      'ContactReason' => $form_state->getValue('ContactReason'),
      'CampaignId' => $campaign,
      'Language' => $form_state->getValue('Language'),
      'Source' => $form_state->getValue('Source'),
      'AddressStreet' => $form_state->getValue('AddressStreet'),
      'AddressCity' => $form_state->getValue('AddressCity'),
      'TourDate' => $form_state->getValue('TourScheduleOptout') == 1 ? NULL : $form_state->getValue('TourDate') . ' ' . $form_state->getValue('TourTime'),
      'TourId' => $form_state->getValue('TourScheduleOptout') == 1 ? NULL : $form_state->getValue('TourId'),
      'TourScheduleOptout' => $form_state->getValue('TourScheduleOptout'),
      'LocationServices' => $LocationServices,
      'PreferredDays' => $PreferredDaysValues,
      'PreferredTimes' => $PreferredTimes,
      'NameTitle' => $form_state->getValue('NameTitle'),
      'NameFirst' => $form_state->getValue('NameFirst'),
      'NameMiddle' => $form_state->getValue('NameMiddle'),
      'NameLast' => $form_state->getValue('NameLast'),
      'AddressPostcode' => $postcode,
      'AddressHouseNumber' => $form_state->getValue('AddressHouseNumber'),
      'ContactPhone' => $form_state->getValue('ContactPhone'),
      'ContactEmail' => $form_state->getValue('ContactEmail'),
      'Accompany' => ($form_state->getValue('more') == 1) ? 'Ja' : 'Nee',
      'Remarks' => $form_state->getValue('Remarks')
    ];

    \Drupal::logger('location tour data')->notice('<pre>' . print_r($collected_data, 1) . '</pre>');
    $lead_id = strip_tags(ss_location_remote_request($crm_url, $collected_data));

    if ($lead_id && stripos($lead_id, 'Error') === FALSE) {
      //Type=Tour&Service=[KDV|BSO|PSZ]&Campaign=[CampaignId]
      $query = [
        'Type' => 'Tour',
        'Service' => $collected_data['LocationServices'],
        'Campaign' => $campaign
      ];

      if ($collected_data['TourId']) {
        ss_location_tour_book($collected_data['TourId'], $lead_id);
      }

      if (in_array('KDV', $LocationServices) && $config->get("thank_you_page.tour_kdv")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_kdv")], ['query' => $query]);
      }
      elseif (in_array('BSO', $LocationServices) && $config->get("thank_you_page.tour_bso")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_bso")], ['query' => $query]);
      }
      elseif (in_array('PSZ', $LocationServices) && $config->get("thank_you_page.tour_psz")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_psz")], ['query' => $query]);
      }
      else {
        $form_state->setRedirect('entity.ss_location.tour.thank', ['ss_location' => $form_state->getValue('Location')], ['query' => $query]);
      }
    }
    else {
      drupal_set_message(t('Application error. Response: ') . $lead_id, 'error');
    }
  }
}
