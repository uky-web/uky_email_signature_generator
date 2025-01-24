<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\ScrollTopCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class SignatureGeneratorForm.
 */
class SignatureGeneratorForm extends FormBase {
  protected Renderer $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  public static function create(ContainerInterface $container): SignatureGeneratorForm {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'signature_generator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#prefix'] = '<div id="signature-generator-form" class="ui form left">';
    $form['#suffix'] = '</div>';

    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -1000,
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#required' => TRUE,
    ];

    $form['credentials'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Credentials'),
    ];

    $form['pronouns'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pronouns'),
    ];

    $form['position_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Position/Title'),
    ];

    $form['department_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department/Unit'),
    ];

    $form['sub_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sub-Unit'),
    ];

    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
    ];

    $form['city_state_zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City, State ZIP'),
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone'),
      '#attributes' => [
        'pattern' => '\d{3}-\d{3}-\d{4}',
      ]
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Signature'),
      '#ajax' => [
        'event' => 'click',
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'signature-generator-form',
      ],
    ];

    return $form;
  }

  /**
   * AJAX callback for the form submission.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state): AjaxResponse {
    $config = $this->config('uky_email_signature_generator.settings');
    $form_values = $form_state->getValues();

    $data = [
      '#theme' => 'signature_result',
      '#name' => implode(' ', [$form_values['first_name'], $form_values['last_name']]),
      '#creds' => $form_values['credentials'],
      '#pronouns' => $form_values['pronouns'],
      '#title' => $form_values['position_title'],
      '#university' => $config->get('uky_email_signature_generator.university_name'),
      '#department' => $form_values['department_unit'],
      '#sub_unit' => $form_values['sub_unit'],
      '#address' => $form_values['address'],
      '#city' => $form_values['city_state_zip'],
      '#phone' => $form_values['phone'],
      '#email' => $form_values['email'],
    ];

    $response = new AjaxResponse();
    // Scroll to top of form/page and update form container
    $response->addCommand(new ScrollTopCommand('.scroll-to'));
    // ReplaceCommand to replace the entire form, including the wrapper/selector
    $response->addCommand(new ReplaceCommand(NULL, $form));

    if ($form_state->hasAnyErrors()) {
      // Update form with errors
      $first_field = sprintf('input[name="%s"]', array_key_first($form_state->getErrors()));
      $response->addCommand(new InvokeCommand('#result-column', 'removeClass', ['generated']));
      $response->addCommand(new InvokeCommand($first_field, 'focus'));
    } else {
      // Build signature and display result
      // HtmlCommand to replace the contents of the wrapper, leaving the wrapper div in place
      $response->addCommand(new HtmlCommand('#result-wrapper', $this->renderer->render($data)));
      $response->addCommand(new InvokeCommand('#result-column', 'addClass', ['generated']));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Check for required first and last name
    if (empty($form_state->getValue('first_name'))) {
      $form_state->setErrorByName('first_name');
    }
    if (empty($form_state->getValue('last_name'))) {
      $form_state->setErrorByName('last_name');
    }

    // Process/format phone number, or display error if incorrectly formatted
    $phone = $form_state->getValue('phone');
    if (!empty($phone)) {
      // Strip out non-numeric characters
      $phone = preg_replace('/\D/', '', $phone);
      if (strlen($phone) === 11 && $phone[0] === '1') {
        // Strip out USA country code
        $phone = substr($phone, 1);
      }
      // Check length of resulting number
      if (strlen($phone) !== 10) {
        $form_state->setErrorByName('phone', $this->t('Phone must be a 10-digit US-based number.'));
      } else {
        // Update value with stripped-down number, will be re-formatted for display in the template
        $form_state->setValue('phone', $phone);
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $form_state->setRebuild(TRUE);
    // Do nothing as this form does not actually process/store data.
  }
}
