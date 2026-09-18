<?php session_start();
include("../config.php");
require_once('../'.CLS);
$iot = new Iot();
$set = $iot->getSetting();

function optionLoop($start, $end, $val){
	$data='';

	for ($i=$start; $i < $end; $i++) {
			$selected = ($val==$i)? 'selected':'';
		$data .= "<option ".$selected.">".$i."</option>";
	}	
	return $data;
}


$wet_data = $iot->getAllinfo('soil', 'wet');
$tem_data = $iot->getAllinfo('soil', 'tem');


?>
<style>
.soildata{
    position: absolute;
    left: 40%;
    top:-3px;
    z-index: 1;
    
}.soildata span{
    font-size: 35px;

}
</style>
<div class="container iot-pannel">
<form method="post">
<div class="row">
<div class="col-md-12">
	<div class="card my-1">
	<div class="card-header">🍄 HamroBari Agriculture Farm    <div class="text-right float-right text-decoration-none" id="logout"><a href="#">😎 Logout 🚫</a></div></div>
	<div class="card-body text-center"><b>Nepal Time:</b> <span id="datetime"></span></div>
	</div>
</div>


<div class="col-md-12">
	<div class="card my-4">
	  <div class="card-header">💧️ Soil Moisture Chart</div>
	  <div class="card-body">   
	  <div class="soildata">
		  <span class="emoji">🌡️</span><span id="stemp"></span><sup>°C </sup>     
		  <span class="emoji"> 🌱</span><span id="soil"></span><sup>% </sup>	  
	  </div>
		  <div class="form-row">
			<div class="form-group col-md-12 soil weather">
			
				<div id="container" style="height:	 100%; width: 100%"></div>
			</div>
		  </div>	
		  
	  </div>
	</div>
</div>

<script type="text/javascript">
var chart; // global
		
function liveData() {
	var series = this.series[0],
		series2 = this.series[1];
		setInterval(function () {
		$.getJSON({
			url: "get_soil_info.php",
			success: function (result, status, xhr) {														
			var x = (new Date(result.date)).getTime();
			var	y = parseFloat(result.wet);
			var	z = parseFloat(result.tem);
			series.addPoint([x, y], true, true);
			series2.addPoint([x, z], true, true);			
			}
							
		});	
				
	}, 1000);
}
	

$(document).ready(function() {
chart = new Highcharts.stockChart('container', {
		  chart: {
			events: {
			   load: liveData
			}
		  },
		  time: {
			useUTC: false
		  },
		  rangeSelector: {
			buttons: [{
			  count: 1,
			  type: 'minute',
			  text: '1M'
			}, {
			  count: 5,
			  type: 'minute',
			  text: '5M'
			}, {
			  type: 'all',
			  text: 'All'
			}],
			inputEnabled: true,
			selected: 0
		  },
		  title: {
          text: ' ',
			x: -20 //center
		  },
		  subtitle: {
			 text: '',
			 x: -20
		  },
		  yAxis: {
			 title: {
				text: 'Soil Temprature & Moisture data'
			 },
			 plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			 }]
		  },
		scrollbar: {
			  barBackgroundColor: 'gray',
			  barBorderRadius: 7,
			  barBorderWidth: 0,
			  buttonBackgroundColor: 'gray',
			  buttonBorderWidth: 0,
			  buttonBorderRadius: 7,
			  trackBackgroundColor: 'none',
			  trackBorderWidth: 1,
			  trackBorderRadius: 8,
			  trackBorderColor: '#CCC'
			},

		  exporting: {
			enabled: true
		  },

		  series: [{
			name: 'Soil Moisture',
			color: '#41aff4',
			id: 'swet',
			data: (<?=json_encode($wet_data);?>),
						
			 marker: {
				enabled: true,
				radius: 1
			  },
			  shadow: true,
			tooltip: {
				valueDecimals: 2,
				valueSuffix: '%'
			  },
		  },{
			name: 'Soil Temprature',
			color: '#ff0000',
			id: 'swet',
			data: (<?=json_encode($tem_data);?>),
						
			 marker: {
				enabled: true,
				radius: 1
			  },
			  shadow: true,
			tooltip: {
				valueDecimals: 2,
				valueSuffix: '°C'
			  },
		  }
		  ]
	});

	
});
		</script>





<div class="col-md-6">
	<div class="card my-4">
	  <div class="card-header">☀️ Temprature</div>
	  <div class="card-body">   
		  <div class="form-row">
			<div class="form-group col-md-12">
				<div class="row"> 
					<div class="form-group col-md-6 temp weather">
						 <span class="emoji">🌡️</span><span id="temp"></span><sup>°C </sup>
					</div>
					<div class="form-group col-md-6 temp weather">					
						<span class="emoji bulb" >💡</span>
					</div>
				</div>	
				<div class="row">
					<div class="form-group col-md-6">						
						<input class="slide_sw"  id="temp-sw" name="temp_status" <?=($set['57'][1]=='on')? 'checked':'';?> type="checkbox"  data-toggle="toggle" data-on='Automatic' data-off='Manual ' data-onstyle='success' data-offstyle='danger' >				
					</div>	
					<div class="form-group col-md-6" id="temp-mn">	
						<input type="checkbox" class="slide_sw" name="temp_sw" <?=($set['58'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='Switch ON' data-off='Switch OFF' data-onstyle='success' data-offstyle='danger' >				
					</div>						
					
				</div>					
								
				<div class="row" id="temp-auto">	
					<div class="form-group col-md-6">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Min </span>
							  </div>
								<input type="number" name="min_t" id="min_t" class="form-control" value="<?=$set['52'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">°C</span>
								</div>	
							</div>
						</div>
					</div>
					<div class="form-group col-md-6">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Max </span>
							  </div>
								<input type="number" name="max_t" id="max_t" class="form-control" value="<?=$set['54'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">°C</span>
								</div>	
							</div>
						</div>
					</div>	
					<div class="form-group col-md-12"><button type="button" class="btn btn-primary save wait"> Save <i class="fa fa-save"></i></button></div>	
				</div>
				
			</div>
		  </div>	
		  
	  </div>
	</div>
</div>

<div class="col-md-6">
	<div class="card my-4">
	  <div class="card-header">💦 Humidity</div>
	  <div class="card-body">   
		  <div class="form-row">
			<div class="form-group col-md-12">
				<div class="row">
					<div class="form-group col-md-6 hum weather">					
						<span class="emoji">💧️</span> <span id="hum"></span><sup>% </sup>
					</div>
					<div class="form-group col-md-6 hum weather">					
						<span class="emoji bulb off">💡</span>
					</div>					
				</div>	
				
				<div class="row">
					<div class="form-group col-md-6">						
						<input class="slide_sw" id="hum-sw" name="hum_status" <?=($set['59'][1]=='on')? 'checked':'';?> type="checkbox"  data-toggle="toggle" data-on='Automatic' data-off='Manual ' data-onstyle='success' data-offstyle='danger' >				
					</div>	
					<div class="form-group col-md-6" id="hum-mn">	
						<input type="checkbox" class="slide_sw" name="hum_sw" <?=($set['60'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='Switch ON' data-off='Switch OFF' data-onstyle='success' data-offstyle='danger' >				
					</div>						
					
				</div>					
				
				
				<div class="row" id="hum-auto">	
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Min </span>
							  </div>
								<input type="number" name="min_h" id="min_h" class="form-control" value="<?=$set['55'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>	
							</div>
						</div>
					</div>
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Max </span>
							  </div>
								<input type="number" name="max_h" id="max_h" class="form-control" value="<?=$set['56'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>	
							</div>
						</div>
					</div>				
					<div class="form-group col-md-12"><button type="button" class="btn btn-primary save wait"> Save <i class="fa fa-save"></i></button></div>	
				</div>	
			</div>	
		  </div>
		
	  </div>
	</div>
</div>

<div class="col-md-6">
	<div class="card my-4">
	  <div class="card-header">🌪 Air Quality</div>
	  <div class="card-body">   
		  <div class="form-row">
			<div class="form-group col-md-12">
				<div class="row">
					<div class="form-group col-md-6 ppm weather">					
						<span class="emoji">🌫️</span> <span id="ppm"></span><sup>ppm </sup>
					</div>
					<div class="form-group col-md-6 ppm weather">					
						<span class="emoji bulb off">💡</span>
					</div>					
				</div>	
				
				<div class="row">
					<div class="form-group col-md-6">						
						<input class="slide_sw" id="ppm-sw" name="ppm_status" <?=($set['63'][1]=='on')? 'checked':'';?> type="checkbox"  data-toggle="toggle" data-on='Automatic' data-off='Manual ' data-onstyle='success' data-offstyle='danger' >				
					</div>	
					<div class="form-group col-md-6" id="ppm-mn">	
						<input type="checkbox" class="slide_sw" name="ppm_sw" <?=($set['64'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='Switch ON' data-off='Switch OFF' data-onstyle='success' data-offstyle='danger' >				
					</div>						
					
				</div>	
				
				<div class="row" id="ppm-auto">	
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Min  </span>
							  </div>
								<input type="number" name="min_p" id="min_p" class="form-control" value="<?=$set['61'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>	
							</div>
						</div>
					</div>
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Max </span>
							  </div>
								<input type="number" name="max_p" id="max_p" class="form-control" value="<?=$set['62'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">ppm</span>
								</div>	
							</div>
						</div>
					</div>				
					<div class="form-group col-md-12"><button type="button" class="btn btn-primary save wait"> Save <i class="fa fa-save"></i></button></div>	
				</div>				
			</div>				
					
				</div>				
				<div class="row">
					<div class="form-group col-md-12 card-header">						
						<strong>📅 Scheduler:</strong> 24 hours format 
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-md-6 weather">						
						<span id="time">⏲️</span> 				
					</div>	
					<div class="form-group col-md-6 " >						
						<div class="input-group time  py-2">
							<div class="input-group-prepend">
								<span class="input-group-text">⏰</span>
							</div>
							<select name="ppm_t1" id="ppm_t1" class="form-control">
								<?=optionLoop(0, 25, $set['70'][1]);?>
							</select>	
							<div class="input-group-append">													
									<input type="checkbox" class="slide_sw" name="ppm_t1s"  <?=($set['72'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='on' data-off='off' data-onstyle='success' data-offstyle='danger'  >							
							</div>	
						</div>
						<div class="input-group time py-2">
							<div class="input-group-prepend">
								<span class="input-group-text">⏰</span>
							</div>
							<select name="ppm_t2" id="ppm_t2" class="form-control">
								<?=optionLoop(0, 25, $set['71'][1]);?>
							</select>	
							<div class="input-group-append">													
									<input type="checkbox" class="slide_sw" name="ppm_t2s"  <?=($set['73'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='on' data-off='off' data-onstyle='success' data-offstyle='danger'  >							
							</div>	
						</div>						
						
					</div>			
			
		  </div>
		
	  </div>
	</div>
</div>
<div class="col-md-6">
	<div class="card my-4">
	  <div class="card-header">🔌 Light</div>
	  <div class="card-body">   
		  <div class="form-row">
			<div class="form-group col-md-12">
				<div class="row">
					<div class="form-group col-md-6 ldr weather">					
						<span class="emoji">💡</span> <span id="ldr"></span><sup>LDR </sup>
					</div>
					<div class="form-group col-md-6 ldr weather">					
						<span class="emoji bulb off">💡</span>
					</div>					
				</div>
<div class="row">
					<div class="form-group col-md-6">						
						<input class="slide_sw" id="ldr-sw" name="sw_status" <?=($set['67'][1]=='on')? 'checked':'';?> type="checkbox"  data-toggle="toggle" data-on='Automatic' data-off='Manual ' data-onstyle='success' data-offstyle='danger' >				
					</div>	
					<div class="form-group col-md-6" id="ldr-mn">
						<input type="checkbox" class="slide_sw" name="sw_sw" <?=($set['68'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='Switch ON' data-off='Switch OFF' data-onstyle='success' data-offstyle='danger' >
					</div>						
					
				</div>					
				
				<div class="row" id="ldr-auto">	
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Min  </span>
							  </div>
								<input type="number" name="min_s" id="min_s" class="form-control" value="<?=$set['65'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>	
							</div>
						</div>
					</div>
					<div class="form-group col-md-5">
						<div class="inline text">				
							<div class="input-group">
							  <div class="input-group-prepend">
								<span class="input-group-text">Max </span>
							  </div>
								<input type="number" name="max_s" id="max_s" class="form-control" value="<?=$set['66'][1]?>">	
								<div class="input-group-append">
									<span class="input-group-text">ppm</span>
								</div>	
							</div>
						</div>
					</div>				
					<div class="form-group col-md-12"><button type="button" class="btn btn-primary save wait"> Save <i class="fa fa-save"></i></button></div>	
				</div>	
        </div></div>
				<div class="row">
					<div class="form-group col-md-12 card-header">						
						<strong>📅 Scheduler:</strong>  24 hours format 
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-md-6 weather">						
						<span id="time">⏲️</span> 				
					</div>	
					<div class="form-group col-md-6 " >						
						<div class="input-group time  py-2">
							<div class="input-group-prepend">
								<span class="input-group-text">⏰</span>
							</div>
							<select name="sw_t1" id="sw_t1" class="form-control">
								<?=optionLoop(0, 25, $set['74'][1]);?>
							</select>	
							<div class="input-group-append">													
									<input type="checkbox" class="slide_sw" name="sw_t1s"  <?=($set['75'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='on' data-off='off' data-onstyle='success' data-offstyle='danger'  >							
							</div>	
						</div>
						<div class="input-group time py-2">
							<div class="input-group-prepend">
								<span class="input-group-text">⏰</span>
							</div>
							<select name="sw_t2" id="sw_t2" class="form-control">
								<?=optionLoop(0, 25, $set['76'][1]);?>
							</select>	
							<div class="input-group-append">													
									<input type="checkbox" class="slide_sw" name="sw_t2s"  <?=($set['77'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='on' data-off='off' data-onstyle='success' data-offstyle='danger'  >							
							</div>	
						</div>						
						
					</div>						
					
				</div>					
							
			
			
		  
	  </div>
	</div>
</div>


<div class="col-md-6">
	<div class="card my-4">
	  <div class="card-header">🔄 Reset Device</div>
	  <div class="card-body">   
		  <div class="form-row">
			<div class="form-group col-md-12">	
			<input type="checkbox" class="slide_sw" name="reset" <?=($set['10'][1]=='on')? 'checked':'';?>  data-toggle="toggle" data-on='Switch ON' data-off='Switch OFF' data-onstyle='success' data-offstyle='danger' >				
			</div>	
		  </div>
	  </div>
	</div>
</div>

</div>
</form>
</div>


<script type="text/javascript">

$(document).ready(function(){
	$(".slide_sw").bootstrapToggle();
	setInterval(live,5000);	
		setInterval(function() {
			getURL("include/time.php","#datetime");			
			
		}, 1000);
	
	live();	
		smart_sw("temp");
		smart_sw("hum");		
		smart_sw("ppm");
		smart_sw("ldr");

	$(".slide_sw").change(function(e) {
	  e.preventDefault();
	  Save();
	});	
	$("button").click(function(e) {
	  e.preventDefault();
			Save();
	});	
	

	$("#logout").click(function(e) {
	  e.preventDefault();
		localStorage.setItem("login", "false");
		localStorage.removeItem("user");
		localStorage.removeItem("pwd");
		getURL("include/login.php","#result");
		notify('warning', 'Logout', "Complete logout to secure system");
	});	
	
		
	

  })
  function Save(){
			$.ajax({
				url : 'include/save_data.php',
				type : 'POST',
				data : $('form').serialize(),					
				beforeSend: function () {
					$("button").attr("disabled", true);	
					$("button").addClass('wait');					
				},
				complete: function() {
					$("button").attr("disabled", false);
					$("button").removeClass('wait');
				},
				success : function(response) {
					var obj = JSON.parse(response);
					notifyUp(obj.type, "Save", obj.message);					
				}
			});	  
  }	

  
 function live(){
		
			$.ajax({
					url: 'include/get_info.php',
					 type: 'GET',
					 dataType: 'json',					 
					success: function (response) {
						$('span#temp').html(response.tem);
						$('span#hum').html(response.hum);
						$('span#ppm').html(response.ppm);
						$('span#soil').html(response.wet);	
						$('span#stemp').html(response.stem);		
					}
				});	
				
					
}  
 	function smart_sw(x){
		var temp_stat = $('#'+x+'-sw').prop('checked');
		var bulb_stat = $('#'+x+'-mn input').prop('checked');
		 if(temp_stat == true){
			$('#'+x+'-mn').hide(200);
			$('#'+x+'-auto').show(200);	
		 }else {
			 $('#'+x+'-auto').hide(200);
			$('#'+x+'-mn').show(200);				
		 }
		  if(bulb_stat==true){
			  $('.'+x+' .bulb').addClass('on');
			  $('.'+x+' .bulb').removeClass('off');
		  }else{
			  $('.'+x+' .bulb').addClass('off');
			  $('.'+x+' .bulb').removeClass('on');
		  }
		 
		$('#'+x+'-sw').change(function() {	
			var stat = $(this).prop('checked');
			 if(stat == true){
				$('#'+x+'-mn').hide(200);
				$('#'+x+'-auto').show(200);	
			 }else {
				 $('#'+x+'-auto').hide(200);
				$('#'+x+'-mn').show(200);	
			 }  
		})
		
		$('#'+x+'-mn input').change(function() {
		  var stat = $(this).prop('checked');
		  if(stat==true){
			  $('.'+x+' .bulb').addClass('on');
			  $('.'+x+' .bulb').removeClass('off');
		  }else{
			  $('.'+x+' .bulb').addClass('off');
			  $('.'+x+' .bulb').removeClass('on');
		  }
		  
		})		
		
		
		 
	}

</script>