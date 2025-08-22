@extends('layouts.app')
@section('title',"البلديات")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
             @if ((Auth::user()->user_type_id == 1) || (Auth::user()->user_type_id == 2)) 
                
        <a type="button" href="{{ route('municipal/create') }}" class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">اضافة بلدية</a>
    @endif    
    </div>
    </div>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title">عرض البلديات </h4>
                <div class="table-responsive" data-pattern="priority-columns">
                <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom " style="cursor: pointer;">
                    <thead>
                        <tr>
                            <th>المنطقة الصحية</th>
                            <th>البلدية</th>

                            <th>تاريخ الإضافة</th>

<?php         if ((Auth::user()->user_type_id == 1) || (Auth::user()->user_type_id == 2)) {
    ?>
 <th></th>
  <?php }?>          
  <?php         if ((Auth::user()->user_type_id == 1) || (Auth::user()->user_type_id == 2)) {
    ?>
 <th></th>
  <?php }?>              
                             

                        </tr>
                    </thead>
                    <tbody>
                        <script>
          
                          $(document).ready( function () {
                         
                             $('#datatable1').dataTable({
                               "language": {
                                "url": "../Arabic.json" //arbaic lang
                         
                                   },
                                   "lengthMenu":[5,10],
                                   "bLengthChange" : true, //thought this line could hide the LengthMenu
                           serverSide: false,
                           paging: true,
                             searching: true,
                             ordering: true,
                             contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                             ajax: '{!! route('municipal/municipal')!!}',
                          
                          columns: [
                                   { data: 'cities.name'},
                                   { data: 'name'},

                                   {data: 'created_at'},
                                   <?php         if ((Auth::user()->user_type_id == 1) || (Auth::user()->user_type_id == 2)) {
?> 
                                   {data: 'edit'},
 <?php }?>
 <?php         if ((Auth::user()->user_type_id == 1) || (Auth::user()->user_type_id == 2)) {
?> 
                                   {data: 'delete'}
 <?php }?>
                                ],

                                 dom: 'Blfrtip',

buttons: [
{
extend: 'copyHtml5',
exportOptions: {
columns: [ ':visible' ]
},
text:'نسخ'
},
{
extend: 'excelHtml5',
exportOptions: {
columns: ':visible'
},
text:'excel تصدير كـ '

},
{
extend:  'colvis',
text:'الأعمدة'

},
],
                         
                             });
                         
                           });
                         </script>
                      </tbody>
                   
                </table>
                </div>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
      
    </div>
</div>

@endsection