<?php

namespace Ixolit\Dislo\CDE\Routing;

interface RouterInterface {
	/**
	 * @param ServerRequestInterface $request
	 *
	 * @return RoutingResponse
	 */
	public function route(ServerRequestInterface $request);
}