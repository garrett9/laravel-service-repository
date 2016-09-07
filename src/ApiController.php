<?php
namespace Garrett9\LaravelServiceRepository;

use Garrett9\LaravelServiceRepository\Contracts\IService;
use Garrett9\LaravelServiceRepository\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

/**
 * An abstract Controller class to be extended by all controllers that require the use of JSON responses.
 *
 * @author garrettshevach@gmail.com
 *        
 */
abstract class ApiController extends Controller
{

    /**
     * The constructor.
     *
     * @param IService $service            
     * @param Request $request            
     * @param ResponseFactory $response_factory            
     */
    public function __construct(IService $service, Request $request, ResponseFactory $response_factory)
    {
        parent::__construct($service, $request, $response_factory);
    }

    /**
     * Count the number of results in the controller's service's repository.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        return $this->ok([
            'results' => $this->service->count()
        ]);
    }

    /**
     * Return all of the results from the controller's service's repository.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->ok($this->service->get()
            ->toArray());
    }

    /**
     * Retrieve a record given its ID.
     *
     * @param number $id
     *            The ID of the record to retrieve.
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        return $this->ok($this->service->find($id)
            ->toArray());
    }

    /**
     * Create a new record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->internalCreate($this->request->input());
    }

    /**
     * Update a record.
     *
     * @param number $id
     *            The ID of the record to update.
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->internalUpdate($id, $this->request->input());
    }

    /**
     * Delete a record.
     *
     * @param number $id
     *            The ID of the record to delete.
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->service->delete($id);
        return $this->ok();
    }

    /**
     * Create a record using the controller's service.
     *
     * @param array $data
     *            The data to create the record with.
     * @return \Illuminate\Http\Response
     */
    protected function internalCreate(array $data = [])
    {
        try {
            $id = $this->service->create($data);
            return $this->created([
                'id' => $id
            ]);
        } catch (ValidationException $e) {
            return $this->badRequest('Failed to create the record.', [
                'errors' => $e->getErrors()
            ]);
        }
    }

    /**
     * Update a record using the controller's service.
     *
     * @param number $id
     *            The primary key value of the record to update.
     * @param array $data
     *            The data to update the record with.
     * @return \Illuminate\Http\Response
     */
    protected function internalUpdate($id, array $data = [])
    {
        try {
            $this->service->update($id, $data);
            return $this->ok();
        } catch (ValidationException $e) {
            return $this->badRequest('Failed to save your changes.', [
                'errors' => $e->getErrors()
            ]);
        }
    }
    
    /**
     * Delete a record using the controller's service.
     * 
     * @param number $id The primary key value of the record to delete.
     * @return \Illuminate\Http\Response
     */
    protected function internalDelete($id)
    {
        $this->service->delete($id);
        return $this->ok();
    }

    /**
     * Return an OK JSON response.
     *
     * @param array $data
     *            The data to return with the JSON response.
     * @param number $status
     *            The status code of the response
     * @return \Illuminate\Http\Response
     */
    protected function ok(array $data = [])
    {
        return $this->response_factory->json($data, Response::HTTP_OK);
    }

    /**
     * Return a Created JSON response.
     *
     * @param array $data
     *            The data to return with the response.
     * @return \Illuminate\Http\Response
     */
    protected function created(array $data = [])
    {
        return $this->response_factory->json($data, Response::HTTP_CREATED);
    }

    /**
     * Return a Bad Request JSON response.
     *
     * @param string $message
     *            An error message to return with the response.
     * @param array $data
     *            Extra data to return with the response.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message = 'Bad Request!', array $data = [])
    {
        return $this->response_factory->json(array_merge([
            'message' => $message
        ], $data), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Return a Forbidden JSON response.
     *
     * @param string $message
     *            An error message to return with the response.
     * @return \Illuminate\Http\Response
     */
    protected function forbidden($message = 'Forbidden!')
    {
        return $this->response_factory->json([
            'message' => $message
        ], Response::HTTP_FORBIDDEN);
    }
}