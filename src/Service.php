<?php
namespace Garrett9\LaravelServiceRepository;

use Garrett9\LaravelServiceRepository\Contracts\IRepository;
use Garrett9\LaravelServiceRepository\Contracts\IService;

/**
 * An abstract Service class to be extended by all others Services.
 *
 * @author garrettshevach@gmail.com
 *        
 */
abstract class Service implements IService
{

    /**
     * The IRepository instance for the service.
     *
     * @var IRepository
     */
    protected $repository;

    /**
     * The constructor.
     *
     * @param IRepository $repository            
     */
    public function __construct(IRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::count()
     */
    public function count(array $where = [])
    {
        return $this->repository->count($where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::min()
     */
    public function min($column, array $where = [])
    {
        return $this->repository->min($column, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::max()
     */
    public function max($column, array $where = [])
    {
        return $this->repository->max($column, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::sum()
     */
    public function sum($column, array $where = [])
    {
        return $this->repository->sum($column, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::avg()
     */
    public function avg($column, array $where = [])
    {
        return $this->repository->avg($column, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::increment()
     */
    public function increment($id, $column, $amount = 1)
    {
        return $this->repository->increment($id, $column, $amount);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::decrement()
     */
    public function decrement($id, $column, $amount = 1)
    {
        return $this->repository->decrement($id, $column, $amount);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::get()
     */
    public function get(array $where = [], array $with = [])
    {
        return $this->repository->get($where, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::whereIn()
     */
    public function whereIn($column, array $whereIn = [], array $where = [], array $with = [])
    {
        return $this->repository->whereIn($column, $whereIn, $where, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::groupBy()
     */
    public function groupBy($group, array $where = [], array $with = [])
    {
        return $this->repository->groupBy($group, $where, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::search()
     */
    public function search(array $whereLike = [], array $where = [], array $with = [])
    {
        return $this->repository->search($whereLike, $where, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::exists()
     */
    public function exists($id)
    {
        return $this->repository->exists($id);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::find()
     */
    public function find($id, array $with = [])
    {
        return $this->repository->find($id, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::lockForUpdate()
     */
    public function lockForUpdate($id, array $with = [])
    {
        return $this->repository->lockForUpdate($id, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::create()
     */
    public function create(array $data = [])
    {
        return $this->repository->create($data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::insert()
     */
    public function insert(array $data = [])
    {
        return $this->repository->insert($data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::update()
     */
    public function update($id, array $data = [])
    {
        return $this->repository->update($id, $data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::directUpdate()
     */
    public function directUpdate($id, array $data = [])
    {
        return $this->repository->directUpdate($id, $data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::delete()
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\IService::clear()
     */
    public function clear(array $where = [])
    {
        return $this->repository->clear($where);
    }
}