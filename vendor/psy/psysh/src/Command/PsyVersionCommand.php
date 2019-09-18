     }

            // clean this up for super.
            $type = $type & ~self::NUMBER_LINES & ~self::OUTPUT_RAW;
        }

        parent::write($messages, $newline, $type);
    }

    /**
     * Writes a message to the output.
     *
     * Handles paged output, or writes directly to the output stream.
     *
     * @param string $message A message to write to the output
     * @param bool   $newline Whether to add a newline or not
     */
    public function doWrite($message, $newline)
    {
        if ($this->paging > 0) {
            $this->pager->doWrite($message, $newline);
        } else {
            parent::doWrite($message, $newline);
        }
    }

    /**
     * Flush and close the output pager.
     */
    private function closePager()
    {
        if ($this->paging <= 0) {
            $this->pager->close();
        }
    }

    /**
     * Initialize output formatter styles.
     */
    private funct