<?php 
namespace GCGeneral\Model;

use PhpMyCP\System\Model;

class Genre extends Model {
	
	public $primaryKey = "genre_id";
	public $table = "genres";
	public $timestamps = false;
	
	protected $fillable = [
			"genre",
			"description"
	];
	
	//Used to load static Genres on installation
	public static $baseGenres = [
			"RPG" 				=> "Roll Playing Game - Example Description",
			"Sandbox" 			=> "Sandbox Game - Example Description",
			"Survival" 			=> "Survival Game - Example Description",
			"Action-Adventure" 	=> "Action-Adventure Game - Example Description",
			"FPS" 				=> "First Person Shooter Game - Example Description",
			"Open World" 		=> "Open World Game - Example Description",
			"Fighting" 			=> "Fighting Game - Example Description",
			"Platformer" 		=> "Platformer Game - Example Description",
			"Stealth" 			=> "Steath Game - Example Description",
			"Survival Horror" 	=> "Survival Horror Game - Example Description"
	];
	
	public static function install() {
		foreach(self::$baseGenres as $genreName => $description) {
			$genre = new Genre();
			$genre->genre = $genreName;
			$genre->description = $description;
			$genre->save();
		}
	}
	
}