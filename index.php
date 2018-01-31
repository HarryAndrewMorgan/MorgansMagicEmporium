<?php
session_start();
var_dump($_SESSION);
$view = new stdClass();
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');
