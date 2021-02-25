<?php
declare(strict_types=1);

require_once "classes/Post.php";
require_once "classes/PostLoader.php";

$postStorage = "all_posts.txt";

//stap 1: create classes (y)
//stap 2: create form (y)
//stap 3: create object from user input (y) @todo add validation to these.
//stap 4: change object into array then write to doc. (y) -> @todo to write more than 1 to doc read and save the old then add newly glued info
//stap 5: read doc to then show previous post @todo add to array to show multiple in foreach
//
// soo in a visual path
// post -write-> document -read-> posts[0-19] -show-> foreach(posts as I => post):  echo H2 -> article *I* etc

if (isset($_POST['action'])) {

    $input = new Post($_POST['title'], $_POST['content'], $_POST['author']);
    $postLoader = new PostLoader();
    $postLoader->writeToFile($postStorage, $input);
    $postLoader->setAllPosts($postStorage);

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My site</title>
</head>

<body>
<h1>Welcome to my site! Leave me a "nice" message</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="title">
        <input type="text" class="textField" name="title" placeholder="Title"/>
    </label>
    <label for="content">
        <input type="text" class="textField" name="content" placeholder="Your message..."/>
    </label>
    <label for="author">
        <input type="text" class="textField" name="author" placeholder="Written by..."/>
    </label>

    <button type="submit" class="btn" name="action">Enter!</button>
</form>
<h2>Recent articles</h2>
<?php
if (isset($postLoader)) {
    for ($i = 0; $i <= 20; $i++) {
        echo $postLoader->getAllPosts()[$i]['title'] . "<br>";
        echo $postLoader->getAllPosts()[$i]['content'] . "<br>";
        echo "Written by ".$postLoader->getAllPosts()[$i]['author'] . "<br>";
        echo $postLoader->getAllPosts()[$i]['date'] . "<br>";
    }
}
 ?>


</body>

</html>
