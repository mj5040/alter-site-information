<?php

namespace Drupal\custom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\NodeInterface;

/**
 * Class CustomController
 * 
 * Return node JSON Response.
 */
class CustomController extends ControllerBase {

  /**
   * Provide JSON Response of node.
   *
   * @param $api_key
   *   Site API Key configured in Drupal Site Information form.
   *   
   * @param \Drupal\node\NodeInterface $node
   * 
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */  
  public function content($api_key, NodeInterface $node) {
    $bundle = $node->bundle();
    $site_api_key = $this->config('system.site')->get('siteapikey');
    if (($api_key != $site_api_key) || ($bundle != 'page')) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
    
    $data = [
      'type' => $node->get('type')->target_id,
      'id' => $node->get('nid')->value,
      'title' => $node->get('title')->getValue()[0]['value'],
      'body' => $node->get('body')->getValue()[0]['value'],
    ];
    return new JsonResponse($data);
  }

}