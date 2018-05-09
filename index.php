<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Напиши в меня</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <?php
            session_start(); //НАчинаем сессию (а, может, не надо?)
        ?>
    </head>
    <body>
        <div id="header">
        <?php
        /**
         * Получаем заголовок из базы (он первый и единственный(но это не точно))
         */
        $con = new mysqli('localhost', 'root', '', 'vuz_of', '3306');
        $sql = "SELECT Nazv FROM Zagolovok WHERE id = 1";
        $result = mysqli_query($con, $sql);
        while ($row = $result->fetch_assoc()){
            $zagol = $row['Nazv'];
        }
        echo "<h1>$zagol</h1>";
        ?>
        </div>
        <div id="menu">
            <nav>
                <ul class="topmenu">
                    <li><a href="index.php">Домой</a></li>
                    <li><a href="" class="down">Кафедры</a>
                        <ul class="submenu">
                            <?php
                            /**
                             * Поллучаем список кафедр с адресами страниц (последних может и не быть)
                             */
                            $sql1 = "SELECT Nazv as A, Adress as B FROM Kafedra";
                            $result1 = mysqli_query($con, $sql1);
                            while ($row = $result1->fetch_assoc()){
                                $url = $row['B'];
                                $nazv = $row['A'];
                                echo "<li><a href=\"$url\">$nazv</a></li>";
                            }
                            ?>
                        </ul>
                    </li>
                    <li><a href="news.php">Новости</a></li>
                    <li><a href="login.php">Данные</a></li>
                </ul>
            </nav>
        </div>
        <div id="news" class="wrapper">
            <input type="radio" name="point" id="slide1" checked="checked">
            <input type="radio" name="point" id="slide2">
            <input type="radio" name="point" id="slide3">
            <div class="slider">
                <div class="slides slide1">
                    <?php
                    /**
                     * Получаем первую новость для слайдера
                     */
                    $sql2 = "SELECT Zag as A, Text as B, Pic as C FROM News WHERE id = (SELECT MAX(id) FROM News)";
                    $result2 = mysqli_query($con, $sql2);
                    while ($row = $result2->fetch_assoc()){
                        $news_zag = $row['A'];
                        $news_text = $row['B'];
                        $news_img = $row['C'];
                        echo "<h5>$news_zag</h5>";
                        echo "<p>$news_text</p>";
                        echo "<img src=\"$news_img\">";
                    }
                    ?>
                </div>
                <div class="slides slide2">
                    <?php
                    /**
                     * Получаем вторую новость для слайдера
                     */
                    $sql3 = "SELECT Zag as A, Text as B, Pic as C FROM News WHERE id = (SELECT MAX(id) FROM News)-1";
                    $result3 = mysqli_query($con, $sql3);
                    while ($row = $result3->fetch_assoc()){
                        $news_zag = $row['A'];
                        $news_text = $row['B'];
                        $news_img = $row['C'];
                        echo "<h5>$news_zag</h5>";
                        echo "<p>$news_text</p>";
                        echo "<img src=\"$news_img\">";
                    }
                    ?>
                </div>
                <div class="slides slide3">
                    <?php
                    /**
                     * Получаем третью новость для слайдера
                     */
                    $sql4 = "SELECT Zag as A, Text as B, Pic as C FROM News WHERE id = (SELECT MAX(id) FROM News)-2";
                    $result4 = mysqli_query($con, $sql4);
                    while ($row = $result4->fetch_assoc()){
                        $news_zag = $row['A'];
                        $news_text = $row['B'];
                        $news_img = $row['C'];
                        echo "<h5>$news_zag</h5>";
                        echo "<p>$news_text</p>";
                        echo "<img src=\"$news_img\">";
                    }
                    ?>
                </div>
            </div>
            <div class="controls">
                <label for="slide1"></label>
                <label for="slide2"></label>
                <label for="slide3"></label>
            </div>
        </div>
        <div id="footer">
            Напиши сюда что-нибудь
        </div>
    </body>
</html>
