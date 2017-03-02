<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Controller routines for location search autocomplete.
 */
class LocationContactController extends ControllerBase {

  public function page() {
    $location_id = isset($_GET['location']) ? $_GET['location'] : NULL;
    $question_form_status = 'none';

    if (isset($_SERVER['HTTP_REFERER'])) {
      $url = parse_url($_SERVER['HTTP_REFERER']);
      $url_object = \Drupal::service('path.validator')->getUrlIfValid($url['path']);

      $params = $url_object->getRouteParameters();

      if (isset($params['ss_location'])) {
        $location_id = $params['ss_location'];
        $question_form_status = 'block';
      }
    }

    $location = NULL;
    if ($location_id) {
      $location_storage = \Drupal::entityTypeManager()->getStorage('ss_location');
      $location = $location_storage->load($location_id);;
    }

    $config = \Drupal::state();

    $url = NULL;
    if ($config->get('contact.page.image')) {
      $file = File::load($config->get('contact.page.image')[0]);
      $url = file_create_url($file->getFileUri());
    }

    $header = [
      'title' => $config->get('contact.page.title'),
      'links' => [
        Link::fromTextAndUrl($config->get('contact.page.links_location'), Url::fromRoute('entity.ss_location.contact', [], ['query' => $_GET, 'fragment' => 'search'])),
        Link::fromTextAndUrl($config->get('contact.page.links_service'), Url::fromRoute('entity.ss_location.contact', [], ['query' => $_GET, 'fragment' => 'contact'])),
        Link::fromTextAndUrl($config->get('contact.page.links_question'), Url::fromRoute('entity.ss_location.contact', [], ['query' => $_GET, 'fragment' => 'question'])),
        Link::fromTextAndUrl($config->get('contact.page.links_confidential'), Url::fromRoute('entity.ss_location.contact', [], ['query' => $_GET, 'fragment' => 'confidential']))
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
      'title' => $config->get('contact.page.search_title'),
      'text' => $config->get('contact.page.search_text'),
      'form' => $search_form
    ];

    $url = NULL;
    if ($config->get('contact.page.questions_image')) {
      $file = File::load($config->get('contact.page.questions_image')[0]);
      $url = file_create_url($file->getFileUri());
    }

    $questions = [
      'title' => $config->get('contact.page.questions_title'),
      'text' => $config->get('contact.page.questions_text'),
      'image' => $url,
      'button' => t('Neem contact op')
    ];

    $questions_form = \Drupal::formBuilder()->getForm('Drupal\ss_location\Form\QuestionsSuggestionsForm', $location_id);

    $files_fields = [
      'complaints_file_1',
      'complaints_file_2',
      'complaints_file_3',
      'complaints_file_4',
      'complaints_file_5'
    ];

    $files = [];
    foreach ($files_fields as $file_field) {
      if ($config->get("contact.page.$file_field")) {
        $file = File::load($config->get("contact.page.$file_field")[0]);
        $files[] = Link::fromTextAndUrl($file->getFilename(), Url::fromUri(file_create_url($file->getFileUri()), ['attributes' => ['download' => TRUE]]));
      }
    }

    $complaints = [
      'title' => $config->get('contact.page.complaints_title'),
      'text' => $config->get('contact.page.complaints_text'),
      'subtitle' => t('Downloads'),
      'links' => $files,
      'button' => t('Neem contact op')
    ];

    $complaints_form = \Drupal::formBuilder()->getForm('Drupal\ss_location\Form\ComplaintForm', $location_id, 'Complaints');

    $confidential = [
      'title' => $config->get('contact.page.confidential_title'),
      'text' => $config->get('contact.page.confidential_text')
    ];

    return [
      '#theme' => 'ss_location_contact_page',
      '#header' => $header,
      '#breadcrumbs' => $breadcrumbs,
      '#search' => $search,
      '#contacts' => ss_location_contact_section($location_id),
      '#questions' => $questions,
      '#question_form_status' => $question_form_status,
      '#question_form' => $questions_form,
      '#complaints' => $complaints,
      '#complaint_form' => $complaints_form,
      '#consultants' => ss_location_consultants_info(),
      '#confidential' => $confidential
    ];
  }
}