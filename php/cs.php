<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../functions/santz.php';
include '../db_connection.php';
include '../functions/sanitize.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = test_input($_POST['Company_name']);
    $phone = test_input($_POST['Phone']);
    $email = test_input($_POST['Email']);
    $location = test_input($_POST['Location']);
    $logo = '';

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["logo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["logo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["logo"]["name"]) . " has been uploaded.";
            $logo = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $sql = "INSERT INTO company (company_name, phone, email, location, logo) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $company_name, $phone, $email, $location, $logo);
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
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
    <script src="typewriter.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Company setup</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="Company_name" id="Company_name" placeholder=" " required>
                    <label for="Company_name">Company name</label>
                    <span class="error"><?php echo $company_nameErr; ?></span>
                </div>
                <div class="form-group">
                    <input type="tel" name="Phone" id="Phone" placeholder=" " required>
                    <label for="Phone">Phone</label>
                    <span class="error"><?php echo $phoneErr; ?></span>
                </div>
                <div class="form-group">
                    <input type="email" name="Email" id="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>
                <div class="form-group">
                    <input type="text" name="Location" id="Location" placeholder=" " required>
                    <label for="Location">Location</label>
                    <span class="error"><?php echo $locationErr; ?></span>
                </div>
                <div class="form-group">
                    <input type="file" name="logo" id="logo">
                    <label for="logo">Choose logo</label>
                </div>
                <div id="btn">
                    <button type="submit" name="submit">SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
