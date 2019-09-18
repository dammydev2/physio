::ARRAY_RECURSIVE_KEY]);
        }elseif ($data instanceof \stdClass){
            if(isset($storage[$data])){
                return;
            }
            $storage[$data] = true;
            foreach ($data as &$property){
                static::unwrapClosures($property, $storage);
            }
        } elseif (is_object($data) && !($data instanceof Closure)){
            if(isset($storage[$data])){
                return;
            }
            $storage[$data] = true;
            $reflection = new ReflectionObject($data);

            do{
                if(!$reflection->isUserDefined()){
                    break;
                }
                foreach ($reflection->getProperties() as $property){
                    if($property->isStatic() || !$property->getDeclaring