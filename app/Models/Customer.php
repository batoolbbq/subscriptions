<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
       
    public function cities()
    {
        return $this->belongsTo(City::class);
    }
    public function municipals()
    {
        return $this->belongsTo(Municipal::class);
    }
    public function socialstatuses()
    {
        return $this->belongsTo(Socialstatus::class);
    }
    public function nationalities()
    {
        return $this->belongsTo(Nationality::class);
    }
    public function assigns()
    {
        return $this->belongsTo(Assign::class);
    }
    public function bloodtypes()
    {
        return $this->belongsTo(Bloodtype::class);
    }
    public function requesttypes()
    {
        return $this->belongsTo(requesttype::class);
    }

    public function retireds()
    {
        return $this->hasMany(retired::class, 'customers_id', 'id');
    }

    public function prescriptions()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function appointments()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function permissionDispenseMedications()
    {
        return $this->belongsTo(PermissionDispenseMedication::class);
    }
    public function confirmationofmedications()
    {
        return $this->belongsTo(Confirmationofmedication::class);
    }
    public function managementappointments()
    {
        return $this->belongsTo(Managementappointment::class);
    }
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function permissionDispenseClaims()
    {
        return $this->belongsTo(PermissionDispenseClaims::class);
    } 
    public function personalphotos()
    {
        return $this->belongsTo(Personalphotos::class);
    }


    public function beneficiaries_sup_categories_id()
    {
        return $this->belongsTo(beneficiariesSupCategories::class , 'beneficiaries_sup_categories_id' , 'id');
    }

    public function beneficiaries_categories_id()
    {
        return $this->belongsTo(beneficiariesCategories::class , 'beneficiaries_categories_id' , 'id');
    }


    public function beneficiariesCategoryRelation()
    {
    return $this->belongsTo(\App\Models\beneficiariesCategories::class, 'beneficiaries_categories_id', 'id')
                ->withDefault();
    }

    public function beneficiariesSupCategoryRelation()
    {
    return $this->belongsTo(\App\Models\beneficiariesSupCategories::class, 'beneficiaries_sup_categories_id', 'id')
                ->withDefault();
    }


    
    public function martyrs_wounded()
    {
        return $this->hasMany(Martyrs_wounded::class, 'customer_id', 'id');
    }

     public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }
   
    public function institucion()
    {
        return $this->belongsTo(\App\Models\Institucion::class, 'institucion_id');
    }
    
  public function subscription()
{
    return $this->belongsTo(\App\Models\Subscription::class, 'subscription_id');
}


public function lastPhoto()
{
    return $this->hasOne(\App\Models\Personalphotos::class, 'customers_id')->latestOfMany();
}


   public function mainCustomer()
    {
        return $this->belongsTo(Customer::class, 'main_customer_id');
    }

        public function dependents()
    {
        return $this->hasMany(Customer::class, 'main_customer_id');
    }



    
}