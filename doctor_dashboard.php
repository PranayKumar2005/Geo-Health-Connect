<?php
session_start();
include 'db_config.php'; // Include the centralized connection

// Security check: if not logged in, send back to login page
if(!isset($_SESSION['doctor_email'])){ 
    header("Location: doctor_login.html"); 
    exit();
}

$email = $_SESSION['doctor_email'];

// Handle Status Update Logic
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $update_sql = $conn->prepare("UPDATE doctors SET status=? WHERE email=?");
    $update_sql->bind_param("ss", $new_status, $email);
    $update_sql->execute();
}

// Fetch current doctor details from database
$query = $conn->prepare("SELECT * FROM doctors WHERE email=?");
$query->bind_param("s", $email);
$query->execute();
$doctor = $query->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Panel | HealthConnect</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #28a745; color: white; position: fixed; padding: 30px 20px; box-sizing: border-box; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .content { margin-left: 260px; padding: 50px; width: 100%; }
        
        .profile-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 500px; }
        .status-badge { padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; text-transform: uppercase; }
        .status-online { background: #d4edda; color: #155724; }
        .status-offline { background: #f8d7da; color: #721c24; }
        
        .toggle-form { margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; }
        select { padding: 10px; border-radius: 5px; border: 1px solid #ddd; width: 150px; margin-right: 10px; }
        .btn-update { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        
        .logout-link { display: inline-block; margin-top: 40px; color: #fff; text-decoration: none; font-weight: bold; border: 1px solid #fff; padding: 10px 20px; border-radius: 5px; transition: 0.3s; }
        .logout-link:hover { background: #fff; color: #28a745; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="margin-bottom: 5px;">HealthConnect</h2>
        <p style="font-size: 14px; opacity: 0.8;">Medical Provider Portal</p>
        <hr style="border: 0.5px solid rgba(255,255,255,0.2); margin: 20px 0;">
        <p>Welcome, <br><span style="font-size: 20px; font-weight: bold;">Dr. <?php echo htmlspecialchars($doctor['name']); ?></span></p>
        
        <a href="doctor_logout.php" class="logout-link">Logout</a>
    </div>

    <div class="content">
        <h1>Doctor Dashboard</h1>
        
        <div class="profile-card">
            <h3 style="margin-top: 0;">Clinic Status</h3>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($doctor['location']); ?> (Ghatkesar)</p>
            <p><strong>Hospital:</strong> <?php echo htmlspecialchars($doctor['hospital']); ?></p>
            
            <p>Current Status: 
                <span class="status-badge <?php echo ($doctor['status'] == 'Online') ? 'status-online' : 'status-offline'; ?>">
                    ● <?php echo $doctor['status']; ?>
                </span>
            </p>

            <div class="toggle-form">
                <p style="font-size: 14px; color: #666;">Change your availability for patients in <strong><?php echo htmlspecialchars($doctor['location']); ?></strong>:</p>
                <form method="POST">
                    <select name="status">
                        <option value="Online" <?php if($doctor['status'] == 'Online') echo 'selected'; ?>>Online</option>
                        <option value="Offline" <?php if($doctor['status'] == 'Offline') echo 'selected'; ?>>Offline</option>
                    </select>
                    <button type="submit" name="update_status" class="btn-update">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>