<?php
namespace Garrett9\LaravelServiceRepository;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Garrett9\LaravelServiceRepository\IService;
use Garrett9\LaravelServiceRepository\Exceptions\ValidationException;

/**
 * An abstract Controller class to be extended by all controllers that require the use of view responses.
 *
 * @author garrettshevach@gmail.com
 *        
 */
abstract class ViewController extends Controller
{

    /**
     * The Response instance for the controller.
     *
     * @var Response
     */
    protected $response;

    /**
     * The base path to the controller's views.
     *
     * @var string
     */
    protected $path;

    /**
     * The constructor.
     *
     * @param string $path
     *            The base path to the controller's views.
     * @param Response $response            
     */
    public function __construct(IService $service, Request $request, Response $response, Guard $guard, $path = null)
    {
        parent::__construct($service, $request, $response, $guard);
        $this->path = is_null($path) ? '' : rtrim(str_replace('/', '.', $path), '.') . '.';
        $this->response = $response;
    }

    /**
     * Return all of the results from the controller's service.
     *
     * @return Response
     */
    public function showIndex()
    {
        return $this->response->view($this->path . 'index', [
            'records' => $this->service->get()
        ]);
    }

    /**
     * Return a single record from the controller's service given the record's primary key value.
     *
     * @param number $id
     *            The primary key value of the record to show.
     * @return Response
     */
    public function showView($id)
    {
        return $this->response->view($this->path . 'show', [
            'record' => $this->service->find($id)
        ]);
    }

    /**
     * Returns the view to create a new record.
     *
     * @return Response
     */
    public function showCreate()
    {
        return $this->response->view($this->path . 'create');
    }

    /**
     * Creates a new record, and then return's the view to show it.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $id = $this->service->create($this->request->input());
            return $this->showView($id);
        } catch (ValidationException $e) {
            return $this->showView($id)->setStatusCode(400, 'Failed to create the new record.');
        }
    }

    /**
     * Return the view for editing a record.
     * 
     * @param number $id The primary key value of the record to edit.
     * @return Response
     */
    public function showEdit($id)
    {
        return $this->response->view($this->path . 'edit', [
            'record' => $this->service->find($id)
        ]);
    }
    
    /**
     * Edits an existing record, and then returns the view to edit it.
     * 
     * @param number $id The primary key value of the record to edit.
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $this->service->update($id, $this->request->input());
            return $this->showEdit($id);
        } catch(ValidationException $e) {
            return $this->showEdit($id)->with($e->getErrors())->setStatusCode(Response::HTTP_BAD_REQUEST, 'Failed to save your changes.');
        }
    }
}