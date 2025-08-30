<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NidController extends Controller
{

    private $token;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    public function getToken()
    {



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://sso.ndb.gov.ly/connect/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=nid&client_id=LHI&client_secret=Nhq*7f0#IS6o0wf@RT1wCN#w@56unRx1Qhq3cd7");
        
        $headers = array();
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $token = json_decode($result);
        return $token->access_token;
    }


    public function getNidData($nid)
    {


        $nid = ['nid' => $nid];
        $nid = json_encode($nid);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://nid.ndb.gov.ly/search/byNid');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nid);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->getToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $result = json_decode($result);
    }

    public function getNidEnData($nid)
    {


        $nid = ['nid' => $nid];
        $nid = json_encode($nid);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://nid.ndb.gov.ly/search/byNiden');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nid);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->getToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $result = json_decode($result);
    }
}
