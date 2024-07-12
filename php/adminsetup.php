<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin setup</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="typewriter.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Admin Account setup</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["Product_activation.php"])?>" method="post">
                <div id="form-group">
                    <input type="text" name="full_name" id="full_name" placeholder=" " required>
                    <label for="full_name">Full name</label>
                </div>
                <div id="form-group">
                    <input type="email" name="Email" id="Email" placeholder=" " required>
                    <label for="Email">Email</label>
                    </div>
                        <div id="form-group">
                            <input type="tel" name="Phone" id="Phone" placeholder=" " required>
                            <label for="Phone">Phone</label>
                        </div>
                <div id="form-group">
                    <input type="text" name="Username" id="Username" placeholder=" " required>
                    <label for="Username">Username</label>
                </div>
                <div id="form-group">
                    <input type="password" name="Password" id="Password" placeholder=" " required>
                    <label for="Password">Password</label>
                </div>
                
                <div id="btn">
                    <button type="submit" >SUBMIT & CONTINUE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>