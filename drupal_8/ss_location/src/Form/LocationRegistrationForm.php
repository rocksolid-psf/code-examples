<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LocationRegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $location = NULL) {
    $form['#attached']['library'][] = 'ss_location/registration_form';
    $form['#attached']['library'][] = 'ss_common/search';

    if (isset($_GET['additional'])) {
      $form['additional'] = [
        '#type' => 'markup',
        '#markup' => 'skip'
      ];
    }

    if (isset($_SESSION['location_reg_data']['Location']) && $_SESSION['location_reg_data']['Location'] != $location->getPath()) {
      unset($_SESSION['location_reg_data']);
    }

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
      '#value' => 'Registration'
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

    $form['step_1']['title'] = [
      '#type' => 'markup',
      '#markup' => t('Wat leuk dat je je kind wilt inschrijven bij Smallsteps @location', ['@location' => $location->getName()])
    ];

    $form['step_1']['intro'] = [
      '#type' => 'markup',
      '#markup' => gc('LocationFormRegistrationText')
    ];

//    $form['step_1']['search'] = [
//      '#type' => 'textfield',
//      '#attributes' => [
//        'placeholder' => t('zoek op locatienaam'),
//      ]
//    ];
//
//    $theme_path = drupal_get_path('theme', 'smallsteps');
//
//    $form['step_1']['searchsubmit'] = [
//      '#type' => 'image_button',
//      '#src' => $theme_path . '/images/search2.png',
//      '#limit_validation_errors' => []
//    ];

    $form['step_2']['title'] = [
      '#type' => 'markup',
      '#markup' => t('Wie wil je inschrijven?')
    ];

    $form['step_2']['ChildNameLast'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Achternaam'),
      ]
    ];

    $form['step_2']['ChildNameFirst'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Voornaam'),
      ]
    ];

    $form['step_2']['ChildNameOptout'] = [
      '#type' => 'checkbox',
      '#title' => t('Nog niet bekend')
    ];

    $form['step_2']['gendertitle'] = [
      '#type' => 'markup',
      '#markup' => t('Is het een jongen of een meisje?')
    ];

    $form['step_2']['ChildGender'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'jongen' => t('Jongen'),
        'meisje' => t('Meisje'),
        'onbekend' => t('Nog niet bekend')
      ],
    ];

    $form['step_2']['birthdatetitle'] = [
      '#type' => 'markup',
      '#markup' => t('Wat is de geboortedatum? (of wanneer verwacht je dat het kindje wordt geboren?)')
    ];

    $form['step_2']['ChildBirthDate'] = [
      '#type' => 'textfield',
      '#required' => TRUE
    ];

    $form['step_2']['childadditionaltitle'] = [
      '#type' => 'markup',
      '#markup' => t('Heb je andere kinderen die al zijn ingeschreven bij Smallsteps?')
    ];

    $form['step_2']['ChildAdditional'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'Ja' => t('Ja'),
        'Nee' => t('Nee'),
      ],
    ];

    $services_options = [];
    $default_service = NULL;
    if ($location->getServiceKDV() == 1) {
      $services_options['KDV'] = LOCATION_SERVICES_NAMES['KDV'];
      $default_service = $default_service ? $default_service : 'KDV';
    }

    if ($location->getServicePSZ() == 1) {
      $services_options['PSZ'] = LOCATION_SERVICES_NAMES['PSZ'];
      $default_service = $default_service ? $default_service : 'PSZ';
    }

    if ($location->getServiceBSO() == 1) {
      $services_options['BSO'] = LOCATION_SERVICES_NAMES['BSO'];
      $default_service = $default_service ? $default_service : 'BSO';
    }

    $location_services_count = count($services_options);

    if ($location_services_count > 1) {
      $form['step_3']['title'] = [
        '#type' => 'markup',
        '#markup' => t('Waar ben je naar op zoek?')
      ];

      $form['step_3']['LocationServices'] = [
        '#title' => NULL,
        '#required' => TRUE,
        '#type' => 'radios',
        '#options' => $services_options,
        '#default_value' => isset($_SESSION['location_reg_data']['LocationServices']) ? $_SESSION['location_reg_data']['LocationServices'] : $default_service
      ];
    }

    if ($location_services_count == 1) {
      $form['step_3']['title'] = [
        '#type' => 'markup',
        '#markup' => t('Je bent bezig met inschrijving voor: @service', ['@service' => $services_options[$default_service]])
      ];

      $form['services_wrapper']['LocationServices'] = [
        '#type' => 'value',
        '#value' => key($services_options)
      ];

      $form['#attached']['drupalSettings']['ss_location']['registration']['service'] = key($services_options);
    }

    if (isset($services_options['BSO'])) {
      $form['step_3']['LocationServiceBSOTitle'] = [
        '#type' => 'markup',
        '#markup' => t('Om welke type opvang gaat het?')
      ];

      $form['step_3']['LocationServiceBSO'] = [
        '#type' => 'checkboxes',
        '#options' => LOCATION_BSO_SERVICES,
        '#default_value' => isset($_SESSION['location_reg_data']['LocationServiceBSO']) ? $_SESSION['location_reg_data']['LocationServiceBSO'] : []
      ];

      $form['step_3']['PlacementSchoolTitle'] = [
        '#type' => 'markup',
        '#markup' => t('Naar welke school gaat je kind?')
      ];

      $form['step_3']['PlacementSchool'] = [
        '#type' => 'textfield',
        '#attributes' => [
          'placeholder' => t('Naam school'),
        ],
        '#default_value' => isset($_SESSION['location_reg_data']['PlacementSchool']) ? $_SESSION['location_reg_data']['PlacementSchool'] : NULL
      ];
    }

    $form['step_3']['PlacementDateTitle'] = [
      '#type' => 'markup',
      '#markup' => t('Wanneer wil je starten met de opvang?')
    ];

    $form['step_3']['PlacementDate'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => isset($_SESSION['location_reg_data']['PlacementDate']) ? $_SESSION['location_reg_data']['PlacementDate'] : NULL
    ];

    $form['step_3']['PlacementDaysTitle'] = [
      '#type' => 'markup',
      '#markup' => t('Om welke dagen gaat het?')
    ];

    $form['step_3']['PlacementDays'] = [
      '#required' => TRUE,
      '#type' => 'checkboxes',
      '#options' => [
        'Monday' => t('Maandag'),
        'Tuesday' => t('Dinsdag'),
        'Wednesday' => t('Woensdag'),
        'Thursday' => t('Donderdag'),
        'Friday' => t('Vrijdag'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['PlacementDays']) ? $_SESSION['location_reg_data']['PlacementDays'] : []
    ];

    $form['step_3']['RemarksTitle'] = [
      '#type' => 'markup',
      '#markup' => t('Typ hier je vraag of opmerking')
    ];

    $form['step_3']['Remarks'] = [
      '#type' => 'textarea',
      '#attributes' => [
        'placeholder' => t('Type hier je tekst...')
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['Remarks']) ? $_SESSION['location_reg_data']['Remarks'] : NULL
    ];

    $form['step_4']['title'] = [
      '#type' => 'markup',
      '#markup' => t('Zou je je gegevens willen invullen, zodat we contact met je kunnen opnemen?')
    ];

    $form['step_4']['NameTitle'] = [
      '#type' => 'radios',
      '#options' => [
        'mevrouw' => t('Mevrouw'),
        'heer' => t('Meneer')
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['NameTitle']) ? $_SESSION['location_reg_data']['NameTitle'] : NULL
    ];

    $form['step_4']['NameFirst'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Voornaam'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['NameFirst']) ? $_SESSION['location_reg_data']['NameFirst'] : NULL
    ];

    $form['step_4']['NameMiddle'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Tussenvoegsel'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['NameMiddle']) ? $_SESSION['location_reg_data']['NameMiddle'] : NULL
    ];

    $form['step_4']['NameLast'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Achternaam'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['NameLast']) ? $_SESSION['location_reg_data']['NameLast'] : NULL
    ];

    $form['step_4']['AddressPostcode'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Postcode'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['AddressPostcode']) ? $_SESSION['location_reg_data']['AddressPostcode'] : NULL
    ];

    $form['step_4']['AddressHouseNumber'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Huisnummer'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['AddressHouseNumber']) ? $_SESSION['location_reg_data']['AddressHouseNumber'] : NULL
    ];

    $form['step_4']['AddressAdditional'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Toevoeging'),
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['AddressAdditional']) ? $_SESSION['location_reg_data']['AddressAdditional'] : NULL
    ];

    $form['step_4']['AddressStreet'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Straatnaam'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['AddressStreet']) ? $_SESSION['location_reg_data']['AddressStreet'] : NULL
    ];

    $form['step_4']['AddressCity'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Woonplaats'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['AddressCity']) ? $_SESSION['location_reg_data']['AddressCity'] : NULL
    ];

    $form['step_4']['ContactEmail'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('E-mail'),
        'data-rule-required' => 'true'
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['ContactEmail']) ? $_SESSION['location_reg_data']['ContactEmail'] : NULL
    ];

    $form['step_4']['ContactPhone'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Telefoonnummer'),
        'pattern' => ".{8,12}"
      ],
      '#default_value' => isset($_SESSION['location_reg_data']['ContactPhone']) ? $_SESSION['location_reg_data']['ContactPhone'] : NULL
    ];

    $full_address = [];
    $full_address[] = 'Smallsteps ' . $location->getName();
    $full_address[] = $location->getStreetAddress();
    $full_address[] = $location->getPostCode() . ' ' . $location->getCity();
    $form['step_5']['locationaddress'] = [
      '#type' => 'markup',
      '#markup' => implode('<br />', $full_address)
    ];

    $form['step_5']['NameFirst'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['NameFirst']) ? $_SESSION['location_reg_data']['NameFirst'] : NULL
    ];

    $form['step_5']['NameLast'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['NameLast']) ? $_SESSION['location_reg_data']['NameLast'] : NULL
    ];

    $form['step_5']['AddressStreet'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['AddressStreet']) ? $_SESSION['location_reg_data']['AddressStreet'] : NULL
    ];

    $form['step_5']['AddressHouseNumber'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['AddressHouseNumber']) ? $_SESSION['location_reg_data']['AddressHouseNumber'] : NULL
    ];

    $form['step_5']['AddressAdditional'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['AddressAdditional']) ? $_SESSION['location_reg_data']['AddressAdditional'] : NULL
    ];

    $form['step_5']['AddressPostcode'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['AddressPostcode']) ? $_SESSION['location_reg_data']['AddressPostcode'] : NULL
    ];

    $form['step_5']['AddressCity'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['AddressCity']) ? $_SESSION['location_reg_data']['AddressCity'] : NULL
    ];

    $form['step_5']['ContactPhone'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['ContactPhone']) ? $_SESSION['location_reg_data']['ContactPhone'] : NULL
    ];

    $form['step_5']['ContactEmail'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['ContactEmail']) ? $_SESSION['location_reg_data']['ContactEmail'] : NULL
    ];

    $form['step_5']['PlacementDate'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['PlacementDate']) ? $_SESSION['location_reg_data']['PlacementDate'] : NULL
    ];

    $form['step_5']['LocationServices'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['LocationServices']) ? $_SESSION['location_reg_data']['LocationServices'] : NULL
    ];

    if ($location_services_count == 1) {
      $form['step_5']['LocationServices']['#markup'] = $services_options[$default_service];
    }

    $form['step_5']['PlacementDays'] = [
      '#type' => 'markup',
      '#markup' => isset($_SESSION['location_reg_data']['PlacementDays']) ? implode(', ', $_SESSION['location_reg_data']['PlacementDays']) : NULL
    ];

    $form['step_5']['ChildAddTitle'] = [
      '#type' => 'markup',
      '#markup' => t('Wil je nog een kind aanmelden?')
    ];

    $form['step_5']['ChildAdd'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        '1' => 'Ja',
        '-1' => 'Nee'
      ],
      '#default_value' => 'nee'
    ];

    $form['step_5']['ChildAddText'] = [
      '#type' => 'markup',
      '#markup' => t('Verstuur eerst de aanmelding de aanmelding voor dit kind. Daarna ga je automatisch verder met de aanmelding van het volgende kind.')
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Verstuur')
    ];

    return $form;
  }

  /**
   * Implements a form submit handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::state();
    $crm_url = $config->get('services.crm');

    $campaign = $form_state->getValue('CampaignId');

    $LocationServices = $form_state->getValue('LocationServices');
    $LocationServicesBSO = [];
    if ($LocationServices == 'BSO') {
      $services = $form_state->getValue('LocationServiceBSO');
      foreach ($services as $service) {
        if ($service) {
          $LocationServicesBSO[] = $service;
        }
      }
    }
    $placement_days = $form_state->getValue('PlacementDays');
    $PlacementDays = [];
    foreach ($placement_days as $placement_day) {
      if ($placement_day) {
        $PlacementDays[] = $placement_day;
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
      'LocationServices' => $LocationServices,
      'LocationServiceBSO' => $LocationServicesBSO,
      'PlacementDays' => $PlacementDays,
      'PlacementDate' => $form_state->getValue('PlacementDate'),
      'PlacementSchool' => $form_state->getValue('PlacementSchool'),
      'ChildBirthStatus' => $form_state->getValue('ChildNameOptout'),
      'ChildBirthDate' => $form_state->getValue('ChildBirthDate'),
      'ChildGender' => $form_state->getValue('ChildGender'),
      'ChildNameFirst' => $form_state->getValue('ChildNameFirst'),
      'ChildNameLast' => $form_state->getValue('ChildNameLast'),
      'ChildNameOptout' => $form_state->getValue('ChildNameOptout'),
      'ChildAdditional' => $form_state->getValue('ChildAdditional'),
      'NameTitle' => $form_state->getValue('NameTitle'),
      'NameFirst' => $form_state->getValue('NameFirst'),
      'NameMiddle' => $form_state->getValue('NameMiddle'),
      'NameLast' => $form_state->getValue('NameLast'),
      'AddressPostcode' => $postcode,
      'AddressHouseNumber' => $form_state->getValue('AddressHouseNumber'),
      'AddressStreet' => $form_state->getValue('AddressStreet'),
      'AddressCity' => $form_state->getValue('AddressCity'),
      'ContactPhone' => $form_state->getValue('ContactPhone'),
      'ContactEmail' => $form_state->getValue('ContactEmail'),
      'Remarks' => $form_state->getValue('Remarks'),
      'ChildAdd' => $form_state->getValue('ChildAdd')
    ];

    $_SESSION['location_reg_data'] = $collected_data;
    $_SESSION['location_reg_data']['Location'] = $form_state->getValue('Location');

    \Drupal::logger('location reg data')->notice('<pre>' . print_r($collected_data, 1) . '</pre>');
    $lead_id = strip_tags(ss_location_remote_request($crm_url, $collected_data));

    if ($lead_id && stripos($lead_id, 'Error') === FALSE) {
      //Type=Registration&Service=[KDV|BSO|PSZ]&Campaign=[CampaignId]
      $query = [
        'Type' => 'Registration',
        'Service' => $collected_data['LocationServices'],
        'Campaign' => $campaign
      ];

      if ($LocationServices == 'KDV' && $config->get("thank_you_page.registration_kdv")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.registration_kdv")], ['query' => $query]);
      }
      elseif ($LocationServices == 'BSO' && $config->get("thank_you_page.registration_bso")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.registration_bso")], ['query' => $query]);
      }
      elseif ($LocationServices == 'PSZ' && $config->get("thank_you_page.registration_psz")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.registration_psz")], ['query' => $query]);
      }
      else {
        $form_state->setRedirect('entity.ss_location.registration.thank', ['ss_location' => $form_state->getValue('Location')], ['query' => $query]);
      }
    }
    else {
      drupal_set_message(t('Application error. Response: ') . $lead_id, 'error');
    }
  }
}
