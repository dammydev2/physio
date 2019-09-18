 @return string
     */
    protected function presentValue(\ReflectionProperty $property, $target)
    {
        // If $target is a class, trait or interface (try to) get the default
        // value for the property.
        if (!\is_object($target)) {
            try {
                $refl = new \ReflectionClass($target);
                $props = $refl->getDefaultProperties();
                if (\array_key_exists($property->name, $props)) {
                    $suffix = $property->isStatic() ? '' : ' <aside>(default)</aside>';

                    return $this->presentRef($props[$property->name]) . $suffix;
                }
            } catch (\Exception $e) {
                // Well, we gave it a shot.
            }

            return '';
        }

        $property->setAccessible(true);
        $value = $property->getValue($target);

        return $this->presentRef