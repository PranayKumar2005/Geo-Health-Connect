<?php
session_start();
include 'db_config.php';

// Check if user is logged in
if(!isset($_SESSION['user_name'])){ 
    header("Location: user_login.html"); 
    exit();
}

// Get filter value safely
$city_filter = isset($_POST['city']) ? $conn->real_escape_string($_POST['city']) : '';
$u_name = $_SESSION['user_name'];

// Fetch user details for the SOS feature
$user_query = $conn->prepare("SELECT blood_group FROM users WHERE name=?");
$user_query->bind_param("s", $u_name);
$user_query->execute();
$user_data = $user_query->get_result()->fetch_assoc();
$my_blood = $user_data['blood_group'] ?? 'Not Set';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard | HealthConnect</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f0f4f8; color: #333; }
        .nav { background: #28a745; color: white; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .container { padding: 30px; max-width: 1000px; margin: auto; }
        .emergency-box { background: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; border-top: 5px solid #dc3545; }
        .sos-btn { background: #dc3545; color: white; border: none; padding: 15px 30px; font-size: 18px; font-weight: bold; border-radius: 50px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4); }
        .sos-active { background: #ffc107 !important; color: #000 !important; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .status-dot { height: 12px; width: 12px; background-color: #25d366; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .status-dot.offline { background-color: #6c757d; }
        .filter-card { background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 20px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        select, input[type="submit"] { padding: 12px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; }
        input[type="submit"] { background: #28a745; color: white; border: none; cursor: pointer; font-weight: bold; }
        .doctor-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; border-left: 6px solid #28a745; transition: transform 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .doctor-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .wa-btn { background: #25d366; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; transition: 0.3s; }
        .wa-btn:hover { background: #128c7e; }
    </style>
</head>
<body>

<div class="nav">
    <h2>HealthConnect</h2>
    <div>
        <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span> | 
        <a href="profile.php" style="color: white; text-decoration: none; font-weight: bold;">My Profile</a> |
        <a href="logout.php" style="color: white; text-decoration: none; margin-left: 10px; font-weight: bold;">Logout</a>
    </div>
</div>

<div class="container">
    <div class="emergency-box">
        <h3>Need Urgent Help?</h3>
        <p>Activate SOS to share your live location and blood group with doctors.</p>
        <button id="sosBtn" class="sos-btn" onclick="toggleEmergency()">🚨 TRIGGER EMERGENCY</button>
        <p id="sosStatus" style="margin-top: 15px; font-weight: bold; color: #dc3545; display: none;">Emergency Mode: ACTIVE</p>
    </div>

    <div class="filter-card">
        <strong>Find Doctors in:</strong>
        <form method="POST" style="display: flex; gap: 10px;">
            <select name="city">
                <option value="">-- All Areas --</option>
                <?php
                $city_res = $conn->query("SELECT DISTINCT location FROM doctors WHERE location != '' ORDER BY location ASC");
                while($c_row = $city_res->fetch_assoc()) {
                    $loc = htmlspecialchars($c_row['location']);
                    $selected = ($city_filter == $loc) ? 'selected' : '';
                    echo "<option value='$loc' $selected>$loc</option>";
                }
                ?>
            </select>
            <input type="submit" value="Search Area">
        </form>
    </div>

    <div id="doctorList">
        <?php
        // Query update: Using LIKE for more flexible location matching
        $sql = "SELECT * FROM doctors";
        if($city_filter != '') { 
            $sql .= " WHERE location LIKE '%$city_filter%'"; 
        }
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $is_online = (strtolower($row['status']) == 'online'); 
                ?>
                <div class="doctor-card">
                    <div>
                        <h3 style="margin: 0;">
                            <span class="status-dot <?php echo $is_online ? '' : 'offline'; ?>"></span>
                            Dr. <?php echo htmlspecialchars($row['name']); ?>
                        </h3>
                        <p style="margin: 5px 0; color: #666;">
                            <strong><?php echo htmlspecialchars($row['specialization']); ?></strong><br>
                            <?php echo htmlspecialchars($row['hospital']); ?> | <?php echo htmlspecialchars($row['location']); ?>
                        </p>
                    </div>
                    
                    <button class="wa-btn" 
                            style="<?php echo !$is_online ? 'background: #6c757d; cursor: not-allowed;' : ''; ?>"
                            onclick="<?php echo $is_online ? "sendWA('".$row['phone']."', '".$row['name']."')" : "alert('Doctor is currently away.')"; ?>">
                        <?php echo $is_online ? "WhatsApp SOS" : "Offline"; ?>
                    </button>
                </div>
            <?php }
        } else {
            echo "<div style='text-align:center; padding:40px;'>No doctors found in <strong>$city_filter</strong>. Try a different area.</div>";
        } ?>
    </div>
</div>

<script>
    let isEmergency = false;
    let myBlood = "<?php echo $my_blood; ?>";
    let userLocation = "";

    function toggleEmergency() {
        const btn = document.getElementById('sosBtn');
        const status = document.getElementById('sosStatus');
        
        if(!isEmergency) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Fixed Maps Link format
                    userLocation = "https://www.google.com/maps?q=" + position.coords.latitude + "," + position.coords.longitude;
                    isEmergency = true;
                    btn.innerHTML = "⚠️ SOS ACTIVE";
                    btn.classList.add('sos-active');
                    status.style.display = "block";
                }, function() {
                    alert("Please enable GPS for Emergency Mode.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        } else {
            isEmergency = false;
            btn.innerHTML = "🚨 TRIGGER EMERGENCY";
            btn.classList.remove('sos-active');
            status.style.display = "none";
        }
    }

    function sendWA(phone, docName) {
        let msg = "Hello Dr. " + docName + ", I found your profile on HealthConnect and need a consultation.";
        if(isEmergency && userLocation !== "") {
            msg = "🚨 *URGENT MEDICAL EMERGENCY* 🚨\n\nI need immediate help!\n*Blood Group:* " + myBlood + "\n*My Live Location:* " + userLocation;
        }
        window.open("https://wa.me/" + phone + "?text=" + encodeURIComponent(msg), '_blank');
    }
</script>
</body>
</html>