<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Activation</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="typewriter.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="container">
            <p id="container-info">POS (point of sales) v3</p>
        </div>
        <div id="Company-setup">
            <h1>Product Activation</h1>
            <form action="login.html" method="post">
                <br> <br> <br> <br>
                <div id="form-group">
                    <input type="text" name="Company_name" id="Company_name" placeholder=" " required>
                    <label for="Company_name">Company name</label>
                </div> <br>
                <div id="form-group">
                    <input type="text" name="Activation_code" id="Activation_code" placeholder=" " required>
                    <label for="Activation_code">Activation code</label>
                </div> <br> <br>
                <div id="btn">
                    <button type="submit" >ACTIVATE NOW</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
