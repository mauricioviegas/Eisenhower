<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');
	$error = "";
	
	if (array_key_exists ("q", $_GET)) $quadrantContent = $_GET['q'];
	else $quadrantContent = 0;

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) $_SESSION['id'] = $_COOKIE['id'];
	
	if (array_key_exists("id", $_SESSION)) {
		include("../torei/connection.php");
		
	}
	else header("Location: ../torei/index.php?p=ME");
	
include("header.php");
include("navigation.php");

?>

  

  <!-- Portfolio -->
  <section class="content-section">
    <div class="container">
      <div class="content-section-heading text-center">
        <h3 class="text-secondary mb-0">Quadrante <?php echo($quadrantContent)?></h3>
        <h2 class="mb-5">
		<?php
		if ($quadrantContent == 1) echo("FAÇA PRIMEIRO");
		elseif ($quadrantContent == 2) echo("AGENDE");
		elseif ($quadrantContent == 3) echo("DELEGUE");
		elseif ($quadrantContent == 4) echo("NÃO FAÇA");
		else echo("SEM CLASSIFICAÇÃO");
		?>
		</h2>
      </div>
      <div class="row no-gutters">
        
	    <div class="col-lg-12" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">
				<?php
				if ($quadrantContent == 1) echo("Urgente e importante.");
				elseif ($quadrantContent == 2) echo("Menos urgente, mas importante.");
				elseif ($quadrantContent == 3) echo("Urgente, mas menos importante.");
				elseif ($quadrantContent == 4) echo("Não é urgente nem importante.");
				else echo('Urgente ou importante?');
				?>
				</div>
                <p class="mb-4">
				<?php
				if ($quadrantContent == 1) echo("Atividades importantes que exigem atenção imediata.");
				elseif ($quadrantContent == 2) echo("Atividades importantes que não exigem atenção imediata.");
				elseif ($quadrantContent == 3) echo("Atividades que exigem atenção imediata, mas que não contribuem com nossos objetivos.");
				elseif ($quadrantContent == 4) echo("Não requerem atenção imediata nem contribuem com nossos objetivos.");
				else echo("Classifique estas atividades sobre sua urgência e importância.");
				?>
				</p>
				
				<?php			
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
			
						echo ('
						<div class="list-group-item"><div class="btn btn-outline-success mr-3">
						<a href="add_task.php?id='.$idTask.'&add=4&q='.$quadrantContent.'" class="list-group-item-action ">FEITO!</a></div>
						<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><b>'.$tagTask.' '.$taskTask.'</b> ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']
						');
						if($deadlineTask < date('Y-m-d')) echo ('<span href="done.php" class="badge badge-danger">ATRASADA!</span>');
						elseif($deadlineTask == date('Y-m-d')) echo ('<span href="done.php" class="badge badge-warning">HOJE!</span>');
						echo ('</div></a>');

					}
				}
				else echo ('<a href="add_task.php?q='.$quadrantContent.'" class="list-group-item list-group-item-action">Não há tarefas neste quadrante.<br>Acrescente uma tarefa clicando aqui.</a>');
				
				if ($quadrantContent == 1) {
				$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND `date_deadline` < '".date('Y-m-d 00:00:00')."' AND done = '0' AND active = '1' ORDER BY `quadrant`, `priority` DESC") or die ("Não foi possível carregar.");
				$count = mysqli_num_rows($query);
				if($count != 0){
					while ($row = mysqli_fetch_array($query2)){
						$idTask = $row['id'];
						$taskTask = $row['task'];
						$priorityTask = $row['priority'];
						$idtagTask = $row['tag'];
						$deadlineTask = $row['date_deadline'];
						$quadrantTask = $row['quadrant'];
						
						if ($quadrantTask != 0 && $quadrantTask != 1){
							$tagTask = "";
							if ($idtagTask !=0){
								$query3 = mysqli_query($link, "SELECT name FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$idtagTask."' AND active = '1' LIMIT 1") or die ("Não foi possível carregar.");
								$row3 = mysqli_fetch_array($query3);
								$tagTask = "[".$row3['name']."]";
							}
				
							echo ('
							<div class="list-group-item"><div class="btn btn-outline-success mr-3">
							<a href="add_task.php?id='.$idTask.'&add=4&q='.$quadrantContent.'" class="list-group-item-action ">FEITO!</a></div>
							<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><b>'.$tagTask.' '.$taskTask.'</b> ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']
							');
							if($deadlineTask < date('Y-m-d')) echo ('<span href="done.php" class="badge badge-danger">ATRASADA!</span>');
							elseif($deadlineTask == date('Y-m-d')) echo ('<span href="done.php" class="badge badge-warning">HOJE!</span>');
							if($quadrantTask == 2) echo (' <span href="done.php" class="badge badge-secondary">Q2 - Agende</span>');
							if($quadrantTask == 3) echo (' <span href="done.php" class="badge badge-secondary">Q3 - Delegue</span>');
							if($quadrantTask == 4) echo (' <span href="done.php" class="badge badge-secondary">Q4 - Não faça</span>');
							echo ('</div></a>');
						}
					}
				}
				$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND quadrant = '0' AND `date_deadline` < '".date('Y-m-d 00:00:00')."' AND done = '0' AND active = '1' ORDER BY `priority` DESC") or die ("Não foi possível carregar.");
				$count = mysqli_num_rows($query);
				if($count != 0){
					while ($row = mysqli_fetch_array($query2)){
						$idTask = $row['id'];
						$taskTask = $row['task'];
						$priorityTask = $row['priority'];
						$idtagTask = $row['tag'];
						$deadlineTask = $row['date_deadline'];
						$quadrantTask = $row['quadrant'];
						
						
						$tagTask = "";
						if ($idtagTask !=0){
							$query3 = mysqli_query($link, "SELECT name FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$idtagTask."' AND active = '1' LIMIT 1") or die ("Não foi possível carregar.");
							$row3 = mysqli_fetch_array($query3);
							$tagTask = "[".$row3['name']."]";
						}
			
						echo ('
						<div class="list-group-item"><div class="btn btn-outline-success mr-3">
						<a href="add_task.php?id='.$idTask.'&add=4&q='.$quadrantContent.'" class="list-group-item-action ">FEITO!</a></div>
						<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><b>'.$tagTask.' '.$taskTask.'</b> ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']
						');
						if($deadlineTask < date('Y-m-d')) echo ('<span href="done.php" class="badge badge-danger">ATRASADA!</span>');
						elseif($deadlineTask == date('Y-m-d')) echo ('<span href="done.php" class="badge badge-warning">HOJE!</span>');
						echo (' <span href="done.php" class="badge badge-secondary">Sem classificação</span></div></a>');
						
					}
				}
				}
				
				$query2 = $query = mysqli_query($link, "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND quadrant = '".$quadrantContent."' AND done = '1' AND date_done > '".date('Y-m-d 00:00:00')."' AND active = '1' ORDER BY `date_done` DESC") or die ("Não foi possível carregar.");
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
						<a href="add_task.php?id='.$idTask.'&add=5&q='.$quadrantContent.'" class="list-group-item-action ">DESFAZER</a></div>
						<a href="add_task.php?id='.$idTask.'" class="list-group-item-action"><s>'.$tagTask.' '.$taskTask.' ['.date("d/m/Y", strtotime($deadlineTask)).'] ['.$priorityTask.']</s>
						</div></a>
						');

					}
				}
				?>
				
				
				
              </div>
            </div>
          
          </a>
        </div>
        
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="content-section bg-primary text-white">
    <div class="container text-center">
      
	  <h2 class="mb-4">Acrescentar tarefa?</h2>
      <a href="add_task.php?q=<?php echo($quadrantContent)?>" class="btn btn-xl btn-success mb-4">Acrescentar nova tarefa</a>
      <a href="tasks.php?q=0" class="btn btn-xl btn-warning mb-4 mr-4 ml-4">Mostrar tarefas não classificadas</a>
	  <a href="done.php" class="btn btn-xl btn-dark mb-4">Tarefas concluídas</a>
	  
    </div>
  </section>



<?php include("footer.php");?>