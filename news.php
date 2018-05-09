<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <?php
        session_start();
        
        ?>
    </head>
    <body>
        <div id="header">
        <?php
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
        <div id="content">
            <h2>Новости</h2>
            <table>
                <?php
                $connews = new mysqli('localhost', 'root', '', 'vuz_of', '3306');
                $sqlnews = "SELECT Zag as A, Text as B, Pic as C FROM News";
                $resultnews = mysqli_query($connews, $sqlnews);
                $i = 0;
                echo "<tr>";
                while ($row = $resultnews->fetch_assoc()){
                    $zag = $row['A'];
                    $text = $row['B'];
                    $pic = $row['C'];
                    echo "<td><i class=\"ttext\">$zag</i><br>$text<br><img src=\"$pic\"></td>";
                    $i++;
                    if ($i == 4){
                        echo "</tr>";
                        echo "<tr>";
                        $i = 0;
                    }
                }
                echo "</tr>";
                ?>
            </table>
        </div>
        <div id="footer">
            Костыли и велосипеды - программируем как можем. 2018
            
            
        </div>
    </body>
</html>