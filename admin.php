н<html>
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
        if (!$_SESSION['group'] | $_SESSION['group'] == 2){
            echo "<script type=\"text/javascript\">";
            echo "window.location=\"index.php\"";
            echo "</script>";
        }
        ?>
        <script type="text/javascript" src="external/jquery/jquery.js"></script>
        <script type="text/javascript" src="jquery-ui.js"></script>
        <!-- Аккордеон для отображения, а также возможность адекватно выбирать дисциплины, преподов, семестры, группы -->
        <script type="text/javascript">
            $(function(){
                $("#accordion").accordion({
                    active: false,
                    navigation: true,
                    collapsible: true

                });
                $("#prepselect").change(function () {
                    var singleVal = $(this).val();
                    $("#fioprep").val(singleVal);
                });
                $("#discselect").change(function () {
                    var multiValue = $(this).val();
                    $("#discip").val(multiValue.join(", "));
                });
                $("#Semest").change(function () {
                    var multiValueSem = $(this).val();
                    $("#SemCon").val(multiValueSem.join(", "));
                });
                $("#Semestt").change(function () {
                    var multiValueSemNag = $(this).val();
                    $("#SemSelNagr").val(multiValueSemNag.join(", "));
                });
                $("#groupNagr").change(function () {
                    var multiValueGrNag = $(this).val();
                    $("#grNag").val(multiValueGrNag.join(", "));
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
                             * Получаем список кафедр с адресами страниц для меню
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
                <h3><a href="#">Добавить кафедру</a> </h3>
                <div id="kafedra">
                    <form method="POST">
                        <input name="kaf_naim" type="text" placeholder="Название кафедры">
                        <input name="kaf_URL" type="text" placeholder="Введите URL кафедры">
                        <input name="kaf_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем новую кафедру
                     */
                    if (isset($_POST['kaf_acc']) & $_SESSION['group']==1){
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $kaf = "'".$_POST['kaf_naim']."'";
                        $kafurl = "'".$_POST['kaf_URL']."'";
                        $sql = "INSERT INTO 
                                    Kafedra (Kafedra) 
                                VALUES 
                                    ($kaf);";
                        if (mysqli_query($con, $sql)){
                            echo "Успешно";
                        }
                        else {
                            echo mysqli_error($con);
                        }
                        $concont = new mysqli('localhost', 'root', '', 'vuz_of', '3306');
                        $sqlcont = "INSERT INTO 
                                      Kafedra (Nazv, Adress) 
                                    VALUES 
                                      ($kaf, $kafurl)";
                        if (mysqli_query($concont, $sqlcont)){
                            echo "Успешно";
                        }
                        else{
                            echo mysqli_error($concont);
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить должность</a> </h3>
                <div id="dolj">
                    <form method="POST">
                        <input name="dolj_naim" type="text" placeholder="Название">
                        <input name="dolj_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем должности
                     */
                    if (isset($_POST['dolj_acc']) & $_SESSION['group']==1){
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $Dolg = "'".$_POST['dolj_naim']."'";
                        $sql = "INSERT INTO 
                                  Dolj(Dolg) 
                                VALUES 
                                  ($Dolg)";
                        if (mysqli_query($con, $sql)){
                            echo 'Успешно';
                        }
                        else {
                            mysqli_error($con);
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить преподавателя</a> </h3>
                <div id="Prepod">
                    <form method="POST">
                        <input name="familiya" type="text" placeholder="Фамилия">
                        <input name="imya" type="text" placeholder="Имя">
                        <input name="otchestvo" type="text" placeholder="Отчество">
                        <select id="dolj_sel" name="dolg">
                            <option>Выберите должность</option>
                            <?php
                            /**
                             * Получаем список должностей для выбора
                             */
                            $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                            $sqll = "SELECT Dolg as A FROM Dolj";
                            $res = mysqli_query($con, $sqll);
                            while ($row = $res->fetch_assoc()){
                                $doljn = $row['A'];
                                echo "<option>$doljn</option>";
                            }
                            ?>
                        </select>
                        <select id="kaf_sel" name="kafedra" >
                            <option>Выберите кафедру</option>
                            <?php
                            /**
                             * Получаем список кафедр для выбора
                             */
                            $sqlkaf = 'SELECT Kafedra as A FROM Kafedra';
                            $reskaf = $con->query($sqlkaf);
                            while ($row = $reskaf->fetch_assoc()){
                                $kfdr = $row['A'];
                                echo "<option>$kfdr</option>";
                            }
                            ?>
                        </select>
                        <input name="prepod_dob" type="submit">
                    </form>

                    <?php
                    /**
                     * Добавляем препода
                     */
                    if (isset($_POST['prepod_dob']) & $_SESSION['group']==1){
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $fam = "'".$_POST['familiya']."'";
                        $imya = "'".$_POST['imya']."'";
                        $otch = "'".$_POST['otchestvo']."'";
                        try {
                            if ($_POST['dolg'] != "Выберите должность") {
                                $dolg = "'" . $_POST['dolg'] . "'";
                            }
                            else {
                                $ex = "Вы не можете оставить стандартное значение";
                                throw new Exception($ex);
                            }
                            if ($_POST['kafedra'] != "Выберите кафедру"){
                                $kafedr = "'" . $_POST['kafedra'] . "'";
                            }
                            else {
                                $ex = "Вы не можете оставить стандартное значение";
                                throw new Exception($ex);
                            }
                            $sql1 = "SELECT id 
                                     FROM Dolj 
                                     WHERE Dolg = $dolg;";
                            $result = mysqli_query($con, $sql1);
                            while ($row = $result->fetch_assoc()) {
                                $did = $row['id'];
                            }
                            $sql2 = "SELECT id 
                                     FROM Kafedra 
                                     WHERE Kafedra = $kafedr;";
                            $result1 = mysqli_query($con, $sql2);
                            while ($row = $result1->fetch_assoc()) {
                                $kid = $row['id'];
                            }
                            $sql3 = "INSERT INTO 
                                        Prepod (Familya, Imya, Otchestvo, Id_Dolg, Id_Kaf) 
                                     VALUES 
                                        ($fam, $imya, $otch, $did, $kid)";
                            if (mysqli_query($con, $sql3)) {
                                echo 'Успешно';
                            } else {
                                echo mysqli_error($con);
                            }
                        }
                        catch (Exception $ex){
                            echo "Вы не можете оставить стандартное значение";
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить дисциплину</a> </h3>
                <div id="Disc">
                    <form method="POST">
                        <input name="Nazv_disc" type="text" placeholder="Название">
                        <input name="Disc_dobav" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем дисциплину
                     */
                    if (isset($_POST['Disc_dobav']) & $_SESSION['group']==1){
                        $nazv = "'".$_POST['Nazv_disc']."'";
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $sql = "INSERT INTO 
                                  Disc (Nazv) 
                                VALUES 
                                  ($nazv)";
                        if (mysqli_query($con, $sql)){
                            echo 'Успешно';
                        }
                        else {
                            mysqli_errno($con);
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить дисциплины преподавателю</a> </h3>
                <div id="Disc_Prep">
                    <form method="POST">
                        <input name="fio_prep" type="text" id="fioprep" style="display: none">
                        <input name="Discs" type="text" id="discip" style="display: none">
                        <select id="prepselect">
                            <option>Выберите преподавателя</option>
                            <?php
                            /**
                             * Выбираем препода
                             */
                            $sqlp = "SELECT Prepod.Familya as A1, 
                                     Prepod.Imya as A2, 
                                     Prepod.Otchestvo as A3 
                                     FROM Prepod";
                            $respr = $con->query($sqlp);
                            while ($row = $respr->fetch_assoc()){
                                $prepsel = $row['A1']." ".$row['A2']." ".$row['A3'];
                                echo "<option>$prepsel</option>";
                            }
                            ?>
                        </select>
                        <select id="discselect" multiple="multiple">
                            <option disabled>Выберите дисциплины</option>
                            <?php
                            /**
                             * Выбираем дисциплины
                             */
                            $sqld = "SELECT Nazv as A FROM Disc";
                            $resdis = $con->query($sqld);
                            while ($row = $resdis->fetch_assoc()){
                                $discvyv = $row['A'];
                                echo "<option>$discvyv</option>";
                            }
                            ?>
                        </select>
                        <input name="Disc_Prep_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем преподу дисциплины
                     */
                    if (isset($_POST['Disc_Prep_acc']) & $_SESSION['group']==1){
                        //echo $_POST['toP'];
                        $prep = $_POST['fio_prep'];
                        $discs = $_POST['Discs'];
                        $prep1 = array();
                        $j = 0;
                        $prep1[0] = "";
                        for ($i = 0; $i < strlen($prep); $i++){
                            if ($prep[$i] == " "){
                                $j++;
                                $prep1[$j] = "";
                            }
                            else {
                                $prep1[$j] .= $prep[$i];
                            }
                        }
                        $sqlp = "SELECT id 
                                 FROM Prepod 
                                 WHERE Familya = '$prep1[0]' 
                                 AND Imya = '$prep1[1]' 
                                 AND Otchestvo = '$prep1[2]'";
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $result = mysqli_query($con, $sqlp);
                        while ($row = $result->fetch_assoc()){
                            $pid = $row['id'];
                            //echo $row['id'];
                        }
                        //echo $pid;
                        $discs1 = array();
                        $jj = 0;
                        $discs1[0] = "";
                        for ($i = 0; $i < strlen($discs); $i++){
                            if ($discs[$i] == ","){
                                $jj++;
                                $discs1[$jj] = "";
                            }
                            else {
                                $discs1[$jj] .= $discs[$i];
                            }
                        }
                        $discs2 = array();
                        for ($i = 0; $i < count($discs1); $i++){
                            $discs2[$i] = trim($discs1[$i]);
                        }
                        $did = array();
                        //print_r($discs2);
                        for ($i = 0; $i < count($discs2); $i++){
                            $sqll = "SELECT id 
                                     FROM Disc 
                                     WHERE Nazv = '$discs2[$i]'";
                            $result3 = mysqli_query($con, $sqll);
                            while ($row = $result3->fetch_assoc()){
                                $did[$i] = $row['id'];
                            }
                        }
                        //print_r($did);
                        for ($i = 0; $i < count($did); $i++){
                            $sqlf = "INSERT INTO 
                                      Disc_Prep (id_Disc, id_Prepod) 
                                     VALUES 
                                      ($did[$i], $pid)";
                            if (mysqli_query($con, $sqlf)){
                                echo 'Успешно';
                            }
                            else {
                                echo mysqli_error($con);
                            }
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить специальность</a> </h3>
                <div id="Spec">
                    <form method="POST">
                        <input name="spec" type="text" placeholder="Название">
                        <input name="spec_dobav" type="submit">
                    </form>
                </div>
                <?php
                /**
                 * Добавляем специальность
                 */
                if (isset($_POST['spec_dobav']) & $_SESSION['group']==1){
                    $spec = "'".$_POST['spec']."'";
                    $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                    $sql = "INSERT INTO 
                              Spec (Nazv) 
                            VALUES 
                              ($spec)";
                    if (mysqli_query($con, $sql)){
                        echo 'Успешно';
                    }
                    else{
                        echo mysqli_error($con);
                    }
                }
                ?>
                <h3><a href="#">Добавить форму отчёта</a> </h3>
                <div id="Otch">
                    <form method="POST">
                        <input name="otch" type="text" placeholder="Название">
                        <input name="otch_dobav" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем форму отчёта
                     */
                    if (isset($_POST['otch_dobav']) & $_SESSION['group']==1){
                        $otch = "'".$_POST['otch']."'";
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $sql = "INSERT INTO 
                                  Form_Otch (Otchet) 
                                VALUES 
                                  ($otch)";
                        if (mysqli_query($con, $sql)){
                            echo 'Успешно';
                        }
                        else {
                            echo mysqli_error($con);
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Создать учебный план</a> </h3>
                <div id="plan">
                    <form method="POST">
                        <input name="Kol_chas" type="text" placeholder="Количество часов">
                        <select name="Special">
                            <option>Выберите специальность</option>
                            <?php
                            /**
                             * Выбираем специальность
                             */
                            $sqlspec = "SELECT Nazv 
                                        FROM Spec";
                            $resspec = $con->query($sqlspec);
                            while ($row = $resspec->fetch_assoc()){
                                $specnazv = $row['Nazv'];
                                echo "<option>$specnazv</option>";
                            }
                            ?>
                        </select>
                        <select name="Discip">
                            <option>Выберите дисциплину</option>
                            <?php
                            /**
                             * Выбираем дисциплину
                             */
                            $sqldis = "SELECT Nazv 
                                       FROM Disc";
                            $resdisc = $con->query($sqldis);
                            while ($row = $resdisc->fetch_assoc()){
                                $discnazv = $row['Nazv'];
                                echo "<option>$discnazv</option>";
                            }
                            ?>
                        </select>
                        <select name="fotch">
                            <option>Выберите форму отчёта</option>
                            <?php
                            /**
                             * Выбираем форму отчёта
                             */
                            $sqlfo = "SELECT Otchet as A 
                                      FROM Form_Otch";
                            $resfo = $con->query($sqlfo);
                            while ($row = $resfo->fetch_assoc()){
                                $foname = $row['A'];
                                echo "<option>$foname</option>";
                            }
                            ?>
                        </select>
                        <input id="SemCon" name="Semest" type="text" style="display: none">
                        <select id="Semest" multiple="multiple">
                            <option disabled>Выберите семестры</option>
                            <?php
                            /**
                             * Выбираем семестры
                             */
                            $sqlsem = "SELECT Semestr as A 
                                       FROM Semestr";
                            $ressem = $con->query($sqlsem);
                            while ($row = $ressem->fetch_assoc()){
                                $semname = $row['A'];
                                echo "<option>$semname</option>";
                            }
                            ?>
                        </select>
                        <input name="plan_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем учебный план
                     */
                    if (isset($_POST['plan_acc']) & $_SESSION['group']==1){
                        $kch = $_POST['Kol_chas'];
                        $spec = "'".$_POST['Special']."'";
                        $disc = "'".$_POST['Discip']."'";
                        $fo = "'".$_POST['fotch']."'";
                        $sem1 = $_POST['Semest'];
                        $sem2 = str_replace(" ", "", $sem1);
                        $sem3 = array();
                        $j = 0;
                        for ($i=0; $i < iconv_strlen($sem2); $i++){
                            if (is_numeric($sem2[$i])){
                                $sem3[$j] = $sem2[$i];
                                $j++;
                            }
                        }
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $sql1 = "SELECT id 
                                 FROM Disc 
                                 WHERE Nazv = $disc";
                        $result1 = mysqli_query($con, $sql1);
                        while ($row = $result1->fetch_assoc()){
                            $did = $row['id'];
                        }
                        $sql2 = "SELECT id 
                                 FROM Spec 
                                 WHERE Nazv = $spec";
                        $result2 = mysqli_query($con, $sql2);
                        while ($row = $result2->fetch_assoc()){
                            $sid = $row['id'];
                        }
                        $sql3 = "SELECT id 
                                 FROM Form_Otch 
                                 WHERE Otchet = $fo";
                        $result3 = mysqli_query($con, $sql3);
                        while ($row = $result3->fetch_assoc()){
                            $oid = $row['id'];
                        }
                        $sql4 = "INSERT INTO 
                                    Uch_Plan (Kol_chas, Spec, Disc, id_Otch) 
                                 VALUES 
                                    ($kch, $sid, $did, $oid)";
                        if (mysqli_query($con, $sql4)){
                            echo "Успешно";
                        }
                        else {
                            echo mysqli_error($con);
                        }
                        $sql5 = "SELECT MAX(id) 
                                 FROM Uch_Plan";
                        $result4 = mysqli_query($con, $sql5);
                        while ($row = $result4->fetch_assoc()){
                            $mid = $row['MAX(id)'];
                        }
                        for ($i=0; $i < count($sem3); $i++){
                            $sql6 = "INSERT INTO 
                                        Plan_Sem (id_Plan, id_Semestr) 
                                     VALUES 
                                        ($mid, $sem3[$i])";
                            if (mysqli_query($con, $sql6)){
                                echo 'Успешно';
                            }
                            else {
                                echo mysqli_error($con);
                            }
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить группу</a> </h3>
                <div id="group">
                    <form method="POST">
                        <input name="group_naim" type="text" placeholder="Название">
                        <input name="group_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем группу
                     */
                    if (isset($_POST['group_acc']) & $_SESSION['group']==1){
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $naim = "'".$_POST['group_naim']."'";
                        $sql = "INSERT INTO 
                                  vuz1.Group (vuz1.Group.Group) 
                                VALUES 
                                  ($naim);";
                        if (mysqli_query($con, $sql)){
                            echo 'Успешно';
                        }
                        else {
                            echo mysqli_error($con);
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить нагрузку</a> </h3>
                <div id="Nagruz_ochka">
                    <form method="POST">
                        <input name="Kol_chasov" type="text"placeholder="Количество часов">
                        <select name="Specialn">
                            <option>Выберите специальность</option>
                            <?php
                            /**
                             * Выбираем специальность
                             */
                            $sqlspec = "SELECT Nazv FROM Spec";
                            $resspec = $con->query($sqlspec);
                            while ($row = $resspec->fetch_assoc()){
                                $specnazv = $row['Nazv'];
                                echo "<option>$specnazv</option>";
                            }
                            ?>
                        </select>
                        <select name="Discipl">
                            <option>Выберите дисциплину</option>
                            <?php
                            /**
                             * Выбираем дисциплину
                             */
                            $sqldis = "SELECT Nazv FROM Disc";
                            $resdisc = $con->query($sqldis);
                            while ($row = $resdisc->fetch_assoc()){
                                $discnazv = $row['Nazv'];
                                echo "<option>$discnazv</option>";
                            }
                            ?>
                        </select>
                        <select name="fotchet">
                            <option>Выберите форму отчёта</option>
                            <?php
                            /**
                             * Выбираем форму отчёта
                             */
                            $sqlfo = "SELECT Otchet as A FROM Form_Otch";
                            $resfo = $con->query($sqlfo);
                            while ($row = $resfo->fetch_assoc()){
                                $foname = $row['A'];
                                echo "<option>$foname</option>";
                            }
                            ?>
                        </select>
                        <input id="SemSelNagr" name="Semestr" type="text" style="display: none">
                        <select id="Semestt" multiple="multiple">
                            <option disabled>Выберите семестры</option>
                            <?php
                            /**
                             * Выбираем семестры
                             */
                            $sqlsem = "SELECT Semestr as A FROM Semestr";
                            $ressem = $con->query($sqlsem);
                            while ($row = $ressem->fetch_assoc()){
                                $semname = $row['A'];
                                echo "<option>$semname</option>";
                            }
                            ?>
                        </select>
                        <select name="Prepod">
                            <option>Выберите преподавателя</option>
                            <?php
                            /**
                             * Выбираем препода
                             */
                            $sqlpr = "SELECT vuz1.Prepod.Familya as A1, 
                                      vuz1.Prepod.Imya as A2, 
                                      vuz1.Prepod.Otchestvo as A3 
                                      FROM vuz1.Prepod";
                            $respr = $con->query($sqlpr);
                            while ($row = $respr->fetch_assoc()){
                                $prname = $row['A1']." ".$row['A2']." ".$row['A3'];
                                echo "<option>$prname</option>";
                            }
                            ?>
                        </select>
                        <input id="grNag" name="groups" type="text" style="display: none">
                        <select id="groupNagr" multiple="multiple">
                            <option disabled>Выберите группу</option>
                            <?php
                            $sqlgr = "SELECT vuz1.`Group`.`Group` as A 
                                      FROM vuz1.`Group`";
                            $resgr = $con->query($sqlgr);
                            while ($row = $resgr->fetch_assoc()){
                                $grname = $row['A'];
                                echo "<option>$grname</option>";
                            }
                            ?>
                        </select>
                        <input name="nagruz_acc" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем нагрузку
                     */
                    if (isset($_POST['nagruz_acc']) & $_SESSION['group']==1){
                        $kch = $_POST['Kol_chasov'];
                        $spec = "'".$_POST['Specialn']."'";
                        $disc = "'".$_POST['Discipl']."'";
                        $fo = "'".$_POST['fotchet']."'";
                        $sem1 = $_POST['Semestr'];
                        $prep1 = $_POST['Prepod'];
                        $gr1 = str_replace(" ", "", $_POST['groups']);
                        $sem2 = str_replace(" ", "", $sem1);
                        $sem3 = array();
                        $j = 0;
                        for ($i=0; $i < iconv_strlen($sem2); $i++){
                            if (is_numeric($sem2[$i])){
                                $sem3[$j] = $sem2[$i];
                                $j++;
                            }
                        }
                        $prep2 = array();
                        $prep2[0] = "";
                        $jj = 0;
                        for ($i = 0; $i < strlen($prep1); $i++){
                            //echo $prep1[$i];
                            if ($prep1[$i] == " "){
                                $jj++;
                                $prep2[$jj] = "";
                            }
                            else {
                                $prep2[$jj] .= $prep1[$i];
                            }
                        }
                        $gr2 = array();
                        $gr2[0] = "";
                        $jjj = 0;
                        for ($i = 0; $i < strlen($gr1); $i++){
                            if ($gr1[$i] == ","){
                                $jjj++;
                                $gr2[$jjj] = "";
                            }
                            else {
                                $gr2[$jjj] .= $gr1[$i];
                            }
                        }
                        //echo $prep1;
                        //print_r($prep2);
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        $sql1 = "SELECT id 
                                 FROM Disc 
                                 WHERE Nazv = $disc";
                        $result1 = mysqli_query($con, $sql1);
                        while ($row = $result1->fetch_assoc()){
                            $did = $row['id'];
                        }
                        $sql2 = "SELECT id 
                                 FROM Spec 
                                 WHERE Nazv = $spec";
                        $result2 = mysqli_query($con, $sql2);
                        while ($row = $result2->fetch_assoc()){
                            $sid = $row['id'];
                        }
                        $sql3 = "SELECT id 
                                 FROM Form_Otch 
                                 WHERE Otchet = $fo";
                        $result3 = mysqli_query($con, $sql3);
                        while ($row = $result3->fetch_assoc()){
                            $oid = $row['id'];
                        }
                        $sql4 = "SELECT id 
                                 FROM Prepod 
                                 WHERE Familya = '$prep2[0]' 
                                 AND Imya = '$prep2[1]' 
                                 AND Otchestvo = '$prep2[2]'";
                        $result4 = mysqli_query($con, $sql4);
                        while ($row = $result4->fetch_assoc()){
                            $pid = $row['id'];
                        }
                        $sql5 = "INSERT INTO 
                                    Nagruz (Kol_chas, Spec, Disc, id_Otch, id_Prepod) 
                                 VALUES 
                                    ($kch, $sid, $did, $oid, $pid)";
                        if (mysqli_query($con, $sql5)){
                            echo 'Успешно';
                        }
                        else{
                            echo mysqli_error($con);
                        }
                        $sql6 = "SELECT MAX(id) 
                                 FROM Nagruz";
                        $result5 = mysqli_query($con, $sql6);
                        while ($row = $result5->fetch_assoc()){
                            $mid = $row['MAX(id)'];
                        }
                        for ($i = 0; $i < count($sem3); $i++){
                            $sql7 = "INSERT INTO 
                                        Nagruz_Sem (id_Nagruz, id_Semestr)
                                     VALUES 
                                        ($mid, $sem3[$i])";
                            if (mysqli_query($con, $sql7)){
                                echo 'Успешно';
                            }
                            else{
                                echo mysqli_error($con);
                            }
                        }
                        $grid = array();
                        //print_r($gr2);
                        for ($i = 0; $i < count($gr2); $i++){
                            $sql8 = "SELECT id 
                                     FROM vuz1.Group 
                                     WHERE vuz1.Group.Group = '$gr2[$i]'";
                            $result6 = mysqli_query($con, $sql8);
                            while ($row = $result6->fetch_assoc()){
                                $grid[$i] = $row['id'];
                            }
                        }
                        //print_r($grid);
                        for ($i = 0; $i < count($grid); $i++){
                            $sql9 = "INSERT INTO 
                                        Nagruz_Group (id_Nagruz, id_Group) 
                                     VALUES 
                                        ($mid, $grid[$i])";
                            if (mysqli_query($con, $sql9)){
                                echo 'Успешно';
                            }
                            else {
                                echo mysqli_error($con);
                            }
                        }
                    }
                    ?>
                </div>
                <h3><a href="#">Добавить аспиранта</a> </h3>
                <div id="aspir">
                    <form method="POST">
                        <input name="familiya" type="text" placeholder="Фамилия">
                        <input name="imya" type="text" placeholder="Имя">
                        <input name="otchestvo" type="text" placeholder="Отчество">
                        <select name="spec_asp">
                            <option>Выберите специальность</option>
                            <?php
                            /**
                             * Выбираем специальность
                             */
                            $sqlspec = "SELECT Nazv 
                                        FROM Spec";
                            $resspec = $con->query($sqlspec);
                            while ($row = $resspec->fetch_assoc()){
                                $specnazv = $row['Nazv'];
                                echo "<option>$specnazv</option>";
                            }
                            ?>
                        </select>
                        <select name="prepodav">
                            <option>Выберите преподавателя</option>
                            <?php
                            /**
                             * Выбираем препода
                             */
                            $sqlpr = "SELECT vuz1.Prepod.Familya as A1, 
                                      vuz1.Prepod.Imya as A2, 
                                      vuz1.Prepod.Otchestvo as A3 
                                      FROM vuz1.Prepod";
                            $respr = $con->query($sqlpr);
                            while ($row = $respr->fetch_assoc()){
                                $prname = $row['A1']." ".$row['A2']." ".$row['A3'];
                                echo "<option>$prname</option>";
                            }
                            ?>
                        </select>
                        <input name="asp_dob" type="submit">
                    </form>
                    <?php
                    /**
                     * Добавляем аспиранта
                     */
                    if (isset($_POST['asp_dob']) & $_SESSION['group']==1){
                        $fam = "'".$_POST['familiya']."'";
                        $imya = "'".$_POST['imya']."'";
                        $otch = "'".$_POST['otchestvo']."'";
                        $spec = "'".$_POST['spec_asp']."'";
                        $prep1 = $_POST['prepodav'];
                        $j=0;
                        $prep2[0]="";
                        $con = new mysqli('localhost', 'root', '', 'vuz1', '3306');
                        for ($i = 0; $i < strlen($prep1); $i++){
                            //echo $prep1[$i];
                            if ($prep1[$i] == " "){
                                $j++;
                                $prep2[$j] = "";
                            }
                            else {
                                $prep2[$j] .= $prep1[$i];
                            }
                        }
                        $sql2 = "SELECT id 
                                 FROM Spec 
                                 WHERE Nazv = $spec";
                        $result2 = mysqli_query($con, $sql2);
                        while ($row = $result2->fetch_assoc()){
                            $sid = $row['id'];
                        }
                        $sql4 = "SELECT id 
                                 FROM Prepod 
                                 WHERE Familya = '$prep2[0]' 
                                 AND Imya = '$prep2[1]' 
                                 AND Otchestvo = '$prep2[2]'";
                        $result4 = mysqli_query($con, $sql4);
                        while ($row = $result4->fetch_assoc()){
                            $pid = $row['id'];
                        }
                        $sql = "INSERT INTO 
                                  Aspir (Family, Imya, Otchestvo, Spec, Prepod) 
                                VALUES 
                                  ($fam, $imya, $otch, $sid, $pid)";
                        if (mysqli_query($con, $sql)){
                            echo 'Успешно';
                        }
                        else {
                            echo mysqli_error($con);
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="footer">
            Напиши в меня что-нибудь
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

