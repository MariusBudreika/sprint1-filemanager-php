<?php
//auth
session_start();
if (!$_SESSION['logged_in']) {
    header('Location: ' . 'login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP web file manager</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    //open/list folders
    print('<h1>File manager</h1>');
    $path = "./" . $_GET['path'];
    $files = array_diff(scandir($path), array('..', '.'));

    print('<h2>Directory path: ' . $path . '</h2>');
    print('<table><th>Type</th><th>Name</th><th>Actions</th>');
    foreach ($files as $file) {
        print('<tr>');
        print('<td>' . (is_dir($path . $file) ? "Dir" : "File") . "</td>");
        print('<td>' . (is_dir($path . $file) ? '<a href="' . (isset($_GET['path'])
            ? $_SERVER['REQUEST_URI'] . $file . '/'
            : $_SERVER['REQUEST_URI'] . '?path=' . $file . '/') . '">' . $file . '</a>'
            : $file) . '</td>');
        print('<td>'
            . (is_dir($path . $file)
                ? ''
                : '<form style="display: inline-block" action="" method="post">
                <input type="hidden" name="download" value=' . $file . '>
                <button id="download" type="submit">Download</button>
               </form>
                <form style="display: inline-block" action="" method="post">
                <input  type="hidden" name="delete" value=' . $file . '>
                <button id="delete" type="submit">Delete</button>
                </form>')
            . "</form></td>");
        print('</tr>');
    }
    print("</table>");

    // delete
    if (isset($_POST['delete'])) {
        unlink('./' . $_GET['path'] . $_POST['delete']);
        header("Refresh:0.1");
    }

    // create folders
    function create()
    {
        if (isset($_POST['create'])) {
            if ($_POST['create'] === "") {
                echo "Folder name can't be emty string!";
            }
            if ($_POST['create'] != "") {
                $dirCreate = './' . $_GET['path'] . $_POST['create'];
                if (!is_dir($dirCreate)) {
                    mkdir($dirCreate);
                    echo 'Success! Folder created.';
                    header("Refresh:0.1");
                }
                if (is_dir($dirCreate)) {
                    echo "Folder already exist!";
                }
            }
        }
    }
    create();

    // Upload img
    if (isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];

        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
        $extensions = array("jpeg", "jpg", "png");
        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }
        if ($file_size > 2097152) {
            $errors[] = 'File size must be smaller than 2 MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $path . $file_name);
            echo "Succes";
            header("Refresh:0.1");
        } else {
            print_r($errors);
        }
    }

    // download
    if (isset($_POST['download'])) {
        $file = './' . $_GET["path"] . $_POST['download'];
        $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
        ob_clean();
        ob_start();
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileToDownloadEscaped));
        ob_end_flush();
        readfile($fileToDownloadEscaped);
        exit;
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" />
        <input type="submit" value="Upload" />
    </form>

    <form action="" method="POST">
        <input type="text" id="create" name="create" placeholder="write folder name here">
        <input type="submit" value="Create">
    </form>

    Click here to <a href="login.php?action=logout"> logout.
</body>

</html>