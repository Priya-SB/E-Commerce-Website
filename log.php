<!--PHP for Signing in-->
<?php
	define('DB_SERVER', 'localhost'); //Connect to database
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'db_registration');
	 
	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($link === false)
	{
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}

	// Initializing the session
	session_start();
	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	  header("location: index.php");
	  exit;
	}
	 
	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = "";
	 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
	 
		// Check if username is empty
		if(empty(trim($_POST["name"])))
		{
			$username_err = "Please enter username.";
		} else
		{
			$username = trim($_POST["name"]);
		}
		
		// Check if password is empty
		if(empty(trim($_POST["password"])))
		{
			$password_err = "Please enter your password.";
		} else
		{
			$password = trim($_POST["password"]);
		}
		
		// Validate credentials
		if(empty($username_err) && empty($password_err))
		{
			// Prepare a select statement
			$sql = "SELECT id, name, password FROM register WHERE name = ? AND password = ?";
			
			if($stmt = mysqli_prepare($link, $sql))
			{
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
	 
				// Set parameters
				$param_username = $username;
				$param_password = $password;
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt))
				{
					// Store result
					mysqli_stmt_store_result($stmt);
					
					// Check if username and password match
					if(mysqli_stmt_num_rows($stmt) == 1) 
					{      
						// start a new session		
						session_start();
						// Store data in session variables
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["username"] = $username;                            
						// Redirect user to shop now page
						header("location: index.php");
					} 
					else
					{
						// Display an error message if credentials are invalid
						$username_err = "Invalid Credentials!";
						echo "<script type='text/javascript'>alert('$username_err'); </script>";
					}
				} 
				else
				{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			// Close statement
			mysqli_stmt_close($stmt);
		}
		// Close connection
		mysqli_close($link);
	}
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>Furniture WebStore</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> <! for navigation bar>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script> <!for drop down>
		<link rel="stylesheet" type="text/css" href="page1style.css">
	</head>


<body>
	<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="http://designextracts.com/index.html">
			<img src="images\logo.png" height="40" width="180" alt="logo"><br>
		</a>

		<a class="navbar-brand" href="#"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="home.html">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="contact.html">Contact Us</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="index.php">Shop Now</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="order.html" >Your Orders</a>
				</li>
				
				<li class="nav-item dropdown active">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">User
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						 <a class="dropdown-item" href="s.html">Sign Up</a>
						 <a class="dropdown-item" href="log.php">Sign In</a>
						 <a class="dropdown-item" href="logout.php">Sign Out</a>
					</div>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0">
				 <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
				 <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
	</div>
	</nav >
	<br/><br/><br/>
	
	<h2>Login</h2>                                                                    <!--Log In Form-->
	<p>Please fill in your credentials to login.</p>
	<div class="form1">
		<form action="log.php" method="post">
			<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
				<label>Name</label>
				<input type="text" name="name" class="form-control" value="<?php echo $username; ?>">
				<span class="help-block"><?php echo $username_err; ?></span>
			</div>    
			<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
				<label>Password</label>
				<input type="password" name="password" class="form-control">
				<span class="help-block"><?php echo $password_err; ?></span>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Login">
			</div>
			<p>Don't have an account? <a href="s.html">Sign up now</a>.</p>
		</form>
	</div>    
</body>
</html>