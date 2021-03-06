<?php

namespace Garrett9\LaravelServiceRepository;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Garrett9\LaravelServiceRepository\Contracts\IService;
use Illuminate\Contracts\Routing\ResponseFactory;

/**
 * The base controlelr instance for the class.
 * 
 * @author garrettshevach@gmail.com
 *
 */
abstract class Controller extends BaseController
{
    /**
     * The IService instance for the controller.
     * 
     * @var IService
     */
    protected $service;
    
    /**
     * The Request instance for the controller.
     * 
     * @var Request
     */
    protected $request;
    
    /**
     * The ResponseFactory for the controller.
     * 
     * @var ResponseFactory
     */
    protected $response_factory;
    
    /**
     * The constructor.
     * 
     * @param IService $service
     * @param Request $request
     * @param Guard $guard
     */
    public function __construct(IService $service, Request $request, ResponseFactory $response_factory)
    {
        $this->service = $service;
        $this->request = $request;
        $this->response_factory = $response_factory;
    }
}
