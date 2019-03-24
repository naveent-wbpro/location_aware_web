@extends ('companies.company_layout')

@section ('sub-content')
    
     <h4>
          Assignments: {{ $user->name }} </h4> 
             <p class="lead">  Total {{ $assignments->count() }}  Assignments between 
        {{ $search['from_date'] }} and {{ $search['to_date'] }} </p>
  


    <table class="table">
    <tr  class="bg-info clearfix"> 
    <td align="left">  </td>
    <td align="right">
    
     {{ Form::open(array('method' => 'POST')) }} 

       <table>
       <tr>

       <td width="6%">Filter By</td>

        <td width="15%"><select class="form-control" id="filterdata" name="search_filter">
              <option value="">Select </option>
              <option value="last_week">Last Week</option>
              <option value="current_month">Current month</option>
              <option value="last_month">Last Month</option>
              <option value="year_to_date">YTD</option>
              <option value="last_quarter">Last Qtr</option>
              <option value="last_year">Last Year</option>

             
                        
                     </select> </td>
         <td width="8%"><div class='pull-right'>Date Range</div></td>            
         <td width="18%"> <div class='pull-right'>
                <div class="form-group  noMarginBottom">
                    <div class='input-group date' id='datetimepickerfrom'>
                        <input type='text' id="from_date" value="<?php  if(isset($_POST['from_date'])) echo $_POST['from_date'];?>" name="from_date" class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </td>
        <td width="1%"></td>
        <td width="18%"> <div class='pull-right'>
                 <div class="form-group  noMarginBottom marginNormalLeft">
                    <div class='input-group date' id='datetimepickerto'>
                        <input type='text' id="to_date" value="<?php  if(isset($_POST['to_date'])) echo $_POST['to_date'];?>"  name="to_date" class="form-control " />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </td>
        <td width="10%">  
            <div class='pull-right'><button type="submit"  class="btn btn-primary marginNormalLeft">Search</button></div>
        </td>
        <td width="1%"> </td>
        <td width="13%"> 
           
                <button type="submit" value="csv" name="csv" class="btn btn-primary marginNormalLeft">CSV</button>
                 <button type="submit" value="pdf" name="pdf" class="btn btn-primary marginNormalLeft">PDF</button>
    
        </td>
        </tr></table>
        </form>

                <script type="text/javascript">
                    $(function () {
                        $('#datetimepickerfrom').datetimepicker({ pickTime: false }); 
                        $('#datetimepickerto').datetimepicker({ pickTime: false }); 
                    });
                </script>
    </td>
    </tr>
    </table>
    <table class="tablereport table-striped">
        <thead>
 
            <tr>
                <th>
                    Start - Arrive - End <br>
                    Time
                    <small class="text-muted">
                        {{ $user->office->timezone or 'UTC' }} timezone
                    </small>
                </th>
                <th>Total Time in Hours </th>
                <th>Distance Travelled</th>
                <th>Job By/<br> Customer Name</th>
                <th>Insurance Carrier</th>
                <th>Job Type</th>
            </tr>
        </thead>
         <tbody>
            @foreach ($assignments as $assignment)
                <tr>
                    <td>

                             <?php if($assignment['contacted_on'] !== NULL) {  ?>
                    
                                   S: {{ $assignment->contacted_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia') }}<br>

                                    <?php } else ?> 

                            <?php if($assignment['arrived_on'] !== NULL) {  ?>
                                    A: {{ $assignment->arrived_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia') }}<br>
                                    <?php } else ?> 

                             <?php if($assignment['closed_on'] !== NULL) {  ?>
                                E  :     {{ $assignment->closed_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia') }}<br>
                                  
                                    <?php } ?> 
                       
                    </td>
                     <td>
                      {{ \Auth::user()->totalActiveGpsTimeRequest($assignment['contacted_on'], $assignment['closed_on'],$user->id) }}
                       
                    </td>
                    <td>
                            {{ number_format(\Auth::user()->totalDistanceTravelledByrequest($assignment['contacted_on'],$assignment['closed_on'],$user->id)) }} miles
                    </td>
                   
                    <td>
                            {{$assignment['customer_name']}}
 
                    </td>
                    <td>
                             N/A
                    </td>
                    <td>
                    <select data-request_id="{{$assignment['id']}}"  class="form-control selectboxid">
                          <option <?php if( $assignment['type'] == '0')  echo 'selected'; ?> value="0">New Assigment</option>
                          <option <?php if( $assignment['type'] == '1')  echo 'selected'; ?> value="1">Existing</option>
                          <option <?php if( $assignment['type'] == '2')  echo 'selected'; ?> value="2">Monitor Only</option>
                     </select> 
                    </td>
                </tr>
            @endforeach
        </tbody> 

     
    </table>
    <script>
    $(document).ready(function() {
        $('.tablereport').dataTable( {
          "columns": [
            { "width": "22%" },
            { "width": "18%" },
            { "width": "10%" },
            { "width": "10%" },
            { "width": "10%" },
            { "width": "20%" }
          ]
    } );  
    })

    $(".selectboxid").change(function() {
       var job_type    = this.value;
       var request_id  = $(this).data('request_id');
       $.ajax({
            method: 'POST', 
            url: '/companies/{{ $user->company_id }}/ajax_request_update', 
            data: {'request_id' : request_id,'job_type':job_type}, 
            success: function(response){ 
                 $("#div1").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) { 
               console.log(JSON.stringify(jqXHR));
              
            }
        });
    });

    $("#filterdata").change(function() {
        var filter_type  = this.value;

        if(filter_type=='last_week'){
            var from_date    = '{{$validate['last_week_start']}}';
            var to_date_value= '{{$validate['last_week_end']}}';
     
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        }   
        else if(filter_type=='last_month'){
            var from_date    = '{{$validate['last_month_start']}}';
            var to_date_value= '{{$validate['last_month_end']}}';
     
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        }  
        else if(filter_type=='last_quarter'){
            var from_date    = '{{$validate['last_quarter_start']}}';
            var to_date_value= '{{$validate['last_quarter_end']}}';
     
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        } 
        else if(filter_type=='year_to_date'){
            var from_date    = '{{$validate['year_to_date']}}';
            var to_date_value= '{{$validate['to_date']}}';
   
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        } 
        else if(filter_type=='last_year'){
            var from_date    = '{{$validate['last_year_start']}}';
            var to_date_value= '{{$validate['last_year_end']}}';
     
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        }else if(filter_type=='current_month'){
            var from_date    = '{{$validate['current_month_start']}}';
            var to_date_value= '{{$validate['to_date']}}';
     
            $('#from_date').val(from_date);
            $('#to_date').val(to_date_value);
        } 
        
       
    });

</script>
@endsection