<?php
namespace Egc\Mvc;

class View {

	private $pageVars = array();
	private $template;

	public function __construct($template, $vars = array())
	{
		$this->template = APP_DIR .'view/'. $template .'.php';

		foreach ($vars as $var => $val)
		{
		    $this->set($var, $val);
		}
	}

	public function __get($var_name)
	{
	    $result = null;
        if (isset($this->pageVars[$var_name]))
            $result = $this->pageVars[$var_name];

        return $result;
	}

	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}

	public function render()
	{
		extract($this->pageVars);

		ob_start();
		require($this->template);
		echo ob_get_clean();
	}

}
