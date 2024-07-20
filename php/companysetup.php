<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';

// Initialize variables
$company_name = $phone = $email = $location = $logo = "";
$company_nameErr = $phoneErr = $emailErr = $locationErr = $logoErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $company_name = test_input($_POST["Company_name"]);
    $phone = test_input($_POST["Phone"]);
    $email = test_input($_POST["Email"]);
    $location = test_input($_POST["Location"]);

    // Handle file upload
    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] == UPLOAD_ERR_OK) {
        $logo_tmp_name = $_FILES["logo"]["tmp_name"];
        $logo_name = basename($_FILES["logo"]["name"]);
        $logo_target = "../uploads/" . $logo_name;
        
        // Move the file to the target directory
        if (move_uploaded_file($logo_tmp_name, $logo_target)) {
            $logo = file_get_contents($logo_target); // Get file contents to store in BLOB
        } else {
            $logoErr = "Failed to upload logo.";
        }
    }

    // Validate and check for errors
    if (empty($company_name) || empty($phone) || empty($email) || empty($location)) {
        // Handle empty fields (add error messages if needed)
    }

    // Inserting data into company table
    if (empty($company_nameErr) && empty($phoneErr) && empty($emailErr) && empty($locationErr) && empty($logoErr)) {
        $stmt = $conn->prepare("INSERT INTO company (company_name, phone, email, location, logo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $company_name, $phone, $email, $location, $logo);

        // Reconnect on failure
        if (!$stmt->execute()) {
            if ($conn->errno == 2006) { // MySQL server has gone away error number
                $conn->close();
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                // Retry the statement execution
                $stmt = $conn->prepare("INSERT INTO company (company_name, phone, email, location, logo) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $company_name, $phone, $email, $location, $logo);
                if ($stmt->execute()) {
                    echo "New company record created successfully";
                    // Redirect to adminsetup.php after successful insertion
                    header("Location: adminsetup.php");
                    exit(); // Ensure no more output is sent
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "New company record created successfully";
            // Redirect to adminsetup.php after successful insertion
            header("Location: adminsetup.php");
            exit(); // Ensure no more output is sent
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company setup</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Company setup</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div id="form-group">
                    <input type="text" name="Company_name" id="Company_name" placeholder=" " required>
                    <label for="Company_name">Company name</label>
                </div>
                <div id="form-group">
                    <input type="tel" name="Phone" id="Phone" placeholder=" " required>
                    <label for="Phone">Phone</label>
                </div>
                <div id="form-group">
                    <input type="email" name="Email" id="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                </div>
                <div id="form-group">
                    <input type="text" name="Location" id="Location" placeholder=" " required>
                    <label for="Location">Location</label>
                </div>
                <div id="form-group">
                    <input type="file" name="logo" id="logo">
                    <label for="logo">Choose logo</label>
                </div>
                <div id="btn">
                    <button type="submit">SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
