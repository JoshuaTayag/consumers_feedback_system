<?php
namespace App\Helpers;

class Helper
{

    public static function IDGenerator($model, $trow, $length = 4, $prefix){
        $data = $model::whereYear('created_at', $prefix)->orderBy('id','desc')->first();
        if(!$data){
            $og_length = $length;
            $last_number = 1;
        }else{
            $code = (int)(substr($data->$trow, strlen($prefix)+1));
            $actual_last_number = ($code/1)*1;
            $increment_last_number = ((int)$actual_last_number)+1;
            $last_number_length = strlen($increment_last_number);
            $og_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for($i=0;$i<$og_length;$i++){
            $zeros.="0";
        }
        return $prefix.'-'.$zeros.$last_number;
    }

    public static function IDGeneratorQueryBuilder($model, $trow, $length = 4, $prefix){
        $data = $model->whereYear('created_at', $prefix)->orderBy('id','desc')->first();
        if(!$data){
            $og_length = $length;
            $last_number = 1;
        }else{
            $code = (int)(substr($data->$trow, strlen($prefix)+1));
            $actual_last_number = ($code/1)*1;
            $increment_last_number = ((int)$actual_last_number)+1;
            $last_number_length = strlen($increment_last_number);
            $og_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for($i=0;$i<$og_length;$i++){
            $zeros.="0";
        }
        return $prefix.'-'.$zeros.$last_number;
    }
  
}
?>