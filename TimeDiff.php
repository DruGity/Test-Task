<?php
//Created By Aleksey Chudak For Provectus 
//I worked within only one class so in my opinion there is no place for polymorphism and inheritance
class TimeDiff
{
    private $date1;
    private $date2;

    public function __construct($date1, $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    public function getDaysMonthsYears()
    {
        $date1 = $this->date1;
        $date2 = $this->date2;

        $temp1 = explode('-',$date1);
        $temp2 = explode('-',$date2);

        $date = [
            'year1' => $temp1[0],
            'month1' => $temp1[1],
            'day1' => $temp1[2],
            'year2' => $temp2[0],
            'month2' => $temp2[1],
            'day2' => $temp2[2]
        ];

        return $date;

    }
    public function validate()
    {

        $d1 = preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/", $this->date1 );
        $d2 = preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/", $this->date2);

        if ($d1 != true || $d2 != true)
        {
            echo "Date1: ".$date1=$this->date1;
            echo "<br>";
            echo "Date2: ".$date2=$this->date2;
            echo "<br>";
            echo "Вы ввели неверный формат даты(YYYY-MM-DD) или такой даты не существует!)";
            die();
        }
        else
        {
            return true;
        }
    }

    public function getDiffInYears()
    {
        $date = $this->getDaysMonthsYears();
        $year1 = $date['year1'];
        $year2 = $date['year2'];

        $years = $year1-$year2;
        $years = abs($years);

        return $years;
    }

    public function getDiffInMonths()
    {
        $date = $this->getDaysMonthsYears();
        $month1 = $date['month1'];
        $month2 = $date['month2'];

        $months = $month1-$month2;
        $months = abs($months);

        return $months;
    }

    public function getDiffInDays()
    {
        $date = $this->getDaysMonthsYears();
        $day1 = $date['day1'];
        $day2 = $date['day2'];

        $days = $day1-$day2;
        $days = abs($days);

        return $days;
    }

    public function numberNormalisator($x)
    {
        if (substr($x,0,1) == "0" )
        {
            $x = substr($x,1,1);
        }
        return $x;
    }

    public function getDaysInMonth($x)
    {
        $x = $this->numberNormalisator($x);
        $test = 28+floor($x+($x/8))%2+2%$x+2*floor(1/$x);

        return $test;
    }
    public function getLastIndex($arr)
    {
        end($arr);
        $last_index = key($arr);
        return $last_index;
    }

    public function getTotalDaysInMonth()
    {
        $date = $this->getDaysMonthsYears();
        $month1 = $date['month1'];
        $month2 = $date['month2'];

        $day1 = $date['day1'];
        $day2 = $date['day2'];

        if ($month1<$month2)
        {
            $arr = array_fill($this->numberNormalisator($month1), 1, $this->getDaysInMonth($month1));

            for($i=$this->numberNormalisator($month1); $i<=$this->numberNormalisator($month2); $i++)
            {
                array_push($arr, $this->getDaysInMonth($i));
            }

            array_splice($arr,0,1);

            $qq = $arr[0]-$day1;
            $arr[0] = $qq;

            $arr[$this->getLastIndex($arr)] = $this->numberNormalisator($day2);
            $days_in_month = array_sum($arr);
        }
        else
        {
            $arr = array_fill($this->numberNormalisator($month2), 1, $this->getDaysInMonth($month1));

            for($i=$this->numberNormalisator($month2); $i<=$this->numberNormalisator($month1); $i++)
            {
                array_push($arr, $this->getDaysInMonth($i));
            }

            array_splice($arr,0,1);

            $qq = $arr[0]-$day2;
            $arr[0] = $qq;

            $arr[$this->getLastIndex($arr)] = $this->numberNormalisator($day1);
            $days_in_month = array_sum($arr);
        }

        return $days_in_month;
    }

    public function getLeapYear()
    {
        $date = $this->getDaysMonthsYears();
        $year1 = $date['year1'];
        $year2 = $date['year2'];

        $j = 0;
        if ($year1>$year2)
        {
            for ($i = $year2; $i <=$year1; $i++)
            {
                if ($i % 4 == 0 && $i % 4 !== 100 || $i % 4 == 400)
                {
                    $j = $j+1;
                }
            }
        }
        else
        {
            for ($i = $year1; $i <=$year2; $i++)
            {
                if ($i % 4 == 0 && $i % 4 !== 100 || $i % 4 == 400)
                {
                    $j = $j+1;
                }
            }
        }
        return $j;
    }

    public  function getTotalDays()
    {
        $date = $this->getDaysMonthsYears();

        $month1 = $date['month1'];
        $month2 = $date['month2'];

        $days_in_month = $this->getTotalDaysInMonth();

        $years = $this->getDiffInYears();
        $days = $this->getDiffInDays();

        $totalYears = $years*365+$this->getLeapYear();
        $totalMonth = $days_in_month;
        if ($month1!=$month2)
        {
            $total_days = $totalYears+$totalMonth;
        }
        else
        {
            $total_days = $totalYears+$days;
        }

        return $total_days;
    }

    public function getInvert()
    {
        $date = $this->getDaysMonthsYears();

        $day1 = $date['day1'];
        $day2 = $date['day2'];

        $month1 = $date['month1'];
        $month2 = $date['month2'];

        $year1 = $date['year1'];
        $year2 = $date['year2'];

        $totalDaysInDate1 = $year1*365+($this->numberNormalisator($month1)*$this->getDaysInMonth($month1))+$day1;
        $totalDaysInDate2 = $year2*365+($this->numberNormalisator($month2)*$this->getDaysInMonth($month1))+$day2;
        if ($totalDaysInDate1>$totalDaysInDate2)
        {
            $invert = true;
        }
        else
        {
            $invert = false;
        }

        return $invert;

    }

    public function showResult()
    {
        $this->validate();

        $diffYears = $this->getDiffInYears();
        $diffMonths = $this->getDiffInMonths();
        $diffDays = $this->getDiffInDays();
        $total_days = $this->getTotalDays();

        echo "Date1: ".$date1=$this->date1;
        echo "<br>";
        echo "Date2: ".$date2=$this->date2;
        echo "<br>";
        echo "<br>";
        echo "Difference in Years: ".$diffYears." "."year(s)";
        echo "<br>";
        echo "Difference in Months: ".$diffMonths." "."month(s)";
        echo "<br>";
        echo "Difference in Days: ".$diffDays." "."day(s)";echo "<br />";
        echo "Difference in total days:"." ".$total_days." "."total day(s)";
        echo "<br />";

        if ($this->getInvert() === true)
        {
            echo "invert = true";
            echo "<br />";
        }
        else
        {
            echo  "invert = false";
            echo "<br />";
        }

    }
}

$firstDate = $_GET['date1'];
$secondDate = $_GET['date2'];

$a = new TimeDiff($firstDate,$secondDate);
$a->showResult();




