<?php
include '../functions/sanitize.php';
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
            <form action="<?php echo htmlspecialchars($_SERVER["adminsetup.php"])?>" method="post">
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
                    <label for="logo" >Choose logo</label>
                </div>
                <div id="btn">
                    <button type="submit" >SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
