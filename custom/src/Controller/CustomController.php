<?php

namespace Drupal\custom\Controller;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for JSON representation of given node.
 */
class CustomController {

  /**
   * Provide JSON Response of node.
   *
   * @param $api_key
   *   Site API Key configured in Drupal Site Information form.   
   * @param $node_id
   *   The Node ID.
   * 
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */  
  public function content($api_key, $node_id) {
    if(is_numeric($node_id) && $node = Node::load($node_id))
    {
        $bundle = $node->bundle();    
    }    
    $siteApiKey = \Drupal::config('system.site')->get('siteapikey');
    if(($api_key != $siteApiKey) || (!isset($bundle) || $bundle != 'page'))
    {
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
    
    $data = array(
        'type' => $node->get('type')->target_id,
        'id' => $node->get('nid')->value,
        'title' => $node->get('title')->getValue()[0]['value'],
        'body' => $node->get('body')->getValue()[0]['value'],
    );
    return new JsonResponse($data);
  }
}
?>