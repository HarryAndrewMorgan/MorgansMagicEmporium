<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Your Adverts';
require_once('Views/youradverts.phtml');
