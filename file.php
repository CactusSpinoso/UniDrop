<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Carica File</title>
</head>

<body>
    <h1>Carica File</h1>
    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    ?>
    <form method="post" action="upload.php" enctype="multipart/form-data">
        <label for="file">Seleziona file:</label>
        <input type="file" name="file">
        <br><br>
        <button type="submit">Carica</button>
    </form>
</body>

</html>