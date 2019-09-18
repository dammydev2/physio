  * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany($ids, $columns = ['*'])
    {
        if (empty($ids)) {
            return $this->getRelated()->newCollection();
        }

        return $this->whereIn(
            $this->getRelated()->getQualifiedKeyName(), $ids
        )->get($columns);
    }

    /**
     * Find a related model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->find($id, $columns);

        if (is_array($id)) {
            if (count($result) === count(array_unique($id))) {
                return $result;
            