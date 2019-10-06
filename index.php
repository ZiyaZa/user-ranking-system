<!DOCTYPE html>
<?php
include_once("db.php");

$chapters = $dbconn->query("SELECT DISTINCT(Chapter) FROM `$tasks_table_name`")->fetch_all();
for ($i = 0; $i < count($chapters); $i++)
{
	$sections[$i] = $dbconn->query("SELECT DISTINCT(Section) FROM `$tasks_table_name` WHERE Chapter=" . $chapters[$i][0])->fetch_all();
	for ($j = 0; $j < count($sections[$i]); $j++)
	{
		$tasks[$i][$j] = $dbconn->query("SELECT Name, ID FROM `$tasks_table_name` WHERE Chapter=" . $chapters[$i][0] . " && Section=" . $sections[$i][$j][0])->fetch_all();
	}
}

$users = $dbconn->query("SELECT * FROM `$users_table_name` ORDER BY Num_of_solved DESC")->fetch_all();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		
        <link rel="stylesheet" href="style.css?ver=<?php echo time();?>">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		
		<script>
			var logged_in = <?php 
							if (isset($_SESSION["logged_in"])) echo json_encode($_SESSION["logged_in"]);
							else echo "\"0\"";
							?>;
			
			var sliderState = 0;
			
			function cellClick( cellID)
			{
				var arr = cellID.split(":");
				var userID = arr[0];
				var taskID = arr[1];
				
				if (logged_in !== userID)
				{
					var pwd = prompt ("Please log in first by entering the password for " + document.getElementById(cellID).parentElement.children[1].innerText);
					if (pwd === null) return;
					$.get( "login.php", { user: userID, pwd: pwd}).done(function( data ) {
						if (data.length > 0)
						{
							alert(data);
						}
						else
						{
							logged_in = userID;
						}
						cellClick( cellID);
					});
					return;
				}
				
				$.post( "toggle.php", { user: userID, task: taskID}).done(function( data ) {
					if (data.length > 0)
					{
						alert(data);
					}
					location.reload();
				});
			}
			
			$(document).ready(function() {
				$("#toggle").click(function() {
					sliderState = 1 - sliderState;
					if ( sliderState === 0) $("table").animate({height: "95vh"}, "slow");
					else  $("table").animate({height: "65vh"}, "slow");
					$("#add_user_panel").slideToggle("slow");
				});
			
				$("#add_user").click(function() {
					var name = $("#name").val();
					var surname = $("#surname").val();
					
					if (name.length === 0 || surname.length === 0) alert("Please fill all of the forms!");
					else
					{
						var pwd = "";
						var chars = "0123456789"
						var len = 8;
						
						for (var i = 0; i < len; i++)
						{
							pwd += chars[Math.floor(Math.random() * chars.length)];
						}
						
						$.post( "add_user.php", { name: name, surname: surname, pwd: pwd }).done(function( data ) {
							if (data[0] === 'N') alert( "Your password is: " + pwd + "\n" + data );
							else alert(data);
							location.reload();
						});
					}
				});
			});
		</script>
    </head>
	
	
    <body>
		<table class="m-0 table table-bordered table-hover table-dark table-striped table-responsive text-center">
			<thead>
				<tr>
					<th rowspan="3" class="align-middle">№</th>
					<th rowspan="3" class="align-middle">Users</th>
					<th rowspan="3" class="align-middle">TOTAL</th>

					<?php
					for ($i = 0; $i < count($chapters); $i++)
					{
						echo "<th colspan=\"" . $dbconn->query("SELECT COUNT(ID) FROM tasks WHERE Chapter=" . $chapters[$i][0])->fetch_all()[0][0] . "\">Chapter " . $chapters[$i][0] . "</th>\n";
					}
					?>
				</tr>
				<tr>
					<?php
					for ($i = 0; $i < count($chapters); $i++)
					{
						for ($j = 0; $j < count($sections[$i]); $j++)
						{
							echo "<th colspan=\"" . count($tasks[$i][$j]) . "\">Section " . $sections[$i][$j][0] . "</th>\n";
						}
					}
					?>
				</tr>
				<tr>
					<?php
					for ($i = 0; $i < count($chapters); $i++)
					{
						for ($j = 0; $j < count($sections[$i]); $j++)
						{
							for ($k = 0; $k < count($tasks[$i][$j]); $k++)
							{
								echo "<th>" . $tasks[$i][$j][$k][0] . "</th>\n";
							}
						}
					}
					?>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$num = 0;
				
				for ($l = 0; $l < count($users); $l++)
				{
					if ($l == 0 || $users[$l][5] !== $users[$l - 1][5]) $num = $l + 1;
					echo "<tr>\n<td>" . $num . "</td>\n<td>" . $users[$l][1] . " " . $users[$l][2] . "</td>\n<td>" . $users[$l][5] . "</td>\n";
					for ($i = 0; $i < count($tasks); $i++)
					{
						for ($j = 0; $j < count($tasks[$i]); $j++)
						{
							for ($k = 0; $k < count($tasks[$i][$j]); $k++)
							{
								echo "<td id=\"" . $users[$l][0] . ":" . $tasks[$i][$j][$k][1] . "\" onclick=\"cellClick(this.id)\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . $tasks[$i][$j][$k][0] . "\"><img src=\"";
								if ( strpos($users[$l][4], ":" . $tasks[$i][$j][$k][1] . ":") !== false) echo "green-tick.png";
								else echo "red-x.png";
								echo "\" class=\"status-img\" /></td>\n";
							}
						}
					}
					echo "</tr>\n";
				}
				?>
			</tbody>
        </table>
		
		<div id="toggle">
			<strong>Click here to add yourself to the list</strong>
		</div>
		<div id="add_user_panel">
			<label class="mt-3">Please fill in the fields.</label>
			<div class="d-flex justify-content-center mb-3">
				<input type="text" placeholder="Name" id="name" class="form-control">
			</div>
			<div class="d-flex justify-content-center">
				<input type="text" placeholder="Surname" id="surname" class="form-control">
			</div>
			<button type="submit" id="add_user" class="m-3 btn btn-dark">Submit</button>
		</div>
    </body>
</html>