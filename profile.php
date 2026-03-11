<?php
session_start();
// 1. Include centralized DB config
include 'db_config.php';

if(!isset($_SESSION['user_name'])){ 
    header("Location: user_login.html"); 
    exit(); 
}

$old_name = $_SESSION['user_name'];

// 2. Handle Profile Update
if(isset($_POST['update_profile'])) {
    $new_name = $conn->real_escape_string($_POST['full_name']);
    $new_age = (int)$_POST['age'];
    $new_blood = $conn->real_escape_string($_POST['blood_group']);
    $new_contact = $conn->real_escape_string($_POST['emergency_contact']);

    // Use Prepared Statement to update
    $update_stmt = $conn->prepare("UPDATE users SET name=?, age=?, blood_group=?, emergency_contact=? WHERE name=?");
    $update_stmt->bind_param("sisss", $new_name, $new_age, $new_blood, $new_contact, $old_name);
    
    if($update_stmt->execute()) {
        $_SESSION['user_name'] = $new_name; // Update session with new name
        echo "<script>alert('Medical Profile Updated Successfully!');</script>";
    } else {
        echo "<script>alert('Update failed: " . $conn->error . "');</script>";
    }
    $update_stmt->close();
}

// 3. Re-fetch fresh data using the session name
$current_name = $_SESSION['user_name'];
$fetch_stmt = $conn->prepare("SELECT * FROM users WHERE name=?");
$fetch_stmt->bind_param("s", $current_name);
$fetch_stmt->execute();
$user = $fetch_stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical ID Card | HealthConnect</title>
    <style>
        :root { --primary: #28a745; --success: #28a745; --danger: #dc3545; --dark: #2c3e50; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #eef2f7; 
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; margin: 0;
        }

        .medical-card { 
            background: white; width: 420px; border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; 
            border: 1px solid #dbe3eb; position: relative;
        }

        .card-accent { height: 10px; background: var(--primary); }

        .card-header { 
            padding: 30px 20px; text-align: center; background: #fdfdfd; 
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-avatar {
            width: 80px; height: 80px; background: var(--primary); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px; font-size: 32px; font-weight: bold;
            box-shadow: 0 8px 15px rgba(40, 167, 69, 0.2);
        }

        .badge {
            display: inline-block; padding: 4px 12px; border-radius: 20px;
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            background: #e8f5e9; color: var(--success); margin-top: 5px;
        }

        .card-body { padding: 25px 30px; background: #fff; }

        .form-group { margin-bottom: 15px; }

        label { 
            display: block; font-size: 11px; font-weight: 700; color: #adb5bd;
            text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px;
        }

        input, select { 
            width: 100%; padding: 12px; border: 2px solid #f1f3f5;
            border-radius: 10px; font-size: 15px; color: var(--dark);
            transition: all 0.3s ease; box-sizing: border-box;
            background: #f8f9fa;
        }

        input:focus { border-color: var(--primary); background: #fff; outline: none; }

        .btn-group { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }

        .btn {
            padding: 12px; border-radius: 10px; font-weight: 700; font-size: 14px;
            cursor: pointer; text-align: center; text-decoration: none; transition: 0.3s;
            border: none;
        }

        .btn-save { background: var(--primary); color: white; }
        .btn-save:hover { background: #218838; }

        .btn-back { background: #f1f3f5; color: var(--dark); }
    </style>
</head>
<body>

<div class="medical-card">
    <div class="card-accent"></div>
    <div class="card-header">
        <div class="profile-avatar"><?php echo strtoupper(substr($user['name'] ?? 'P', 0, 1)); ?></div>
        <h2 style="margin: 0; color: var(--dark);"><?php echo htmlspecialchars($user['name']); ?></h2>
        <div class="badge">Verified Patient</div>
    </div>

    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Age</label>
                    <input type="number" name="age" value="<?php echo $user['age']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Blood Group</label>
                    <select name="blood_group">
                        <?php 
                        $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                        foreach($groups as $g) {
                            $sel = ($user['blood_group'] == $g) ? 'selected' : '';
                            echo "<option value='$g' $sel>$g</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Emergency Contact No.</label>
                <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars($user['emergency_contact'] ?? ''); ?>" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="update_profile" class="btn btn-save">Update Profile</button>
                <a href="user_dashboard.php" class="btn btn-back">Return to Dashboard</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>