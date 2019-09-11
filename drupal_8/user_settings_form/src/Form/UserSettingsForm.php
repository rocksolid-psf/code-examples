<?php

namespace Drupal\user_settings_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\user\UserData;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSettingsForm.
 */
class UserSettingsForm extends FormBase {

  /**
   * Drupal\Core\Routing\RouteMatchInterface definition.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Drupal\user\UserData definition.
   *
   * @var \Drupal\user\UserData
   */
  protected $userData;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Get user id.
   * @param \Drupal\user\UserData $user_data
   *   Service user.data.
   */
  public function __construct(RouteMatchInterface $route_match, UserData $user_data) {
    $this->routeMatch = $route_match;
    $this->userData = $user_data;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('user.data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $uid = $this->routeMatch->getParameter('user');
    $color = $this->userData->get('user_settings_form', $uid, 'site_background_color');

    $form['site_background_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Site background color'),
      '#options' => [
        'red' => $this->t('Red'),
        'black' => $this->t('Black'),
        'white' => $this->t('White'),
        'yellow' => $this->t('Yellow'),
        'blue' => $this->t('Blue'),
        'orange' => $this->t('Orange'),
        'green' => $this->t('Green'),
        'silver' => $this->t('Silver'),
        'pink' => $this->t('Pink'),
        'gray' => $this->t('Gray'),
      ],
      '#default_value' => $color,
      '#empty_option' => $this->t('- Select -'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $colorValue = $form_state->getValue('site_background_color');
    $uid = $this->routeMatch->getParameter('user');
    $this->userData->set('user_settings_form', $uid, 'site_background_color', $colorValue);
  }

}
