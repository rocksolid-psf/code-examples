<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class QuestionsSuggestionsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_location_questions_suggestions_form';
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

    $form['#attached']['library'][] = 'ss_location/contact_form';

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

    $form['title'] = [
      '#type' => 'markup',
      '#markup' => t('Vragen en suggesties')
    ];

    $form['ContactReason'] = [
      '#type' => 'radios',
      '#title' => t('Ik heb een'),
      '#required' => TRUE,
      '#options' => [
        'Question' => t('Vraag'),
        'Suggestion' => t('Suggestie')
      ],
      '#default_value' => 'Question'
    ];

    $form['QuestionSubject'] = [
      '#type' => 'radios',
      '#title' => t('Mijn vraag gaat over (graag aankruisen wat van toepassing is)'),
      '#options' => [
        'Een offerte' => t('Een offerte'),
        'De startdatum voor mijn kind' => t('De startdatum voor mijn kind'),
        'Een factuur / betaling' => t('Een factuur / betaling'),
        'Mijn overeenkomst' => t('Mijn overeenkomst'),
        'Anders' => t('Anders')
      ]
    ];

    $form['QuestionSubjectAdditional'] = [
      '#type' => 'radios',
      '#options' => [
        'Wijzigen mijn gegevens' => t('Wijzigen mijn gegevens'),
        'Wijzigen overeenkomst' => t('Wijzigen overeenkomst'),
        'Opzeggen overeenkomst' => t('Opzeggen overeenkomst'),
        'Overig' => t('Overig')
      ]
    ];
    
    $form['Remarks'] = [
      '#type' => 'textarea',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Vul hier je vraag in*')
      ]
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

    $form['LocationId'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#empty_option' => t('- Kies een locatie - *'),
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
      'SendTo' => $form_state->getValue('SendTo'),
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
      'ChildNameFirst' => $form_state->getValue('ChildNameFirst'),
      'ChildBirthDate' => $form_state->getValue('ChildBirthDate')
    ];

    if ($collected_data['ContactReason'] == 'Question') {
      $collected_data['QuestionSubject'] = $form_state->getValue('QuestionSubject');
      $collected_data['QuestionSubjectAdditional'] = $form_state->getValue('QuestionSubjectAdditional');
    }

    $lead_id = strip_tags(ss_location_remote_request($crm_url, $collected_data));

    if ($lead_id && stripos($lead_id, 'Error') === FALSE) {
      //Type=Question&Campaign=[CampaignId]
      //Type=Suggestion&Campaign=[CampaignId]
      $query = [
        'Type' => $collected_data['ContactReason'],
        'Campaign' => $campaign
      ];

      if ($collected_data['ContactReason'] == 'Complaint' && $config->get("thank_you_page.complaint")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.complaint")], ['query' => $query]);
      }
      elseif ($collected_data['ContactReason'] == 'Question' && $config->get("thank_you_page.question")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.question")], ['query' => $query]);
      }
      elseif ($collected_data['ContactReason'] == 'Suggestion' && $config->get("thank_you_page.suggestion")) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $config->get("thank_you_page.suggestion")], ['query' => $query]);
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
