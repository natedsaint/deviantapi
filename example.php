<?php
  
  // include the class

  require_once("deviantArt.class.php");
  
  // create a new instance using your username. NOTE: it is required upon instantiation.

  $api = new deviantArt("natedsaint");

  // now grab all your content in one go

  $site = $api->getAllContent();

  // this method returns an array full of simpleXML objects for each gallery. 
  
  foreach ($site as $gallery) {
    $ns = $gallery->getNamespaces(true);
    foreach ($gallery->channel->item as $item) {
      $media = $item->children($ns["media"]);
      echo var_dump($media);
    }
  }
  

?>
