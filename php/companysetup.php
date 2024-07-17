<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';

$company_name = $phone = $email = $location = $logo = "";
$company_nameErr = $phoneErr = $emailErr = $locationErr = $logoErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Company_name"])) {
        $company_nameErr = "Company name is required";
    } else {
        $company_name = test_input($_POST["Company_name"]);
    }

    if (empty($_POST["Phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["Phone"]);
    }

    if (empty($_POST["Email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["Email"]);
    }

    if (empty($_POST["Location"])) {
        $locationErr = "Location is required";
    } else {
        $location = test_input($_POST["Location"]);
    }

    if ($_FILES["logo"]["error"] == UPLOAD_ERR_NO_FILE) {
        $logoErr = "Logo is required";
    } else {
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES["logo"]["tmp_name"]);
        $error = !in_array($detectedType, $allowedTypes);

        if ($error) {
            $logoErr = "Only image files are allowed";
        } else {
            $logo = file_get_contents($_FILES["logo"]["tmp_name"]);
        }
    }

    // INSERTING DATA TO COMPANY TABLE
    if (empty($company_nameErr) && empty($phoneErr) && empty($emailErr) && empty($locationErr) && empty($logoErr)) {
        $stmt = $conn->prepare("INSERT INTO company (company_name, phone, email, location, logo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $company_name, $phone, $email, $location, $logo);

        if ($stmt->execute()) {
            echo "New company record created successfully";

        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
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

