<!doctype html>

<?php
include_once "functions.php";
include_once "DBManager.php";


$page = getPageName();

$db_manager = new DBManager($page);
$db_manager->getData();

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="style.css">

<script src="request.js"></script>
<script>
  const PAGE = "<?= $page; ?>";
  const TASKS = JSON.parse('<?= $db_manager->getTasksInverseJson(); ?>');
</script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">TODO_LIST</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link <?= isPage('design'); ?> " href="?page=design">Design</a>
        <a class="nav-link <?= isPage('code'); ?> " href="?page=code">Code</a>
      </div>
    </div>
  </div>
</nav>


<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col align-self-center">
      <form action="api.php?page=<?= $page; ?>" onsubmit="request.post(event, this, tasks_manager.addNew);">
        <div class="input-group mb-3">
          <input name="task" type="text" class="form-control" placeholder="New task">
          <button class="btn btn-success" type="submit" id="button-addon2">Add</button>
        </div>
      </form>
    </div>

  </div>
  <div class="row justify-content-center">
    <ul id="task_list" class="col-lg-6 col align-self-center todo">
      <li class="list-group-item todo__item template todo__item--initial">
        <input type="checkbox" class="done" onchange="tasks_manager.taskDone.bind(this)();">
        <span class="todo__description"></span>
        <div class="todo__options">
          <a href="#" class="todo__save" title="save"><i class="bi bi-save"></i></a>
          <a href="#" class="todo__cancel" title="cancel"><i class="bi bi-backspace"></i></a>
          <a href="#" class="todo__edit" title="edit"><i class="bi bi-pencil-square"></i></a>
          <a href="#" class="todo__delete" title="delete" onclick="tasks_manager.delete.bind(this)(event);"><i class="bi bi-x-square"></i></a>
        </div>
      </li>
    </ul>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="script.js"></script>


