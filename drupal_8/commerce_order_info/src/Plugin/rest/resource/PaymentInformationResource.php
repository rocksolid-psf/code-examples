<?php

namespace Drupal\commerce_order_info\Plugin\rest\resource;

use Drupal\Core\Url;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a resource to create and edit products form one request.
 *
 * @RestResource(
 *   id = "get_payment_information_link",
 *   label = @Translation("Get payment information link"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/payment-information/{id}",
 *   }
 * )
 */
class PaymentInformationResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new PaymentInformationResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger, $entity_type_manager);

    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('commerce_order_info'),
      $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Provides payment information by order id.
   *
   * @param $id
   *
   * @return ResourceResponse
   */
  public function get($id) {
    if (!$this->currentUser->hasPermission('update default commerce_order')) {
      throw new AccessDeniedHttpException();
    }
    // Get order by id.
    $order = $this->entityTypeManager->getStorage('commerce_order')->loadByProperties(['order_id' => (int) $id]);
    if ($order) {
      $order = reset($order);
      if ($this->currentUser->id() != $order->getCustomer()->id()) {
        throw new AccessDeniedHttpException();
      }
      if ($order->get('checkout_step')->getString() == 'complete' && !$order->get('payment_gateway')->isEmpty()) {
        $payment_gateway = $order->get('payment_gateway')->getEntity();
        $state = $payment_gateway->getState()->getString();
        if ($state == 'completed') {
          $url = Url::fromRoute('commerce_checkout.form', ['commerce_order' => $id, 'step' => 'complete'], ['absolute' => TRUE]);
          $data = [
            'order_id' => $id,
            'url' => $url->toString(TRUE)->getGeneratedUrl(),
          ];
        }
        else {
          $url = Url::fromRoute('commerce_checkout.form', ['commerce_order' => $id, 'step' => 'order_information'], ['absolute' => TRUE]);
          $data = [
            'order_id' => $id,
            'url' => $url->toString(TRUE)->getGeneratedUrl(),
          ];
        }
      }
      else {
        $url = Url::fromRoute('commerce_checkout.form', ['commerce_order' => $id, 'step' => 'order_information'], ['absolute' => TRUE]);
        $data = [
          'order_id' => $id,
          'url' => $url->toString(TRUE)->getGeneratedUrl(),
        ];
      }
      return new ResourceResponse($data, 200);
    }
    else {
      // If order not found.
      throw new AccessDeniedHttpException();
    }
  }

}
