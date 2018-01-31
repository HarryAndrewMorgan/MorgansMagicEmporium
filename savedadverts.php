<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Saved Adverts';
require_once('Views/savedadverts.phtml');
