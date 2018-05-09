<html>
    <head>
        <title>Напиши в меня</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Если у пользователя не включен js, то выбрасываем на главную страницу -->
        <noscript>
            <meta http-equiv="refresh" content="0, url=index.php">
        </noscript>
        <?php
        /*
         * Если пользователь не залогинился, то выбрасываем на главную страницу
         */
        session_start();
        if (!$_SESSION['group']){
            echo "<script type=\"text/javascript\">";
            echo "window.location=\"index.php\"";
            echo "</script>";
        }
        else {
            $sesgr = $_SESSION['group'];
        }
        ?>
        <script type="text/javascript" src="external/jquery/jquery.js"></script>
        <script type="text/javascript" src="jquery-ui.js"></script>
        <!-- аккордеон для вывода списка кафедр (это уже jQuery) -->
        <script type="text/javascript">
            $(function(){
                $("#accordion").accordion({
                    active: false,
                    navigation: true,
                    collapsible: true
                });
            });
        </script>
    </head>
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
                            $conmen = new mysqli('localhost', 'root', '', 'vuz_of', '3306');
                            $sql1 = "SELECT Nazv as A, Adress as B FROM Kafedra";
                            $result1 = mysqli_query($conmen, $sql1);
                            while ($row = $result1->fetch_assoc()){
                                $url = $row['B'];
                                $nazv = $row['A'];
                                echo "<li><a href=\"$url\">$nazv</a></li>";
                                
                            }
                            ?>
                        </ul>
                    </li>
                    <li><a href="news.php">Новости</a></li>
                    <li><a href="" class="down">Данные</a>
                        <ul class="submenu">
                            <li><a href="admin.php">Добавить данные</a></li>
                            <li><a href="otobr.php">Просмотреть данные</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div id="content">
            <h3>Здравствуйте, <?php echo $_SESSION['username'] ?>!</h3>
            <div id="accordion">
                <h3><a href="#">Кафедры</a></h3>
                <div id="kafedras">
                <table>
                    <?php
                    /**
                     * Получаем список кафедр для вывода
                     */
                    if ($_SESSION['group']){
                    $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                    $sql = "SELECT * FROM Kafedra";
                    $result = mysqli_query($con, $sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        $kafedra = $row['Kafedra'];
                        echo "<td>$kafedra</td>";
                        echo "</tr>";
                    
                    }
                    }
                    ?>
                </table>
                </div>
                <h3><a href="#">Должности</a></h3>
                    <div id="dolj">
                        <table>
                        <?php
                        /**
                         * Получаем список должностей
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql1 = "SELECT * FROM Dolj";
                            $result1 = mysqli_query($con, $sql1);
                            while ($row = $result1->fetch_assoc()){
                                echo '<tr>';
                                $doljnost = $row['Dolg'];
                                echo "<td>$doljnost</td>";
                                echo '</tr>';
                            }
                        }
                        ?>
                        </table>
                    </div>
                <h3><a href="#">Учебные планы</a> </h3>
                <div id="uch_plan">
                    <table>
                        <tr>
                            <td>Название дисциплины</td>
                            <td>Название специальности</td>
                            <td>Количество часов</td>
                            <td>Форма отчёта</td>
                            <td>Семестры</td>
                        </tr>
                        <?php
                        /**
                         * Получаем список учебных планов
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql2 = "SELECT 
                                        Kol_chas as A, 
                                        Disc.Nazv AS B, 
                                        Spec.Nazv as C, 
                                        Otchet as D, 
                                        GROUP_CONCAT(Semestr SEPARATOR ', ') as E 
                                        FROM Uch_Plan 
                                        INNER JOIN Disc ON Uch_Plan.Disc = Disc.id 
                                        INNER JOIN Spec ON Uch_Plan.Spec = Spec.id 
                                        INNER JOIN Plan_Sem ON Plan_Sem.id_Plan = Uch_Plan.id 
                                        INNER JOIN Semestr ON Plan_Sem.id_Semestr = Semestr.id 
                                        INNER JOIN Form_Otch ON Uch_Plan.id_Otch = Form_Otch.id 
                                        GROUP BY Disc.Nazv";
                            $result2 = mysqli_query($con, $sql2);
                            while ($row = $result2->fetch_assoc()){
                                $A1 = $row['A'];
                                $B1 = $row['B'];
                                $C1 = $row['C'];
                                $D1 = $row['D'];
                                $E1 = $row['E'];
                                echo "<tr>";
                                echo "<td>$B1</td>";
                                echo "<td>$C1</td>";
                                echo "<td>$A1</td>";
                                echo "<td>$D1</td>";
                                echo "<td>$E1</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
            </div>
                <h3><a href="#">Нагрузка</a> </h3>
                <div id="Nagruz_ochka">
                    <table>
                        <tr>
                            <td>Название специальности</td>
                            <td>Название дисциплины</td>
                            <td>Количество часов</td>
                            <td>Форма отчёта</td>
                            <td>ФИО преподавателя</td>
                            <td>Семестры</td>
                            <td>Группы</td>
                        </tr>
                        <?php
                        /**
                         * Получаем нагрузку
                         */
                        if ($_SESSION['group']==$sesgr){
                        $sql3 = "SELECT Spec.Nazv as A, 
                                    Disc.Nazv as B, 
                                    Nagruz.Kol_chas as C, 
                                    Form_Otch.Otchet as D, 
                                    Prepod.Familya as E1, 
                                    Prepod.Imya as E2, 
                                    Prepod.Otchestvo as E3, 
                                    GROUP_CONCAT(Semestr.Semestr SEPARATOR ',') as F, 
                                    GROUP_CONCAT(vuz1.Group.Group SEPARATOR',') as G 
                                    FROM Nagruz 
                                    INNER JOIN Spec ON Nagruz.Spec = Spec.id 
                                    INNER JOIN Disc ON Nagruz.Disc = Disc.id 
                                    INNER JOIN Prepod ON Nagruz.id_Prepod = Prepod.id 
                                    INNER JOIN Form_Otch ON Nagruz.id_Otch = Form_Otch.id 
                                    INNER JOIN Nagruz_Sem ON Nagruz_Sem.id_Nagruz = Nagruz.id 
                                    INNER JOIN Semestr ON Nagruz_Sem.id_Semestr = Semestr.id 
                                    INNER JOIN Nagruz_Group ON Nagruz_Group.id_Nagruz = Nagruz.id 
                                    INNER JOIN vuz1.Group ON Nagruz_Group.id_Group = vuz1.Group.id";
                        $result3 = mysqli_query($con, $sql3);
                        while ($row = $result3->fetch_assoc()){
                            $A2 = $row['A'];
                            $B2 = $row['B'];
                            $C2 = $row['C'];
                            $D2 = $row['D'];
                            $E2 = $row['E1']." ".$row['E2']." ".$row['E3'];
                            $F2 = $row['F'];
                            $G2 = $row['G'];
                            $F2m = array();
                            $F2m[0] = "";
                            $j = 0;
                            for ($i = 0; $i < strlen($F2); $i++){
                                if ($F2[$i] == ","){
                                    $j++;
                                    $F2m[$j] = "";
                                }
                                else {
                                    $F2m[$j] .= $F2[$i];
                                }
                            }
                            $F2mm = array_unique($F2m);
                            $G2m = array();
                            $G2m[0] = "";
                            $jj = 0;
                            for ($i = 0; $i < strlen($G2); $i++){
                                if ($G2[$i] == ","){
                                    $jj++;
                                    $G2m[$jj] = "";
                                }
                                else {
                                    $G2m[$jj] .= $G2[$i];
                                }
                            }
                            $G2mm = array_unique($G2m);
                            echo "<tr>";
                            echo "<td>$A2</td>";
                            echo "<td>$B2</td>";
                            echo "<td>$C2</td>";
                            echo "<td>$D2</td>";
                            echo "<td>$E2</td>";
                            echo "<td>";
                            foreach ($F2mm as $value) {
                                echo $value;
                                if (next($F2mm)){
                                    echo ", ";
                                }
                            }
                            unset($value);
                            echo "</td>";
                            echo "<td>";
                            foreach ($G2mm as $value) {
                                echo $value;
                                if (next($G2mm)){
                                    echo ", ";
                                }
                            }
                            unset($value);
                            echo "</td>";
                            echo "</tr>";
                        }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Преподаватели</a> </h3>
                <div id="Prepod">
                    <table>
                        <tr>
                            <td>ФИО преподавателя</td>
                            <td>Должность</td>
                            <td>Дисциплины</td>
                            <td>Кафедра</td>
                        </tr>
                        <?php
                        /**
                         * Получаем список преподов
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql4 = "SELECT Prepod.Familya as A1, 
                                        Prepod.Imya as A2, 
                                        Prepod.Otchestvo as A3, 
                                        Dolj.Dolg as B, 
                                        GROUP_CONCAT(Disc.Nazv SEPARATOR ', ') as C, 
                                        Kafedra.Kafedra as D 
                                        FROM Prepod 
                                        INNER JOIN Dolj ON Prepod.id_Dolg = Dolj.id 
                                        INNER JOIN Disc_Prep ON Disc_Prep.id_Prepod = Prepod.id 
                                        INNER JOIN Disc ON Disc.id = Disc_Prep.id_Disc 
                                        INNER JOIN Kafedra ON Kafedra.id = Prepod.id_Kaf";
                            $result4 = mysqli_query($con, $sql4);
                            while ($row = $result4->fetch_assoc()){
                                echo "<tr>";
                                $A3 = $row['A1']." ".$row['A2']." ".$row['A3'];
                                $B3 = $row['B'];
                                $C3 = $row['C'];
                                $D3 = $row['D'];
                                echo "<td>$A3</td>";
                                echo "<td>$B3</td>";
                                echo "<td>$C3</td>";
                                echo "<td>$D3</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Дисциплины</a> </h3>
                <div id="Disc">
                    <table>
                        <?php
                        /**
                         * Получаем список дисциплин
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql5 = "SELECT Disc.Nazv FROM Disc";
                            $result5 = mysqli_query($con, $sql5);
                            while ($row = $result5->fetch_assoc()){
                                echo "<tr>";
                                $d = $row['Nazv'];
                                echo "<td>$d</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Специальности</a> </h3>
                <div id="Spec">
                    <table>
                        <?php
                        /**
                         * Получаем список специальностей
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql6 = "SELECT Spec.Nazv FROM Spec";
                            $result6 = mysqli_query($con, $sql6);
                            while ($row = $result6->fetch_assoc()){
                                echo "<tr>";
                                $s = $row['Nazv'];
                                echo "<td>$s</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Группы</a> </h3>
                <div id="Group">
                    <table>
                        <?php
                        /**
                         * Получаем список групп
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql7 = "SELECT vuz1.Group.Group FROM vuz1.Group";
                            $result7 = mysqli_query($con, $sql7);
                            while ($row = $result7->fetch_assoc()){
                                echo "<tr>";
                                $g = $row['Group'];
                                echo "<td>$g</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Формы отчёта</a> </h3>
                <div id="Fotch">
                    <table>
                        <?php
                        /**
                         * Получаем список форм отчёта
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql8 = "SELECT Form_Otch.Otchet FROM Form_Otch";
                            $result8 = mysqli_query($con, $sql8);
                            while ($row = $result8->fetch_assoc()){
                                echo "<tr>";
                                $fo = $row['Otchet'];
                                echo "<td>$fo</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Семестры</a> </h3>
                <div id="Sem">
                    <table>
                        <?php
                        /**
                         * Получаем список семестров (а почему бы и нет?)
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql9 = "SELECT Semestr.Semestr FROM Semestr";
                            $result9 = mysqli_query($con, $sql9);
                            while ($row = $result9->fetch_assoc()){
                                echo "<tr>";
                                $sem = $row['Semestr'];
                                echo "<td>$sem</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3><a href="#">Аспиранты</a> </h3>
                <div id="Aspir">
                    <table>
                        <tr>
                            <td>ФИО аспиранта</td>
                            <td>Специальность</td>
                            <td>ФИО преподавателя</td>
                            <td>Кафедра</td>
                        </tr>
                        <?php
                        /**
                         * Получаем список аспирантов
                         */
                        if ($_SESSION['group']==$sesgr){
                            $sql10 = "SELECT Aspir.Family as A1, 
                                        Aspir.Imya as A2, 
                                        Aspir.Otchestvo as A3, 
                                        Spec.Nazv as B, 
                                        Prepod.Familya as C1, 
                                        Prepod.Imya as C2, 
                                        Prepod.Otchestvo as C3, 
                                        Kafedra.Kafedra as D 
                                        FROM Aspir 
                                        INNER JOIN Prepod ON Aspir.Prepod = Prepod.id 
                                        INNER JOIN Spec ON Aspir.Spec = Spec.id 
                                        INNER JOIN Kafedra ON Prepod.id_Kaf = Kafedra.id";
                            $result10 = mysqli_query($con, $sql10);
                            while ($row = $result10->fetch_assoc()){
                                $A4 = $row['A1']." ".$row['A2']." ".$row['A3'];
                                $B4 = $row['B'];
                                $C4 = $row['C1']." ".$row['C2']." ".$row['C3'];
                                $D4 = $row['D'];
                                echo "<tr>";
                                echo "<td>$A4</td>";
                                echo "<td>$B4</td>";
                                echo "<td>$C4</td>";
                                echo "<td>$D4</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="footer">
            Наполни меня
            <h5>Выход</h5>
            <form method="POST">
                <input name="logout" type="submit" value="Выйти">
            </form>
            <?php
            /**
             * Выходим из аккаунта
             */
            if (isset($_POST['logout'])){
                unset($_SESSION['group']);
                unset($_SESSION['username']);
                session_destroy();
                echo "<script type=\"text/javascript\">";
                echo "window.location=\"index.php\"";
                echo "</script>";
            }
            
            ?>

        </div>
        
    </body>
</html>