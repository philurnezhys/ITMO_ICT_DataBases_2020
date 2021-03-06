<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Стоимость Приема</title>
</head>


<?php

$data = null;
$status = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $dbuser = 'postgres';		$dbpassword = '756831';		$host = 'localhost';		$dbname = 'db_lab';

    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser , $dbpassword );

    if (isset($_POST["delete"])) {
        $sql = 'DELETE from lab3."Reception_cost" where "id_reception" = :id_reception';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id_reception' => $_POST["id_reception"]));
        $data = $sth->fetchAll();
        if(count($data) > 0){
            $status = "Запись удалена";
        }
        else{
            $status = "Поля других таблиц зависимы от данного, удалите сначала там:)";
        }
    }
    if (isset($_POST["find"])) {
        $sql = 'SELECT * from lab3."Reception_cost" where "id_reception" = :id_reception';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute([':id_reception' => $_POST["id_reception"]]);
        $data = $sth->fetchAll();
        print_r ($sth->errorInfo()[2]);
        if(count($data) > 0){
            $status = "Запись найдена";
        }
        else{
            $status = "Запись не найдена";
        }
    }
    if (isset($_POST["edit"])) {
        if($_POST["id_reception"] != ""){
            $sql = 'SELECT * from lab3."Reception_cost" where "id_reception" = :id_reception';
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id_reception' => intval($_POST["id_reception"])));
            $data = $sth->fetchAll();
        }
        if($_POST["id_reception"] != "" && count($data) > 0){
            $sql = 'UPDATE lab3."Reception_cost" SET "reception_price" = :reception_price, "reception_name" = :reception_name, "reception_descr" = :reception_descr where "id_reception" = :id_reception';
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':id_reception' => $_POST["id_reception"],':cost' => $_POST["reception_price"],':reception_name' => $_POST["reception_name"],':reception_descr' => $_POST["reception_descr"]));
            $data = $sth->fetchAll();
            print_r ($sth->errorInfo()[2]);
            $status = "Запись изменена";
            $data = null;

        }else{
            $status = "Упс, что-то пошло не так... введите id";
        }
    }
    if (isset($_POST["add"])){
        $sql = 'INSERT INTO lab3."Reception_cost"("reception_price", "reception_name", "reception_descr") VALUES (:reception_price, :reception_name, :reception_descr)';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':reception_price' => $_POST["reception_price"],':reception_name' => $_POST["reception_name"],':reception_descr' => $_POST["reception_descr"]));
        $data = $sth->fetchAll();
        print_r ($sth->errorInfo()[2]);
        $status = "Запись добавлена";
        $data = null;
    }

}
?>



<body>
<form action="" method="post">
    <input name="id_reception" placeholder="id..." value="<?php echo '' ?>"></br>
    <button type="submit" name="find">Найти </button>
    <button type="submit" name="delete">Удалить</button>


</form>
<?php echo $status ?>
</br>
<form action="" method="post">
    <input name="id_priem" size="40" placeholder="id..." value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['id_reception']?>"> <-id</br>
    <input name="cost" size="40" placeholder="Стоимость..." value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['reception_price']?>"> <-Стоимость</br>
    <input name="title" size="40" placeholder="Название..." value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['reception_name']?>"> <-Название</br>
    <input name="description" size="40" placeholder="Описание..." value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['reception_descr']?>"> <-Описание </br>
    <button type="submit" name="add">Добавить</button>
    <button type="submit" name="edit">Редактировать</button>
</form>
</body>
</html>