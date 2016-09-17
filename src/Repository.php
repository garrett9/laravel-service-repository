<?php
namespace Garrett9\LaravelServiceRepository;

use Garrett9\LaravelServiceRepository\Contracts\IRepository;
use LaravelBook\Ardent\Ardent;
use Doctrine\DBAL\Query\QueryBuilder;
use Garrett9\LaravelServiceRepository\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Garrett9\LaravelServiceRepository\Exceptions\IntegrityConstraintViolationException;
use Garrett9\LaravelServiceRepository\Exceptions\ValidationException;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;

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
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::countWhere()
     */
    public function countWhere($column, $operator, $value, array $where = [])
    {
        return $this->model->where($column, $operator, $value)->where($where)->count();
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
    public function get(array $where = [], array $with = [], $limit = 0)
    {
        return $this->buildGetQuery($where, $with, $limit)->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::getWhere()
     */
    public function getWhere($column, $operator, $value, array $where = [], array $with = [], $limit = 0)
    {
        return $this->buildGetQuery($where, $with, $limit)
            ->where($column, $operator, $value)
            ->get();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::countPerDayForMonthsAgo($months, $where, $with)
     */
    public function countCreatedPerDayForMonthsAgo($months = 1, array $where = [])
    {
        return $this->countPerDayForDaysAgo($months * 30, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::countPerDayForWeeksAgo($weeks, $where, $with)
     */
    public function countCreatedPerDayForWeeksAgo($weeks = 1, array $where = [])
    {
        return $this->countPerDayForDaysAgo($weeks * 7, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::countPerDayForDaysAgo($days, $where, $with)
     */
    public function countCreatedPerDayForDaysAgo($days = 1, array $where = [])
    {
        return $this->countCreatedPerDayForMinutesAgo($days * 24 * 60, $where);
    }

    /**
     * Count the number of records that were created for each day for the given number of minutes ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param number $minutes
     *            The number of previous minutes to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param array $with
     *            Extra relationships to load with the results.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function countCreatedPerDayForMinutesAgo($minutes = 1, array $where = [], \Closure $closure = null)
    {
        $qry = $this->model->selectRaw('COUNT(*) as count, created_at')
            ->where($where)
            ->where('created_at', '>', (new Carbon())->subMinutes($minutes))
            ->groupBy($this->model->getQuery()
            ->raw('DATE(created_at)'));
        if (! is_null($closure))
            $closure($qry);
        return $qry->get()->groupBy(function ($model) {
            return (new Carbon($model->created_at))->format('Y-m-d');
        });
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::countCreatedPerHourForHoursAgo()
     */
    public function countCreatedPerHourForHoursAgo($hours = 1, array $where = [])
    {
        return $this->countCreatedPerHourForMinutesAgo($hours * 60, $where);
    }

    /**
     * Count the number of records that were created for each hour for the given number of minutes ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param number $minutes
     *            The number of minutes to count when counting the number of records per hour.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param \Closure $closure
     *            A closure passed the Builder instance allowing for editing the query before it is executed.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function countCreatedPerHourForMinutesAgo($minutes = 1, array $where = [], \Closure $closure = null)
    {
        $qry = $this->model->selectRaw('COUNT(*) as count, created_at')
            ->where($where)
            ->where('created_at', '>', (new Carbon())->subMinutes($minutes))
            ->groupBy($this->model->getQuery()
            ->raw('HOUR(created_at), DATE(created_at)'));
        if (! is_null($closure))
            $closure($qry);
        return $qry->get()->groupBy(function ($model) {
            return (new Carbon($model->created_at))->format('Y-m-d H');
        });
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::sumPerDayForMonthsAgo()
     */
    public function sumPerDayForMonthsAgo($column, $months = 1, array $where = [])
    {
        return $this->sumMinutesForDaysAgo($column, $months * 30, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::sumPerDayForWeeksAgo()
     */
    public function sumPerDayForWeeksAgo($column, $weeks = 1, array $where = [])
    {
        return $this->sumPerDayForDaysAgo($column, $weeks * 7, $where);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::sumPerDayForDaysAgo()
     */
    public function sumPerDayForDaysAgo($column, $days = 1, array $where = [])
    {
        return $this->sumPerDayForMinutesAgo($column, $days * 24 * 60, $where);
    }

    /**
     * Sum a column's value for all records per day that were created for the given number of minutes ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the value for.
     * @param number $minutes
     *            The number of previous minutes to count when summing the column's value per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param \Closure $closure
     *            A closure passed the Builder instance allowing for editing the query before it is executed.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function sumPerDayForMinutesAgo($column, $minutes = 1, array $where = [], \Closure $closure = null)
    {
        $qry = $this->model->selectRaw('SUM(' . $this->model->getQuery()
            ->raw($column) . ') as sum, created_at')
            ->where($where)
            ->where('created_at', '>', (new Carbon())->subMinutes($minutes))
            ->groupBy($this->model->getQuery()
            ->raw('DATE(created_at)'))
            ->orderBy('created_at', 'DESC');
        if (! is_null($closure))
            $closure($qry);
        return $qry->get()->groupBy(function ($model) {
            return (new Carbon($model->created_at))->format('Y-m-d');
        });
        ;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::sumPerHourForHoursAgo()
     */
    public function sumPerHourForHoursAgo($column, $hours = 1, array $where = [])
    {
        return $this->sumPerHourForMinutesAgo($column, $hours * 60, $where);
    }

    /**
     * Sum a column's value for all records per hour that were created for the given number of minutes ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the value for.
     * @param number $minutes
     *            The number of previous minutes to count when summing the column's value per hour.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param \Closure $closure
     *            A closure passed the Builder instance allowing for editing the query before it is executed.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function sumPerHourForMinutesAgo($column, $minutes = 1, array $where = [], \Closure $closure = null)
    {
        $qry = $this->model->selectRaw('SUM(' + $this->model->getQuery()
            ->raw($column) + ') as sum')
            ->where($where)
            ->where('created_at', '>', (new Carbon())->subMinutes($minutes))
            ->groupBy($this->model->getQuery()
            ->raw('HOUR(created_at), DATE(created_at'))
            ->orderBy('created_at', 'DESC');
        if (! is_null($closure))
            $closure($qry);
        return $qry->get()->groupBy(function ($model) {
            return (new Carbon($model->created_at))->format('Y-m-d H');
        });
        ;
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
    public function search(array $whereLike = [], array $where = [], array $with = [], $limit = 0)
    {
        $qry = $this->buildGetQuery($where, $with);
        foreach ($whereLike as $column => $search) {
            if (! is_null($search))
                $qry->where($column, 'like', '%' . $search . '%');
        }
        if($limit > 0)
            $qry->take($limit);
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
     *
     * @see \Garrett9\LaravelServiceRepository\Contracts\IRepository::delete()
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     *
     * {@inheritDoc}
     *
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
    protected function buildGetQuery(array $where = [], array $with = [], $limit = 0)
    {
        $qry = $this->model->where($where)->with($with);
        if ($limit > 0)
            $qry->take($limit);
        return $qry;
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