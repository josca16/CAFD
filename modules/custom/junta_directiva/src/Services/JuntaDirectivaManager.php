<?php  
namespace Drupal\junta_directiva\Services;  

use Drupal\node\Entity\Node;  

class junta_directivaManager {  

  public function getjunta_directiva() {  
    $query = \Drupal::entityQuery('node')  
      ->condition('type', 'junta_directiva')  
      ->condition('status', 1)  
      ->sort('title', 'ASC');  
    $nids = $query->execute();  
    return Node::loadMultiple($nids);  
  }  
}  