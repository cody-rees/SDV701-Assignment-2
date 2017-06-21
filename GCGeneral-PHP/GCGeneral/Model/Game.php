<?php 
namespace GCGeneral\Model;

use PhpMyCP\System\Model;
use PhpMyCP\System\System;

class Game extends Model {
	
	public $primaryKey = "game_id";
	public $table = "games";
	public $timestamps = true;
	
	protected $fillable = [
			"title",
			"description",
			"price",
			"genre_id",
			"type",
			"download_url",
			"quantity",
			"deleted"
	];
	
	//Install Schema file for Project
	public static function install() {
		System::installFromSQLFile("game-central.sql");
	}
	
}