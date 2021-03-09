<?php namespace App\Controllers;

class AdjectivesApp extends BaseController
{
	public function index()
	{
		$this->data["view"] = "homepage";
		return view('/template/main_template',$this->data);
	}

	//--------------------------------------------------------------------

} 
