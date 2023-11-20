<?php

namespace App\Services\Validators;

use App\Services\Database\DBW;

class Validator
{
    private $error_message;
    public function getErrrorMessage()
    {
        return $this->error_message;
    }

    public function setErrrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    /**
     * @param array $data
     * @param string $db_name
     */
    public function check_unique_db(array $data, string $db_name): bool
    {
        $bd = new DBW();
        foreach ($data as $key => $value) {
            $count = $bd->select(['count(*) as count'], $db_name)->where("$key", "$value")->get();
            if ($count['count'] > 0) {
                $this->setErrrorMessage("Поле $key уже занято");
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     */
    public function check_empty_value(array $data): bool
    {
        foreach ($data as $key => $value) {
            if ($value == "") {
                $this->setErrrorMessage("Поле $key обязательное");
                return false;
            }
        }

        return true;
    }
}
