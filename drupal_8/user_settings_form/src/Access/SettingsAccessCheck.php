<?php

namespace Drupal\user_settings_form\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Checks access for user routes.
 *
 * @see \Drupal\Core\Access\CustomAccessCheck
 */
class SettingsAccessCheck {

  /**
   * Checks access.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function checkAccess(RouteMatchInterface $route_match, AccountInterface $account) {
    $result = AccessResult::allowedIfHasPermissions($account, [
      'administer manage own site settings',
    ]);

    $current_user = $route_match->getParameter('user');
    if ($current_user == $account->id()) {
      $result = AccessResult::allowedIfHasPermissions($account, [
        'manage own site settings',
      ])->cachePerUser();
    }

    return $result;
  }

}
