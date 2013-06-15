<?php defined('SYSPATH') or die('No direct script access.');

class REST_Json_Response extends REST_Response implements ArrayAccess
{
    protected $data;

    /**
     * Stores the data that gets passed in to the public members.
     *
     * @param  $status string  The HTTP status value.
     * @param  $headers string  The parsed headers.
     * @param  $body string  The response body.
     * @throws REST_Exception
     * @throws Kohana_HTTP_Exception
     */
    public function __construct($status, $headers, $body)
    {
        $this->data = json_decode($body);

        if($status !== 200){
            if($this->data !== null){
                $message = $this->data->message;
            }else{
                $message = 'REST Response code was status and body failed to parse as json';
            }
            throw new REST_Exception($message,array('body'=>$body,'headers'=>$headers,'status'=>$status),$status);
        }
        if($this->data === null){throw new REST_Exception('Cannot decode REST response body as json',array('body' => $body));}

        parent::__construct($status,$headers,$body);
    }

    public function offsetExists($offset){
        return property_exists($this->data,$offset) || method_exists($this,'get_'.$offset);
    }

    public function offsetGet($offset){
        return method_exists($this,'get_'.$offset) ?
            $this->{'get_'.$offset}() :
            $this->data->$offset;
    }

    public function offsetSet($offset, $value){
        throw new REST_Exception('readonly');
    }
    public function offsetUnset($offset){
        $this->offsetSet($offset,null);
    }

    public function __get($name){
        return $this->offsetGet($name);
    }
    public function __isset($name){
        return $this->offsetExists($name);
    }
    public function __set($name,$value){
        $this->offsetSet($name,$value);
    }
    public function __unset($name){
        $this->offsetUnset($name);
    }
}