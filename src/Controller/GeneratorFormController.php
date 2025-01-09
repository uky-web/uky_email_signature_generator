<?php
namespace Drupal\uky_email_signature_generator\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class GeneratorFormController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function page() {
    return [
      '#markup' => 'Hello, world',
    ];
  }
}
