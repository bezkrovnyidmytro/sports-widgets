<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin;

class ViewAdminSettings
{
	private string $template_path = VCSW_DIR_PATH . 'admin/templates/';

	public function __construct()
	{
	}

	public function render(string $template_name = '', array $args = [])
	{
		if(! file_exists($this->template_path . $template_name)) {
			return;
		}

		include $this->template_path . $template_name;
	}
}
