<?php

  include("conexao.php");
  include('include/header.php');
  include('helpers/helpers.php');
 ini_set('default_charset',"UTF-8");
 $msg = false;
 $info = false;

 $file = "xml.xml";
 


 if (isset($_FILES['arquivo'])) {
   $file = $_FILES['arquivo']['name'];
    $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); //pega a extensao do arquivo
    $novo_nome = md5(time()) . $extensao; //define o nome do arquivo
    $diretorio = "upload/"; //define o diretorio para onde enviaremos o arquivo
    $Fazer_upload = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
  
    
    $flag = 1;

    if($extensao !=  ".xml") {
     echo   $msg = "Arquivo deve ser um xml";
        $class = "danger";
        $flag = 0;
        exit;      
    }


    if(!CNPJVerifique($_FILES['arquivo']['name'])){
        $msg = "O sistema deve permitir somente o upload do arquivo xml se o campo CNPJ do emitente(<emit>) for 09066241000884";
        $class = "danger";
        $flag = 0;
    }

    if(!ProtocoloVerifique($_FILES['arquivo']['name'])){
        $info = "validação se nota possui protocolo de autorização preenchido";
        $class_alert = "secondary";
        $flag = 2;
    }

    if(!$Fazer_upload){
        $msg = "Erro ao Fazer Upload";
        $class = "danger";
        $flag = 0;
    }

    if($flag){       
      $class = "success";
      $msg = "Arquivo enviado com sucesso";
    }
     
       
        
}

    
      
 


?>
<div class="container">
<h1>Upload de Arquivos</h1>
  <p><a href="index.php" class="btn btn-success">Listar</a></p>
  <?php if(isset($msg) && $msg != false):?>
  <div class="alert-<?=$class ?>">
 <?= $msg ?>
  </div>
  <?php endif; ?>

  <?php if(isset($class_alert) && $class_alert != false):?>
  <div class="alert-dark">
 <?= $info ?>
  </div>
  <?php endif; ?>

  
<form action="upload.php" method="POST" enctype="multipart/form-data">
  Arquivo: <input type="file" required name="arquivo">
  <input type="submit" value="Salvar">
</form>
</div>