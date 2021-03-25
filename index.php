<?php
require_once('config/db.php');
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: /inotes/signup.php');
}
// $error = null;
// $msg = null;
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

if (isset($_SESSION['msg'])) {
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
}
$uid = $_SESSION['user'];
$sql = "SELECT * FROM notes WHERE userid='$uid'";
$res = mysqli_query($GLOBALS['db'],$sql);

$sql = "SELECT label, count(label) as count FROM notes WHERE userid='$uid' Group By label";
$labels = mysqli_query($GLOBALS['db'],$sql);
// $row = mysqli_fetch_assoc($res2);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/style.css">
  <script src="js/lib.js"></script>
  <script src="js/script.js"></script>

  <title>iNotes</title>
</head>

<body class="bg-light">
  <header>
    <!-- nav bar -->
    <?php include_once('layout/_nav.php') ?>
    <!-- .nav bar -->
    <div class="mask mask-gradient">
      <div class="container mt-5">
        <div class="row">
          <div class="col-md-7 mt-5 text-white">
            <h1>Welcome, <?php if (isset($_SESSION['name'])) echo $_SESSION['name']; ?></h1>
            <p>Save notes.. And thats all we want. Keeping it simple, iNotes has all the tools (only necessary one's ðŸ˜‰) you need at one place</p>
          </div>
          <div class="col-md-4">
            <div class="card mt-5">
              <div class="card-body">
                <h5 class="card-title">Add new Note</h5>
                <hr>
                <?php if (isset($error)) : ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>
                <?php if (isset($msg)) : ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>
                <p class="card-text ">
                <form class="row g-3 needs-validation" action="/inotes/addNotes.php" method="POST" validate>
                  <div class="form-floating">
                    <input type="text" name="note_title" class="form-control" id="validationCustom01" placeholder="Title" required>
                    <label for="validationCustom01">Note Title</label>
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                  </div>

                  <div class="form-floating">
                    <input type="text" max="10" min="1" class="form-control" name="note_label" id="inputQuizDuration" placeholder="Label">
                    <label for="inputQuizDuration">Label</label>
                  </div>
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="note_body"></textarea>
                    <label for="floatingTextarea2">Note Body</label>
                  </div>
                  <!-- <div class="mb-3 form-check">
                        <input type="checkbox" name="agree" class="form-check-input" id="exampleCheck1" required>
                        <label class="form-check-label" for="exampleCheck1">I'll be honest during Quiz</label>
                      </div> -->


                  <button class="btn btn-primary" type="submit">Save</button>

                </form>
                </p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-9 mb-3">
        <article>
          <h4>Your Notes</h4>
          <hr class="w-75">
          <ul class="list-group">
            <?php while($note = mysqli_fetch_assoc($res)): ?>
            <li class="list-group-item">
              <div class="row">
                <div class="note col-sm-8">
                  <h5><?=$note['title']?><span class="ms-2 badge bg-primary"><?=$note['label']?></span></h5>
                  <p><?=$note['body']?></p>
                  <input type="hidden" name="id" value="<?=$note['id']?>">
                </div>
                <div class="col-sm-4 text-end">
                  
                  <!-- <button class="btn btn-outline-primary btn-sm me-2">
                    Edit
                  </button> -->
                  <button class="btn btn-danger btn-sm" onclick="del(this)">Delete</button>
                </div>
              </div>
            </li>
            <?php endwhile?>

          </ul>
        </article>
      </div>
      <div class="col-md-3">
        <aside>
          <h4>All Labels</h4>
          <hr class="w-75">
          <ol class="list-group list-group-numbered">
            <?php while($c = mysqli_fetch_assoc($labels)):?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">

                <a class="" href="#"><?=$c['label']?></a>
              </div>
              <span class="badge bg-primary rounded-pill"><?=$c['count']?></span>
            </li>
            <?php endwhile?>
          </ol>
        </aside>
      </div>
    </div>
  </div>
  <!-- data-bs-toggle="modal" data-bs-target="#exampleModal" -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="deletenote.php" method="post">
      
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="modalBody" class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
      </form>
    </div>
  </div>
</div>

  <?php include_once('layout/_footer.php') ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  <script>
    function del(e){
      var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
      var modalBody = document.getElementById('modalBody');
      modalBody.innerHTML = e.parentElement.parentElement.querySelector('.note').innerHTML;
      myModal.show();
    }
  </script>
</body>

</html>