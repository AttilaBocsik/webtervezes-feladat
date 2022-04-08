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
     * @param $email
     * @return mixed || NULL
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
        //bekérjük a módosítani kívánt felhasználó adatait
        $actualUserData = $this->getOneUserPublicList($email);
        //töröljük módosítani kívánt felhasználót a tömbből
        if (($key = array_search($actualUserData, $userDataLists)) !== false) {
            unset($userDataLists[$key]);
        }
        //berakjuk az új adatokat a felhasználóba
        $aktualis_felhasznalo["public_list"] = $publicData;
        //hozzáadjuk a módosított felhasználót a tömbhöz
        $userDataListsAfterCount = array_push($userDataLists, $actualUserData);
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
