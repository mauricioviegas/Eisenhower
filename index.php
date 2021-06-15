<?php

    session_start();
	date_default_timezone_set('America/Sao_Paulo');
	$error = "";

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
        <h3 class="text-secondary mb-0">TAREFAS</h3>
        <h2 class="mb-5">Matriz de Eisenhower</h2>
      </div>
      <div class="row no-gutters">
        <div class="col-lg-6" id="do_first">
          <a class="portfolio-item" href="tasks.php?q=1">
            <div class="caption">
              <div class="caption-content">
                <div class="h2 mb-3">Faça primeiro</div>
				<p>
				<?php
					$quadrantContent = 1;				
					include("tasklistindex.php");	
				?>
              </p>
			  </div>
            </div>
            <img class="img-fluid" src="img/portfolio-4.jpg" alt="">
          </a>
        </div>
        <div class="col-lg-6" id="schedule">
          <a class="portfolio-item" href="tasks.php?q=2">
            <div class="caption">
              <div class="caption-content">
                <div class="h2 mb-3">Agende</div>
				<p>
				<?php
					$quadrantContent = 2;				
					include("tasklistindex.php");	
				?>
              </p>
              </div>
            </div>
            <img class="img-fluid" src="img/portfolio-1.jpg" alt="">
          </a>
        </div>
        <div class="col-lg-6" id="delegate">
          <a class="portfolio-item" href="tasks.php?q=3">
            <div class="caption">
              <div class="caption-content">
                <div class="h2 mb-3">Delegue</div>
				<p>
				<?php
					$quadrantContent = 3;				
					include("tasklistindex.php");	
				?>
              </p>
              </div>
            </div>
            <img class="img-fluid" src="img/portfolio-3.jpg" alt="">
          </a>
        </div>
        <div class="col-lg-6" id="dont_do">
          <a class="portfolio-item" href="tasks.php?q=4">
            <div class="caption">
              <div class="caption-content">
                <div class="h2 mb-3">Não faça</div>
				<p>
				<?php
					$quadrantContent = 4;				
					include("tasklistindex.php");	
				?>
              </p>
              </div>
            </div>
            <img class="img-fluid" src="img/portfolio-2.jpg" alt="">
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="content-section bg-primary text-white">
    <div class="container text-center">
	  <h2 class="mb-4">Acrescentar tarefa?</h2>
      <a href="add_task.php" class="btn btn-xl btn-success mb-3">Acrescentar nova tarefa</a>
      <a href="tasks.php?q=0" class="btn btn-xl btn-warning mb-3 ml-4 mr-4">Mostrar tarefas não classificadas</a>
	  <a href="done.php" class="btn btn-xl btn-dark mb-3">Tarefas concluídas</a>
	  <p></p>
      <h2 class="mb-4">Outras opções</h2>
      <a href="tasksbytag.php" class="btn btn-xl btn-light">Tarefas por Tags!</a>
    </div>
  </section>

 
<?php include("footer.php");?>