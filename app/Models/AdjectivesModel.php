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
            $this->db = \Config\Database::connect();
            $this->positiveWords = $this->getAdjectives(1);
            $this->negativeWords = $this->getAdjectives(-1);
        }
        catch(\Exception $e)
        {
            echo($e->getMessage());
            return false;
        }
    }
    public function search($term)
    {
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
            //asort to put words with highest usage at the top
            asort($poswordArray);
            asort($negwordArray);

            //output results to array and retun it
            //if the first in our sorted is 0 the rest are too.. we found no results
            if(array_key_first($poswordArray) ==0 || array_key_first($negwordArray) ==0)
            {
                throw new \Exception('No matches in our database for that search term.');
            }
            return false;
        }            
        catch(\Exception $e)
        {
            echo($e->getMessage());
            return false;
        }

    }

    private function searchAPI($term)
    {

        $certificate_location = getenv('pem_location');
        $curl = curl_init();    
        // set url
        curl_setopt($curl, CURLOPT_URL, "https://api.twitter.com/2/tweets/search/recent?query=" . urlencode($term));
        //return the transfer as a string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
             getenv('twitterAPIKey')
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $certificate_location);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $certificate_location);        
        // turn output into an array
        $output = json_decode(curl_exec($curl),true);
        if(curl_errno($curl)){
            echo 'Curl error: ' . curl_error($curl);
            die();
        }            
        curl_close($curl);       
        if($output != "" && $output !== null)
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
        if($res = $this->db->query("SELECT adj_word FROM adjectives WHERE adj_positive = ?",[$posOrNeg]))
        {
            foreach ($res->getResult() as $row)
            {
                $wordArray[] = $row->adj_word;
            }           
        }
        else{
            //error
            throw new \Exception('Unable to retrieve adjectives');
        }
        return $wordArray;
        
    }
}