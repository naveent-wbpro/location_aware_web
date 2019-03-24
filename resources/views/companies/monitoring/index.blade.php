@extends ('companies.company_layout')

@section ('sub-content')

       
            <div class="panel panel-primary company-calandar">

                  <div class="panel-heading">

                    <div class="col-md-7">Monitoring Calendar</div>
                    
                    <div class="col-md-3"> </div>

                    <div class="col-md-2"> </div>
                   
                    <dir class="clear-fix"></dir>

                  </div>
                  <div class="panel-body" >
                    
                        {!! $calendar->calendar() !!}
         
                        {!! $calendar->script() !!} 
                  </div>    
            </div>

<!-- Modal -->
<!--
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">


    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Request: William David</h4>
      </div>
      <div class="modal-body">
        <div class="track_assignment">
            <div class="panel panel-primary">
               <label>Employees Avalilable for monitoring</label>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="checkbox">
                               
                                <label for="checkbox">
                                   Smith Johnson
                                </label>
                            </div>
                          
                        </li>
                        <li class="list-group-item">
                            <div class="checkbox">
                          
                                <label for="checkbox2">
                                  Williams Brown
                                </label>
                            </div>
                           
                        </li>
                        <li class="list-group-item">
                            <div class="checkbox">
                               
                                <label for="checkbox3">
                                   Jones Miller
                                </label>
                            </div>
                            
                        </li>
                        <li class="list-group-item">
                            <div class="checkbox">
                           
                                <label for="checkbox4">
                                  Davis  Garcia
                                </label>
                            </div>
                           
                        </li>
                        <li class="list-group-item">
                            <div class="checkbox">
                              
                                <label for="checkbox5">
                                    Rodriguez Wilson
                                </label>
                            </div>
                          
                        </li>
                    </ul>
                </div>
               
            </div>
        </div>


      </div>
      <div class="modal-footer">
       
      </div>
    </div>

  </div>
</div> -->


<script>
    $(".employee-dropdown").change(function(){
 
       $( "#target" ).submit();     
    })
</script>

@endsection