<?php  
namespace Drupal\cafd_colabs\Services;  

use Drupal\node\Entity\Node;  

class ColaboradoresManager {  

  public function getColaboradores() {  
    $query = \Drupal::entityQuery('node')  
      ->condition('type', 'colaboradores')  
      ->condition('status', 1)  
      ->sort('title', 'ASC');  
    $nids = $query->execute();  
    return Node::loadMultiple($nids);  
  }  
}  