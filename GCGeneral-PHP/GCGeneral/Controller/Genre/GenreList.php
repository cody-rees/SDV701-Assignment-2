<?php 

namespace GCGeneral\Controller\Genre;

use PhpMyCP\System\Controller;
use GCGeneral\Model\Genre;

class GenreList extends Controller {

	//Register Routes
	public function initRoutes() {
		$this->app()->get('/json-encode/genres-list', [$this, 'jsonEncodeGenresList']);
	}
	
	public function jsonEncodeGenresList() {
		echo json_encode(Genre::all());
	}
	
}