<?php

class TimeZoneHelper {


    public static function getTimeZoneData() {
        $regions = array(
            'Africa' => DateTimeZone::AFRICA,
            'America' => DateTimeZone::AMERICA,
            'Antarctica' => DateTimeZone::ANTARCTICA,
            'Aisa' => DateTimeZone::ASIA,
            'Atlantic' => DateTimeZone::ATLANTIC,
            'Europe' => DateTimeZone::EUROPE,
            'Indian' => DateTimeZone::INDIAN,
            'Pacific' => DateTimeZone::PACIFIC
        );
        $result = array();
        foreach ($regions as $mask) {
            $zones = DateTimeZone::listIdentifiers($mask);
            $zones = self::prepareZones($zones);
            $i = 0;
            foreach ($zones as $zone) {
                $i++;
                $continent = $zone['continent'];
                $city = $zone['city'];
                $subcity = $zone['subcity'];
                $p = $zone['p'];
                $timeZone = $zone['time_zone'];
                if ($city) {
                    if ($subcity) {
                        $city = $city . '/' . $subcity;
                    }
                    $result[$continent][$timeZone] = "(UTC " . $p . ") " . str_replace('_', ' ', $city);
                }
            }
        }
        return $result;
    }

    /**
     * @param string $zone Example "Europe/Kiev"
     * @return string 
     */
    public static function getTimeZone($zone) {
        if (isset($zone)) {
            $time = new DateTime(NULL, new DateTimeZone($zone));
            $p = $time->format('P');
            return Yii::t('default', 'CURRENT_TIMEZONE', array(
                        '{zone}' => $zone,
                        '{p}' => $p
            ));
        }
        //print_r($zones);
    }

    private static function prepareZones(array $timeZones) {
        $list = array();
        foreach ($timeZones as $zone) {
            $time = new DateTime(NULL, new DateTimeZone($zone));
            $p = $time->format('P');
            if ($p > 13) {
                continue;
            }
            $parts = explode('/', $zone);

            $list[$time->format('P')][] = array(
                'time_zone' => $zone,
                'continent' => isset($parts[0]) ? $parts[0] : '',
                'city' => isset($parts[1]) ? $parts[1] : '',
                'subcity' => isset($parts[2]) ? $parts[2] : '',
                'p' => $p,
            );
        }

        ksort($list, SORT_NUMERIC);

        $zones = array();
        foreach ($list as $grouped) {
            $zones = array_merge($zones, $grouped);
        }

        return $zones;
    }

}
