<?php

namespace Drupal\commerce_order_info\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $routes = [
      $collection->get('commerce_checkout.form'),
      $collection->get('commerce_payment.checkout.return'),
    ];
    foreach ($routes as $route) {
      $route->setRequirement('_custom_access', '\Drupal\commerce_order_info\Controller\CustomCheckoutController::orderCheckAccess');
      $route->setOption('no_cache', TRUE);
    }
  }
}