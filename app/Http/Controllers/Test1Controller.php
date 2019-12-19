<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Test1Controller extends Controller
{
    public function index()
    {
        

        $client = new Client();

        $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/comments');
        $body = $response->getBody();
        $body = json_decode($body);
        $collection = collect($body); 
        $grouped = $collection->countBy('postId')->sortByDesc(5)->take(5);
        

        $newcollection = null;
        foreach ( $grouped as $key => $value ){
        
            $response = $client->get('https://jsonplaceholder.typicode.com/posts/'.$key);
            $body = $response->getBody();
            $body = json_decode($body);
            //force string to make into json format
            $newcollection = $newcollection . json_encode($body).",";
            $newcollection = substr($newcollection, 0, -3);
            $newcollection = $newcollection.'","totalcomment":'.$value."},";

            //dd($newcollection);
        }

        
        $newcollection = substr($newcollection, 0, -1);
        $newcollection = "[".$newcollection."]";
        //dd($newcollection);
        $body = json_decode($newcollection);
        
       //dd($body);
         return view('welcome', ['data_body'=>$body]); 
                             
                        
    }
    
}
