<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for faq entity view.
 */
class MeetingController extends ControllerBase {

  public function page() {

    $config = \Drupal::state();

    $meeting_form = \Drupal::formBuilder()->getForm('Drupal\ss_location\Form\MeetingForm', 'Complaints');
    
    return [
      '#theme' => 'ss_location_meeting_page',
      '#text' => $config->get('meeting.page.text'),
      '#form' => $meeting_form,
    ];
  }
}