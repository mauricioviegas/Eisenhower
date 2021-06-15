
<?php

/*------------INFO PROCESS3_0BEGIN----------------
	
	//////for all
	--> $link
	--> $quadrantContent
	--> $tagContent
	
	//////example
	$quadrantContent = 1;
	include("tasklistbytag.php");
	---------------------------------------*/

if ($tagContent !=""){

	$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND quadrant = '".$quadrantContent."' AND tag = '".$tagContent."' AND done = '0' AND active = '1' ORDER BY `priority` DESC") or die ("Não foi possível carregar.");
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

			echo ('
			<div class="list-group-item"><div class="btn btn-outline-success mr-3">
			<a href="add_task.php?id='.$idTask.'&add=4&tag='.$idtagTask.'" class="list-group-item-action ">FEITO!</a></div>
			<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><b>'.$tagTask.' '.$taskTask.'</b> ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']
			');
			if($deadlineTask < date('Y-m-d')) echo ('<span href="done.php" class="badge badge-danger">ATRASADA!</span>');
			elseif($deadlineTask == date('Y-m-d')) echo ('<span href="done.php" class="badge badge-warning">HOJE!</span>');
			echo ('</div></a>');

		}
	}
	else echo ('<a href="add_task.php?q='.$quadrantContent.'" class="list-group-item list-group-item-action">Não há tarefas neste quadrante.<br>Acrescente uma tarefa clicando aqui.</a>');
	
	$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND quadrant = '".$quadrantContent."' AND tag = '".$tagContent."' AND done = '1' AND date_done > '".date('Y-m-d 00:00:00')."' AND active = '1' ORDER BY `date_done` DESC") or die ("Não foi possível carregar.");
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

			echo ('
			<div class="list-group-item"><div class="btn btn-outline-danger mr-3">
			<a href="add_task.php?id='.$idTask.'&add=5&tag='.$idtagTask.'" class="list-group-item-action ">DESFAZER</a></div>
			<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><s>'.$tagTask.' '.$taskTask.' ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']</s>
			</div></a>
			');

		}
	}
}	
	?>
