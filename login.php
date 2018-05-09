<html>
    <head>
        <title>Напиши в меня</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <?php
        session_start();
        $_SESSION['head'] = "<div id =\"header\"><h1>\$zagol</h1></div>";
        ?>
        <script type="text/javascript" src="external/jquery/jquery.js"></script>
        <script type="text/javascript" src="jquery-ui.js"></script>
        <!-- Скрипт с диалоговым окном для логина -->
        <script type="text/javascript">
            $(function () {
                $("#dialog").dialog({
                    width: 700,
                    resizable: false
                });
            })
        </script>
    </head>
    <body>
        <body>
        <div id="header">
            <?php
            /**
             * Получаем заголовок
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
                             * Получаем список кафедр для меню
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
        <div id="content">
            <h3>Введите данные</h3>
            <form method="POST">
                <input type="text" name="login" placeholder="Введите логин">
                <input type="password" name="password" placeholder="Введите пароль">
                <input name="log" type="submit">
            </form>
            <?php
            /**
             * Логинимся
             */
            if (isset($_POST['log'])){
                $name = $_POST['login'];
                $pword = $_POST['password'];
                $sql = "SELECT Ac_gr, Family, Imya, Otchestvo FROM Users WHERE Name = '$name' AND Pword = '$pword'"; //Получаем данные из базы по введённым данным
                $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                try {
                    $result = mysqli_query($con, $sql);
                    while ($row = $result->fetch_assoc()){
                        $acid = $row['Ac_gr'];
                        $fio = $row['Family']." ".$row['Imya']." ".$row['Otchestvo'];
                    }
                    if (!isset($acid)){
                        $acid = 0;
                    }
                    if (!$acid){
                        throw new Exception();
                    }
                    $_SESSION['username'] = $fio;
                    $_SESSION['group'] = $acid;
                    $name2 = $_SESSION['username'];
                    if ($acid == 1){
                        echo "<div id=\"dialog\" title=\"Здравствуйте\"><p style='text-align: center'>Здравствуйте, $name2! <br>Если вы хотите изменить данные, то нажмите <a href=\"admin.php\">сюда</a><br>Если вы хотите просмотреть данные, то нажмите <a href=\"otobr.php\">сюда</a><br></p></div>";
                    }
                    else if ($acid == 2) {
                        echo "<div id=\"dialog\" title=\"Здравствуйте\"><p style='text-align: center'>Здравствуйте, $name2! <br>Если вы хотите просмотреть данные, то нажмите <a href=\"otobr.php\">сюда</a><br></p></div>";
                    }
                } catch (Exception $ex) {
                    echo "<div id=\"dialog\" title=\"У вас ошибка!\"><p style='text-align: left'>Неверная пара логина и пароля<br></p> </div>";
                }
                
            }
            try{
                if (true){
                    throw new Exception();
                }
                if (!$_SESSION['group']){
                    throw new Exception();
                }
                if ($_SESSION['group']==1){
                    $name1 = $_SESSION['username'];
                    echo "Здравствуйте, $name1! <br>";
                    echo "Если вы хотите изменить данные, то нажмите <a href=\"admin.php\">сюда</a><br>";
                    echo "Если вы хотите просмотреть данные, то нажмите <a href=\"otobr.php\">сюда</a><br>";
                }
                if ($_SESSION['group']==2){
                    $name1 = $_SESSION['username'];
                    echo "Здравствуйте, $name1! <br>";
                    echo "Если вы хотите просмотреть данные, то нажмите <a href=\"otobr.php\">сюда</a><br>";
                }
            } catch (Exception $ex) {

            }
           
            ?>
        </div>
            <div id="footer">
                Напиши в меня
            </div>
    </body>
    </body>
</html>

