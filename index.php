<!DOCTYPE html>
<!--
    Code of help.cloudsdalefm.net is licensed under the Mozilla Public License 2.0
    https://www.mozilla.org/en-US/MPL/2.0
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="CloudsdaleFM Developers"/>
        <title>Sekcja pomocy CloudsdaleFM</title>
        <meta name="description" content="Rozwiązania problemów technicznych, dokumentacje API, itp. - wszystko w jednym miejscu!"/>
        <meta property="og:title" content="Strona główna"/>
        <meta property="og:site_name" content="Sekcja pomocy CloudsdaleFM"/>
        <meta property="og:description" content="Rozwiązania problemów technicznych, dokumentacje API, itp. - wszystko w jednym miejscu!"/>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" type="image/png" href="https://cloudsdalefm.net/favicon.png"/>
        <link rel="shortcut icon" type="image/png" href="https://cloudsdalefm.net/favicon.png"/>
    </head>
    <body>
        
        <div class="topbar">
            <div class="logo"></div>
            <div class="searchbox">
                <form method="POST" id="searchbox">
                    <input type="text" name="searchQuery" autofocus placeholder="Wpisz zapytanie lub kliknij Szukaj, by wyświetlić pełną listę."><input type="submit" value="Szukaj">
                </form>
            </div>
        </div>
        
        <div class="content">
            <div id="searchResults">
                <?php
                    if(isset($_GET["kb"]))
                    {
                        require_once 'api.php';
                        echo makeHtmlFromParsedArticle(parseArticle($_GET["kb"]));
                    }
                ?>
            </div>
        </div>
        
        <div class="legal">
            <a href="https://github.com/FabulousKana/helpcloudsdalefmnet" target="_blank">open-source</a>, cloudsdalefm.net &copy; 2018
        </div>
        
        <script type="text/javascript" src="script.js"></script>
    </body>
</html>
