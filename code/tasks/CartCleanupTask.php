<?php
/**
 * Cart Cleanup Task
 * Removes all orders (carts) that are older than a specific number of days.
 * @package shop
 * @subpackage tasks
 */
class CartCleanupTask extends WeeklyTask{

	static $cleardays = 90;

	function set_clear_days($days = 90){
		self::$cleardays = $days;
	}

	//Find and remove carts older than X days
	function process(){

		$time = date('Y-m-d H:i:s', strtotime("-".self::$cleardays." days"));
		$filter = "\"Status\" = 'Cart'";
		
		if(self::$cleardays){
			$filter .= " AND \"LastEdited\" < '$time'";
		}
		
		if($oldcarts = DataObject::get('Order',$filter)){
			echo "<br/>\nDeleted ids: ";
			foreach($oldcarts as $cart){
				echo $cart->Title." ";
				$cart->delete();
				$cart->destroy();
			}
		}

	}

}
