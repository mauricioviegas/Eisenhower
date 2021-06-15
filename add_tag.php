<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) $_SESSION['id'] = $_COOKIE['id'];

	$error = "";
	
	if (array_key_exists ("id", $_GET)) $id = $_GET['id'];
	else $id = 0;
	if (array_key_exists ("nametag", $_GET)) $nametagContent = $_GET['nametag'];
	else $nametagContent = "";
	if (array_key_exists ("idtask", $_GET)) $idtaskContent = $_GET['idtask'];
	else $idtaskContent = "";
	if (array_key_exists ("add", $_GET)) $addContent = $_GET['add'];
	else $addContent = 0;
	
	if (array_key_exists("id", $_SESSION)) {
              
		include("../torei/connection.php");
		
		
		if ($nametagContent == ""){
		$query = "SELECT name FROM `eisenhower_tags` WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND active = '1' LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		if($row && $id != 0) $nametagContent = $row['name'];
		}
		
		if ($addContent == 1 && $id == 0) {
			
			$count = 0;
			if ($nametagContent == "") {$error .= "É necessário um nome para a tag.<br>"; $count++;}
			
			$query = "SELECT id FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND name = '".mysqli_real_escape_string($link, $nametagContent)."' AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tag acrescentada.<br>";
			
			if ($count == 0) {
				
				$query = mysqli_query($link, "INSERT INTO `eisenhower_tags` (`name`, `id_user`,`date_insert`) VALUES (
						'".mysqli_real_escape_string($link, $nametagContent)."',
						'".mysqli_real_escape_string($link, $_SESSION['id'])."',
						'".date('Y-m-d H:i:s')."'
						)");
				
				if (!$query) $error .= "Não foi possível acrescentar nova tag, tente mais tarde.<br>";
				elseif ($idtaskContent != "") header("Location: add_task.php?id=".$idtaskContent."");
				else header("Location: tags.php");
			  
		}}
		if ($addContent == 2 && $id != 0) {
			
			$count = 0;
			if ($nametagContent == "") {$error .= "É necessário um nome para a tag.<br>"; $count++;}
			
			$query = "SELECT id FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." 
			AND id = '".$id."' 

			AND `name` = '".mysqli_real_escape_string($link, $nametagContent)."' 			
			
			AND active = '1' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tag atualizada.<br>";
			
			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tags` SET `name` = '".mysqli_real_escape_string($link, $nametagContent)."' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND active = '1' LIMIT 1";
				mysqli_query($link, $query);
				
				if (!$query) $error .= "Não foi possível atualizar tag, tente mais tarde.<br>";
				elseif ($idtaskContent != "") header("Location: add_task.php?id=".$idtaskContent."");
				else header("Location: tags.php");
			  
			}
		}

		if ($addContent == 3 && $id != 0){
			
			$count = 0;
			
			$query = "SELECT id FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND id = '".$id."' AND active = '0' LIMIT 1";
			$count = $count + mysqli_num_rows(mysqli_query($link, $query));
			if ($count != 0) $error .= "Tag apagada.<br>";
			
			if ($count == 0) {
			
				$query = "UPDATE `eisenhower_tags` SET `active` = '0' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);
				
				if (!$query) $error .= "Não foi possível apagar tarefa, tente mais tarde.<br>";
				
				$query = "UPDATE `eisenhower_tags` SET `date_deleted` = '".date('Y-m-d H:i:s')."' WHERE id = '".$id."' AND `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
				mysqli_query($link, $query);

				if (!$query) $error .= "Não foi possível apagar tag, tente mais tarde.<br>";
				elseif ($idtaskContent != "") header("Location: add_task.php?id=".$idtaskContent."");
				else header("Location: tags.php");
			  
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

<!-- Form -->
<form>
  <div class="form-row">
  
	<div class="form-group col-md-12">
      <label>Nome Tag</label>
      <input type="text" class="form-control" name="nametag" value="<?php echo($nametagContent)?>">
    </div>
    
	
  </div>
		<?php 
		if ($id == 0) {echo ('
		<button type="submit" class="btn btn-xl btn-success mb-4" name="add" value="1">Acrescentar tag</button>
		<a href="');if ($idtaskContent != "") echo('add_task.php?id='.$idtaskContent.'');else echo('tags.php');echo('" class="btn btn-xl btn-warning mb-4 mr-4 ml-4">Voltar</a>
		<a href="tags.php" class="btn btn-xl btn-dark mb-4">Editar Tags</a>
		');}
		else {echo ('
		<button type="submit" class="btn btn-xl btn-success mb-4" name="add" value="2">Atualizar</button>
		<a href="');if ($idtaskContent != "") echo('add_task.php?id='.$idtaskContent.'');else echo('tags.php');echo('" class="btn btn-xl btn-warning mb-4 mr-4 ml-4">Voltar</a>
		<a href="tags.php" class="btn btn-xl btn-dark mb-4">Editar Tags</a>
		<br><p></p><button type="submit" class="btn btn-danger" name="add" value="3">Apagar</button>
		');}
		?>


	  <input type="hidden" class="form-control-plaintext" name="id" value="<?php echo($id)?>" readonly>
	  <input type="hidden" class="form-control-plaintext" name="idtask" value="<?php echo($idtaskContent)?>" readonly>

</form>



 <?php include("footer.php");?>
