<?php 

namespace App\Controllers;
use App\Models\AdjectivesModel;

class AdjectivesApp extends BaseController
{
	protected $helpers = ['url', 'form'];
	public function index()
	{
		$this->data["view"] = "homepage";
		return view('/template/main_template',$this->data);
	}

	public function search()
	{
		$AdjectivesModel = new AdjectivesModel();
		$term = $this->request->getPost('term');
		$result = $AdjectivesModel->search($term);
		if($result)
		{
			$this->data["view"] = "results";
			return view('/template/main_template',$this->data);			
		}
	}
	//--------------------------------------------------------------------

} 
