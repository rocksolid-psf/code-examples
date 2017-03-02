<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class MeetingForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_meeting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $location_id = NULL, $contact_reason = NULL) {
    $location = NULL;

    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()
        ->getStorage('ss_location');
      $location = $location_storage->load($location_id);
    }

    $form['#attached']['library'][] = 'ss_location/meeting_form';

    $campaign = 16;

    $form['CampaignId'] = [
      '#type' => 'hidden',
      '#value' => $campaign
    ];

    $form['Language'] = [
      '#type' => 'hidden',
      '#value' => t('Dutch')
    ];

    $form['Source'] = [
      '#type' => 'hidden',
      '#value' => 1
    ];

    $form['title'] = [
      '#type' => 'markup',
      '#markup' => t('Ik heb een klacht')
    ];

    $form['ContactReason'] = [
      '#type' => 'hidden',
      '#value' => 'Other'
    ];

    $form['Remarks'] = [
      '#type' => 'textarea',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Tijdens de bijeenkomst zou ik graag willen praten over:')
      ]
    ];

    $form['LocationId'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#empty_option' => t('Ik ben lid van de oudercommissie van:'),
      '#options' => ss_location_get_location_list_simple(),
      '#default_value' => $location ? $location->getId() : $location
    ];

    $form['NameTitle'] = [
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'mevrouw' => t('Mevrouw'),
        'heer' => t('Meneer')
      ],
      '#default_value' => 'mevrouw'
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

    $services_options = [
      'KDV' => t('kinderdagverblijf'),
      'BSO' => t('buitenschoolse opvang'),
    ];

    $form['LocationServices'] = [
      '#type' => 'checkboxes',
      '#options' => $services_options,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Inschrijving versturen')
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
    
    $services = $form_state->getValue('LocationServices');
    $LocationServices = [];
    foreach ($services as $service) {
      if ($service) {
        $LocationServices[] = $service;
      }
    }
    
    $crm_url = $config->get('services.crm');

    $campaign = $form_state->getValue('CampaignId');

    $postcode = $form_state->getValue('AddressPostcode');
    $postcode = str_replace(' ', '', $postcode);
    $postcode = strtoupper($postcode);

    $collected_data = [
      'LocationId' => $form_state->getValue('LocationId'),
      'ContactReason' => $form_state->getValue('ContactReason'),
      'CampaignId' => $campaign,
      'Language' => $form_state->getValue('Language'),
      'Source' => $form_state->getValue('Source'),
      'Remarks' => $form_state->getValue('Remarks'),
      'NameTitle' => $form_state->getValue('NameTitle'),
      'NameFirst' => $form_state->getValue('NameFirst'),
      'NameMiddle' => $form_state->getValue('NameMiddle'),
      'NameLast' => $form_state->getValue('NameLast'),
      'AddressPostcode' => $postcode,
      'AddressHouseNumber' => $form_state->getValue('AddressHouseNumber'),
      'AddressAdditional' => $form_state->getValue('AddressAdditional'),
      'AddressStreet' => $form_state->getValue('AddressStreet'),
      'AddressCity' => $form_state->getValue('AddressCity'),
      'ContactEmail' => $form_state->getValue('ContactEmail'),
      'ContactPhone' => $form_state->getValue('ContactPhone'),
      'LocationServices' => $LocationServices,
    ];

    if ($collected_data['ContactReason'] == 'Question') {
      $collected_data['QuestionSubject'] = $form_state->getValue('QuestionSubject');
      $collected_data['QuestionSubjectAdditional'] = $form_state->getValue('QuestionSubjectAdditional');
    }

    $lead_id = strip_tags(ss_location_remote_request($crm_url, $collected_data));

    if ($lead_id && stripos($lead_id, 'Error') === FALSE) {
      //Type=Complaint&Campaign=[CampaignId]

      $query = [
        'Type' => $collected_data['ContactReason'],
        'Service' => $collected_data['LocationServices'],
        'Campaign' => $campaign,
      ];

      if ($config->get("thank_you_page.meeting")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.meeting")], ['query' => $query]);
      }
      else {
        drupal_set_message(t('Thank you'));
      }
    }
    else {
      drupal_set_message(t('Application error. Response: ') . $lead_id, 'error');
    }
  }
}
