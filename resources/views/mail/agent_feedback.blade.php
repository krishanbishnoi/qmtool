<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <style>   
    table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    }
    .button {
      background-color: #4CAF50; /* Green */
      border: none;
      color: white;
      padding: 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 12px;
    }
  </style> 

</head>
<body>
<p>
  
<h3><center><u>External Quality – Audit Feedback</u></center></h3>
<br>
Dear <b><?php echo $response['audit_detail'][0]->agent_name ?></b>,<br><br>

Your call dated <b><?php echo $response['audit_detail'][0]->call_time ?></b> with Call Id <b><?php echo $response['audit_detail'][0]->call_id ?></b> for <?php echo "PORTER_OBCC and ".$response['audit_detail'][0]->refrence_number ?> has been audited. Below are your audit scores and related feedback. 
<br><br>

<br>
We request you to go through the remarks section to learn about the opportunities for your call quality improvement.
</p>
<br><br>
<u><b>Call Scorecard:</b></u>
<?php //print_r($response['overall_sheet_parameters']); die; ?>
<table style="width:100%" >
  <tr>

    <th >Behavior</th>
   
    <th >Observation</th>
    <th >Scorable</th>
    <th >Scored</th>
    <th >Score %</th>
  </tr>
  <?php $sum_scorable = 0; 
        $sum_scored = 0; 
        $is_critical = 0;
        $average_scored = 0;
  
  ?>
  <?php if(isset($response['overall_sheet_parameters']))
        {
    ?>
  @foreach($response['overall_sheet_parameters'] as $value)

    <?php 
    $sum_scorable += $value->weight;
    
    $sum_scored += $value->score;
   
    ?>
    
  <tr>
    <td  style="width:25%; text-align:center"><b>
       {{$value->parameter}}
  </b>
    </td>
    <td  style="text-align:center">
       @if($value->is_non_scoring == 0)
            @if($value->selected_option==1)
            <span style= "color:green">Pass</span>
            @elseif($value->selected_option==2)
            <span style= "color:red">Fail</span>
            @elseif($value->selected_option==3)
            <span style= "color:maroon">Critical</span>
            <?php $is_critical = 1; ?>
            @elseif($value->selected_option==4)
            N/A
            @elseif($value->selected_option==5)
            <span style= "color:red">PWD</span>
            @elseif($value->selected_option==6)
            NCF
            @endif
        @elseif($value->is_non_scoring == 1)
            @if($value->selected_option==1)
            Yes
            @elseif($value->selected_option==2)
            No
            @elseif($value->selected_option==3)
            Promoter
            @elseif($value->selected_option==4)
            Passive
            @elseif($value->selected_option==5)
            Detractor
            @elseif($value->selected_option==6)
            Excellent
    
            @elseif($value->selected_option==7)
            Good
            
            @elseif($value->selected_option==8)
            Average
            
            @elseif($value->selected_option==9)
            Poor
           
            @elseif($value->selected_option==10)
            Excellent
            
            @elseif($value->selected_option==11)
            Good
            @elseif($value->selected_option==12)
            Average
            @elseif($value->selected_option==13)
            Poor
            @elseif($value->selected_option==14)
            Un-exceptable
            @endif
         @endif
    </td>
   
  
    <td  style="text-align:center">
    {{$value->weight}}
    </td>
   <td  style="text-align:center">
   @if($value->score==0)
    <span >{{$value->score}}</span>
    @else
    <span >{{$value->score}}</span>
    @endif
   
   </td>
   <td  style="text-align:center">
   @if($value->with_fatal_score_per==0)
    {{$value->with_fatal_score_per}}%
    @else
    {{$value->with_fatal_score_per}}%
    @endif
    
   </td>
  
  </tr>
  @endforeach
  <?php } ?>

  
  <tr>

    <th  colspan="2">Overall</th>
   
    
    <th >
   <?php echo $sum_scorable
   ?>
    </th>
    <th >
    <?php 
    if($is_critical == 1)
     echo 0;
     else
     echo $sum_scored ;
   ?>
    </th>
    <th >{{$response['audit_detail'][0]->with_fatal_score_per}}% </th>
  </tr>
</table>


<br><br>

  <u><b>Parameter Wise Observations:</b></u>

  <table style="width:100%"  >
  <tr>
  
    <th style="width:15%" >Behavior</th>
    <th style= "width:25%">Parameter</th>
    <th style= "width:5%" >Scorable</th>
    <th style= "width:5%">Observation</th>
    <th style= "width:5%" >Failure Type </th>
    <th style= "width:20%">Failure Reason</th>
    <th style= "width:25%">Remarks</th>
  </tr>
  <?php if(isset($response['overall_sheet_parameters']))
        {
    ?>
  @foreach($response['overall_sheet_parameters'] as $value)
  <tr>
    <td  style="text-align:center"><b>
       {{$value->parameter}}
  </b>  
    </td>
    <td  style="text-align:center">
    {{$value->sub_parameter}}

    </td>
    <td  style="text-align:center">
    @if($value->weight==0)
    {{$value->weight}}
    @else
  {{$value->weight}}
    @endif
  
    </td>
    <td  style="text-align:center">
       @if($value->is_non_scoring == 0)
            @if($value->selected_option==1)
            <span style= "color:green">Pass</span>
            @elseif($value->selected_option==2)
            <span style= "color:red">Fail</span>
            @elseif($value->selected_option==3)
            <span style="color:maroon">Critical</span>
            @elseif($value->selected_option==4)
            N/A
            @elseif($value->selected_option==5)
            <span style= "color:red">PWD</span>
            @elseif($value->selected_option==6)
            NCF
            @endif
        @elseif($value->is_non_scoring == 1)
            @if($value->selected_option==1)
            Yes
            @elseif($value->selected_option==2)
            No
            @elseif($value->selected_option==3)
            Promoter
            @elseif($value->selected_option==4)
            Passive
            @elseif($value->selected_option==5)
            Detractor
            @elseif($value->selected_option==6)
            Excellent
    
            @elseif($value->selected_option==7)
            Good
            
            @elseif($value->selected_option==8)
            Average
            
            @elseif($value->selected_option==9)
            Poor
           
            @elseif($value->selected_option==10)
            Excellent
            
            @elseif($value->selected_option==11)
            Good
            @elseif($value->selected_option==12)
            Average
            @elseif($value->selected_option==13)
            Poor
            @elseif($value->selected_option==14)
            Un-exceptable
            @else
            -
            @endif
         @endif
    </td>
   <td  style=" text-align:center">
   {{$value->reason_type_name}}
   </td>
   <td  style="text-align:center">
   {{$value->reason_name}}
   </td>
   <td  style="text-align:center">
   {{$value->remarks}} 
   </td>
  </tr>
  @endforeach
    <?php } ?>
</table>
<br>


<table  style="width:100%" >
  <th  style= "width:25%; text-align:center ">
   Overall Summary
  </th>
  <td style = "padding-right: 10px; padding-left: 10px">
  {{$response['audit_detail'][0]->overall_summary}}
  </td>
</table>

<br>
<table  style="width:100%" >
  <th  style= "width:25%; text-align:center ">
   Feedback to agent
  </th>
  <td style = "padding-right: 10px; padding-left: 10px">
  {{$response['audit_detail'][0]->feedback}}
  </td>
</table>
<br><br>
<p><b>
Please click on the play icon below if you’d like to listen to the call recording for the above audit feedback:
<br><br>
<a href = "<?php echo $response['file_url'] ?>" ><img src="https://img.icons8.com/glyph-neue/50/000000/circled-play.png"/></a>

<p>
Disclamier - This file will be available only for 6 days from this mail arrive.
</p>
<br><br>



Thanks & Regards,
<br>

Porter External Quality.
</p></b>

</body>
</html>