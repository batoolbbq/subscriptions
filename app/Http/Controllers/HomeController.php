<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\City;
use App\Models\Municipal;
use App\Models\WorkCategory;
use App\Models\Institucion;
use App\Models\insuranceAgents;
use App\Models\beneficiariesCategories;

class HomeController extends Controller
{
    public function index()
    {

      $users=User::count();
      $Customers=Customer::count();
      $institucion=Institucion::count();
      $workcategory= WorkCategory::withcount('institucion')->get();
      $activeAgents = InsuranceAgents::where('status', 1)->count();
      $inactiveAgents = InsuranceAgents::where('status', '!=', 1)->count();
       $ben=beneficiariesCategories::withcount('customer')->get();
       $municipals = Municipal::withCount('customer')->get();
      $cities = City::withCount('customer')->get();

        return view('welcome')
         ->with('users',$users)
         ->with('Customer',$Customers)
         ->with('institucion',$institucion)
         ->with('workcategory',$workcategory)
         ->with('activeAgents',$activeAgents)
          ->with('inactiveAgents',$inactiveAgents)
         ->with('ben',$ben)
         ->with('municipals',$municipals)
         ->with('cities', $cities)
        ;
    }
}


