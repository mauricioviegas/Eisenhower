<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) $_SESSION['id'] = $_COOKIE['id'];

	$error = "";
	$doneContent = 0;
	
	if (array_key_exists ("id", $_GET)) $id = $_GET['id'];
	else $id = 0;
	if (array_key_exists ("q", $_GET)) $quadrantContent = $_GET['q'];
	else $quadrantContent = 0;
	if (array_key_exists ("tag", $_GET)) $tagContent = $_GET['tag'];
	else $tagContent = 0;
	if (array_key_exists ("task", $_GET)) $taskContent = $_GET['task'];
	else $taskContent = "";
	if (array_key_exists ("urgent", $_GET)) $urgentContent = $_GET['urgent'];
	else $urgentContent = "";
	if (array_key_exists ("important", $_GET)) $importantContent = $_GET['important'];
	else $importantContent = "";
	if (array_key_exists ("priority", $_GET)) $priorityContent = $_GET['priority'];
	else $priorityContent = 0;
	if (array_key_exists ("deadline", $_GET)) $deadlineContent = $_GET['deadline'];
	else $deadlineContent = 0;
	if (array_key_exists ("add", $_GET)) $addContent = $_GET['add'];
	else $addContent = 0;
	
	if (!array_key_exists ("q", $_GET) && array_key_exists ("tag", $_GET)) $tagContent2 = $_GET['tag'];
	else $tagContent2 = "";
	
	if (!empty($urgentContent) && !empty($importantContent)) {
		if ($urgentContent == 2 && $importantContent == 2) $quadrantContent = 4;
		elseif ($urgentContent == 1 && $importantContent == 2) $quadrantContent = 3;
		elseif ($urgentContent == 2 && $importantContent == 1) $quadrantContent = 2;
		elseif ($urgentContent == 1 && $importantContent == 1) $quadrantContent = 1;
	}
	
	if (array_key_exists("id", $_SESSION)) {
              
		include("../torei/connection.php");
		
		if ($id != 0){
		$query = "SELECT done FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND active = '1' LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));

			$doneContent = $row['done'];
			
		}
		
		if ($taskContent == ""){
		$query = "SELECT * FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND active = '1' LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		if($row){
			
			$tagContent = $row['tag'];
			$taskContent = $row['task'];
			$quadrantContent = $row['quadrant'];
			$priorityContent = $row['priority'];
			$deadlineContent = $row['date_deadline'];
			$doneContent = $row['done'];
			
			if ($quadrantContent == 0) {$urgentContent = ""; $importantContent = "";}
			elseif ($quadrantContent == 1) {$urgentContent = 1; $importantContent = 1;}
			elseif ($quadrantContent == 2) {$urgentContent = 2; $importantContent = 1;}
			elseif ($quadrantContent == 3) {$urgentContent = 1; $importantContent = 2;} 
			elseif ($quadrantContent == 4) {$urgentContent = 2; $importantContent = 2;}

		}
		}
		if ($addContent == 1 && $id == 0) {
			
			$count = 0;
			if ($taskContent == "") {$error .= "É necessário uma tarefa.<br>"; $count++;}
				
			$query = "SELECT id FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND task = '".mysqli_real_escape_string($link, $taskContent)."' AND tag = '".$tagContent."' AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tarefa acrescentada.<br>";

			if ($count == 0) {
			
				$query = mysqli_query($link, "INSERT INTO `eisenhower_tasks` (`id_user`, `task`, `quadrant`, `priority`, `date_insert`, `tag`, `date_deadline`) VALUES (
						'".mysqli_real_escape_string($link, $_SESSION['id'])."',
						'".mysqli_real_escape_string($link, $taskContent)."',
						'".$quadrantContent."',
						'".$priorityContent."',
						'".date('Y-m-d H:i:s')."',
						'".$tagContent."',
						'".date('Y-m-d')."'
						)");
				
				if (!$query) $error .= "Não foi possível acrescentar nova tarefa, tente mais tarde.<br>";
				else header("Location: tasks.php?q=".$quadrantContent."");
			  
		}}
		if ($addContent == 2 && $id != 0 && $doneContent == 0) {
			
			$count = 0;
			if ($taskContent == "") {$error .= "É necessário uma tarefa.<br>"; $count++;}
			
			$query = "SELECT id FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." 
			AND id = '".$id."' 
			
			AND `task` = '".mysqli_real_escape_string($link, $taskContent)."'
			AND `quadrant` = '".$quadrantContent."' 
			AND `priority` = '".$priorityContent."' 
			AND `tag` = '".$tagContent."' 
			AND `date_deadline` = '".$deadlineContent."' 
			
			AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tarefa atualizada.<br>";
			
			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tasks` SET `task` = '".mysqli_real_escape_string($link, $taskContent)."' WHERE id = '".$id."' AND active = '1' LIMIT 1";
				mysqli_query($link, $query);
				$query = "UPDATE `eisenhower_tasks` SET `quadrant` = '".$quadrantContent."' WHERE id = '".$id."' AND active = '1' LIMIT 1";
				mysqli_query($link, $query);
				$query = "UPDATE `eisenhower_tasks` SET `priority` = '".$priorityContent."' WHERE id = '".$id."' AND active = '1' LIMIT 1";
				mysqli_query($link, $query);
				$query = "UPDATE `eisenhower_tasks` SET `tag` = '".$tagContent."' WHERE id = '".$id."' AND active = '1' LIMIT 1";
				mysqli_query($link, $query);
				$query = "UPDATE `eisenhower_tasks` SET `date_deadline` = '".$deadlineContent."' WHERE id = '".$id."' AND active = '1' LIMIT 1";
				mysqli_query($link, $query);

				if (!$query) $error .= "Não foi possível atualizar a tarefa, tente mais tarde.<br>";
				else header("Location: tasks.php?q=".$quadrantContent."");
			  
			}
		}

		if ($addContent == 3 && $id != 0 && $doneContent == 0){
			
			$count = 0;
			
			$query = "SELECT id FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND active = '0' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tarefa apagada.<br>";

			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tasks` SET `active` = '0' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);
				
				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				
				$query = "UPDATE `eisenhower_tasks` SET `date_deleted` = '".date('Y-m-d H:i:s')."' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);

				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				else header("Location: tasks.php?q=".$quadrantContent."");
			  
			}}
		
		if ($addContent == 4 && $id != 0 && $doneContent == 0){
			
			$count = 0;
			
			$query = "SELECT id FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND done = '1' AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tarefa finalizada.<br>";

			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tasks` SET `done` = '1' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);
				
				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				
				$query = "UPDATE `eisenhower_tasks` SET `date_done` = '".date('Y-m-d H:i:s')."' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);

				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				elseif ($tagContent2 != "") header("Location: tasksbytag.php?tag=".$tagContent2."");
				else header("Location: tasks.php?q=".$quadrantContent."");
			  
			}}

		if ($addContent == 5 && $id != 0 && $doneContent == 1){
			
			$count = 0;
			
			$query = "SELECT id FROM `eisenhower_tasks` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND done = '0' AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tarefa recuperada.<br>";

			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tasks` SET `done` = '0' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);
				
				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				
				$query = "UPDATE `eisenhower_tasks` SET `date_done` = NULL WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);

				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				elseif ($tagContent2 != "") header("Location: tasksbytag.php?tag=".$tagContent2."");
				else header("Location: tasks.php?q=".$quadrantContent."");
			  
			}}		
	 
	} else header("Location: ../torei/index.php?p=ME");


include("header.php");
include("navigation.php");

?>



<!-- Header -->
  <header class="masthead d-flex">
    <div class="container text-center my-auto">

<!-- Error -->	
 <div id="error"><?php if ($error!="") 
		echo ('<div class="alert alert-danger" role="alert">'.$error.'</div>');
?></div>

<h4>Tarefas com duração máxima de 1 dia!</h4>
<p>As tarefas tem que estar divididas de maneira que você consiga começar e finalizar elas em apenas um dia.<br>Organize-se!</p>

 <!-- Form -->
<form>
  <div class="form-row">
  <div class="form-group col-md-12">
  <?php 
		if ($id != 0 && $doneContent == 0) echo ('
		<button type="submit" class="btn btn-xl btn-primary mb-4 mr-4" name="add" value="4">TAREFA FEITA!</button>
		');
		else if ($id != 0 && $doneContent == 1) echo ('
		<button type="submit" class="btn btn-xl btn-danger mb-4 mr-4" name="add" value="5">MARCAR TAREFA COMO NÃO FEITA!</button>
		');
  ?>
  </div>
  <div class="form-group col-md-2">
      <label>Tag</label>
      <select class="form-control" name="tag">
	  <option <?php if ($tagContent == 0) echo ("selected"); ?>></option>
	    <?php			
		$query2 = $query = mysqli_query($link, "SELECT id, name FROM `eisenhower_tags` WHERE (`id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." OR `id_user` IS NULL) AND active = '1' ORDER BY `name`") or die ("Não foi possível carregar.");
		$count = mysqli_num_rows($query);
		if($count != 0){
			while ($row = mysqli_fetch_array($query2)){
				$idTag= $row['id'];
				$nameTag = $row['name'];
				
				if ($idTag != 0) echo ('<option value="'.$idTag.'"'); if ($tagContent == $idTag) echo ("selected"); echo ('>'.$nameTag.'</option>');

			}
		}
		else echo ('<a href="add_task.php?q='.$quadrantContent.'" class="list-group-item list-group-item-action">Não há tarefas neste quadrante.<br>Acrescente uma tarefa clicando aqui.</a>');
		?>
	  </select>
    </div>
	<div class="form-group col-md-10">
      <label>Tarefa</label>
      <input type="text" class="form-control" name="task" value="<?php echo($taskContent)?>">
    </div>
    <div class="form-group col-md-3">
      <label >Urgência</label>
	  <select class="form-control" name="urgent">
      <option></option>
	  <option value="1" <?php if ($urgentContent == "1") echo ("selected"); ?>>É urgente</option>
	  <option value="2" <?php if ($urgentContent == "2") echo ("selected"); ?>>NÃO é urgente</option>
	  </select>
    </div>
    <div class="form-group col-md-3">
      <label>Importância</label>
      <select class="form-control" name="important">
        <option></option>
		<option value="1" <?php if ($importantContent == "1") echo ("selected"); ?>>É importante</option>
		<option value="2" <?php if ($importantContent == "2") echo ("selected"); ?>>NÃO é importante</option>
      </select>
    </div>
	
	<div class="form-group col-md-3">
      <label>Prazo</label>
      <input type="date" class="form-control" name="deadline" value="<?php echo($deadlineContent)?>">
    </div>
	<div class="form-group col-md-3">
      <label>Prioridade no dia</label>
      <select class="form-control" name="priority">
        <option <?php if ($priorityContent == "0") echo ("selected"); ?>></option>
		<option value="5" <?php if ($priorityContent == "5") echo ("selected"); ?>>5 - Máxima</option>
		<option value="4" <?php if ($priorityContent == "4") echo ("selected"); ?>>4 - Muita</option>
		<option value="3" <?php if ($priorityContent == "3") echo ("selected"); ?>>3 - Normal</option>
		<option value="2" <?php if ($priorityContent == "2") echo ("selected"); ?>>2 - Baixa</option>
		<option value="1" <?php if ($priorityContent == "1") echo ("selected"); ?>>1 - Mínima</option>
      </select>
    </div>
	
	
  </div>
		<?php 
		if ($id == 0) echo ('
		<button type="submit" class="btn btn-xl btn-success mb-4" name="add" value="1">Acrescentar tarefa</button>
		<a href="tasks.php?q='.$quadrantContent.'" class="btn btn-xl btn-warning mb-4 mr-4 ml-4">Voltar</a>
		<a href="add_tag.php?idtask='.$id.'" class="btn btn-xl btn-dark mb-4">Nova Tag</a>
		');
		else echo ('
		<button type="submit" class="btn btn-xl btn-success mb-4" name="add" value="2">Atualizar</button>
		<a href="tasks.php?q='.$quadrantContent.'" class="btn btn-xl btn-warning mb-4 mr-4 ml-4">Voltar</a>
		<a href="add_tag.php?idtask='.$id.'" class="btn btn-xl btn-dark mb-4">Nova Tag</a>
		<br><p></p><button type="submit" class="btn btn-danger" name="add" value="3">Apagar</button>
		');
		?>


	  <input type="hidden" class="form-control-plaintext" name="id" value="<?php echo($id)?>" readonly>

</form>


 <?php include("footer.php");?>
