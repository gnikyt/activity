<?php

namespace TylerKing\Activity;

abstract class Activity implements \TylerKing\Activity\ActivityInterface
{
    protected $data;

    public function __construct()
    {
        $this->data = [];

        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setCreator($id)
    {
        $this->creator = (int) $id;

        return $this;
    }

    public function getCreator()
    {
        return (int) $this->creator;
    }

    public function setTime($time)
    {
        $datetime   = new \DateTime;
        $this->time = ($time instanceof $datetime) ? $time : $datetime->setTimestamp($time);
    }

    public function getTime()
    {
        if (! isset($this->time)) {
            $this->setTime(new \DateTime);
        }

        return $this->time;
    }

    public function __call($name, $arguments = null) {
        $_type = substr($name, 0, 3);
        $_name = substr($name, 3);

        switch ($_type) {
            case 'get':
                return array_key_exists($_name, $this->data) ? $this->data[$_name] : null;
                break;
            case 'set':
                $this->data[$_name] = $arguments[0];
                return $this;
            default:
                throw new \Execption(sprintf('No method "%d" exists.', $name));
                break;
        }
    }

    public function serialize()
    {
        return  json_encode(array_merge([
                    'Issuer'  => get_class($this),
                    'Creator' => $this->getCreator(),
                    'Time'    => $this->getTime()->getTimeStamp()
                ], $this->data));
    }

    public function load($json)
    {
        $json = is_object($json) ? $json : json_decode($json);
        foreach ($json as $key => $value) {
            call_user_func_array([$this, "set{$key}"], [$value]);
        }
    }
}
