<?php
    $example_persons_array = [
        [
            'fullname' => 'Иванов Иван Иванович',
            'job' => 'tester',
        ],
        [
            'fullname' => 'Степанова Наталья Степановна',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Пащенко Владимир Александрович',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Громов Александр Иванович',
            'job' => 'fullstack-developer',
        ],
        [
            'fullname' => 'Славин Семён Сергеевич',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Цой Владимир Антонович',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Быстрая Юлия Сергеевна',
            'job' => 'PR-manager',
        ],
        [
            'fullname' => 'Шматко Антонина Сергеевна',
            'job' => 'HR-manager',
        ],
        [
            'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Бардо Жаклин Фёдоровна',
            'job' => 'android-developer',
        ],
        [
            'fullname' => 'Шварцнегер Арнольд Густавович',
            'job' => 'babysitter',
        ],
    ];

    function getFullnameFromParts($surname, $name, $patronomyc) {
        $arr = [$surname, $name, $patronomyc];
        $string = implode(' ', $arr);
        return $string;
    };

    function  getPartsFromFullname($fullname) {
        $arr = explode(' ', $fullname);
        return $arr;
    };

    function  getShortName($fullname) {
        $arr = getPartsFromFullname($fullname);
        $shortName = $arr[1].' '.mb_substr($arr[0], 0, 1).'.';
        return $shortName;
    };

    function getGenderFromName($fullname) {
        $arr = getPartsFromFullname($fullname);
        $genderScores = 0;
        $patronomyc = $arr[2];
        $name = $arr[1];
        $surname = $arr[0];

        if(mb_substr($patronomyc, -3) == 'вна') {
            $genderScores--;
        } elseif (mb_substr($patronomyc, -2) == 'ич') {
            $genderScores++;
        }

        if(mb_substr($name, -1) == 'a' || mb_substr($name, -1) == 'я') {
            $genderScores--;
        } elseif (mb_substr($name, -2) == 'й' || mb_substr($name, -2) == 'н') {
            $genderScores++;
        }

        if(mb_substr($surname, -2) == 'ва') {
            $genderScores--;
        } elseif (mb_substr($surname, -1) == 'в') {
            $genderScores++;
        }

        if($genderScores > 0):
            return 1;
        elseif($genderScores === 0):
            return 0;
        elseif($genderScores < 0):
            return -1;
        endif;
    };

    function getGenderDescription($array) {
        $men = 0;
        $women = 0;
        $undefined = 0;
        $count = count($array);
        foreach($array as $value) {
            $gender = getGenderFromName($value['fullname']);
            switch($gender) {
                case 1:
                    $men++;
                    break;
                case 0:
                    $undefined++;
                    break;
                case -1:
                    $women++;
                    break;
            }
        }
        unset($value);
        $men = number_format($men / $count * 100, 2, '.', '');
        $women = number_format($women / $count * 100 , 2, '.', '');
        $undefined = number_format($undefined / $count * 100 , 2, '.', '');

        $output = <<<EOD
        Гендерный состав аудитории:
        ---------------------------
        Мужчины - $men%
        Женщины - $women%
        Не удалось определить - $undefined%
        EOD;
        return $output;
    };

    function getPerfectPartner($surname, $name, $patronomyc, $array) {
        $surname = mb_convert_case($surname, MB_CASE_TITLE);
        $name = mb_convert_case($name, MB_CASE_TITLE);
        $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE);
        $fullname = getFullnameFromParts($surname, $name, $patronomyc);
        $gender1 = getGenderFromName($fullname);

        $rand = rand(0, count($array) - 1);
        $coupleName = $array[$rand]['fullname'];
        $gender2 = getGenderFromName($coupleName);
        while($gender1 === $gender2 || $gender2 === 0) {
            $rand = rand(0, count($array) - 1);
            $coupleName = $array[$rand]['fullname'];
            $gender2 = getGenderFromName($coupleName);
        }
        $shortName1 = getShortName($fullname);
        $shortName2 = getShortName($coupleName);
        $matching = number_format(rand(5000, 10000) / 100, 2, '.');
        if($gender1 === 0 || $gender2 === 0) {
            return 'Не удалось определить пол партнера';
        }
        $output =<<<END
        $shortName1 + $shortName2 = 
        ♡ Идеально на $matching% ♡
        END;
        return $output;
    };
?>