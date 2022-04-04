<?php

class User
{
    public $filename;

    public function __construct()
    {
        $this->filename = "felhasznalok.txt";
    }

    /**
     * DocBlock
     * @param $input
     * @return boolean
     * @name("addUser")
     */
    public function addUser($input)
    {
        $felhasznalok = $this->readUsers();
        $felhasznalokBeforeCount = count($felhasznalok);
        array_push($felhasznalok, $input);
        $felhasznalokAfterCount = count($felhasznalok);
        $this->writingUsers($felhasznalok);
        return ($felhasznalokAfterCount > $felhasznalokBeforeCount) ? true : false;
    }

    /**
     * DocBlock
     * @param $email
     * @return boolean
     * @name("removeUser")
     */
    public function removeUser($email)
    {
        $felhasznalok = $this->readUsers();
        $felhasznalokBeforeCount = count($felhasznalok);
        $aktualis_felhasznalo = $this->getOneUsers($email);
        if (($key = array_search($aktualis_felhasznalo, $felhasznalok)) !== false) {
            unset($felhasznalok[$key]);
        }
        $felhasznalokAfterCount = count($felhasznalok);
        $this->writingUsers($felhasznalok);
        return ($felhasznalokAfterCount < $felhasznalokBeforeCount) ? true : false;
    }

    /**
     * DocBlock
     * @param $email
     * @return boolean
     * @name("isEmailUsers")
     */
    public function isEmailUsers($email)
    {
        $res = "";
        $felhasznalok = $this->readUsers();
        foreach ($felhasznalok as $felhasznalo) {
            if (array_key_exists("email", $felhasznalo)) {
                $res = array_search($email, $felhasznalo);
            }
        }
        if ($res != null && $res === "email") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * DocBlock
     * @param $email
     * @return string
     * @name("getPasswordUsers")
     */
    public function getPasswordUsers($email)
    {
        $felhasznalok = $this->readUsers();
        foreach ($felhasznalok as $felhasznalo) {
            if (array_key_exists("email", $felhasznalo)) {
                $key = array_search($email, $felhasznalo);
                if ($key === "email") {
                    return $felhasznalo["password"];
                }
            }
        }
        return "";
    }

    /**
     * DocBlock
     * @param $email
     * @return User || NULL
     * @name("getOneUsers")
     */
    public function getOneUsers($email)
    {
        $felhasznalok = $this->readUsers();
        foreach ($felhasznalok as $felhasznalo) {
            if (array_key_exists("email", $felhasznalo)) {
                $key = array_search($email, $felhasznalo);
                if ($key === "email") {
                    return $felhasznalo;
                }
            }
        }
    }

    /**
     * DocBlock
     * @return array
     * @name("readUsers")
     */
    public function readUsers()
    {
        $file = fopen($this->filename, "r");
        $felhasznalok = [];
        while (($line = fgets($file)) !== false) {
            $felhasznalok[] = unserialize($line);
        }
        fclose($file);
        return $felhasznalok;
    }

    /**
     * DocBlock
     * @return void
     * @name("writingUsers")
     */
    public function writingUsers($felhasznalok)
    {
        // Felhasználók kiírása fájlba
        $file = fopen($this->filename, "w");
        foreach ($felhasznalok as $felhasznalo) {
            fwrite($file, serialize($felhasznalo) . "\n");
        }
        fclose($file);
    }
}