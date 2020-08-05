<?php

  include("../../php/conexao.php");

  if (isset($_POST['setup'])) {
      if ($_POST['setup'] == "new") {
          mysql_query("CREATE TABLE actx (
                              id int(11) NOT NULL auto_increment,
                              id_us varchar(30) NOT NULL,
                              id_pg varchar(30) NOT NULL,
                              acion varchar(30) NOT NULL,
                              tabela varchar(30) NOT NULL,
                              idrow varchar(30) NOT NULL,
                              data int(10) NOT NULL,
                              PRIMARY KEY (id),
                              FOREIGN KEY (id_pg) REFERENCES user_perfil(id_us) ON DELETE CASCADE
          )");
      }elseif ($_POST['setup'] == "delete"){
          mysql_query("DROP TABLE actx");
      }
  }
 
  if (mysql_query("SELECT * FROM actx")) {
      $status = "<font color='#008000'>ON</font>";
  }else{
      $status = "<font color='#cd0000'>OFF</font>";
  }
 
?>

<form action="?" method="post">
      &nbsp;SETUP ACTX <?php echo $status; ?><BR>
      &nbsp;Instar:<input type="radio" name="setup" value="new">
      &nbsp;Deletar:<input type="radio" name="setup" value="delete">
      &nbsp;<input value="&nbsp;Setup&nbsp;" type="submit"><br><br>
</form>
