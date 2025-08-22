<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyStatus extends Model 
{
    public static $pending = 'pending';
     public static $accepted = 'accepted'; 
     public static $declined = 'declined';// رفض نهائي 
    public static $rejected='rejected'; //رفض 
    public static $editing='editing';


   public static function changetoArabic($text){
        if($text=='pending'){
            return 'انتظار';
        }else if($text=='accepted'){
            return 'تم قبولها';
        }else if($text=='declined'){
            return 'تم رفضها نهائيا';
        }else if($text=='rejected'){
            return 'تم رفضها';
        }
        else if($text=='editing'){
            return 'تم  تعديلها';
        }
        
    }
    public static function changeColor($text){
        if($text=='pending'){
            return 'label label-warning';
        }else if($text=='accepted'){
            return 'label label-success';
        }else if($text=='declined'){
            return 'label label-default';
        }else if($text=='rejected'){
            return 'label label-danger';
        }
        else if($text=='editing'){
            return 'label label-primary';
        }
        
    }
}