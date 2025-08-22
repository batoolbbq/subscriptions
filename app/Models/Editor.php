<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    use HasFactory;
    // public $timestamps = false;

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function saveEditor($user_id,$oldphone,$newphone,$oldname,$newname,
    $oldnid,$newnid,$oldwarrantynumber,$newarrantynumber, $oldRegnumber, $newRegnumber){

        $ed=new Editor();
        $ed->user_id=$user_id;
        $ed->oldphone=$oldphone;
        $ed->newphone=$newphone;
        $ed->oldname=$oldname;
        $ed->newname=$newname;
        $ed->oldnid=$oldnid;
        $ed->newnid=$newnid;
        $ed->oldwarrantynumber=$oldwarrantynumber;
        $ed->newarrantynumber=$newarrantynumber;
        $ed->oldregnumber=$oldRegnumber;
        $ed->newregnumber=$newRegnumber;
       $ed->save();

    }
}
