<?php

namespace Drupal\ss_location;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the Location entity type.
 */
class LocationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    if ($operation == 'view') {
      return AccessResult::allowedIfHasPermission($account, 'view location entity');
    }

    // No opinion.
    return AccessResult::neutral();
  }

}
