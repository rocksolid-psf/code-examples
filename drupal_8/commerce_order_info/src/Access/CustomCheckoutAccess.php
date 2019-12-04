<?php

namespace Drupal\commerce_order_info\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides the checkout form page.
 */
class CustomCheckoutAccess extends AccessInterface {

  /**
   * Custom access for Checkout form.
   *
   * @param RouteMatchInterface $route_match
   * @param AccountInterface $account
   * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   */
  public function access(RouteMatchInterface $route_match, AccountInterface $account) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $route_match->getParameter('commerce_order');
    if ($order->getState()->getId() == 'canceled') {
      return AccessResult::forbidden()->addCacheableDependency($order);
    }
    $access = AccessResult::allowed();
    return $access;
  }

}
