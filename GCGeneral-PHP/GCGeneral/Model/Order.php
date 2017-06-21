<?php 
namespace GCGeneral\Model;

use PhpMyCP\System\Model;

class Order extends Model {
	
	public $primaryKey = "order_id";
	public $table = "orders";
	public $timestamps = true;
	
	protected $fillable = [
			"game_id",
			"first_name",
			"last_name",
			"email",
			"address"
			
	];
	
	public function game() {
		return $this->hasOne(Game::class, 'game_id', 'game_id');
	}
	
	
}