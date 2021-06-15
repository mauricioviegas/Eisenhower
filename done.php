<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');
	$error = "";
	
	if (array_key_exists ("tag", $_GET)) $tagContent = $_GET['tag'];
	else $tagContent = "";
	if (array_key_exists ("task", $_GET)) $taskContent = $_GET['task'];
	else $taskContent = "";
	if (array_key_exists ("start", $_GET)) $startContent = $_GET['start'];
	else $startContent = "";
	if (array_key_exists ("end", $_GET)) $endContent = $_GET['end'];
	else $endContent = "";
	if (array_key_exists ("search", $_GET)) $searchContent = $_GET['search'];
	else $searchContent = "";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) $_SESSION['id'] = $_COOKIE['id'];
	
	if (array_key_exists("id", $_SESSION)) {
		include("../torei/connection.php");
		
	}
	else header("Location: ../torei/index.php?p=ME");
	
include("header.php");
include("navigation.php");

?>

<!-- Error -->	
<div id="error"><?php if ($error!="") 
		echo ('<div class="alert alert-danger" role="alert">'.$error.'</div>');
?></div>

  <!-- Portfolio -->
  <section class="content-section">
    <div class="container">
      <div class="content-section-heading text-center">
        <h3 class="text-secondary mb-0">Edite</h3>
        <h2 class="mb-5">TAREFAS CONCLUÍDAS</h2>
      </div>
      <div class="row no-gutters">
        
	    <div class="col-lg-12" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Lembrando o passado?</div>
                <p class="mb-4">Busque as tarefas concluídas pelos filtros abaixo.</p>
		  </a>

  <!-- Call to Action -->
  <section class="content-section bg-primary text-white">
    <div class="container text-center">
	
	 <!-- Form -->
	<form>
	  <div class="form-row">
		<div class="form-group col-md-3">
		  <label>Tag</label>
		  <select class="form-control" name="tag">
		  <option value=""></option>
			<?php			
			$query2 = $query = mysqli_query($link, "SELECT id, name FROM `eisenhower_tags` WHERE (`id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." OR `id_user` IS NULL) AND active = '1' ORDER BY `name`") or die ("Não foi possível carregar.");
			$count = mysqli_num_rows($query);
				while ($row = mysqli_fetch_array($query2)){
					$idTag= $row['id'];
					$nameTag = $row['name'];
					
					echo ('<option value="'.$idTag.'">'.$nameTag.'</option>');

				}
			?>
		  </select>
		</div>
		<div class="form-group col-md-3">
		  <label>Palavra</label>
		  <input type="text" class="form-control" name="task">
		</div>
		<div class="form-group col-md-3">
		  <label>Finalizado depois de</label>
		  <input type="datetime-local" class="form-control" name="start">
		</div>
		<div class="form-group col-md-3">
		  <label>Finalizado antes de</label>
		  <input type="datetime-local" class="form-control" name="end">
		</div>
		
	  </div>
	
      <button type="submit" class="btn btn-xl btn-dark mb-1" name="search" value="1">Buscar</button>
	 

	  </form>
    </div>
  </section>
				
				
				
				
	<?php
/*

		
*/
if ($searchContent == 1){
	
	$searchstring = " ";
	if ($tagContent != "") $searchstring .= "AND tag = '".$tagContent."' ";
	if ($taskContent != "") $searchstring .= "AND task LIKE '%".$taskContent."%' ";
	if ($startContent != "") $searchstring .= "AND date_done > '".$startContent."' ";
	if ($endContent != "") $searchstring .= "AND date_done < '".$endContent."' ";
	
		$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` 
		WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])."
		".$searchstring."
		AND done = '1' AND active = '1' 
		ORDER BY `date_done` DESC LIMIT 20") or die ("Não foi possível carregar.");
		
	$count = mysqli_num_rows($query);
	if($count != 0){
		echo('<a href="#" class="list-group-item list-group-item-action"><b>TAREFA; QUADRANTE; PRIORIDADE; PRAZO; TAG; CRIAÇÃO; FINALIZADO</b></a>');
		while ($row = mysqli_fetch_array($query2)){
			$idTask = $row['id'];
			$taskTask = $row['task'];
			$quadrantTask = $row['quadrant'];
			$priorityTask = $row['priority'];
			$deadlineTask = $row['date_deadline'];
			$idtagTask = $row['tag'];
			$insertTask = $row['date_insert'];
			$doneTask = $row['date_done'];
			
			$tagTask = "";
			if ($idtagTask !=0){
				$query3 = mysqli_query($link, "SELECT name FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$idtagTask."' AND active = '1' LIMIT 1") or die ("Não foi possível carregar.");
				$row3 = mysqli_fetch_array($query3);
				$tagTask = $row3['name'];
			}

			echo ('
			<a href="add_task.php?id='.$idTask.'" class="list-group-item list-group-item-action"><b>
			'.$taskTask.';</b> '
			.$quadrantTask.'; '
			.$priorityTask.'; '
			.date("d/m/Y", strtotime($deadlineTask)).'; '
			.$tagTask.'; '
			.date("d/m/Y H:i", strtotime($insertTask)).'; '
			.date("d/m/Y H:i", strtotime($doneTask)).'
			</a>');

		}
	}
	}
	?>
				
				
				
              </div>
            </div>
          
          
        </div>
        
      </div>
    </div>
  </section>





<?php include("footer.php");?>