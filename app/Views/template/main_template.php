<?php
if(!isset($view))
{
    echo"Error, no view defined";
    return;
}
echo view("template/header");
//not sure if we need a sidebar just yet, but if we introduce more features then it can be added here
//$this->load("template/sidebar");
echo view($view);
echo view("template/footer");