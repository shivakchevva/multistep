<?php

/**
 * @file
 * Contains \Drupal\custom_multistep\Form\Multistep\MultistepFormBase.
 */

namespace Drupal\custom_multistep\Form\Multistep;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class MultistepFormBase extends FormBase {

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * Constructs a \Drupal\demo\Form\Multistep\MultistepFormBase.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;

    $this->store = $this->tempStoreFactory->get('multistep_data');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('session_manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = true;
      $this->sessionManager->start();
    }

    $form = array();
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#weight' => 10,
    );

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData() {

    // Check customer role is exists.
    $u_role = \Drupal\user\Entity\Role::load('customer');
    if(!$u_role) {
      $data = array('id' => 'customer', 'label' => 'customer');
      //creating role
      $role = \Drupal\user\Entity\Role::create($data);
      //saving role
      $role->save();
    }

    $name = $this->store->get('name');
    $email = $name . '@test.com';
    $pass = $this->store->get('pass');
    $fullname = $this->store->get('fullname');
    $dob = $this->store->get('dob');
    $cor = $this->store->get('country_of_residence');
    $pizza_type = $this->store->get('favorite_type_pizza');

    // Logic for saving data.
    $user = \Drupal\user\Entity\User::create();
    $user->setPassword($pass);
    $user->enforceIsNew();
    $user->setEmail($email);
    $user->setUsername($name);
    $user->set("field_full_name", $fullname);
    $user->set("field_date_of_birth", $dob);
    $user->set("field_country_of_residence", $cor);
    $user->set("field_favorite_type_of_pizza", $pizza_type);
    $user->activate();
    $user->addRole('customer');
    $user->save();

    $this->deleteStore();
    \Drupal::messenger()->addMessage($this->t('Customer user registration was completed.'));
  }

  /**
   * Helper method that removes all the keys from the store collection used for
   * the multistep form.
   */
  protected function deleteStore() {
    $keys = ['name', 'pass', 'fullname', 'country_of_residence', 'favorite_type_pizza', 'dob'];
    foreach ($keys as $key) {
      $this->store->delete($key);
    }
  }
}
