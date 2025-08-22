<?php

namespace App\Services;

use App\Enums\medicalprofilesassigns;
use \App\Log;
use App\Models\Customer;
use App\Models\Medicalprofile;
use App\Models\retired;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Null_;

class CustomerretiredService
{
    public static function save($regnumber, $fullnamea,$fullnamee
    ,$email,$phone,$gender,$yearbitrh,$registrationnumber,$nid,
    $passportnumber,$nationalities_id,$bloodtypes_id,$joptype,
    $municipals_id,$nearestpoint,$cities_id,$socialstatuses_id,$warrantynumber,
    $warrantyoffices_id,$healthfacilities_id,$guarantybranches_id,$diseasestate)
    {
        $customer = new Customer();
        $customer->requesttypes_id=1; 
        $customer->regnumber=$regnumber; 
        $customer->fullnamea=$fullnamea; 
        $customer->fullnamee=$fullnamee; 
        $customer->email=$email; 
        $customer->phone=$phone; 
        $customer->gender=$gender; 
        $customer->yearbitrh=$yearbitrh; 
        $customer->registrationnumber=$registrationnumber; 
        $customer->nid=$nid ;
        $customer->nationalID=decrypt($nid); 
        $customer->passportnumber=$passportnumber; 
        $customer->nationalities_id=$nationalities_id; 
        $customer->bloodtypes_id=$bloodtypes_id; 
        $customer->joptype=$joptype; 
        $customer->municipals_id=$municipals_id; 
        $customer->nearestpoint=$nearestpoint; 
        $customer->cities_id=$cities_id; 
        $customer->socialstatuses_id=$socialstatuses_id;
        $customer->diseasestate= $diseasestate;
        $customer->save();
        $retired = new retired();
        $retired->warrantynumber=$warrantynumber; 
        $retired->warrantyoffices_id=$warrantyoffices_id; 
        $retired->healthfacilities_id=$healthfacilities_id; 
        $retired->guarantybranches_id=$guarantybranches_id; 
        $retired->customers_id=$customer->id; 
        $retired->save();
      
        if($diseasestate==1){
            $Medicalprofile = new Medicalprofile();  
            $Medicalprofile->chronicdiseases_id=null; 
            $Medicalprofile->product=null; 
            $Medicalprofile->customers_id=$customer->id; 
            $Medicalprofile->retireds_id=$retired->id;
            $Medicalprofile->Diagnosis_date=null;
            $Medicalprofile->prescription=null;
            $Medicalprofile->medical_report=null;
            $Medicalprofile->follow_card=null;
            $Medicalprofile->assigns=medicalprofilesassigns::pending;
            $Medicalprofile->save();
        }
            return ['customer'=>$customer->id, 'retired'=>$retired->id];
    
    }
                
}
