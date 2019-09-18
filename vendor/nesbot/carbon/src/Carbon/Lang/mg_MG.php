 {
                    case 0:
                        return '[В прошлое] dddd, [в] LT';
                    case 1:
                    case 2:
                    case 4:
                        return '[В прошлый] dddd, [в] LT';
                    case 3:
                    case 5:
                    case 6:
                        return '[В прошлую] dddd, [в] LT';
                }
            }

            if ($current->dayOfWeek === 2) {
                return '[Во] dddd, [в] LT';
            }

            return '[В] dddd, [в] LT';
        },
        'sameElse' => 'L',
    ],
    'ordinal' => function ($number, $period) {
        switch ($period) {
            case 'M':
            case 'd':
            case 'DDD':
                return $number.'-й';
            case 'D':
                return $number.'-го';
            case 'w':
            case 'W':
                return $number.'-я';
            default:
                return $number;
        }
    },
    'meridiem' => function ($hour) {
        if ($hour < 4) {
            return 'ночи';
        }
        if ($hour < 12) {
            return 'утра';
        }
        if ($hour < 17) {
            return 'дня';
        }

        return 'вечера';
    },
    'months' => ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
    'months_standalone' => ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'],
    'months_short' => ['янв', 'фев', 'мар', 'апр', 'мая', 'июн