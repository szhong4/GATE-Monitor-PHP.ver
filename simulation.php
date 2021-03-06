<?php
error_reporting ( 0 );
session_start ();
if (strcmp ( $_SESSION ['login'], 'in' ) != 0) {
	header ( "Location: index.php" );
}
?>

<!DOCTYPE html>
<html>
<head>

<?php
error_reporting ( 0 );
session_start ();
if (strcmp ( $_SESSION ['login'], 'in' ) != 0) {
	header ( "Location: index.php" );
}
?>

<link type="text/css" rel="stylesheet" href="css/gate.css">
<title>GATE</title>
<meta name="author" content="Shiqi Zhong">


</head>
<body>
	<div id="header">
		<img id="logo" src="./images/logo.gif" align="left">

		<p id="name"></p>
		<a href="mailto:szhong4@vols.utk.edu" id="email">Mail to Shiqi Zhong</a>
		<p id="info">
		<?php
		echo "IP Address:{$_SERVER["REMOTE_ADDR"]}";
		?>			
		<br> Today's date: 
		<?php echo date('l jS \of F Y h:i:s A');?>
		
		</p>
		<br> <br>

	</div>

	<div class="left">

		<h3>
			<a href="./intro.php"><strong>Introduction</strong></a>
		</h3>
		<h3>
			<a href="./simulation.php"><font color="red"><em>Run Simulation</em></font></a>
		</h3>
		<h3>
			<a href="./result.php"><strong>View Result</strong></a>
		</h3>
		<h3>
			<a href="./source.php">Source Code</a>
		</h3>
	</div>

	<div class="right">

		<h4>Simulation</h4>
		<p>
			Choose Simulation Type (Geometry): <select id="simu_type"
				onclick="show_option()">
				<option value="0"></option>

				<option value="1">SPECT</option>

				<option value="2">PET</option>

			</select>

		</p>
		<div id="SPECT" style="display: none">
			<p>
				Collimator type: <select id="Collimator_type"
					onclick="send_parameter('#Collimator_type')">
					<option value="0"></option>
					<option value="1MGP10.mac">1MGP10.mac</option>
					<option value="5MWB.mac">5MWB.mac</option>
					<option value="1MHR05.mac">1MHR05.mac</option>
					<option value="1MME30.mac">1MME30.mac</option>
				</select>
			</p>

			<p>
				Radius of rotation: <select id="Radius_of_rotation"
					onclick="send_parameter('#Radius_of_rotation')">
					<option value="0"></option>
					<option value="25">25</option>
					<option value="30">30</option>
					<option value="360">360</option>
				</select>
			</p>

			<p>
				Isotope: <select id="Isotope" onclick="send_parameter('#Isotope')">
					<option value="0"></option>
					<option value="COBALT57_digitizer">COBALT57_digitizer</option>
					<option value="I123_digitizer_HI">I123_digitizer_HI</option>
					<option value="I123_digitizer_LO">I123_digitizer_LO</option>
					<option value="I125_digitizer">I125_digitizer</option>
					<option value="T99M_digitizersp20">T99M_digitizersp20</option>
				</select>
			</p>

			<p>
				Number of Projections: <select id ="Projection" name="ProjectionAndTime"
					onclick="send_parameter('#Projection')">
					<option value="0"></option>
					<option value="1">30</option>					
					<option value="2">60</option>
				</select>
			</p>
			<p>
				Acquisition Time: <select id ="Time" name="ProjectionAndTime"
					onclick="send_parameter('#Time')">
					<option value="0"></option>
					<option value="1">30</option>
					<option value="2">45</option>
					<option value="2">60</option>
				</select>
			</p>
			
			<p>
		
		</div>


		<div id="PET" style="display: none"></div>

		<a id="simu_start" class="myButton"
			onclick="simu_start('#simu_start')">Start Simulation </a>




	</div>

	<div id="footer">
		<p>&#169 GATE Interactive Monitor, 2015</p>
	</div>


	<script src="https://code.jquery.com/jquery-1.11.3.js"></script>

	<script type="text/javascript" src="scripts/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/d3.v3.min.js"></script>

	<script type="text/javascript">
		function show_option() {
			// Tested on Chrome and Safari, bug has fixed
			var text = $("#simu_type option:selected").text();
			if (text === "SPECT") {
				$("#SPECT").show();
				$("#PET").hide();
			} else if (text === "") {
				$("#SPECT").hide();
				$("#PET").hide();
			} else if (text === "PET") {
				$("#SPECT").hide();
				$("#PET").show();
			} else {
				$("#SPECT").hide();
				$("#PET").hide();
			}
		}

		function send_parameter(thisObj) {
			var type = $(thisObj).parent().parent().attr('id');
			var name = $(thisObj).attr('id');
			//var id = $(this).children(":selected").attr("id");
			var text = $("option:selected", thisObj).text();
			//alert($("#choose_paramter:parent"));
			//alert(type + "," + name +"," + text);
			var cmd = type + "," + name + "," + text;
			if(name != "Projection" && name != "Time"){
				$.post("updateconf.php", {
					command : cmd
				}, function(data) {
					//alert("Data Sent: " + data);
				});
			}else{
				// for projections and time, only when both two are specified, send cmd
				var a = document.getElementById("Projection");
				var b = document.getElementById("Time");
				var projection = a.options[a.selectedIndex].text;
				var time = b.options[b.selectedIndex].text;
				if(projection != "" && time !=""){
				var cmd = type + "," + "ProjectionAndTime" + "," + projection + "," + time;
				$.post("updateconf.php", {
					command : cmd
				}, function(data) {
					//alert("Data Sent: " + data);
				});
				}
			}
		}

		function simu_start() {
			// Tested on Chrome and Safari, bug has fixed
			var type = $("#simu_type option:selected").text();
			//Start the simulation
			/* 			$.post("/GATE-Interactive-Monitor/result.jsp", {
			 type : type
			 }, function(data) {
			 alert("Data Sent: " + data);
			 });
			 */
			 
			if(type !== ""){

			$.post("runsimulation.php", {
				simutype : type
			}, function(data) {
				//alert("Data Sent: " + data);
			});
			window.open ('result.php','_self',false);
			} else {
			alert("Please Select Simulation Type Before Start.");
			}
		}
	</script>

</body>
</html>