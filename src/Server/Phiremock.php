<?php
namespace Mcustiel\Phiremock\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Mcustiel\PowerRoute\PowerRoute;
use Mcustiel\Phiremock\Server\Http\RequestHandlerInterface;
use Mcustiel\Phiremock\Common\StringStream;
use Psr\Log\LoggerInterface;

class Phiremock implements RequestHandlerInterface
{
    /**
     * @var \Mcustiel\PowerRoute\PowerRoute
     */
    private $router;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(PowerRoute $router, LoggerInterface $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Mcustiel\Phiremock\Server\Http\RequestHandler::execute()
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response)
    {
        try {
            return $this->router->start($request, $response);
        } catch (\Exception $e) {
            $this->logger->warning('Unexpected exception: ' . $e->getMessage());
            $this->logger->info($e->__toString());
            return $response->withStatus(500)
                ->withBody(new StringStream($e->getMessage()));
        }
    }
}
