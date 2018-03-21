<?php
    require 'vendor/autoload.php';

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
        $parser = new Parsedown();
        $parser->setSafeMode(true);
        /* get contents of the file */
        $article = file_get_contents("articles/$articleName/content.md", true);
        /* remove html tags, and parse */
        $article = $parser->text($article);
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
