<?php 

namespace GCGeneral\Controller\Order;


use GCGeneral\Model\Order;
use PhpMyCP\System\Controller;
use PhpMyCP\System\Navigation\Dropdown;

class OrdersList extends Controller {
	
	//Page Title
	private $title = "Orders List";
	
	//Initialize routes
	public function initRoutes() {
		$this->app()->get('/orders/list', [$this, 'get'])->name('orders-list');
		$this->app()->get('/json-encode/create-order', [$this, 'createOrderFromJSONEncoded']);
	}
	
	public function createOrderFromJSONEncoded() {
		$orderInfo = json_decode($_POST['order-info']);
		
		echo json_encode([[
					"message" => "Testing"
		]]);
	}
	
	//Creates Dropdown Navigations
	public function onNavigation() {
		$dropdown = new Dropdown('orders',  ['pencil-square-o', 'Orders']);
		$dropdown->add("orders-list", 'Order List', 'orders-list');
	
		$this->navigation()->defaultCategory->addDropdown($dropdown);
	}

	public function get($renderParams = array()) {
		//Breadcrumbs to display in top-right corner
		$breadcrumbs = array();
		
		$breadcrumbs[] = [
				'url'	=> $this->app()->urlFor('dashboard'),
				'name'	=> 'Orders List',
				'icon'	=> 'list'
		];

		$orders = Order::all();
		$total = 0;
		
		//Calculate Total
		foreach($orders as $order) {
			$total += $order->total_paid;
		}
		
		$renderParams = array_merge($renderParams, [
				'header'		=> 'Orders List',
				'title' 		=> $this->title,
				'breadcrumbs' 	=> $breadcrumbs,
				'active'		=> 'orders',
				'total'			=> number_format($total, 2),
				'orders'		=> $orders
		]);
		
		//Render View
		$this->app()->render('order/orders_list.twig', $renderParams);
	}
	
}