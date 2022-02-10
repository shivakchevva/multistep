<?php

/**
 * @file
 * Contains \Drupal\custom_multistep\Form\Multistep\MultistepOneForm.
 */

namespace Drupal\custom_multistep\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;

class MultistepOneForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_one';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Your name'),
      '#default_value' => $this->store->get('name') ? $this->store->get('name') : '',
    );

    $form['pass'] = array(
      '#type' => 'password',
      '#title' => $this->t('Your password'),
      '#default_value' => $this->store->get('pass') ? $this->store->get('pass') : '',
    );

    $form['#cache'] = ['max-age' => 0];
    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('pass', $form_state->getValue('pass'));
    $this->store->set('name', $form_state->getValue('name'));
    $form_state->setRedirect('custom_multistep.multistep_two');
  }

}
