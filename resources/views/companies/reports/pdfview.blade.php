<style>
table tr td,table tr th {padding: 5px;background: #fff;}
table tr th {background:#D9EDF7;}
.line { position: fixed; border-bottom:1px solid gray; padding: 10px 5px;}
.line_top { position: fixed; border-top:1px solid gray; padding: 10px 5px;}
table tr.even td{background: #f3f3f3;}
</style>
<div class="container">
<div class="line"></div>

	<H1>	{{ $assignments['company']->name }} Assignments/Employee:  {{ $assignments['user']->name }}</H1>
	<H3>

	Total {{ $assignments['assignments']->count() }}  Assignments [Report period {{ $assignments['search']['from_date'] }} to {{ $assignments['search']['to_date'] }} ]</H3> 

    <table  width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="line" >Start - Arrive - End </th>
			<th class="line">Total Time in Hours</th>
			<th class="line">Distance Travelled</th>
			<th class="line">Job By/Customer Name</th>
			<th class="line">Insurance Carrier</th>
			<th class="line">Job Type</th>

		</tr>
		  @foreach ($assignments['assignments'] as $key => $assignment)
		<tr class="even">
			<td>			<?php if($assignment['contacted_on'] !== NULL) {  ?>
                    
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
                      {{ \Auth::user()->totalActiveGpsTimeRequest($assignment['contacted_on'], $assignment['closed_on'],$assignments['user']->id) }}
                       
                    </td>
			<td>
                            {{ number_format(\Auth::user()->totalDistanceTravelledByrequest($assignment['contacted_on'],$assignment['closed_on'],$assignments['user']->id)) }} miles
                    </td>
			<td>{{ $assignment->customer_name }}</td>
			<td>N/A</td>

		    <td>
               <?php if( $assignment['type'] == '2') 
               			 echo 'Monitor Only';
               	     elseif( $assignment['type'] == '1') 
               	     	 echo 'Existing';	
               	     else	 	 
               	     	 echo 'New Assigment';
               ?>
            </td>   			
		</tr>
		@endforeach
		<tr><td class="line_top" colspan="6"></td></tr>
        </tbody> 
	
	</table>
</div>