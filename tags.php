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
        <h3 class="text-secondary mb-0">Edite</h3>
        <h2 class="mb-5">TAGS</h2>
      </div>
      <div class="row no-gutters">
        
	    <div class="col-lg-12" >
          <a class="portfolio-item" href="index.php">
            <div class="caption">
              <div class="caption-content">
                <div class="h2">Para o quê etiquetas?</div>
                <p class="mb-4">Classifique as tarefas em projetos, iniciativas ou objetivos diferentes.</p>

				
				<?php			
				$query2 = $query = mysqli_query($link, "SELECT id, name FROM `eisenhower_tags` WHERE `id_user` = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND active = '1'") or die ("Não foi possível carregar.");
				$count = mysqli_num_rows($query);
				if($count != 0){
					while ($row = mysqli_fetch_array($query2)){
						$idTag = $row['id'];
						$nameTag = $row['name'];
												
						if ($idTag != 0) echo ('<a href="add_tag.php?id='.$idTag.'" class="list-group-item list-group-item-action">'.$nameTag.'</a>');

					}
				}
				else echo ('<a href="add_tag.php" class="list-group-item list-group-item-action">Não há tags neste momento.<br>Acrescente uma tag clicando aqui.</a>');
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
      <a href="add_tag.php" class="btn btn-xl btn-success mb-3">Acrescentar nova tag</a>
      <a href="tasks.php?q=0" class="btn btn-xl btn-warning mb-3 ml-4">Mostrar tarefas classificadas por tag</a>
    </div>
  </section>
  
  

 <?php include("footer.php");?>
