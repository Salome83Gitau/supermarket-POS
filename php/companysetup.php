<?php
session_start();
include '../php/dbconnection.php';
include '../functions/sanitize.php';

$company_name = $phone = $email = $location = $logo = "";
$company_nameErr = $phoneErr = $emailErr = $locationErr = $logoErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    if (empty($_POST["Company_name"])) {
        $company_nameErr = "Company name is required";
    } else {
        $company_name = test_input($_POST["Company_name"]);
    }

    if (empty($_POST["Phone"])) {
        $phoneErr = "Phone is required";
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

    // Handle file upload for logo
    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] == 0) {
        $logo = file_get_contents($_FILES["logo"]["tmp_name"]);
    } else {
        $logoErr = "Error uploading logo";
    }

    // Insert data into database
    if (empty($company_nameErr) && empty($phoneErr) && empty($emailErr) && empty($locationErr) && empty($logoErr)) {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO company (company_name, phone, email, location, logo) VALUES (?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sssss", $company_name, $phone, $email, $location, $logo);

        // Reconnect on failure
        if (!$stmt->execute()) {
            if ($conn->errno == 2006) { // MySQL server has gone away error number
                $conn->close();
                include '../php/dbconnection.php'; // Reconnect to the database

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
            exit(); // Ensure that script execution stops after redirection
        }

        $stmt->close();
    } else {
        // Print errors for debugging
        echo "Errors found: ";
        echo "Company Name: " . $company_nameErr . " ";
        echo "Phone: " . $phoneErr . " ";
        echo "Email: " . $emailErr . " ";
        echo "Location: " . $locationErr . " ";
        echo "Logo: " . $logoErr . " ";
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
                    <span class="error"><?php echo $company_nameErr; ?></span>
                </div>
                <div id="form-group">
                    <input type="tel" name="Phone" id="Phone" placeholder=" " required>
                    <label for="Phone">Phone</label>
                    <span class="error"><?php echo $phoneErr; ?></span>
                </div>
                <div id="form-group">
                    <input type="email" name="Email" id="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>
                <div id="form-group">
                    <input type="text" name="Location" id="Location" placeholder=" " required>
                    <label for="Location">Location</label>
                    <span class="error"><?php echo $locationErr; ?></span>
                </div>
                <div id="form-group">
                    <input type="file" name="logo" id="logo">
                    <label for="logo">Choose logo</label>
                    <span class="error"><?php echo $logoErr; ?></span>
                </div>
                <div id="btn">
                    <button type="submit">SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
