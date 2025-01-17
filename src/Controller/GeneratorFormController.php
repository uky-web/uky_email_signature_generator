<?php
namespace Drupal\uky_email_signature_generator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\uky_email_signature_generator\Form\SignatureGeneratorForm;

/**
 * Provides route responses for the Example module.
 */
class GeneratorFormController extends ControllerBase {
  /**
   * The form builder.
   *
   * @var FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a GeneratorFormController object.
   *
   * @param FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  public static function create(ContainerInterface $container): GeneratorFormController|static {
    return new static(
      $container->get('form_builder')
    );
  }

  public function getTitle(): string {
    return $this->config('uky_email_signature_generator.settings')
      ->get('uky_email_signature_generator.page_title');
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function page(): array {
    // Render the form.
    return [
      '#theme' => 'uky_email_signature_generator_form',
      '#form' => $this->formBuilder->getForm(SignatureGeneratorForm::class),
      '#attached' => [
        'library' => [
          'uky_email_signature_generator/semantic-ui',
          'uky_email_signature_generator/uky-email-signature-generator',
          'views/views.ajax',
        ]
      ]
    ];
  }
}
