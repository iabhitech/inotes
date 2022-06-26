<?php
require_once('config/db.php');
function isHas($email)
{
  // require_once('config/db.php');
  $sql = "SELECT * FROM users WHERE email='$email'";
  $res = mysqli_query($GLOBALS['db'], $sql);
  $num = mysqli_num_rows($res);

  if ($num > 0) {
    return true;
  }
  return false;
}
function isVerified($email)
{
  $sql = "SELECT * FROM users WHERE email='$email'";
  $res = mysqli_fetch_assoc(mysqli_query($GLOBALS['db'], $sql));
  if ($res['isVerified'] == 1) {
    return true;
  }
  return false;
}

function sendOTP($email)
{
  $otp = rand(100000, 999999);
  session_start();
  $_SESSION['otp'] = $otp;

  #send email otp
  require_once 'config/mail.php';
  $subject = "iNotes - Verify OTP";
  $body = "Thanks for Register with iNotes\nYour OTP is " . $otp;
  $to = $email;

  $res = mail($to, $subject, $body);

  // $body = "Thanks for Register with iNotes\nYour OTP is " . $otp;
  // $subject = "iNotes - Verify OTP";
  // $headers = "From: iabhiteck@gmail.com";
  if ($res) {
    return true;
  }
  return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  session_start();
  if (!isset($_SESSION['user_signup'])) {
    header('Location: login.php');
  }
  $email = $_SESSION['user_signup'];

  if (!isHas($email)) {
    header('Location: signup.php');
  }
  if (isVerified($email)) {
    header('Location: login.php');
  }

  if (sendOTP($email)) {
    $msg = "OTP has been sent on  your Email.";
  } else {
    $error = "Unable to send OTP! <a href='?resend'>Resend</a>";
  }
}
session_start();
$email = $_SESSION['user_signup'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['otp'])){
    $userOtp = $_POST['otp'];
    if($_SESSION['otp'] == $userOtp){
      $sql = "UPDATE `users` SET `isVerified` = '1' WHERE `users`.`email` = '$email'";
      if(mysqli_query($GLOBALS['db'], $sql)){
        $msg = "Your Account is Verified! Login Now";
        // header("Location: login");
      }
    }
    else{
      $error = "Invaild OTP";
    }
  }
}



mysqli_close($GLOBALS['db']);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <script src="js/lib.js"></script>
  <script src="js/script.js"></script>

  <title>iNotes - SignUp</title>
</head>

<body class="bg-light">
  <header style="height: 100vh;">
    <!-- nav bar -->
    <?php include_once('layout/_nav.php') ?>
    <!-- .nav bar -->
    <div class="mask mask-gradient">
      <div class="container mt-5">
        <div class="d-flex">
          <div class="mx-auto w-100" style="max-width: 460px;">
            <div class="card mt-5">
              <div class="card-body">
                <form action="verify.php" method="POST">
                  <div class="text-center p-2 mt-2">
                    <i class="fas fa-3x text-primary fa-book-open"></i>
                    <div>iNotes - Verify Email</div>
                  </div>
                  <p>Hello User,</p>
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
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $email ?>" aria-label="readonly email" readonly>
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Enter OTP</label>
                    <input type="password" name="otp" class="form-control" id="exampleInputPassword1">
                  </div>

                  <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div class="container mt-3">

  </div>


  <?php include_once('layout/_footer.php') ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>

</html>