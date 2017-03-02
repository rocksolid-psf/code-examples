<?php

namespace Drupal\ss_location\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for faq entity view.
 */
class FAQController extends ControllerBase {

  public function page() {

    $categories = [
      1 => 'Location Landing',
      2 => 'Register and Placement',
      3 => 'Payments and Invoices',
      4 => 'Childcare Allowance',
      5 => 'Miscellaneous',
      6 => 'Existing Customer'
    ];

    $faqs_list = [];
    foreach ($categories as $id => $category) {

      $query = \Drupal::database()->select('channela_ss_dashboard.FAQsRel', 'fr');
      $query->join('channela_ss_dashboard.FAQs', 'fs', 'fs.Id = fr.FAQ');
      $query->addField('fr', 'FAQ');
      $query->condition('fr.Rel', $id);
      $query->condition('fr.Type', 2);
      $query->orderBy('fs.FAQOrder');
      $query->range(0, 10);
      $ids = $query->execute()->fetchCol();

      $faq_list = \Drupal::entityTypeManager()->getStorage('ss_faq')->loadMultiple($ids);

      $list = [];
      foreach ($faq_list as $faq) {
        $list[] = [
          'question' => $faq->getQuestion(),
          'answer' => $faq->getAnswer()
        ];
      }

      $faqs_list[] = [
        'name' => $category,
        'list' => $list
      ];
    }

    return [
      '#theme' => 'ss_location_faq_page',
      '#faqs' => $faqs_list
    ];
  }
}