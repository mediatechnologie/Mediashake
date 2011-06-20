<?php
	set_include_path('classes');
	function __autoload($classname)
	{
		include($classname . '.class.php');
	}
?>