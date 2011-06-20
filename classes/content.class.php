<?php
	class Content extends Site
	{
		
		public function invoke()
		{
			$file = 'html/pages/'.$this->page.'.html';
			
			if(file_exists($file))
			{
				require($file);
			}
			else
			{
				echo '404 error';
			}
		}
		
	}