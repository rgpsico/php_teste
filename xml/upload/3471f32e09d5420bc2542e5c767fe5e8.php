<?php

  include("conexao.php");
  include('include/header.php');
  include('helpers/helpers.php');
 ini_set('default_charset',"UTF-8");
 $msg = false;


 $file = "xml.xml";
 


 if (isset($_FILES['arquivo'])) {
   $file = $_FILES['arquivo']['name'];
    $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); //pega a extensao do arquivo
    $novo_nome = md5(time()) . $extensao; //define o nome do arquivo
    $diretorio = "upload/"; //define o diretorio para onde enviaremos o arquivo
    $Fazer_upload = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);

    $xml = simplexml_load_file($file);

    $Validar = ($xml->infNFe->dest == null ? $xml->NFe->infNFe->dest : $xml->infNFe->dest );

    $data = ($xml->infNFe->ide == null ? $xml->NFe->infNFe->ide : $xml->infNFe->ide );

    $val = ($xml->infNFe == null ? $xml->NFe->infNFe : $xml->infNFe );

    $CNF = $data->cNF;


   $dataEmissao = $data->dhEmi;

    @$valorTotal =  $val->total->ICMSTot->vNF;

    $nome = $Validar->xNome;
    $cpf  = $Validar->CPF;   
   
   $destinatario = $Validar->enderDest;
   
   $logadoro  = $destinatario->xLgr;
   $numero    = $destinatario->nro;
   $xCpl      = $destinatario->xCpl;
   $cMun      = $destinatario->cMun;
   $xMun      = $destinatario->xMun;
   $xBairro   = $destinatario->xBairro;
   $UF        = $destinatario->UF;
   $CEP       = $destinatario->CEP;
   $cPais     = $destinatario->cPais;
   $Email     = $Validar->email;
   $indIEDest = $Validar->indIEDest;


   $sql_code = "INSERT INTO  `arquivo` (`data`, `cpf`, `nome`, `logadoro`, `numero`, `complemento`, 
                                `bairro`, `cmun`, `xmun`, `uf`, `cep`, `cpais`, `IeDest`,
                                 `email`, `valornota`,
                                  `numerodanota`) VALUES 
                                             ('$dataEmissao','$cpf' , '$nome', 
                                             '$logadoro', '$numero', '$xCpl', 
                                             '$xBairro','$cMun','$xMun','$UF', 
                                             '$CEP','$cPais','$indIEDest',
                                             '$Email','$valorTotal','$CNF' )";
                                          

$flag = 1;

    if($extensao !=  ".xml") {
        $msg = "Arquivo deve ser um xml";
        $class = "danger";
        $flag = 0;      
    }


    if(!CNPJVerifique($_FILES['arquivo']['name'])){
        $msg = "CNPJ Não é o requirido";
        $class = "danger";
        $flag = 0;
    }

    if(!ProtocoloVerifique($_FILES['arquivo']['name'])){
        $msg = "numero de protocolo não preenchido";
        $class = "danger";
        $flag = 0;
    }

    if(!$Fazer_upload){
        $msg = "Erro ao Fazer Upload";
        $class = "danger";
        $flag = 0;
    }

    if($flag){
      $mysqli->query($sql_code);
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

  
<form action="upload.php" method="POST" enctype="multipart/form-data">
  Arquivo: <input type="file" required name="arquivo">
  <input type="submit" value="Salvar">
</form>
</div>