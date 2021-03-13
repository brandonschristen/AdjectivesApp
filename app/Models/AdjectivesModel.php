<?php

namespace App\Models;
use CodeIgniter\Model;

class AdjectivesModel extends Model
{
    protected $db;
    private array $positiveWords = array();
    private array $negativeWords = array();

    public function __construct()
    {
        try{
            $this->positiveWords = $this->getAdjectives(1);
            $this->negativeWords = $this->getAdjectives(-1);
        }
        catch(\Exception $e)
        {
            echo($e->getMessage());
            return 0;
        }
    }
    public function search($term)
    {
        //keeps track of the times word was used
        $wordcount = 0;
        //keeps key value pair of positive words and times used
        $poswordArray = array();
        //keeps key value pair of positive words and times used
        $negwordArray = array();    
        //what we will return 
        $resultArray = array();
        /* Technically we can just skip this and add a @ before the array usage in the loop, but assigning them all here makes sure we get valid errors */    
        /* If there is a performance issue we can skip this. */    
        foreach($this->positiveWords as $pword)
        {
            //init keys
            $poswordArray[$pword] = 0;
        }
        foreach($this->negativeWords as $negword)
        {
            //init keys
            $negwordArray[$negword] = 0;
        }        
        try
        {
            $result = $this->searchAPI($term);
            foreach($result as $row)
            {
                /*Count positive and negative words and write to array */
                foreach($this->positiveWords as $pword)
                {
                    $poswordArray[$pword] += substr_count($row["text"],$pword);
                }
                foreach($this->negativeWords as $negword)
                {
                    $negwordArray[$negword] += substr_count($row["text"],$negword);
                }                                  
            }
            sort($poswordArray);
            sort($negwordArray);
            //output results to array and retun it
        }            
        catch(\Exception $e)
        {
            echo($e->getMessage());
            return 0;
        }

    }

    private function searchAPI($term)
    {

        $curl = curl_init();
        // set url
        curl_setopt($curl, CURLOPT_URL, "https://api.twitter.com/2/tweets/search/recent");
        //return the transfer as a string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // turn output into an array
        $output = json_decode(curl_exec($curl));
        curl_close($curl);   
        if($output != "" && !isNull($output))
        {
            return $output;
        }
        else{
            throw new \Exception('Output not received from search API');
        }
    }

    private function searchHistory($term)
    {

    }

    private function getAdjectives($posOrNeg)
    {
        $wordArray = array();
        if($res = $this->db->query("SELECT adj_word FROM adjectives WHERE adj_rating = ?"))
        {
            foreach ($res->getResult() as $row)
            {
                $wordArray[] = $row->adj_word;
            }
            if($posOrNeg == 1)
            {
                $this->positiveWords = $wordArray;
            }
            else{
                $this->negativeWords = $wordArray;
            }            
        }
        else{
            //error
            throw new \Exception('Unable to retrieve adjectives');
        }
        return;
        
    }
}