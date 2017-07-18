<?php

namespace Atlantis\Helpers;

/**
 *  Usage:
 *
 *   $t = \App::make("Transport");
 *
 *  The last parameter will tell the stack to append or to clear and set only one value
 *
 * $t->setEventValue("page.prediscovery", array("name" => "evgeni", "weight" => 10), true );
 *
 */
class Transport extends \ArrayObject
{

    public function registerEvent($event_name)
    {

        if (!$this->offsetExists($event_name)) {
            $this->offsetSet($event_name, new \ArrayObject());
        }
    }

    public function setEventValue($event_name, array $event_value, $clearStack = false)
    {

        // in case this event has not being registered yet
        if (!$this->has($event_name)) {
            $this->registerEvent($event_name);
        }

        if ($clearStack) {
            //delete the entire key stack and set only this value
            $this->offsetUnset($event_name);
            $this->offsetSet($event_name, new \ArrayObject());
            $this->offsetGet($event_name)->append($event_value);
        } else {

            $this->offsetGet($event_name)->append($event_value);
            //sorts the nested array by weight
            $this->offsetGet($event_name)->uasort('\Atlantis\Helpers\Transport::cmp');
        }
    }

    public static function cmp($a, $b)
    {

        if ($a["weight"] == $b["weight"]) {
            return 0;
        }

        return ($a["weight"] < $b["weight"]) ? -1 : 1;
    }

    public function has($event_name)
    {

        if ($this->offsetExists($event_name)) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegisteredEvents()
    {
        return $this->getArrayCopy();
    }

    public function getEvent($event_name, $array = false)
    {

        if ($array) {
            (array)$result = array();
        } else {
            (string)$result = "";
        }

        if ($this->has($event_name)) {

            foreach ($this->offsetGet($event_name) as $v) {

                foreach ($v as $key => $values) {
                    if ($key != "weight") {
                        if ($array) {
                            $result[] = isset($values) ? $values : "";
                        } else {
                            $result .= isset($values) ? $values : "";
                        }
                    }
                }
            }
        }

        return $result;
    }

}