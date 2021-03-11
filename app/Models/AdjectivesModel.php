<?php

namespace App\Models;
use CodeIgniter\Model;

class AdjectivesModel extends Model
{
    protected $db;

    public function search($term)
    {
        //just testing this is set up correctly
        return $term;
    }
}