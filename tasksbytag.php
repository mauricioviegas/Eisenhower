<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');
	$error = "";
	
	if (array_key_exists ("tag", $_GET)) $tagContent = $_GET['tag'];
	else $tagContent = "";
	if (array_key_exists ("search", $_GET)) $searchContent = $_GET['search'];
	else $searchContent = "";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) $_SESSION['id'] = $_COOKIE['id'];
	
	if (array_key_exists("id", $_SESSION)) {
		include("../torei/connection.php");
		
		$nameTagTitle = "";
		if ($tagContent != "") {
			$query = mysqli_query($link, "SELECT name FROM `eisenhower_tags` WHERE  `id` = '".$tagContent."' AND (`id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." OR `id_user` IS NULL) AND active = '1' LIMIT 1") or die ("Não foi possível carregar.");
			$row = mysqli_fetch_array($query);
			$nameTagTitle = $row['name'];
		}
		
	}
	else header("Location: ../torei/index.php?p=ME");
	
include("header.php");
include("navigation.php");

?>
<!-- Call to Action -->
  <section class="content-section bg-primary text-white">
    <div class="container text-center">
      <div class="content-section-heading text-center">
        <h3 class="text-secondary mb-0">Edite</h3>
        <h2 class="mb-5">TAREFAS POR TAG<?php echo (" - ".$nameTagTitle);?></h2>
      </div>
	  <form>
		<div class="form-group">
		  <label>Tag</label>
		  <select class="form-control" name="tag">
		  <option value=""></option>
			<?php			
			$query2 = $query = mysqli_query($link, "SELECT id, name FROM `eisenhower_tags` WHERE (`id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." OR `id_user` IS NULL) AND active = '1' ORDER BY `name`") or die ("Não foi possível carregar.");
			$count = mysqli_num_rows($query);
				while ($row = mysqli_fetch_array($query2)){
					$idTag= $row['id'];
					$nameTag = $row['name'];
					
					if ($idTag == $tagContent) $nameTagTitle = $nameTag;
					
					echo ('<option value="'.$idTag.'">'.$nameTag.'</option>');

				}
			?>
		  </select>
		</div> 
			<button type="submit" class="btn btn-xl btn-light mb-4">Buscar</button>
			
			
			
	  </form>
	  	  
    </div>
  </section>
  

  <!-- Portfolio -->
  <section class="content-section">
    <div class="container">
      <div class="row no-gutters">
        
	    <div class="col-lg-12 mb-4" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Faça primeiro</div>
                <p class="mb-4">Urgente e importante.</p>
				
				<?php
				$quadrantContent = 1;
				include("tasklistbytag.php");
				?>

              </div>
            </div>
          
          </a>
        </div>
		
		<div class="col-lg-12 mb-4">
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Agende</div>
                <p class="mb-4">Menos urgente, mas importante.</p>
				
				<?php
				$quadrantContent = 2;
				include("tasklistbytag.php");
				?>

              </div>
            </div>
          
          </a>
        </div>
		
		<div class="col-lg-12 mb-4">
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Delegue</div>
                <p class="mb-4">Urgente, mas menos importante.</p>
				
				<?php
				$quadrantContent = 3;
				include("tasklistbytag.php");
				?>

              </div>
            </div>
          
          </a>
        </div>
		
		<div class="col-lg-12 mb-4" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Não faça</div>
                <p class="mb-4">Não é urgente nem importante.</p>
				
				<?php
				$quadrantContent = 4;
				include("tasklistbytag.php");
				?>

              </div>
            </div>
          
          </a>
        </div>
		
		<div class="col-lg-12" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Sem classifição</div>
                <p class="mb-4">Classifique estas atividades sobre sua urgência e importância.</p>
				
				<?php
				$quadrantContent = 0;
				include("tasklistbytag.php");
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
      <a href="add_task.php?q=<?php echo($quadrantContent)?>" class="btn btn-xl btn-success mb-4 mr-2">Acrescentar nova tarefa</a>
	  <a href="done.php" class="btn btn-xl btn-dark mb-4 ml-2">Tarefas concluídas</a>
	  
    </div>
  </section>



<?php include("footer.php");?>