<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenericController extends Controller
{
    private $_data;
    private $_debug;
    protected $perPage = 25;
    protected $perPageMaximum = 100;
    
    public function __construct(){
        $this->_data = [];
        $this->_debug = [];
    }
    
    protected function setData($data){
        $this->_data[] = $data;
    }
    
    protected function getData(){
        return $this->_data;
    }
    
    protected function resetData(){
        $this->_data = [];
    }
    
    protected function setDebug($debug){
        $this->_debug[] = $debug;
        
        session()->forget('debug');
        session()->flash('debug', $this->_debug);
    }
    
    protected function getDebug(){
        return $this->_debug;
    }
    
    protected function resetDebug(){
        $this->_debug = [];
    }
    
    protected function showDebug($exit = false){
        foreach($this->_debug as $debug){
            echo '<pre>';
            print_r($debug);
            echo '</pre>';
        }
        
        if($exit){
            exit;
        }
    }
    
    protected function calculatePerPage(Request $request){
        if($request->has('limit')){
            return $request->limit < $this->perPageMaximum ? $request->limit : $this->perPage;
        }
        
        return $this->perPage;
    }
}
