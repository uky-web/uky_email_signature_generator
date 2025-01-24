<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Module settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'uky_email_signature_generator_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'uky_email_signature_generator.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('uky_email_signature_generator.settings');

    $form['page_title'] = [
      '#type' => 'textfield',
      '#title' => t('Signature generator page title:'),
      '#default_value' => $config->get('uky_email_signature_generator.page_title'),
      '#required' => TRUE,
    ];

    $form['university_name'] = [
      '#type' => 'textfield',
      '#title' => t('University name:'),
      '#default_value' => $config->get('uky_email_signature_generator.university_name'),
    ];

    $form['form_instructions'] = [
      '#type' => 'text_format',
      '#format'=> 'basic_html',
      '#title' => t('Form instructions:'),
      '#default_value' => $config->get('uky_email_signature_generator.form_instructions')['value'],
      '#description' => t('Instructions for filling out the generator form.'),
    ];

    $form['copy_instructions'] = [
      '#type' => 'text_format',
      '#format'=> 'basic_html',
      '#title' => t('Signature copy instructions:'),
      '#default_value' => $config->get('uky_email_signature_generator.copy_instructions')['value'],
      '#description' => t('Instructions that display below the signature preview for copying and using it in email clients.'),
    ];

    $form['randomize_example'] = [
      '#type' => 'checkbox',
      '#title' => t('Randomize example signature'),
      '#default_value' => $config->get('uky_email_signature_generator.randomize_example'),
      '#description' => t('Show a randomly-generated example signature on page load, instead of a static one.'),
    ];

    $form['allow_download'] = [
      '#type' => 'checkbox',
      '#title' => t('Allow signature file downloads'),
      '#default_value' => $config->get('uky_email_signature_generator.allow_download'),
      '#description' => t('Whether or not to display a button to download generated signatures as an HTML file.'),
    ];

    $form['page_link'] = [
      '#type' => 'item',
      '#markup' => Link::fromTextAndUrl(
        $this->t('View Signature Generator'),
        Url::fromRoute('uky_email_signature_generator.generator_form'))
        ->toString(),
      '#weight' => 100,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (empty($form_state->getValue('page_title'))) {
      $form_state->setErrorByName('page_title', t('Please enter a valid Page title.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config('uky_email_signature_generator.settings');
    $config->set('uky_email_signature_generator.page_title', $form_state->getValue('page_title'));
    $config->set('uky_email_signature_generator.university_name', $form_state->getValue('university_name'));
    $config->set('uky_email_signature_generator.form_instructions', $form_state->getValue('form_instructions'));
    $config->set('uky_email_signature_generator.copy_instructions', $form_state->getValue('copy_instructions'));
    $config->set('uky_email_signature_generator.randomize_example', $form_state->getValue('randomize_example'));
    $config->set('uky_email_signature_generator.allow_download', $form_state->getValue('allow_download'));
    $config->save();
    parent::submitForm($form, $form_state);
  }
}
