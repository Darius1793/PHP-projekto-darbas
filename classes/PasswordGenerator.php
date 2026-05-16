<?php

class PasswordGenerator {
    private $lowercase = "abcdefghijklmnopqrstuvwxyz";
    private $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private $numbers = "0123456789";
    private $specials = "!@#$%^&*()_+-=.";

    public function generate($lowerCount, $upperCount, $numberCount, $specialCount) {
        $password = "";

        $password .= $this->getRandomChars($this->lowercase, $lowerCount);
        $password .= $this->getRandomChars($this->uppercase, $upperCount);
        $password .= $this->getRandomChars($this->numbers, $numberCount);
        $password .= $this->getRandomChars($this->specials, $specialCount);

        return str_shuffle($password);
    }

    private function getRandomChars($chars, $count) {
        $result = "";

        for ($i = 0; $i < $count; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $result;
    }
}