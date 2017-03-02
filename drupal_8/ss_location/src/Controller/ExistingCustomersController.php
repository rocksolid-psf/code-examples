<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Controller routines for location search autocomplete.
 */
class ExistingCustomersController extends ControllerBase {

  public function page() {
    $location_id = isset($_GET['location']) ? $_GET['location'] : NULL;

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $config = \Drupal::state();

    $url = NULL;
    if ($config->get('existing_customers.page.image')) {
      $file = File::load($config->get('existing_customers.page.image')[0]);
      $url = file_create_url($file->getFileUri());
    }

    $header = [
      'title' => $config->get('existing_customers.page.title'),
      'links' => [
        Link::fromTextAndUrl(t('Contact'), Url::fromRoute('entity.ss_location.existingcustomers', [], ['query' => $_GET, 'fragment' => 'contact'])),
        Link::fromTextAndUrl(t('Veelgestelde vragen'), Url::fromRoute('entity.ss_location.existingcustomers', [], ['query' => $_GET, 'fragment' => 'faq'])),
        Link::fromTextAndUrl(t('Medezeggenschap'), Url::fromRoute('entity.ss_location.existingcustomers', [], ['query' => $_GET, 'fragment' => 'participation'])),
        Link::fromTextAndUrl(t('Vragen, suggesties en klachten'), Url::fromRoute('entity.ss_location.existingcustomers', [], ['query' => $_GET, 'fragment' => 'question'])),
        Link::fromTextAndUrl(t('Vertrouwenspersoon'), Url::fromRoute('entity.ss_location.existingcustomers', [], ['query' => $_GET, 'fragment' => 'confidential'])),
      ],
      'image' => $url
    ];

    $breadcrumbs = [
      Link::fromTextAndUrl(t('Home'), Url::fromRoute('<front>')),
      $header['title']
    ];

    $search_form = \Drupal::formBuilder()->getForm('Drupal\ss_common\Form\SearchLocation', t('zoek op locatienaam'), 'contact');
    $search_form['searchtype']['#value'] = 'locatienaam';
    if ($location) {
      $search_form['search']['#value'] = $location->getName() . ' - ' . $location->getCity();
    }
    $search = [
      'title' => $config->get('existing_customers.page.search_title'),
      'text' => $config->get('existing_customers.page.search_text'),
      'form' => $search_form
    ];

    $faq = [
      'title' => t('Veelgestelde vragen'),
      'list' => []
    ];
    $query = \Drupal::database()->select('channela_ss_dashboard.FAQsRel', 'fr');
    $query->join('channela_ss_dashboard.FAQs', 'fs', 'fs.Id = fr.FAQ');
    $query->addField('fr', 'FAQ');
    $query->condition('fr.Rel', 6);
    $query->condition('fr.Type', 2);
    $query->orderBy('fs.FAQOrder');
    $query->range(0, 10);
    $ids = $query->execute()->fetchCol();

    $faqs = \Drupal::entityTypeManager()->getStorage('ss_faq')->loadMultiple($ids);
    foreach ($faqs as $faq_item) {
      $faq['list'][] = [
        'question' => $faq_item->getQuestion(),
        'answer' => $faq_item->getAnswer()
      ];
    }

    $teaching_plan = [
      'title' => $config->get('existing_customers.page.teaching_plan_title'),
      'text' => $config->get('existing_customers.page.teaching_plan_text'),
      'links' => [],
    ];
    if ($location) {
      $links_fields = [
        'getDataChildCarePlanKDV' => t('Werkplan KDV'),
        'getDataChildCarePlanBSO' => t('Werkplan BSO'),
        'getDataChildCarePlanPSZ' => t('Werkplan PSZ'),
        'getDataChildCarePlanKDVBranch' => t('Werkplan KDV Branch'),
        'getDataChildCarePlanBSOBranch' => t('Werkplan BSO Branch'),
        'getDataChildCarePlanPSZBranch' => t('Werkplan PSZ Branch'),
      ];
      foreach ($links_fields as $field => $title) {
        if ($location->$field()) {
          $teaching_plan['links'][] = [
            'title' => $title,
            'link' => $location->$field()
          ];
        }
      }
    }

    $health = [
      'title' => $config->get('existing_customers.page.health_title'),
      'text' => $config->get('existing_customers.page.health_text'),
      'links' => $config->get('existing_customers.page.health_files')
    ];

    $diet = [
      'title' => $config->get('existing_customers.page.diet_title'),
      'text' => $config->get('existing_customers.page.diet_text'),
      'links' => $config->get('existing_customers.page.diet_files')
    ];

    $security = [
      'title' => $config->get('existing_customers.page.security_title'),
      'text' => $config->get('existing_customers.page.security_text'),
      'links' => $config->get('existing_customers.page.security_files')
    ];

    $protocol = [
      'title' => $config->get('existing_customers.page.protocol_title'),
      'text' => $config->get('existing_customers.page.protocol_text'),
    ];

    $report = [
      'title' => $config->get('existing_customers.page.report_title'),
      'text' => $config->get('existing_customers.page.report_text'),
      'links' => []
    ];
    if ($location) {
      if ($location->getDataGGDReportLRKKDV() && $location->getServiceKDV() == 1) {
        $url = Url::fromUri($location->getDataGGDReportLRKKDV());
        $report['links'][] = Link::fromTextAndUrl(t('GGD rapport KDV'), $url);
      }
      if ($location->getDataGGDReportLRKBSO() && $location->getServiceBSO() == 1) {
        $url = Url::fromUri($location->getDataGGDReportLRKBSO());
        $report['links'][] = Link::fromTextAndUrl(t('GGD rapport BSO'), $url);
      }
      if ($location->getDataGGDReportLRKPSZ() && $location->getServicePSZ() == 1) {
        $url = Url::fromUri($location->getDataGGDReportLRKPSZ());
        $report['links'][] = Link::fromTextAndUrl(t('GGD rapport PSZ'), $url);
      }
      if ($location->getDataGGDReportLRKBranch()) {
        $url = Url::fromUri($location->getDataGGDReportLRKBranch());
        $report['links'][] = Link::fromTextAndUrl(t('GGD rapport Branch'), $url);
      }
    }

    $participation = [
      'title' => $config->get('existing_customers.page.participation_title'),
      'text' => $config->get('existing_customers.page.participation_text'),
      'image' => NULL,
    ];
    if ($config->get('existing_customers.page.participation_image')) {
      $file = File::load($config->get('existing_customers.page.participation_image')[0]);
      $participation['image'] = file_create_url($file->getFileUri());
    }

    $questions = [
      'title' => $config->get('existing_customers.page.questions_title'),
      'text' => $config->get('existing_customers.page.questions_text'),
      'image' => NULL,
      'button' => t('Neem contact op')
    ];
    if ($config->get('existing_customers.page.questions_image')) {
      $file = File::load($config->get('existing_customers.page.questions_image')[0]);
      $questions['image'] = file_create_url($file->getFileUri());
    }

    $questions_form = \Drupal::formBuilder()->getForm('Drupal\ss_location\Form\QuestionsSuggestionsForm', $location_id);

    $complaints = [
      'title' => $config->get('existing_customers.page.complaints_title'),
      'text' => $config->get('existing_customers.page.complaints_text'),
      'links' => $config->get('existing_customers.page.complaints_files'),
      'button_title' => $config->get('existing_customers.page.complaints_button_title'),
      'button_link' => $config->get('existing_customers.page.complaints_button_link')
    ];

    $complaints_form = \Drupal::formBuilder()->getForm('Drupal\ss_location\Form\ComplaintForm', $location_id, 'Complaints');

    $confidential = [
      'title' => $config->get('existing_customers.page.confidential_title'),
      'text' => $config->get('existing_customers.page.confidential_text')
    ];

    return [
      '#theme' => 'ss_location_existing_customers_page',
      '#header' => $header,
      '#breadcrumbs' => $breadcrumbs,
      '#search' => $search,
      '#contacts' => ss_location_contact_section($location_id),
      '#faq' => $faq,
      '#teaching_plan' => $teaching_plan,
      '#health' => $health,
      '#diet' => $diet,
      '#security' => $security,
      '#protocol' => $protocol,
      '#report' => $report,
      '#participation' => $participation,
      '#questions' => $questions,
      '#question_form' => $questions_form,
      '#complaints' => $complaints,
      '#complaint_form' => $complaints_form,
      '#consultants' => ss_location_consultants_info(),
      '#confidential' => $confidential
    ];
  }
}