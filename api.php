<?php

    error_reporting(E_ERROR | E_PARSE);

    function getMeta ($articleName)
    {
        return json_decode(file_get_contents("articles/$articleName/meta.json", true), true);
    }

    function listArticles ($searchQuery="")
    {
        $articles = "";
        foreach (array_diff(scandir("articles"), array(".","..")) as $article)
        {
            if ((strpos(strtolower(getMeta($article)["title"]), strtolower($searchQuery)) !== false) || (!$searchQuery))
            {
                $articles .= "<tr>" .
                             "<td><a href='?kb=$article'>" . getMeta($article)["title"] . "</a></td>" .
                             "<td>" . getMeta($article)["author"] . "</td>" .
                             "<td>" . getMeta($article)["date"] . "</td>" .
                             "<td>" . implode(", ", getMeta($article)["categories"]) . "</td>" .
                             "</tr>";
            }
        }
        return $articles;
    }
    
    function parseArticle($articleName)
    {
        /* get contents of the file */
        $article = file_get_contents("articles/$articleName/content.txt", true);
        /* remove html tags, and create links */
        $article = preg_replace("/{{(https?:\/\/[^\s]+)}!(.+)!}/", "<a href='$1' target='_blank'>$2</a>", htmlentities($article));
        /* create bold text */
        $article = preg_replace("/,,(.+),,/", "<b>$1</b>", $article);
        /* create images */
        $article = preg_replace("/=(https?:\/\/[^\s]+)=/", "<img src='$1'>", $article);
        /* new lines */
        $article = str_replace(array("\n", "\r"), "<br>", $article);
        /* text area */
        $article = preg_replace("/\`\`(.+)\`\`/", "<textarea>$1</textarea>", $article);
        /* center */
        $article = preg_replace("/\_\_(.+)\_\_/", "<p class='centered'>$1</p>", $article);
        /* remove backslashes */
        $article = str_replace("\\", "", $article);
        /* get metadata */
        $articleInfo = getMeta($articleName);
        /* return that stuff */
        return array($articleInfo, $article, $articleName);
    }
    
    function makeHtmlFromParsedArticle($articleArray)
    {
        $articleInfo = $articleArray[0];
        $contents = $articleArray[1];
        return "
                <div class='article'>
                    <h2>$articleInfo[title]</h2>
                    <h4>Opublikowano $articleInfo[date] przez $articleInfo[author]</h4>
                    <hr>
                    <p>
                        $contents
                    </p>
                    <hr>
                    <a href='https://twitter.com/share?text=Dziękuję%20@cloudsdalefm%20za%20pomoc!%20:D&url=https://help.cloudsdalefm.net/index.php?kb=$articleArray[2]' target='_blank' class='toright'>Zatweetuj i wyraź swoje zadowolenie</a>
                </div>
             ";
    }

    /* --------------------------------------------------- */
    
    if (isset($_POST["searchQuery"]))
    {
        echo "<table>
                <tr class='ftr'>
                    <td>Tytuł</td>
                    <td>Autor</td>
                    <td>Data</td>
                    <td>Kategorie</td>
                </tr>
            " . listArticles($_POST["searchQuery"])
              . "</table>";
    }
    
?>
