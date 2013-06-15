<?php defined('SYSPATH') or die('No direct script access.');

class REST_Exception extends Kohana_Exception
{
    public function __construct($message, array $variables = NULL, $code = 0)
    {
        $this->_variables = $variables;
        parent::__construct($message,$variables,$code);
    }

    protected $_variables;
    public function variables()
    {
        return $this->_variables;
    }
}
