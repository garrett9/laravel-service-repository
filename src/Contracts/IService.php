<?php
namespace Garrett9\LaravelServiceRepository\Contracts;

/**
 * An interface to the Service class.
 *
 * @author garrettshevach@gmail.com
 *        
 */
interface IService
{

    /**
     * Count the number of records in the repository.
     *
     * @param array $where
     *            Extra where paramters to add to the query.
     * @return number The number of records found.
     */
    public function count(array $where = []);

    /**
     * Count the number of records using a particular WHERE statement.
     *
     * @param string $column
     *            The column to perform the WHERE statement on.
     * @param string $operator
     *            The operator for the WHERE statement.
     * @param mixed $value
     *            The value for the WHERE statement.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     */
    public function countWhere($column, $operator, $value, array $where = []);

    /**
     * Find the minimum value for a given column.
     *
     * @param string $column
     *            The column to find the minimum value for.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return Model The retrieved record.
     */
    public function min($column, array $where = []);

    /**
     * Find the the maximum value for a given column.
     *
     * @param string $column
     *            The column to find the maximum value for.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return Model The retrieved record.
     */
    public function max($column, array $where = []);

    /**
     * Find the sum of all of a given column's values in the repository's database.
     *
     * @param string $column
     *            The name of the column to find the sum for.
     * @param array $where
     *            Extra WHERE parameters.
     * @return number The calculated sum.
     */
    public function sum($column, array $where = []);

    /**
     * Find the average of all of a given column's values in the repository's database.
     *
     * @param string $column
     *            The name of the column to find the average for.
     * @param array $where
     *            Extra WHERE parameters.
     * @return number The calculated average.
     */
    public function avg($column, array $where = []);

    /**
     * Increment a given column's value for a record by an amount.
     *
     * @param number $id
     *            The primary key of the record to increment a column's value for.
     * @param string $column
     *            The column of the record to increment.
     * @param number $amount
     *            The amount to increment.
     */
    public function increment($id, $column, $amount = 1);

    /**
     * Decrement a given column's value for a record by an amount.
     *
     * @param number $id
     *            The primary key of the record to decrement a column's value for.
     * @param string $column
     *            The column of the record to decrement.
     * @param number $amount
     *            The amount to decrement.
     */
    public function decrement($id, $column, $amount = 1);

    /**
     * Return all records from the repository.
     *
     * @param array $where
     *            WHERE parameters to add to the query.
     * @param array $with
     *            The relationships to load with the retrieved records.
     * @param number $limit
     *            The number of results to return. If 0 or less than 0, then all results will be returned.
     * @return \Illuminate\Database\Eloquent\Collection The retrieved records.
     */
    public function get(array $where = [], array $with = [], $limit = 0);

    /**
     * Return all records from the repository with a specific WHERE statement.
     *
     * @param string $column
     *            The column to perform the WHERE statement on.
     * @param unknown $operator
     *            The operator for the WHERE statement.
     * @param unknown $value
     *            The value for the WHERE statement.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param array $with
     *            The relationships to load with the model.
     * @param number $limit
     *            The number of results to limit to. If 0 or less than 0, all results will be returned.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhere($column, $operator, $value, array $where = [], array $with = [], $limit = 0);

    /**
     * Count the number of records that were created for each day for the given number of months ago (assuming a 30 day month).
     * This method is usefull for building client side charts/graphs.
     *
     * @param number $months
     *            The number of previous months to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countCreatedPerDayForMonthsAgo($months = 1, array $where = []);

    /**
     * Count the number of records that were created for each day for the given number of weeks ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param number $weeks
     *            The number of previous weeks to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countCreatedPerDayForWeeksAgo($weeks = 1, array $where = []);

    /**
     * Count the number of records that were created for each day for the given number of days ago.
     * This method is useful for building client side charts/graphs.
     *
     * @param number $days
     *            The number of previous days to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countCreatedPerDayForDaysAgo($days = 1, array $where = []);

    /**
     * Count the number of records that were created for each hour for the given number of hours ago.
     * This method is usefull for buildign client side charts/graphs.
     *
     * @param number $hours
     *            The number of previous hours to count when counting the number of records per hour.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countCreatedPerHourForHoursAgo($hours = 1, array $where = []);

    /**
     * Sum a column's value for all records per day that were created for the given number of months ago (assuming a 30 day month).
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the values for.
     * @param number $months
     *            The number of previous months to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sumPerDayForMonthsAgo($column, $months = 1, array $where = []);

    /**
     * Sum a column's value for all records per day that were created for the given number of weeks ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the values for.
     * @param number $weeks
     *            The number of previous weeks to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sumPerDayForWeeksAgo($column, $weeks = 1, array $where = []);

    /**
     * Sum a column's value for all records per day that were created for the given number of days ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the values for.
     * @param number $days
     *            The number of previous months to count when counting the number of records per day.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sumPerDayForDaysAgo($column, $days = 1, array $where = []);

    /**
     * Sum a column's value for all records per hour that were created for the given number of hours ago.
     * This method is usefull for building client side charts/graphs.
     *
     * @param string $column
     *            The column to sum the values for.
     * @param number $hours
     *            The number of previous hours to count when counting the number of records per hour.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sumPerHourForHoursAgo($column, $hours = 1, array $where = []);

    /**
     * Return all records that has a column value in an array of given values.
     *
     * @param string $column
     *            The name of the column to perform a WHERE IN statement.
     * @param array $whereIn
     *            The array of values to check if the column has a value in it.
     * @param array $where
     *            Extra WHERE paramters to add to the query.
     * @param array $with
     *            The relationships to include with the retrieved records.
     * @return \Illuminate\Database\Eloquent\Collection The retrieved records.
     */
    public function whereIn($column, array $whereIn = [], array $where = [], array $with = []);

    /**
     * Group all of the results by a given column.
     *
     * @param mixed $group
     *            Either a single column to group by, or an array of columns to group by.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param array $with
     *            Extra relationships to load with the retrieved records.
     * @return \Illuminate\Database\Eloquent\Collection The retrieved results.
     */
    public function groupBy($group, array $where = [], array $with = []);

    /**
     * Perform a search on the given columns.
     *
     * @param array $whereLike
     *            An array of column names => search values.
     * @param array $where
     *            Extra WHERE parameters to add to the query.
     * @param array $with
     *            Extra relationships to load with the retrieved records.
     * @param number $limit
     *            The number of results to limit the query to.
     * @return \Illuminate\Database\Eloquent\Collection The retrieved results.
     */
    public function search(array $whereLike = [], array $where = [], array $with = [], $limit = 0);

    /**
     * Check if a record exists.
     *
     * @param mixed $id
     *            Either the primary key of the record to see if it exists, or an array of WHERE parameters to see if it exists.
     * @return boolean True if at least one record was found, and false otherwise.
     */
    public function exists($id);

    /**
     * Get a single record from the repository.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @param array $with
     *            The relationships to load with the model.
     * @return \Illuminate\Database\Eloquent\Model The found record.
     * @throws NotFoundException If the record was not found.
     */
    public function find($id, array $with = []);

    /**
     * Retrieves, and locks a record for being updated.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @param array $with
     *            The relationships to load with the model.
     * @return \Illuminate\Database\Eloquent\Model The found record.
     * @throws NotFoundException If the record was not found.
     */
    public function lockForUpdate($id, array $with = []);

    /**
     * Create a new record in the repository.
     *
     * @param array $data
     *            The data to create the record with.
     * @return mixed The primary key of the created record.
     * @throws ValidationException If the repository failed to create the new record due to validation errors.
     */
    public function create(array $data = []);

    /**
     * Insert 1 or multiple records into the repository's database.
     * WARNING: This method does not utilize model validation, so be careful when using.
     *
     * @param array $data
     *            The data to insert into the repository's database.
     * @return number How many records were created.
     */
    public function insert(array $data = []);

    /**
     * Update a single record in the repository.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @param array $data
     *            The data to update in the record.
     * @throws NotFoundException If the record was not found.
     * @throws ValidationException If the repository failed to update the record due to validation errors.
     */
    public function update($id, array $data = []);

    /**
     * Update a record, or records, in the repository directly through an UPDATE statement without retrieving an ELOQUENT model.
     * WARNING: This method does not utilize model validation, so be careful when using.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @param array $data
     *            The data to update.
     * @return number The number of records that were updated.
     */
    public function directUpdate($id, array $data = []);

    /**
     * Delete a record in the repository.
     *
     * @param mixed $id
     *            Either the primary key of the record, or an array of WHERE parameters.
     * @throws NotFoundException If the record was not found.
     */
    public function delete($id);

    /**
     * Delete all records in the repositoy's database.
     *
     * @param array $where
     *            WHERE parameters to add to the query.
     * @return number The number of records that were deleted.
     */
    public function clear(array $where = []);
}