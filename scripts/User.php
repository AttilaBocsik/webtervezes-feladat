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
        $felhasznalokAfterCount = array_push($felhasznalok, $input);
        $this->writingUsers($felhasznalok);
        if ($felhasznalokAfterCount > $felhasznalokBeforeCount) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * DocBlock
     * @param $email
     * @param $firstname
     * @param $lastname
     * @param $hash
     * @return boolean
     * @name("modifyUser")
     */
    public function modifyUser($email, $firstname, $lastname, $hash)
    {
        //bekérjük az összes felhasználót és berakjuk egy tömbbe
        $felhasznalok = $this->readUsers();
        //megszámoljuk a tömb elemeit
        $felhasznalokBeforeCount = count($felhasznalok);
        //bekérjük a módosítani kívánt felhasználót
        $aktualis_felhasznalo = $this->getOneUser($email);
        //töröljük módosítani kívánt felhasználót a tömbből
        if (($key = array_search($aktualis_felhasznalo, $felhasznalok)) !== false) {
            unset($felhasznalok[$key]);
        }
        //berakjuk az új adatokat a felhasználóba
        $aktualis_felhasznalo["firstname"] = $firstname;
        $aktualis_felhasznalo["lastname"] = $lastname;
        $aktualis_felhasznalo["password"] = $hash;
        //hozzáadjuk a módosított felhasználót a tömbhöz
        $felhasznalokAfterCount = array_push($felhasznalok, $aktualis_felhasznalo);
        //ki írjuk fájlba a tömböt
        $this->writingUsers($felhasznalok);
        //menézzük, hogy a tömb elemek azonosak-e és visszatérünk igaz vagy hamis értékkel
        if ($felhasznalokAfterCount == $felhasznalokBeforeCount) {
            return true;
        } else {
            return false;
        }
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
        $aktualis_felhasznalo = $this->getOneUser($email);
        if (($key = array_search($aktualis_felhasznalo, $felhasznalok)) !== false) {
            unset($felhasznalok[$key]);
        }
        $felhasznalokAfterCount = count($felhasznalok);
        $this->writingUsers($felhasznalok);
        if ($felhasznalokAfterCount < $felhasznalokBeforeCount) {
            return true;
        } else {
            return false;
        }
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
        if ($res != null && $res == "email") {
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
    public function getOneUser($email)
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

        return null;
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