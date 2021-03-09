<?php

$this->load("template/header");
//not sure if we need a sidebar just yet, but if we introduce more features then it can be added here
//$this->load("template/sidebar");
$this->load($view);
$this->load("template/footer");