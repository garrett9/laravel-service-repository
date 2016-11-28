<?php
namespace Garrett9\LaravelServiceRepository;

use Illuminate\Http\Request;
use Garrett9\LaravelServiceRepository\Contracts\IService;
use Illuminate\Http\Response;
use Garrett9\LaravelServiceRepository\Exceptions\ValidationException;
use Illuminate\Contracts\Routing\ResponseFactory;

/**
 * An abstract Controller class to be extended by all controllers that require the use of view responses.
 *
 * @author garrettshevach@gmail.com
 *        
 */
abstract class ViewController extends Controller
{

    /**
     * The base path to the controller's views.
     *
     * @var string
     */
    protected $path;

    /**
     * The constructor.
     * 
     * @param IService $service
     * @param Request $request
     * @param ResponseFactory $response_factory
     * @param unknown $path
     */
    public function __construct(IService $service, Request $request, ResponseFactory $response_factory, $path = null)
    {
        parent::__construct($service, $request, $response_factory);
        $this->path = (is_null($path) ? strtolower(snake_case(str_replace('Controller', '', class_basename($this)))) : rtrim(str_replace('/', '.', $path), '.'))  . '.';
    }

    /**
     * Return all of the results from the controller's service.
     *
     * @return Response
     */
    public function showIndex()
    {
        return $this->view('index', [
            'models' => $this->service->get()
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
        return $this->view('view', [
            'model' => $this->service->find($id)
        ]);
    }

    /**
     * Returns the view to create a new record.
     *
     * @return Response
     */
    public function showCreate()
    {
        return $this->view('create');
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
            return $this->redirectToAction(__CLASS__ . '@showView', [$id], ['created' => $id]);
        } catch (ValidationException $e) {
            $this->request->flash();
            return $this->view('create', ['errors' => $e->getErrors()]);
        }
    }

    /**
     * Return the view for editing a record.
     *
     * @param number $id
     *            The primary key value of the record to edit.
     * @return Response
     */
    public function showEdit($id)
    {
        return $this->view('edit', [
            'model' => $this->service->find($id)
        ]);
    }

    /**
     * Edits an existing record, and then returns the view to edit it.
     *
     * @param number $id
     *            The primary key value of the record to edit.
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $this->service->update($id, $this->request->input());
            return $this->showEdit($id, ['saved' => $id]);
        } catch (ValidationException $e) {
            return $this->view('edit')->with('errors', $e->getErrors());
        }
    }
    
    /**
     * Delete a record from the controller's service.
     * 
     * @param number $id The primary key value of the record to delete.
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->service->delete($id);
        return $this->view('index', ['deleted' => $id]);
    }

    /**
     * Make a view with the given data and return it.
     *
     * @param string $view
     *            The file of the view to load.
     * @param array $data
     *            The data to load with the view.
     * @param number $code The status code to give the view.
     * @return Response
     */
    protected function view($view, array $data = [], $code = Response::HTTP_OK)
    {
        return $this->response_factory->view('desktop/' . $this->path . $view, $data, $code);
    }

    /**
     * Create a redirect response to a path and return it.
     *
     * @param string $path
     *            The path to redirect to.
     * @param array $data The data to send with the redirect.
     * @return Response
     */
    protected function redirectTo($path, array $data = [])
    {
        return $this->response_factory->redirectTo($path)->with($data);
    }

    /**
     * Create a redirect response to a route and return it.
     *
     * @param string $route
     *            The route to redirect to.
     * @param array $params
     *            The params for the redirection.
     * @param array $data The data to send with the redirect.
     * @return Response
     */
    protected function redirectToRoute($route, array $params = [], array $data = [])
    {
        return $this->response_factory->redirectToRoute($route, $params)->with($data);
    }

    /**
     * Creates a redirect response to the given action.
     *
     * @param string $action
     *            The action to redirect to.
     * @param array $params
     *            The params for the redirection.
     * @param array $data The data to send with the redirect.
     * @return Response
     */
    protected function redirectToAction($action, array $params = [], array $data = [])
    {
        return $this->response_factory->redirectToAction($action, $params)->with($data);
    }
}