<?php

namespace Drupal\ss_location\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class TourForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ss_tour_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign = 0, $thank = 0) {
    $form['#attached']['library'][] = 'ss_location/generic_tour_form';
    $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_dates'] = NULL;
    $form['#attached']['drupalSettings']['ss_location']['tour_form']['schedules_date_hours'] = NULL;

    $form['thank'] = [
      '#type' => 'hidden',
      '#value' => $thank
    ];

    $form['ContactReason'] = [
      '#type' => 'hidden',
      '#value' => 'Tour'
    ];

    $form['LocationServices'] = [
      '#type' => 'value',
      '#value' => ss_location_get_campaign_services($campaign)
    ];

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

    $form_state->setCached(FALSE);

    $form['AddressPostcode'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Postcode'),
      ]
    ];

    $form['AddressHouseNumber'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Huisnummer'),
      ]
    ];

    $form['NameFirst'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Voornaam'),
      ]
    ];

    $form['address_wrapper']['NameLast'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Achternaam'),
      ]
    ];

    $form['ContactEmail'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('E-mail'),
        'data-rule-required' => 'true'
      ]
    ];

    $form['ContactPhone'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Telefoonnummer'),
        'pattern' => ".{8,12}"
      ]
    ];

    $form['LocationId'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#empty_option' => t('Kies locatie'),
      '#options' => ss_location_get_location_city_list($campaign),
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

    $LocationServices = $form_state->getValue('LocationServices');
    $PreferredDaysValues = $PreferredTimes = [];
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
      'ContactEmail' => $form_state->getValue('ContactEmail')
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

      if ($form_state->getValue('thank')) {
        $form_state->setRedirect('entity.node.canonical', ['node' => $form_state->getValue('thank')], ['query' => $query]);
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
