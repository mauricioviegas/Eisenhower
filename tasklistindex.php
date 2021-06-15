
<?php


/*------------INFO PROCESS3_0BEGIN----------------
	
	//////for all
	--> $link
	--> $quadrantContent
	--> $tagContent
	
	//////example
	$quadrantContent = 1;
	include("tasklistindex.php");
	---------------------------------------*/

			
$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND quadrant = '".$quadrantContent."' AND done = '0' AND active = '1' ORDER BY `date_deadline`, `priority` DESC") or die ("Não foi possível carregar.");
$count = mysqli_num_rows($query);
if($count != 0){
	
	
	while ($row = mysqli_fetch_array($query2)){
		$idTask = $row['id'];
		$taskTask = $row['task'];
		$priorityTask = $row['priority'];
		$idtagTask = $row['tag'];
		$deadlineTask = $row['date_deadline'];
		
		$tagTask = "";
		if ($idtagTask !=0){
			$query3 = mysqli_query($link, "SELECT name FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$idtagTask."' AND active = '1' LIMIT 1") or die ("Não foi possível carregar.");
			$row3 = mysqli_fetch_array($query3);
			$tagTask = "[".$row3['name']."]";
		}
		else $tagTask = "[-]";
		

		echo (''.$tagTask.' <b>'.$taskTask.'</b><br>');
		

	}
}
else echo ('Não há tarefas neste quadrante.<br>Acrescente uma tarefa clicando aqui.');
?>
