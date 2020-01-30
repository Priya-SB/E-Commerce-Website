<!--PHP for Signing up-->
<?php
$name = $_POST['name'];
$password = $_POST['password'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$pincode = $_POST['pincode'];
$phone = $_POST['phone'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];

if (!empty($name) || !empty($password) || !empty($dob) || !empty($email) || !empty($pincode) || !empty($phone) || !empty($address1) || !empty($address2) || !empty($city)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "db_registration";
    
	//create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    if (mysqli_connect_error()) 
	{
     die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } 
	else {
     $SELECT = "SELECT email From register Where email = ? Limit 1";
     $INSERT = "INSERT Into register (name,password,dob,email,phone,address1,address2,city,pincode) values('$name', '$password', '$dob', '$email', '$phone', '$address1', '$address2', '$city', '$pincode')";
     
	 //Prepare statement
     $stmt = $conn->prepare($SELECT);
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $stmt->bind_result($email);
     $stmt->store_result();
     $rnum = $stmt->num_rows;
     
	 if ($rnum==0) 
	 {
      $stmt->close();
      $stmt = $conn->prepare($INSERT);
      $stmt->bind_param("sssssssss", $name, $password, $dob, $email, $phone, $address1, $address2, $city, $pincode);
      $stmt->execute();
      header("location: log.php");
     } 
	 else 
	 {
		?>
		<script type="text/javascript">
		alert("Someone has already registered using this email");
		window.location.href = "s.html";
		</script>
		<?php
     }
     $stmt->close();
     $conn->close();
    }
} 

else 
{
 echo "All fields are required";
 die();
}
?>