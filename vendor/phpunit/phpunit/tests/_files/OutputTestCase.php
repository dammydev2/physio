            }
                    } else {
                        if (!is_null($value)) {
                            if(!is_null($relation)) {
                                $query->orWhereHas($relation, function($query) use($field,$condition,$value) {
                                    $query->where($field,$condition,$value);
                                });
                            } else {
                                $query->orWhere($modelTableName.'.'.$field, $condition, $value);
                            }
                        }
                    }
                }
            });
        }

        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode('|', $orderBy);
            if(count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products o