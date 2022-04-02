<?php

class User
{
    public $filename;

    public function __construct()
    {
        $this->filename = "felhasznalok.txt";
    }

    function isOnyUser($input)
    {
        $file = fopen($this->filename, "r");
        $felhasznalok = [];
        while (($line = fgets($file)) !== false) {
            $felhasznalok[] = unserialize($line);
        }
        fclose($file);
        foreach ($felhasznalok as $felhasznalo) {
            if ($felhasznalo["email"] == $input) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * DocBlock
     * @return boolean
     * @name("addUser")
     */
    function addUser($input)
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
     * @return boolean
     * @name("isEmailUsers")
     */
    function isEmailUsers($email)
    {
        $felhasznalok = $this->readUsers();
        foreach ($felhasznalok as $felhasznalo) {
            $res = array_search($email, $felhasznalo);
            //return ()
        }

        return $res;
    }

    /**
     * DocBlock
     * @return array
     * @name("readUsers")
     */
    function readUsers()
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
    function writingUsers($felhasznalok)
    {
        // Felhasználók kiírása fájlba
        $file = fopen($this->filename, "w");
        foreach ($felhasznalok as $felhasznalo) {
            fwrite($file, serialize($felhasznalo) . "\n");
        }
        fclose($file);
    }
}