<?php

class PublicData
{
    public $filename;

    public function __construct()
    {
        $this->filename = "nyilvanosadatok.txt";
    }

    /**
     * DocBlock
     * @return boolean
     * @name("isFile")
     */
    public function isFile()
    {
        return file_exists($this->filename);
    }

    /**
     * DocBlock
     * @param $email
     * @return boolean
     * @name("isEmailUsers")
     */
    public function isEmailData($email)
    {
        $res = "";
        $felhasznalok = $this->readAllDatas();
        if (count($felhasznalok) == 0) return false;
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
     * @return mixed
     * @name("getOneUserPublicList")
     */
    public function getOneUserPublicList($email)
    {
        $userDataLists = $this->readAllDatas();
        foreach ($userDataLists as $actualUserData) {
            if (array_key_exists("email", $actualUserData)) {
                $key = array_search($email, $actualUserData);
                if ($key === "email") {
                    return $actualUserData;
                }
            }
        }
        return null;
    }

    /**
     * DocBlock
     * @param $input
     * @return boolean
     * @name("addUserData")
     */
    public function addUserData($input)
    {
        $usersDataArray = $this->readAllDatas();
        $usersDataArrayBeforeCount = count($usersDataArray);
        $usersDataArrayAfterCount = array_push($usersDataArray, $input);
        $this->writingAllDatas($usersDataArray);
        if ($usersDataArrayAfterCount > $usersDataArrayBeforeCount) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * DocBlock
     * @param $email
     * @param $publicData
     * @return boolean
     * @name("modifyUserData")
     */
    public function modifyUserData($email, $publicData)
    {
        //bekérjük az összes adat listát
        $userDataLists = $this->readAllDatas();
        //megszámoljuk a tömb elemeit
        $userDataListsBeforeCount = count($userDataLists);
        if ($userDataListsBeforeCount == 0) return false;
        //bekérjük a módosítani kívánt felhasználó adatait
        $actualUserData = $this->getOneUserPublicList($email);
        if ($actualUserData == null) return false;
        //töröljük módosítani kívánt felhasználót a tömbből
        if (($key = array_search($actualUserData, $userDataLists)) !== false) {
            unset($userDataLists[$key]);
        }
        //hozzáadjuk a módosított felhasználót a tömbhöz
        $userDataListsAfterCount = array_push($userDataLists, $publicData);
        //ki írjuk fájlba a tömböt
        $this->writingAllDatas($userDataLists);
        //menézzük, hogy a tömb elemek azonosak-e és visszatérünk igaz vagy hamis értékkel
        if ($userDataListsAfterCount == $userDataListsBeforeCount) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * DocBlock
     * @return array
     * @name("readAllDatas")
     */
    public function readAllDatas()
    {
        $file = fopen($this->filename, "r");
        $userDataList = [];
        while (($line = fgets($file)) !== false) {
            $userDataList[] = unserialize($line);
        }
        fclose($file);
        return $userDataList;
    }

    /**
     * DocBlock
     * @param $userDataList
     * @return void
     * @name("writingAllDatas")
     */
    public function writingAllDatas($userDataList)
    {
        $file = fopen($this->filename, "w");
        foreach ($userDataList as $userData) {
            fwrite($file, serialize($userData) . "\n");
        }
        fclose($file);
    }

}
