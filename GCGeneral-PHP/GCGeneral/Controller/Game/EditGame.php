<?php 

namespace GCGeneral\Controller\Game;


use PhpMyCP\System\Controller;
use GCGeneral\Model\Genre;
use GCGeneral\Model\Game;
use Violin\Violin;

class EditGame extends Controller {
	
	
	public function initRoutes() {
		$this->app()->get('/games/list/add', [$this, 'addGameForm'])->name('create-game');
		$this->app()->post('/games/list/add', [$this, 'saveGame']);

		$this->app()->get('/games/list/edit/:gameID', [$this, 'editGameForm'])->name('edit-game');
		$this->app()->post('/games/list/edit/:gameID', [$this, 'saveGame']);
		
		$this->app()->get('/games/list/delete/:gameID', [$this, 'deleteGame'])->name('delete-game');
	}
	
	public function deleteGame($gameID) {
		//Retrieve Game Record
		$game = null;
		if($gameID != null) {
			$results = Game::where('game_id', $gameID)->get();
			$game = isset($results[0]) ? $results[0] : null;
			
			//Redirect if game has been deleted since saving
			if($game == null || $game->deleted == 1) {
				$renderParams['calloutType'] = 'callout-danger';
				$renderParams['calloutMessage']	= 'Could not save game, Database Entry no longer exists.';
		
		
				//Sets window.location.href - Prevents this current route been called on Refresh
				$renderParams['httpLocation'] = $this->app()->urlFor('games-list');
				//Soft Redirect
				GamesList::getInstance()->get($renderParams);
				return;
			}
		}
		
		//SoftDelete Game
		$game->deleted = 1;
		$game->save();
		
		$renderParams['calloutType'] = 'callout-info';
		$renderParams['calloutMessage']	= 'Game Record has been deleted.';


		//Sets window.location.href - Prevents this current route been called on Refresh
		$renderParams['httpLocation'] = $this->app()->urlFor('games-list');
		//Soft Redirect
		GamesList::getInstance()->get($renderParams);
	}
	
	public function saveGame($gameID = null, $renderParams = array()) {
		
		//Retrieve Game Record
		$game = null;
		if($gameID != null) {
			$results = Game::where('game_id', $gameID)->get();
			$game = isset($results[0]) ? $results[0] : null;
			
			//Redirect if game has been deleted since saving
			if($game == null || $game->deleted == 1) {
				$renderParams['calloutType'] = 'callout-danger';
				$renderParams['calloutMessage']	= 'Could not save game, Database Entry no longer exists.';
		
		
				//Sets window.location.href - Prevents this current route been called on Refresh
				$renderParams['httpLocation'] = $this->app()->urlFor('games-list');
				//Soft Redirect
				GamesList::getInstance()->get($renderParams);
				return;
			}
		}
		
		//Validation and Error Handling
		$errors = $this->validate($_POST, $game);
		if(sizeof($errors) > 0) {
			$renderParams['calloutType'] = 'callout-danger';
			$renderParams['calloutMessage']	= 'Could not save game for the following reasons:<br><ol><li>' . join("</li><li>", $errors) . '</li></ol>';
		
			if($gameID == null) {
				$this->addGameForm($renderParams);
				return;
					
			} else {
				$this->editGameForm($gameID, $renderParams);
				return;
		
			}
		}
		
		//Create Game if one is not defined
		if($game == null) {
			$game = new Game();
		}
		
		//Load GameData to model and save
		$game->title 		= $_POST['input-title'];
		$game->description 	= $_POST['input-description'];
		$game->genre_id 	= $_POST['select-genre'];
		$game->price 		= $_POST['input-price'];
		$game->type			= $_POST['select-type'];

		//Set quantity/download url if set in postdata otherwise set null
		$game->quantity		= isset($_POST['input-quantity']) ? $_POST['input-quantity'] : 0;
		$game->download_url	= isset($_POST['input-download-url']) ? $_POST['input-download-url'] : null;
		
		//Save
		$game->save();
		
		
		$renderParams['calloutType'] = 'callout-success';
		$renderParams['calloutMessage']	= 'Game Record successfully saved!';

		//Sets window.location.href - Prevents this current route been called on Refresh
		$renderParams['httpLocation'] = $this->app()->urlFor('games-list');
		//Soft Redirect
		GamesList::getInstance()->get($renderParams);
	}
	
	//Validates PostData to check it is valid for saving
	public function validate(array $post, Game $game = null) {
		//Validation
		$v = new Violin();
		
		//Field Messages when validation Fails
		$v->addFieldMessages([
				'input-title'			=> [
						'required'	=> 'Title field cannot be empty.',
						'max'		=> 'Title cannot be larger than 255 characters due to database restrictions.'
				],
				'select-genre'	=> [
						'required'	=> 'Genre field cannot be empty.',
				],
				'input-price'	=> [
						'required'	=> 'Price field cannot be empty.',
						'number'	=> 'Price field must contain a positive number.',
						'min'		=> 'Price field must contain a positive number.'
				],
				'select-type'	=> [
						'required'	=> 'Type field cannot be empty.',
				],
				'input-download-url'	=> [
						'required'	=> 'Download URL field cannot be empty.',
				],
				'input-quantity'	=> [
						'required'	=> 'Quantity field cannot be empty.',
						'int'		=> 'Quantity field must contain a integer.'
				],
		]);
		
		//Validation Rules
		$validationData = [
				'input-title'			=> [$post['input-title'], 		'required|max(255)'],
				'select-genre'			=> [$post['select-genre'],		'required'],
				'input-price'			=> [$post['input-price'],		'required|number|min(0, number)'],
				'select-type'			=> [$post['select-type'],		'required'],
		];
		
		//Add validation rules depending on type
		if(!empty($post['select-type'])) {
			if($post['select-type'] == 'digital') {
				$validationData['input-download-url'] = [$post['input-download-url'], 'required'];
				
			} else if ($post['select-type'] == 'physical') {
				$validationData['input-quantity'] = [$post['input-quantity'], 'required|int'];
				
			}
		}
		
		//Validate
		$v->validate($validationData);

		//If Validator fails
		$errors = array();
		if($v->fails()) {
			$errors = $v->errors()->all();
		}
		
		//validate title for new entry or title changed
		if($game == null || strcmp($post['input-title'], $game->title) !== 0) {
			
			//Checks if entry by title already exists
			if(sizeof(Game::where('title', 'LIKE', $post['input-title'])->get()) > 0) {
				$errors[] = 'A game by that title already exists';
			}
			
		}
		
		return $errors;
	}
	
	public function addGameForm($renderParams = array()) {

		//Breadcrumbs display in browser
		$breadcrumbs = array();
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('games-list'),
				'name'	=> 'Games List'
		];
		
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('create-game'),
				'name'	=> 'Register New Game',
				'icon'	=> 'plus'
		];
		
		$genreOptions = array();
		foreach(Genre::all() as $genre) {
			$genreOptions[$genre->genre_id] = [
					'label' => $genre->genre
			];
		}
		
		
		//Generates Form from Array
		$formEditGame['section-info'] = [
				'title'		=> [
						'name'		=> 'input-title',
						'type'		=> 'text',
						'label'		=> "Title",
				],
				'description'	=> [
						'name'		=> 'input-description',
						'type'		=> 'textarea',
						'label'		=> 'Description',
						'htmlAfter'	=> "</br></br>"
				]
		];
		
		
		$formEditGame['section-type'] = [
				'genre'		=> [
						'name'		=> 'select-genre',
						'type'		=> 'select',
						'label'		=> 'Genre',
						'options'	=> $genreOptions
				],
				'price'		=> [
						'name'		=> 'input-price',
						'type'		=> 'number',
						'label'		=> "Price",
						'step'		=> "0.01",
						'htmlAfter'	=> "</br></br>"
				],
				'type'	=> [
						'name'		=> 'select-type',
						'type'		=> 'select',
						'label'		=> 'Type',
						'options'	=> [
								'digital'	=> [
										'label' => 'Digital'
								],
								'physical'	=> [
										'label'	=> 'Physical'
								]
						]
				],
				'quantity'	=> [
						'name'		=> 'input-quantity',
						'type'		=> 'number',
						'label'		=> 'Quantity',
						'disabled'	=> true
				],
				'download_url'	=> [
						'name'		=> 'input-download-url',
						'type'		=> 'text',
						'label'		=> 'Download URL',
						'disabled'	=> true
				]
		];
		
		$renderParams['formEditGame'] = [
				'sections'	=> $formEditGame,
				'action'	=> $this->app()->urlFor('create-game'),
				'method'	=> 'post',
				'submit'	=> 'Save',
				'submitInFooter'	=> true
		];
		
		
		$renderParams = array_merge($renderParams, [
				'header'		=> 'Edit Game',
				'active'		=> 'games',
				'title'			=> 'Register New Game',
				'breadcrumbs' 	=> $breadcrumbs
		]);
		
		$this->app()->render('game/game_form.twig', $renderParams);
	}
	
	public function editGameForm($gameID, $renderParams = array()) {
		$results = Game::where('game_id', $gameID)->get();
		$game = isset($results[0]) ? $results[0] : null;
			
		//Redirect if game has been deleted since saving
		if($game == null || $game->deleted == 1) {
			$renderParams['calloutType'] = 'callout-danger';
			$renderParams['calloutMessage']	= 'Could not find game, Database Entry no longer exists.';
		
		
			//Sets window.location.href - Prevents this current route been called on Refresh
			$renderParams['httpLocation'] = $this->app()->urlFor('games-list');
			//Soft Redirect
			GamesList::getInstance()->get($renderParams);
			return;
		}
		
		//Breadcrumbs display in browser
		$breadcrumbs = array();
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('games-list'),
				'name'	=> 'Games List'
		];
		
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('edit-game', ["gameID" => $gameID]),
				'name'	=> 'Edit Game',
				'icon'	=> 'edit'
		];
		
		$genreOptions = array();
		foreach(Genre::all() as $genre) {
			$genreOptions[$genre->genre_id] = [
					'label' => $genre->genre
			];
		}
		
		$genreOptions[$game->genre_id]['selected'] = true;
		
		//Generates Form from Array
		$formEditGame['section-info'] = [
				'title'		=> [
						'name'		=> 'input-title',
						'type'		=> 'text',
						'label'		=> "Title",
						'value'		=> $game->title
				],
				'description'	=> [
						'name'		=> 'input-description',
						'type'		=> 'textarea',
						'label'		=> 'Description',
						'htmlAfter'	=> "</br></br>",
						'value'		=> $game->description
				]
		];
		
		
		$formEditGame['section-type'] = [
				'genre'		=> [
						'name'		=> 'select-genre',
						'type'		=> 'select',
						'label'		=> 'Genre',
						'options'	=> $genreOptions
				],
				'price'		=> [
						'name'		=> 'input-price',
						'type'		=> 'number',
						'label'		=> "Price",
						'step'		=> "0.01",
						'value'		=> $game->price,
						'htmlAfter'	=> "</br></br>"
				],
				'type'	=> [
						'name'		=> 'select-type',
						'type'		=> 'select',
						'label'		=> 'Type',
						'options'	=> [
								'digital'	=> [
										'label' 	=> 'Digital',
										'selected' 	=> strcasecmp($game->type, 'digital') === 0
								],
								'physical'	=> [
										'label'	=> 'Physical',
										'selected' 	=> strcasecmp($game->type, 'physical') === 0
								]
						]
				],
				'quantity'	=> [
						'name'		=> 'input-quantity',
						'type'		=> 'number',
						'label'		=> 'Quantity',
						'value'		=> strcasecmp($game->type, 'physical') === 0 ? $game->quantity : '',
						'disabled'	=> strcasecmp($game->type, 'digital') === 0
				],
				'download_url'	=> [
						'name'		=> 'input-download-url',
						'type'		=> 'text',
						'label'		=> 'Download URL',
						'value'		=> strcasecmp($game->type, 'digital') === 0 ? $game->download_url : '',
						'disabled'	=> strcasecmp($game->type, 'physical') === 0
				]
		];
		
		$renderParams['formEditGame'] = [
				'sections'	=> $formEditGame,
				'action'	=> $this->app()->urlFor('edit-game', ['gameID' => $gameID]),
				'method'	=> 'post',
				'submit'	=> 'Save',
				'submitInFooter'	=> true
		];
		
		
		$renderParams = array_merge($renderParams, [
				'header'		=> 'Edit Game',
				'active'		=> 'games',
				'breadcrumbs' 	=> $breadcrumbs
		]);
		
		
		$this->app()->render('game/game_form.twig', $renderParams);
	}
	
}