if no translation available for current language
            case $name === 'upperMeridiem':
                return $this->meridiem();
            // @property-read int current hour from 1 to 24
            case $name === 'noZeroHour':
                return $this->hour ?: 24;
            // @property int
            case $name === 'milliseconds':
                // @property int
            case $name === 'millisecond':
            // @property int
            case $name === 'milli':
                return (int) floor($this->rawFormat('u') / 1000);

            // @property int 1 through 53
            case $name === 'week':
                return (int) $this->week();

            // @property int 1 through 53
            case $name === 'isoWeek':
                return (int) $this->isoWeek();

            // @property int year according to week format
            case $name === 'weekYear':
                return (int) $this->weekYear();

            // @property int year according to ISO week format
            case $name === 'isoWeekYear':
                return (int) $this->isoWeekYear();

            // @property-read int 51 through 53
            case $name === 'weeksInYear':
                return (int) $this->weeksInYear();

            // @property-read int 51 through 53
            case $name === 'isoWeeksInYear':
                return (int) $this->isoWeeksInYear();

            // @property-read int 1 through 5
            case $name === 'weekOfMonth':
                return (int) ceil($this->day / static::DAYS_PER_WEEK);

            // @property-read int 1 through 5
            case $name === 'weekNumberInMonth':
                return (int) ceil(($this->day + $this->copy()->startOfMonth()->dayOfWeekIso - 1) / static::DAYS_PER_WEEK);

            // @property-read int 0 through 6
            case $name === 'firstWeekDay':
                return $this->localTranslator ? ($this->getTranslationMessage('first_day_of_week') ?? 0) : static::getWeekStartsAt();

            // @property-read int 0 through 6
            case $name === 'lastWeekDay':
                return $this->localTranslator ? (($this->getTranslationMessage('first_day_of_week') ?? 0) + static::DAYS_PER_WEEK - 1) % static::DAYS_PER_WEEK : static::getWeekEndsAt();

            // @property int 1 through 366
            case $name === 'dayOfYear':
                return 1 + intval($this->rawFormat('z'));

            // @property-read int 365 or 366
            case $name === 'daysInYear':
                return $this->isLeapYear() ? 366 : 365;

            // @property int does a diffInYears() with default parameters
            case $name === 'age':
                return $this->diffInYears();

            // @property-read int the quarter of this instance, 1 - 4
            // @call isSameUnit
            case $