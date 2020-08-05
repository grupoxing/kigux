<?php

 include("../../php/conexao.php");

 if (isset($_POST['setup'])) {
     if ($_POST['setup'] == "new") {
         mysql_query("CREATE TABLE amg (
                      id int(11) NOT NULL auto_increment,
                      id_us VARCHAR(30) NOT NULL,
                      id_am VARCHAR(30) NOT NULL,
                      date int(10) NOT NULL,
                      status int(1) NOT NULL,
                      PRIMARY KEY (id)
         )");
         mysql_query("CREATE TABLE seg (
                      id int(11) NOT NULL auto_increment,
                      id_ad VARCHAR(30) NOT NULL,
                      id_ac VARCHAR(30) NOT NULL,
                      date int(10) NOT NULL,
                      PRIMARY KEY (id)
         )");
     }elseif ($_POST['setup'] == "delete"){
         mysql_query("DROP TABLE amg");
         mysql_query("DROP TABLE seg");
     }
 }
 
 // Testar Existencia das Tabelas
 if (mysql_query("SELECT * FROM amg") && mysql_query("SELECT * FROM seg")) {
     $status = "<font color='#008000'>ON</font>";
 }else{
     $status = "<font color='#cd0000'>OFF</font>";
 }
 
?>

<form action="?" method="post">
      &nbsp;SETUP iAMG <?php echo $status; ?><BR>
      &nbsp;Instar:<input type="radio" name="setup" value="new">
      &nbsp;Deletar:<input type="radio" name="setup" value="delete">
      &nbsp;<input value="&nbsp;Setup&nbsp;" type="submit"><br><br>
</form>