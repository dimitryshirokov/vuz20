<html>
    <!-- СУПЕРМЕГААДМИНКА БЕЗ ДИЗАЙНА
         Использовать для первоначальной настройки-->
    <body>
        <?php
        $con2 = new mysqli('localhost', 'root', '', 'vuz1', '3306');
        $sqlpr = "SELECT * FROM Prepod";
        $resultpr = mysqli_query($con2, $sqlpr);
        while ($row = $resultpr->fetch_assoc()){
            print_r($row);
        }
        ?>
        <h4>Заголовок сайта</h4>
        <form method="POST">
            <input type="text" name="Zagol" placeholder="Введите заголовок сайта">
            <input type="submit" name="Zag_acc">
        </form>
        <h4>Кафедры</h4>
        <form method="POST">
            <input type="text" name="Nazv" placeholder="Введите название кафедры">
            <input type="text" name="URL" placeholder="Введите URL кафедры">
            <input type="submit" name="Kaf_acc">
        </form>
        <h4>Новости</h4>
        <form method="POST">
            <input type="text" name="Zag" placeholder="Введите заголовок новости">
            <input type="text" name="Text" placeholder="Введите текст новости">
            <input type="text" name="ImgURL" placeholder="Введите URL картинки">
            <input type="submit" name="News_acc">
        </form>
        <h4>Новый пользователь</h4>
        <form method="POST">
            <input type="text" name="Login" placeholder="Логин">
            <input type="text" name="Pword" placeholder="Пароль">
            <input type="text" name="Fam" placeholder="Введите фамилию">
            <input type="text" name="Imya" placeholder="Введите имя">
            <input type="text" name="Otch" placeholder="Введите отчество">
            <input type="text" name="Ac_gr" placeholder="Введите уровень доступа 1 или 2">
            <input type="submit" name="us_acc">
        </form>
        <?php
        $con = new mysqli('localhost', 'root', '', 'vuz_of', '3306');
        if (isset($_POST['Zag_acc'])){
            $zag_nazv = $_POST['Zagol'];
            $sql = "INSERT INTO Zagolovok (Nazv) VALUES ('$zag_nazv')";
            if (mysqli_query($con, $sql)){
                echo "Успешно";
            }
            else {
                echo mysqli_error($con);
            }
        }
        if (isset($_POST['Kaf_acc'])){
            $kaf_nazv = "'".$_POST['Nazv']."'";
            $kaf_URL = "'".$_POST['URL']."'";
            $sql = "INSERT INTO Kafedra (Nazv, Adress) VALUES ($kaf_nazv, $kaf_URL)";
            if (mysqli_query($con, $sql)){
                echo "Успешно";
            }
            else {
                echo mysqli_error($con);
            }
        }
        if (isset($_POST['News_acc'])){
            $zag_news = "'".$_POST['Zag']."'";
            $news_text = "'".$_POST['Text']."'";
            $news_img = "'".$_POST['ImgURL']."'";
            $sql = "INSERT INTO News (Zag, Text, Pic) VALUES ($zag_news, $news_text, $news_img)";
            if (mysqli_query($con, $sql)){
                echo "Успешно";
            }
            else {
                echo mysqli_error($con);
            }
        }
        if (isset($_POST['us_acc'])){
            $con1 = new mysqli('localhost', 'root', '', 'vuz1', '3306');
            $log = "'".$_POST['Login']."'";
            $pword = "'".$_POST['Pword']."'";
            $Fam = "'".$_POST['Fam']."'";
            $Imya = "'".$_POST['Imya']."'";
            $Otch = "'".$_POST['Otch']."'";
            $acid = $_POST['Ac_gr'];
            $sql = "INSERT INTO Users (Name, Pword, Family, Imya, Otchestvo, Ac_gr) VALUES ($log, $pword, $Fam, $Imya, $Otch, $acid)";
            if (mysqli_query($con1, $sql)){
                echo "Успешно";
            }
            else {
                echo mysqli_error($con1);
            }
        }
        ?>
    </body>
</html>

