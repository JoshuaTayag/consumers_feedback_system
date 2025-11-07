<?php
namespace App\Helpers;

class Helper
{

    public static function IDGenerator($model, $trow, $length, $prefix){
        $data = $model::whereYear('created_at', $prefix)->orderBy('id','desc')->first();
        if(!$data){
            $last_number = 1;
        }else{
            $code = (int)(substr($data->$trow, strlen($prefix)+1));
            $last_number = $code + 1;
        }
        
        // Calculate zeros needed to pad to the desired length
        $zeros = str_pad($last_number, $length, '0', STR_PAD_LEFT);
        
        return $prefix.'-'.$zeros;
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

    public static function IDGeneratorChangeMeter($model, $trow, $length = 5, $year, $prefix)
    {
        // Find the latest record for the given year and prefix
        $data = $model::where($trow, 'LIKE', $prefix . '-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$data) {
            $last_number = 1;
        } else {
            // Extract the numeric part after the last dash
            $parts = explode('-', $data->$trow);
            $last_number = isset($parts[2]) ? intval($parts[2]) + 1 : 1;
        }

        // Pad the number with leading zeros
        $number = str_pad($last_number, $length, '0', STR_PAD_LEFT);
        // dd($prefix . '-' . $year . '-' . $number);
        return $prefix . '-' . $year . '-' . $number;
    }
}
?>