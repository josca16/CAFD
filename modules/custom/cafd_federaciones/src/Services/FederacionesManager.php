<?php  
namespace Drupal\cafd_federaciones\Services;

use Drupal\node\Entity\Node;  

class FederacionesManager {  

  public function getFederaciones() {  
    $query = \Drupal::entityQuery('node')  
      ->condition('type', 'federaciones')  
      ->condition('status', 1)  
      ->sort('title', 'ASC');  
    $nids = $query->execute();  
    return Node::loadMultiple($nids);  
  }  
}