<?php

namespace Api;

use Classes\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['list-all-boradbands'] = array("route" => "/",
								                "controller" => "indexController",
								                "action" => "index");

		$this->setRoutes($routes);
	}

}