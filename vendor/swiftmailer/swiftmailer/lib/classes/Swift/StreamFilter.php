 __toString()
    {
        return $this->toString();
    }

    /** Save a Header to the internal collection */
    private function storeHeader($name, Swift_Mime_Header $header, $offset = null)
    {
        if (!isset($this->headers[strtolower($name)])) {
            $this->headers[strtolower($name)] = [];
        }
        if (!isset($offset)) {
            $this->headers[strtolower($name)][] = $header;
        } else {
            $this->headers[strtolower($name)][$offset] = $header;
        }
    }

    /** Test if the headers can be sorted */
    private function canSort()
    {
        return count($this->order) > 0;
    }

    /** uksort() algorithm for Header ordering */
    private function sortHeade