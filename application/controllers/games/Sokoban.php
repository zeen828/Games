<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sokoban extends CI_Controller
{

	private $data_view;
	
	function __construct ()
	{
		parent::__construct();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	
	public function index ()
	{
		
	}

	public function level ($no)
	{
		$this->load->view(sprintf('games/sokobans/level_%s', $no));
	}
}
