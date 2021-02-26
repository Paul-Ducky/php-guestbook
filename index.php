<?php
declare(strict_types=1);

use JetBrains\PhpStorm\Pure;

require_once "classes/Post.php";
require_once "classes/PostLoader.php";
session_start();
const POSTS_LOCATION = "all_posts.txt";
const MAX_POSTS = 20;

//stap 1: create classes (y)
//stap 2: create form (y)
//stap 3: create object from user input (y) @todo add validation to these.
//stap 4: change object into array then write to doc. (y) -> @todo to write more than 1 to doc read and save the old then add newly glued info
//stap 5: read doc to then show previous post @todo add to array to show multiple in foreach
//step 6: post -store in file-> redirect -read file-> get
// soo in a visual path
// post -write-> document -read-> posts[0-19] -show-> foreach(posts as I => post):  echo H2 -> article *I* etc

function test_input($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function filterWords($text){
    $filterWords = array('darn','poo','fuck','shit','dick','nigger');
    foreach ($filterWords as $iValue) {
        $text = preg_replace_callback('/\b' . $iValue . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
    }
    return $text;
}

//use this one on individual substrings from content input
function stringToEmoji(string $str): string {
    $emojis = [
        'o/'         => '👋',
        '</3'        => '💔',
        '<3'         => '💗',
        '8-D'        => '😁',
        '8D'         => '😁',
        ':-D'        => '😁',
        '=-3'        => '😁',
        '=-D'        => '😁',
        '=3'         => '😁',
        '=D'         => '😁',
        'B^D'        => '😁',
        'X-D'        => '😁',
        'XD'         => '😁',
        'x-D'        => '😁',
        'xD'         => '😁',
        ':\')'       => '😂',
        ':\'-)'      => '😂',
        ':-))'       => '😃',
        '8)'         => '😄',
        ':)'         => '😄',
        ':-)'        => '😄',
        ':3'         => '😄',
        ':D'         => '😄',
        ':]'         => '😄',
        ':^)'        => '😄',
        ':c)'        => '😄',
        ':o)'        => '😄',
        ':}'         => '😄',
        ':っ)'        => '😄',
        '=)'         => '😄',
        '=]'         => '😄',
        '0:)'        => '😇',
        '0:-)'       => '😇',
        '0:-3'       => '😇',
        '0:3'        => '😇',
        '0;^)'       => '😇',
        'O:-)'       => '😇',
        '3:)'        => '😈',
        '3:-)'       => '😈',
        '}:)'        => '😈',
        '}:-)'       => '😈',
        '*)'         => '😉',
        '*-)'        => '😉',
        ':-,'        => '😉',
        ';)'         => '😉',
        ';-)'        => '😉',
        ';-]'        => '😉',
        ';D'         => '😉',
        ';]'         => '😉',
        ';^)'        => '😉',
        ':-|'        => '😐',
        ':|'         => '😐',
        ':('         => '😒',
        ':-('        => '😒',
        ':-<'        => '😒',
        ':-['        => '😒',
        ':-c'        => '😒',
        ':<'         => '😒',
        ':['         => '😒',
        ':c'         => '😒',
        ':{'         => '😒',
        ':っC'        => '😒',
        '%)'         => '😖',
        '%-)'        => '😖',
        ':-P'        => '😜',
        ':-b'        => '😜',
        ':-p'        => '😜',
        ':-Þ'        => '😜',
        ':-þ'        => '😜',
        ':P'         => '😜',
        ':b'         => '😜',
        ':p'         => '😜',
        ':Þ'         => '😜',
        ':þ'         => '😜',
        ';('         => '😜',
        '=p'         => '😜',
        'X-P'        => '😜',
        'XP'         => '😜',
        'd:'         => '😜',
        'x-p'        => '😜',
        'xp'         => '😜',
        ':-||'       => '😠',
        ':@'         => '😠',
        ':-.'        => '😡',
        ':-/'        => '😡',
        ':/'         => '😡',
        ':L'         => '😡',
        ':S'         => '😡',
        ':\\'        => '😡',
        '=/'         => '😡',
        '=L'         => '😡',
        '=\\'        => '😡',
        ':\'('       => '😢',
        ':\'-('      => '😢',
        '^5'         => '😤',
        '^<_<'       => '😤',
        'o/\\o'      => '😤',
        '|-O'        => '😫',
        '|;-)'       => '😫',
        ':###..'     => '😰',
        ':-###..'    => '😰',
        'D-\':'      => '😱',
        'D8'         => '😱',
        'D:'         => '😱',
        'D:<'        => '😱',
        'D;'         => '😱',
        'D='         => '😱',
        'DX'         => '😱',
        'v.v'        => '😱',
        '8-0'        => '😲',
        ':-O'        => '😲',
        ':-o'        => '😲',
        ':O'         => '😲',
        ':o'         => '😲',
        'O-O'        => '😲',
        'O_O'        => '😲',
        'O_o'        => '😲',
        'o-o'        => '😲',
        'o_O'        => '😲',
        'o_o'        => '😲',
        ':$'         => '😳',
        '#-)'        => '😵',
        ':#'         => '😶',
        ':&'         => '😶',
        ':-#'        => '😶',
        ':-&'        => '😶',
        ':-X'        => '😶',
        ':X'         => '😶',
        ':-J'        => '😼',
        ':*'         => '😽',
        ':^*'        => '😽',
        'ಠ_ಠ'        => '🙅',
        '*\\0/*'     => '🙆',
        '\\o/'       => '🙆',
        ':>'         => '😄',
        '>.<'        => '😡',
        '>:('        => '😠',
        '>:)'        => '😈',
        '>:-)'       => '😈',
        '>:/'        => '😡',
        '>:O'        => '😲',
        '>:P'        => '😜',
        '>:['        => '😒',
        '>:\\'       => '😡',
        '>;)'        => '😈',
        '>_>^'       => '😤',
    ];
    return $emojis[$str] ?? $str;
}

function changeEmoji(string $str):string{
   $words = explode(" ",$str);
    foreach ($words as &$word) {
        $word = stringToEmoji($word);
   }
    return implode(" ",$words);
}


switch($_SERVER['REQUEST_METHOD']){
    case 'POST' :
        if(!empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['author'])){
            $title = filterWords(test_input($_POST['title']));
            $content = filterWords(test_input($_POST['content']));
            $author = filterWords(test_input($_POST['author']));
            if(is_numeric($_POST['amount'])){
                $_SESSION['amount']= $_POST['amount'];
            }
            $input = new Post($title, $content, $author);
            $postLoader = new PostLoader();
            $postLoader->writeToFile(POSTS_LOCATION, $input);
            $postLoader->setAllPosts(POSTS_LOCATION);
            header("Location:index.php");
            exit;
        }
        echo '<div class="faulty-input"><p>Please check your input</p></div>';
        break;

    default:
        $postLoader = new PostLoader();
        $postLoader->setAllPosts(POSTS_LOCATION);
        $userNumber = $_SESSION['amount'] ?? MAX_POSTS;
        break;
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
        <input type="textarea" class="textField" name="content" placeholder="Your message..."/>
    </label>
    <label for="author">
        <input type="text" class="textField" name="author" placeholder="Written by..."/>
    </label>
    <label for="amount">
        <input type="number" class="number" name="amount" min="1" max="20"/>
    </label>

    <button type="submit" class="btn" name="action">Enter!</button>
</form>
<h2>Recent articles</h2>
<?php
if (isset($postLoader)) {
    for ($i = 0; $i <= min(MAX_POSTS,$userNumber); $i++) {
         $allPosts = $postLoader->getAllPosts();
        echo $allPosts[$i]['title'] . "<br>";
        echo changeEmoji($allPosts[$i]['content']) . "<br>";
        echo "Written by ".$allPosts[$i]['author'] . "<br>";
        echo $allPosts[$i]['date'] . "<br>";
    }
}
 ?>


</body>

</html>
