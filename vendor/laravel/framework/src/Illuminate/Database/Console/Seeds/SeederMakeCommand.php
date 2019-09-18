     */
    protected function newMorphToMany(Builder $query, Model $parent, $name, $table, $foreignPivotKey,
                                      $relatedPivotKey, $parentKey, $relatedKey,
                                      $relationName = null, $inverse = false)
    {
        return new MorphToMany($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Define a polymorphic, inverse many-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string  $table
     * @param  string  $foreignPivotKey
     * @param  string  $relatedPivotKey
     * @param  string  $parentKey
     * @param  string  $relatedKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function morphedByMany($related, $name, $table = null, $foreignPivotKey = null,
                                  $relatedPivotKey = null, $parentKey = null, $relatedKey = null)
    {
        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();

        // For the inverse of the polymorphic many-to-many relations, we will change
        // the way we determine the foreign and other keys, as it is the opposite
        // of the morph-to-many method since we're figuring out these inverses.
        $relatedPivotKey = $relatedPivotKey ?: $name.'_id';

        return $this->morphToMany(
            $related, $name, $table, $foreignPivotKey,
            $relatedPivotKey, $parentKey, $relatedKey, true
        );
    }

    /**
     * Get the relationship name of the belongsToMany relationship.
     *
     * @return string|null
     */
    protected function guessBelongsToManyRelation()
    {
        $caller = Arr::first(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), function ($trace) {
            return ! in_array(
      