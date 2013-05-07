<?php

/** 
 * Deviant Art Class
 * @author Nathan St. Pierre
 * nathan@nathanstpierre.com
 
 * Authored with MIT License, included via github at /LICENSE
 
 *
*/

class deviantArt {
  public function __construct($username,$format = "php") {
    if (!$username) {
      return "you must provide a username in order to create this class";
    }
    $this->username = $username;
    $this->format = $format;
    $this->formatter = "format_".$this->format;
  }

  public function getJournals() {
    $contents = file_get_contents("http://backend.deviantart.com/rss.xml?q=by%3A".$this->username."&type=journal");
    $this->journals = $contents;
    return $this->returnFormat($contents);
  }

  public function getGallections() {
    $contents = file_get_contents('http://www.deviantart.com/global/difi.php?c[]="Gallections","get_gallections_info_by_username",["'.$this->username.'","20"]&t=xml');
    $contents = new simpleXMLElement($contents);
    $this->gallections = $contents;
    return $this->returnFormat($contents);
  }

  public function getGallery($galleryId) {
    $contents = file_get_contents('http://backend.deviantart.com/rss.xml?q=gallery%3A'.$this->username.'%2F'.$galleryId.'&type=deviation');
    $contents = new simpleXMLElement($contents);
    return $this->returnFormat($contents);
  }

  public function parseGallections($gallections) {
    foreach ($gallections->response->calls->response->content as $gallery) {
      $contents[] = $this->getGallery($gallery->galleryid);
    }
    $this->galleryCollection = $contents;
    return $this->returnFormat($contents);
  }

  public function getAllContent() {
    $gallections = ($this->gallections) ? $this->gallections : $this->getGallections();
    $contents = $this->parseGallections($gallections);
    return $this->returnFormat($contents);
  }

  private function returnFormat($output) {
    $method = $this->formatter;
    return $this->$method($output);
  }

  private function format_php($object) {
    return $object;
  }

  private function format_json($object) {
    return json_encode($object);
  }
}

?>
