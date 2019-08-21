<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     pagination
*/

class Pagination
{
    protected $_variables = array(
        'classes' => array('pagination'),
        'crumbs' => 5,
        'rpp' => 10,
        'key' => 'p',
        'target' => '',
        'next' => 'Next &raquo;',
        'previous' => '&laquo; Prev',
        'clean' => false,
        'cleanURL' => false
    );

    public function __construct($current = null, $total = null)
    {
        if (!is_null($current)) {
            $this->setCurrent($current);
        }
        if (!is_null($total)) {
            $this->setTotal($total);
        }
        $this->_variables['get'] = $this->_encode($_GET);
    }

    protected function _check()
    {
        if (!isset($this->_variables['current'])) {
            throw new Exception('Pagination::current must be set.');
        } elseif (!isset($this->_variables['total'])) {
            throw new Exception('Pagination::total must be set.');
        }
    }

    protected function _encode($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->_encode($value);
            }
            return $mixed;
        }
        return htmlentities($mixed, ENT_QUOTES, 'UTF-8');
    }

    public function addClasses($classes)
    {
        $this->_variables['classes'] = array_merge(
            $this->_variables['classes'],
            (array) $classes
        );
    }

    public function parse()
    {
        $this->_check();
        foreach ($this->_variables as $_name => $_value) {
            $$_name = $_value;
        }
        ob_start();
        if($this->_variables['cleanURL']==true){
            include 'np.render.public.php';
        }else{
            include 'np.render.php';
        }
        $_response = ob_get_contents();
        ob_end_clean();
        return $_response;
    }

    public function setClasses($classes)
    {
        $this->_variables['classes'] = (array) $classes;
    }

    public function setClean()
    {
        $this->_variables['clean'] = true;
    }

    public function setCrumbs($crumbs)
    {
        $this->_variables['crumbs'] = $crumbs;
    }

    public function setCurrent($current)
    {
        $this->_variables['current'] = $current;
    }

    public function setFull()
    {
        $this->_variables['clean'] = false;
    }

    public function setKey($key)
    {
        $this->_variables['key'] = $key;
    }

    public function setNext($str)
    {
        $this->_variables['next'] = $str;
    }

    public function setPrevious($str)
    {
        $this->_variables['previous'] = $str;
    }

    public function setRPP($rpp)
    {
        $this->_variables['rpp'] = $rpp;
    }

    public function setTarget($target)
    {
        $this->_variables['target'] = $target;
    }

    public function setTotal($total)
    {
        $this->_variables['total'] = $total;
    }

    public function setcleanURL(){
        $this->_variables['cleanURL'] = true;
    }

    public function setGETParams($params){
        $this->_variables['get'] = $this->_encode($params);
    }
}
