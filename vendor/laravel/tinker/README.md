
     */
    protected function normalizeUnixObject($item, $base)
    {
        $item = preg_replace('#\s+#', ' ', trim($item), 7);

        if (count(explode(' ', $item, 9)) !== 9) {
            throw new RuntimeException("Metadata can't be parsed from item '$item' , not enough parts.");
        }

        list($permissions, /* $number */, /* $owner */, /* $group */, $size, $month, $day, $timeOrYear, $name) = explode(' ', $item, 9);
        $type = $this->detectType($permissions);
        $path = $base === '' ? $name : $base . $this->separator . $name;

        if ($type === 'dir') {
            return compact('type', 'path');
        }

        $permissions = $this->normalizePermissions($permissions);
        $visibility = $permissions & 0044 ? AdapterInterface::VISIBILITY_PUBLIC : AdapterInterface::VISIBILITY_PRIVATE;
        $size = (int) $size;

        $result = compact('type', 'path', 'visibility', 'size');
        if ($this->enableTimestampsOnUnixListings) {
            $timestamp = $this->normalizeUnixTimestamp($month, $day, $time