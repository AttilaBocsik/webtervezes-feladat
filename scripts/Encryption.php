<?php

class Encryption
{
    public function __construct()
    {

    }

    /**
     * DocBlock
     * @param $password
     * @return string
     * @name("pass_hash")
     */
    public function pass_hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * DocBlock
     * @param $password
     * @param $hash
     * @return boolean
     * @name("pass_verify")
     */
    public function pass_verify($password, $hash)
    {
        if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
    }

}