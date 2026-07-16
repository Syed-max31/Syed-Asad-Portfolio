<?php
  require_once __DIR__ . '/../backend/controllers/ContactController.php';

  // Instantiate controller and process the request
  $controller = new ContactController();
  $controller->handleRequest();
?>
