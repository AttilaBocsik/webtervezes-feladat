<?php

class Validation
{
    public function __construct()
    {

    }

    /**
     * DocBlock
     * @param $pwd
     * @param $pwd2
     * @return boolean
     * @name("password_validation")
     */
    public function password_validation($pwd, $pwd2)
    {
        if ($pwd != $pwd2) return false;
        $number = preg_match('@[0-9]@', $pwd);
        $uppercase = preg_match('@[A-Z]@', $pwd);
        $lowercase = preg_match('@[a-z]@', $pwd);
        $specialChars = preg_match('@[^\w]@', $pwd);
        $number2 = preg_match('@[0-9]@', $pwd2);
        $uppercase2 = preg_match('@[A-Z]@', $pwd2);
        $lowercase2 = preg_match('@[a-z]@', $pwd2);
        $specialChars2 = preg_match('@[^\w]@', $pwd2);
        if (strlen($pwd) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars || strlen($pwd2) < 8
            || !$number2 || !$uppercase2 || !$lowercase2 || !$specialChars2) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * DocBlock
     * @param $data
     * @return string
     * @name("test_input")
     */
    public function test_input($data)
    {
        $data = trim($data); //felesleges karakterek eltávolítása
        $data = stripslashes($data); //fordított perjeleket (\) eltávolítja a felhasználói bemeneti adatokból
        $data = htmlspecialchars($data);
        return $data;
    }

}