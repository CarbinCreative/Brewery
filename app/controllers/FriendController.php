<?php
namespace app\Controller;

use Brewery\Application\ControllerAbstract;

class FriendController extends ControllerAbstract {

	public function getFriendsOf($account) {

		return sprintf("%s | %s", __METHOD__, $account);

	}

}