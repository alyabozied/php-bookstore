<?php
require_once __DIR__ . '/../config.php';
function checkLoginStatus() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_SESSION["user"]) || !isset($_SESSION["token"])) {
        return false;
    }

    $user = $_SESSION["user"];
    $user_token = $_SESSION["token"];
    $sql = "SELECT * FROM user_tokens as t ,users as u WHERE t.token = ? and u.id = t.user_id and u.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_token, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

function logout() {
    session_start();
    if (isset($_SESSION['user']) && isset($_SESSION['token'])) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ? AND user_id = (SELECT id FROM users WHERE username = ?)");
        $stmt->bind_param("ss", $_SESSION['token'], $_SESSION['user']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    session_destroy();
}

function login($username, $password) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("SELECT id , password ,user_type FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows() !==1){
        $stmt->close();
        return ['status'=>false , 'message'=>"User not found."];
    }
    $stmt->bind_result($user_id, $hashed_password,$user_type);
    $stmt->fetch();
    $stmt->close();
    if(!password_verify($password, $hashed_password)){
        return ['status'=>false , 'message'=>"Invalid password."];
    }
    $_SESSION["user"] = $username;
    $_SESSION["token"] = createToken($user_id); 
    $_SESSION["user_type"] = $user_type; 
    return ['status'=>true , 'message'=>"Login successful."];    
}


function register($username, $password) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(IsUsernameTaken($username)){
        return ['status'=>false , 'message'=>"Username already taken."];
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt = $conn->prepare("INSERT INTO users (username, password,user_type) VALUES (?, ? , 'user')");
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        $user_id = $conn->insert_id; // Get the last inserted user ID
        $_SESSION["user"] = $username;
        $_SESSION["token"] = createToken($user_id);
        $_SESSION["user_type"] = 'user';
        return ['status'=>true , 'message'=>"Registered successfully."];
    }   
    
    return ['status'=>false , 'message'=>$conn->error];
    
}

function IsUsernameTaken($username) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();
    return $stmt_check->num_rows > 0;
}
function createToken($user_id) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $token = bin2hex(random_bytes(32)); // Generate a bearer token
    $stmt = $conn->prepare("INSERT INTO user_tokens (user_id, token) VALUES (?, ?) ");
    $stmt->bind_param("ss", $user_id, $token);
    if ($stmt->execute()) 
        return $token;
    
    return null;
    
}
function getUserType($username) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("SELECT user_type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_type);
    $stmt->fetch();
    $stmt->close();
    return $user_type;
}
