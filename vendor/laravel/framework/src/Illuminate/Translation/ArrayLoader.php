<?php

namespace Illuminate\Validation\Rules;

class Dimensions
{
    /**
     * The constraints for the dimensions rule.
     *
     * @var array
     */
    protected $constraints = [];

    /**
     * Create a new dimensions rule instance.
     *
     * @param  array  $constraints;
     * @return void
     */
    public function __construct(array $constraints = [])
    {
        $this->constraints = $constraints;
    }

    /**
     * Set the "width" constraint.
     *
     * @param  int  $value
     * @return $this
     */
    public function width($value)
    {
        $this->constraints['width'] = $value;

        return $this;
    }

    /**
     * Set the "height" constraint.
     *
     * @param  int  $value
     * @return $this
     */
    public function height($value)
    {
        $this->constraints['height'] = $value;

        return $this;
    }

    /**
     * Set the "min width" constraint.
     *
     * @param  int  $value
     * @return $this
     */
    public function minWidth($value)
    {
        $this->constraints['min_width'] = $value;

        return $this;
    }

    /**
     * Set the "min height" constraint.
     *
     * @param  int  $value
     * @return $this
     */
    public function minHeight($value)
    {
        $this->constraints['min_height'] = $value;

        return $this;
    }

    /**
     * Set the "max width" constraint.
     *
     * @param  int  $value
     * @return $this
     */
    public function maxWidth($value)
    {
        $this->constraints['max_width'] = $value;

        return $this;
    }

    /**
     * Set the "max height