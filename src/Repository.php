<?php
namespace Garrett9\LaravelServiceRepository;

use Garrett9\LaravelServiceRepository\Contracts\IRepository;
use LaravelBook\Ardent\Ardent;
use Doctrine\DBAL\Query\QueryBuilder;
use Garrett9\LaravelServiceRepository\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Garrett9\LaravelServiceRepository\Exceptions\IntegrityConstraintViolationException;
use Garrett9\LaravelServiceRepository\Exceptions\ValidationException;

/**
 * The base Repository class for all other repositories to extend.
 *
 * @author garrettshevach@gmail.com
 *        
 */
abstract class Repository implements IRepository
{

    /**
     * The model associated to this repository.
     *
     * @var \LaravelBook\Ardent\Ardent
     */
    protected $model;

    /**
     * The contructor.
     *
     * @param Ardent $model
     *            The model associated to this repository.
     */
    public function __construct(Ardent $model)
    {
        $this->model = $model;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::count()
     */
    public function count(array $where = [])
    {
        return $this->model->where($where)->count();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::min()
     */
    public function min($column, array $where = [])
    {
        return $this->model->where($where)->min($column);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::max()
     */
    public function max($column, array $where = [])
    {
        return $this->model->where($where)->max($column);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::sum()
     */
    public function sum($column, array $where = [])
    {
        return $this->model->where($where)->avg($column);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::avg()
     */
    public function avg($column, array $where = [])
    {
        return $this->model->where($where)->avg($column);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::increment()
     */
    public function increment($id, $column, $amount = 1)
    {
        $this->model->where([
            $this->model->getKeyName() => $id
        ])
            ->increment($column, $amount);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::decrement()
     */
    public function decrement($id, $column, $amount = 1)
    {
        $this->model->where([
            $this->model->getKeyName() => $id
        ])
            ->decrement($column, $amount);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::all()
     */
    public function get(array $where = [], array $with = [])
    {
        return $this->buildGetQuery($where, $with)->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::whereIn()
     */
    public function whereIn($column, array $whereIn = [], array $where = [], array $with = [])
    {
        return $this->buildGetQuery($where, $with)
            ->whereIn($column, $whereIn)
            ->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::groupBy()
     */
    public function groupBy($group, array $where = [], array $with = [])
    {
        return $this->buildGetQuery($where, $with)
            ->groupBy($group)
            ->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::search()
     */
    public function search(array $whereLike = [], array $where = [], array $with = [])
    {
        $qry = $this->buildGetQuery($where, $with);
        foreach ($whereLike as $column => $search) {
            if (! is_null($search))
                $qry($column, 'like', '%' . $search . '%');
        }
        return $qry->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::exists()
     */
    public function exists($id)
    {
        if (is_array($id))
            return $this->model->where($id)->exists();
        else
            return $this->model->where([
                $this->model->getKeyName() => $id
            ])
                ->exists();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::find()
     */
    public function find($id, array $with = [])
    {
        return $this->internalFind($id, $with);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::lockForUpdate()
     */
    public function lockForUpdate($id, array $with = [])
    {
        return $this->internalFind($id, $with, function (QueryBuilder $qry) {
            $qry->lockForUpdate();
        });
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::create()
     */
    public function create(array $data = [])
    {
        return $this->internalCreate($data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::insert()
     */
    public function insert(array $data = [])
    {
        return $this->model->insert($data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::update()
     */
    public function update($id, array $data = [])
    {
        return $this->internalUpdate($id, $data);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::directUpdate()
     */
    public function directUpdate($id, array $data = [])
    {
        if (is_array($id))
            return $this->model->where($id)->update($data);
        else
            return $this->model->where([
                $this->model->getKeyName() => $id
            ])
                ->update($data);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::delete()
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }
    
    /**
     * {@inheritDoc}
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::clear()
     */
    public function clear(array $where = [])
    {
        return $this->model->where($where)->delete();
    }

    /**
     * Build a query builder for performing a query for multiple records.
     *
     * @param array $where
     *            WHERE parameters to add to the query.
     * @param array $with
     *            The relationships to load with the retrieved records.
     * @return QueryBuilder The QueryBuilder instance for performing the query.
     */
    protected function buildGetQuery(array $where = [], array $with = [])
    {
        return $this->model->where($where)->with($with);
    }

    /**
     * An internal method for finding an individual record in the repository.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @param array $with
     *            Extra relationships to load with the model.
     * @param \Closure $closure
     *            An optional \Closure that will be executed before the query is performned. It will be passed the QueryBuilder instance allowing the caller to alter to the query.
     * @return Model The retrieved record.
     */
    protected function internalFind($id, array $with, \Closure $closure = null)
    {
        $qry = $this->model->with($with);
        if (is_array($id))
            $qry->where($id);
        else
            $qry->where([
                $this->model->getKeyName() => $id
            ]);
        if (! is_null($closure))
            $closure($qry);
        $model = $qry->first();
        
        if (! is_null($model))
            return $model;
        throw new NotFoundException();
    }

    /**
     * Handles the logic for creating a new record in the repository.
     *
     * @param array $data
     *            The data to create the record with.
     * @param \Closure $closure
     *            An optional closure which will be provided the model instance after attributes have been filled into the model, and before it is saved.
     * @return mixed The primary key value that was created when the record was created.
     * @throws IntegrityConstraintViolationException If there was an integrity constraint with the database when creating the record.
     * @throws PDOException If there was a database error.
     * @throws ValidationException If the model could not be saved due to validation.
     */
    protected function internalCreate(array $data = [], \Closure $closure = null)
    {
        $model = $this->model->newInstance()->safeFill($data);
        if (! is_null($closure))
            $closure($model);
        try {
            if ($model->save())
                return $model->getKey();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new IntegrityConstraintViolationException($e->getMessage(), $e->getCode(), $e);
            throw $e;
        }
        throw new ValidationException('Failed to create the new record!', $model->errors()->toArray());
    }

    /**
     * Hanldes the logic for updating a record in the repository.
     *
     * @param mixed $id
     *            The record's primary key or WHERE parameters.
     * @param array $data
     *            The data to update the record with.
     * @param \Closure $closure
     *            An optional closure which will be passed the model after it is filled with the given data, and before it is updated.
     * @throws ValidationException If the model could not be saved due to validation.
     */
    protected function internalUpdate($id, array $data = [], \Closure $closure = null)
    {
        $model = $this->find($id)->safeFill($data);
        if (! is_null($closure))
            $closure($model);
        if (! $model->updateUniques())
            throw new ValidationException('Failed to save the record.', $model->errors()->toArray());
    }
}