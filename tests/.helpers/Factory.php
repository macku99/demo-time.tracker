<?php

trait Factory
{

    /**
     * @var int
     */
    protected $times = 1;

    /**
     * Number of times to make entities
     *
     * @param $count
     * @return $this
     */
    protected function times($count)
    {
        $this->times = $count;

        return $this;
    }

    /**
     * Make a new record in the DB
     *
     * @param  string $type
     * @param  array  $attributes
     * @return mixed
     */
    protected function make($type, array $attributes = [])
    {
        $attributes = array_merge($this->getStub(), $attributes);

        return factory($type, $this->times)->create($attributes);
    }

    /**
     * @return array
     * @throws BadMethodCallException
     */
    protected function getStub()
    {
        throw new BadMethodCallException('Create your own getStub method to declare your fields.');
    }

}