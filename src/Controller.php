<?php

namespace Garrett9\LaravelServiceRepository;

use Illuminate\Routing\Controller as BaseController;
use Garrett9\LaravelServiceRepository\IService;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

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
     * The Guard instance for the controller.
     * 
     * @var Guard
     */
    protected $guard;
    
    /**
     * The constructor.
     * 
     * @param IService $service
     * @param Request $request
     * @param Guard $guard
     */
    public function __construct($service, $request, $guard)
    {
        $this->service = $service;
        $this->request = $request;
        $this->guard = $guard;
    }
}
