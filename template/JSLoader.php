<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author Leo
 */
class JSLoader implements Iterator, Countable {
    
    private $core = array();
    private $lib = array();
    private $script = array();
    private $array = array();
    public $variables_var = array();
    public $variables = array();
    private $url;
    
    public function __construct($url) {
        $this->url = $url;
    }

    public function addCore($js) {
        $url = $js;
        if(!in_array($url, $this->core)) {
            $this->core[] = $url;
        }
    }
    
    public function addLib($js) {
        $url = 'lib/'.$js;
        
        if(!in_array($url, $this->lib)) {
            $this->lib[] = $url;
        }        
    }
    
    public function addScript($js) {
        $url = 'jeu/'.$js;
        
        if(!in_array($url, $this->script)) {
            $this->script[] = $url;
        }        
    } 
    
    public function addVariables($name,$value,$raw = false) {
        if($raw) {
            $this->variables_var[$name] = $value;
        } else {
            $this->variables_var[$name] = '"'.$value.'"';
        }
        
    }
    
    public function setVariables($name,$value,$raw = false) {
        if($raw) {
            $this->variables[$name] = $value;
        } else {
            $this->variables[$name] = '"'.$value.'"';
        }        
    }

    public function prepare() {
        $arr = array();
        
        foreach($this->core as $js) {
          $arr[] = array('js' => $js, 'type' => 'core');  
        }
        
        foreach($this->lib as $js) {
          $arr[] = array('js' => $js, 'type' => 'lib');  
        }
        
        foreach($this->script as $js) {
          $arr[] = array('js' => $js, 'type' => 'script');  
        }        

        $this->array = $arr;
    }
    
    public function exportLoad() {
        
        echo '<script type="text/javascript">';
        
        if(count($this->variables_var) > 0) {
            foreach($this->variables_var as $k => $v) {
                echo 'var '.$k.' = ' . $v . ';' . PHP_EOL;
            }
        }
        
        if(count($this->variables) > 0) {
            foreach($this->variables as $k => $v) {
                echo '    '.$k.' = ' . $v . ';' . PHP_EOL;
            }
        }        
        
        echo '</script><script type="text/javascript" src="'.$this->url.'/js/require.min.js"></script>';

        $this->prepare();

        /*echo "<script type='text/javascript'>requirejs.config({
                baseUrl: 'http://localhost/ewo/js'
              });";*/
        echo "<script type='text/javascript'>";        
        


        if(count($this) > 0) {
            $i = 0;
            foreach ($this as $load) {
                if(file_exists($this->url . '/js/' . $load['js'] . '.min.js')) {
                    $time = filemtime($this->url . '/js/' . $load['js'] . '.min.js');
                    echo 'requirejs(["'.$this->url.'/js/'.$load['js'].'.min.js?v='.$time.'"], function() {' . PHP_EOL;

                    $i++;
                } elseif(file_exists($this->url . '/js/' . $load['js'] . '.js')) {
                    $time = filemtime($this->url . '/js/' . $load['js'] . '.js');
                    echo 'requirejs(["'.$this->url.'/js/'.$load['js'].'.js?v='.$time.'"], function() {' . PHP_EOL;

                    $i++;
                } /*else {
                    echo $this->url . '/js/' . $load['js'] . '.js';
                }*/
            }
            
            echo str_repeat("})", $i).';</script>';

        }   

    }
    
    public function current() {
        return current($this->array);
    }

    public function key() {
        return key($this->array);
    }

    public function next() {
        return next($this->array);
    }

    public function rewind() {
        return reset($this->array);
    }

    public function valid() {
        return key($this->array) !== null;
    }

    public function count() {
        return count($this->array);
    }

}

?>
