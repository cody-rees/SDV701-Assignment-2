<?php 

namespace GCGeneral\Controller\Game;


use PhpMyCP\System\Controller;
use PhpMyCP\System\Navigation\Dropdown;
use GCGeneral\Model\Game;
use GCGeneral\Model\Genre;

class GamesList extends Controller {
	//Page title
	private $title = "Games List";
	private static $instance;

	//Get instance method
	public static function getInstance() {
		return self::$instance;
	}
	
	//Register Routes
	public function initRoutes() {
		self::$instance = $this;
		$this->app()->get('/games/list', [$this, 'get'])->name('games-list');
		$this->app()->get('/json-encode/games-list', [$this, 'jsonEncodeGamesList']);
	}
	
	public function jsonEncodeGamesList() {
		//Return all results if genre not specified
		if(!isset($_GET['genre'])) {
			echo json_encode(
					Game::where('deleted', 0)
						->get()
					);
			
			return;
		}
		
		//Return all results for specified type
		echo json_encode(
				Game::where('deleted', 0)
					->where('genre_id', $_GET['genre'])
					->get()
			);
	}
	
	//Creates Dropdown Navigation
	public function onNavigation() {
		$dropdown = new Dropdown('games',  ['gamepad', 'Games']);
		$dropdown->add("games-list", 'Games List', 'games-list');
		$dropdown->add('create-game', 'Register New Game', 'create-game');
	
		$this->navigation()->defaultCategory->addDropdown($dropdown);
	}
	
	public function get($renderParams = array()) {
		//Breadcrumbs to display in top right corner
		$breadcrumbs = array();
		
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('games-list'),
				'name'	=> 'Games List',
				'icon'	=> 'list'
		];
		
		$games = array();
		//If genre is specifed, filter by genre
		if(!isset($_GET['genre']) || empty($_GET['genre'])) {
			$games = Game::where("deleted", 0)->get();
			
		} else {
			
			$renderParams['selected_genre'] = $_GET['genre'];
			$games = Game::where("deleted", 0)
				->where('genre_id', $_GET['genre'])
				->get();
			
		}
		
		$renderParams = array_merge($renderParams, [
				'header'		=> 'Games List',
				'title' 		=> $this->title,
				'active'		=> 'games',
				'breadcrumbs' 	=> $breadcrumbs,
				'games'			=> $games
		]);
		
		//Add Genres list to render parameters
		$renderParams['genres'] = array();
		foreach(Genre::all() as $genre) {
			$renderParams['genres'][$genre->genre_id] = $genre;
		}
		
		//Render view
		$this->app()->render('game/games_list.twig', $renderParams);
	}
	
}