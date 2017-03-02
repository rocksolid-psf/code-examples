<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LocationCalculatorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_calculator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $location = NULL) {
    $form['#attached']['library'][] = 'ss_location/contact_form';

    $form['ContactReasonTemp'] = [
      '#type' => 'radios',
      '#options' => [
        'Question' => t('Vraag'),
        'Suggestion' => t('Suggestie')
      ],
      '#required' => TRUE
    ];

    $form['QuestionSubject'] = [
      '#type' => 'radios',
      '#options' => [
        'Een offerte' => t('Een offerte'),
        'De startdatum voor mijn kind' => t('De startdatum voor mijn kind'),
        'Een factuur / betaling' => t('Een factuur / betaling'),
        'Mijn overeenkomst' => t('Mijn overeenkomst'),
        'Anders' => t('Anders'),
      ],
      '#required' => TRUE
    ];

    $form['QuestionSubjectAdditional'] = [
      '#type' => 'radios',
      '#options' => [
        'Wijzigen mijn gegevens' => t('Wijzigen mijn gegevens'),
        'Wijzigen overeenkomst' => t('Wijzigen overeenkomst'),
        'Opzeggen overeenkomst' => t('Opzeggen overeenkomst'),
        'Overig' => t('Overig'),
      ],
      '#required' => TRUE
    ];

    $form['SendTo'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'Location' => t('Locatie'),
        'CustomerService' => t('Medewerker klantenservice')
      ],
      '#default_value' => 'Location'
    ];
    
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
      '#value' => 'Calculator'
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

    $form['NameTitle'] = [
      '#type' => 'radios',
      '#options' => [
        'mevrouw' => t('Mevrouw'),
        'heer' => t('Meneer')
      ],
      '#required' => TRUE
    ];

    $form['NameFirst'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Voornaam *'),
      ]
    ];

    $form['NameMiddle'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Tussenvoegsel'),
      ]
    ];

    $form['NameLast'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Achternaam *'),
      ]
    ];

    $form['AddressPostcode'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Postcode *'),
      ]
    ];

    $form['AddressHouseNumber'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Huisnummer *'),
      ]
    ];

    $form['AddressAdditional'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Toevoeging'),
      ]
    ];

    $form['AddressStreet'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Straatnaam'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ]
    ];

    $form['AddressCity'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Woonplaats'),
        'readonly' => 'readonly',
        'class' => ['hidden']
      ]
    ];

    $form['ContactEmail'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('E-mail *'),
        'data-rule-required' => 'true'
      ]
    ];

    $form['ContactPhone'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Telefoonnummer *'),
        'pattern' => ".{8,12}"
      ]
    ];

    $form['more'] = [
      '#type' => 'checkbox',
      '#title' => t('Ik neem iemand mee.'),
    ];

    $form['Remarks'] = [
      '#type' => 'textarea',
      '#attributes' => [
        'placeholder' => t('Heb je wensen, vragen of is er iets anders dat je alvast wilt laten weten?')
      ]
    ];

    $form['ChildNameFirst'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Naam kind'),
      ]
    ];

    $form['ChildBirthDate'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => t('Geboortedatum kind'),
      ]
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Rondleiding aanvragen')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::state();
    $crm_url = $config->get('services.crm');

    $campaign = $form_state->getValue('CampaignId');

    $postcode = $form_state->getValue('AddressPostcode');
    $postcode = str_replace(' ', '', $postcode);
    $postcode = strtoupper($postcode);

    $collected_data = [
      'LocationId' => $form_state->getValue('LocationId'),
      'ContactReason' => $form_state->getValue('ContactReason'),
      'ContactReasonTemp' => $form_state->getValue('ContactReasonTemp'),
      'QuestionSubject' => $form_state->getValue('QuestionSubject'),
      'SendTo' => $form_state->getValue('SendTo'),
      'CampaignId' => $campaign,
      'Language' => $form_state->getValue('Language'),
      'AddressStreet' => $form_state->getValue('AddressStreet'),
      'AddressCity' => $form_state->getValue('AddressCity'),
      'NameTitle' => $form_state->getValue('NameTitle'),
      'NameFirst' => $form_state->getValue('NameFirst'),
      'NameMiddle' => $form_state->getValue('NameMiddle'),
      'NameLast' => $form_state->getValue('NameLast'),
      'AddressPostcode' => $postcode,
      'AddressHouseNumber' => $form_state->getValue('AddressHouseNumber'),
      'ContactPhone' => $form_state->getValue('ContactPhone'),
      'ContactEmail' => $form_state->getValue('ContactEmail'),
      'Accompany' => ($form_state->getValue('more') == 1) ? 'Ja' : 'Nee',
      'Remarks' => $form_state->getValue('Remarks'),
      'ChildNameFirst' => $form_state->getValue('ChildNameFirst'),
      'ChildBirthDate' => $form_state->getValue('ChildBirthDate'),
    ];

    \Drupal::logger('location tour data')->notice('<pre>' . print_r($collected_data, 1) . '</pre>');
    $lead_id = strip_tags(ss_location_remote_request($crm_url, $collected_data));

    if ($lead_id && stripos($lead_id, 'Error') === FALSE) {
      //Type=Tour&Service=[KDV|BSO|PSZ]&Campaign=[CampaignId]
      $query = [
        'Type' => 'Tour',
//        'Service' => $collected_data['LocationServices'],
        'Campaign' => $campaign
      ];

      if ($collected_data['TourId']) {
        ss_location_tour_book($collected_data['TourId'], $lead_id);
      }

//      if (in_array('KDV', $LocationServices) && $config->get("thank_you_page.tour_kdv")) {
//        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_kdv")], ['query' => $query]);
//      }
//      elseif (in_array('BSO', $LocationServices) && $config->get("thank_you_page.tour_bso")) {
//        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_bso")], ['query' => $query]);
//      }
//      elseif (in_array('PSZ', $LocationServices) && $config->get("thank_you_page.tour_psz")) {
//        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.tour_psz")], ['query' => $query]);
//      }
//      else {
//        $form_state->setRedirect('entity.ss_location.tour.thank', ['ss_location' => $form_state->getValue('Location')], ['query' => $query]);
//      }
      drupal_set_message(t('Thank you'));
    }
//    else {
//      drupal_set_message(t('Application error. Response: ') . $lead_id, 'error');
//    }
  }
}
