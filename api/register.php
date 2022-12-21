<?php
include_once './config/database.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$firstName = '';
$lastName = '';
$email = '';
$password = '';
$conn = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$firstName = $data->firstname;
$lastName = $data->lastname;
$email = $data->email;
$password = $data->password;

$table_name = 'users';

// Check if user email exists
$email_query = "SELECT email FROM `users` WHERE email = ? LIMIT 0,1";

$stmt2 = $conn->prepare($email_query);
$stmt2->bindParam(1, $email);
$stmt2->execute();
$num = $stmt2->rowCount();

if($num > 0){

    http_response_code(401);
    echo json_encode(['status'=>0, 'message'=>'User email already exists']);
    exit;

}



$query = "INSERT INTO " . $table_name . "
                SET firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password";

$stmt = $conn->prepare($query);

$stmt->bindParam(':firstname', $firstName);
$stmt->bindParam(':lastname', $lastName);
$stmt->bindParam(':email', $email);

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt->bindParam(':password', $password_hash);


if($stmt->execute()){

    http_response_code(200);
    echo json_encode(array("message" => "User was successfully registered."));
}
else{
    http_response_code(400);

    echo json_encode(array("message" => "Unable to register the user."));
}
?>