<?php   
include("conexao.php");
include('include/header.php');
include('helpers/helpers.php');








?>


<div class="container">
  <h2>Ver nota XML</h2>
  <p><a href="upload.php" class="btn btn-success">Fazer Upload</a></p>
          
  <table class="table">
    <thead>
      <tr>
        <th>N° NF</th>
        <th>Data Nota</th>
        <th>Dados do destinatario</th>
        <th>Valor total</th>
      </tr>
    </thead>
    <tbody>
   
 
    </tbody>
  </table>
  <form action="" method="POST" enctype="multipart/form-data">
  <input type="file" required id="arquivo" name="arquivo">
  <input type="submit" class="enviar" value="Ver XML">
 </form>
  
</div>

</body>
</html>

<script>

function getFileExtension1(filename) {
  return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename)[0] : undefined;
}

function converter_data(data){
data.substr(0,10);
data.substr(0,10).split("-");
dia = data.substr(0,10).split("-")[2];
mes = data.substr(0,10).split("-")[1];
ano = data.substr(0,10).split("-")[0];
return dia+"/"+mes+"/"+ano;
}


function cadastrar_no_banco(arquivo) {
  $.ajax({
     url : "controller.php",
     type : 'POST',
     data: {cadastrar:arquivo},
     beforeSend : function(){
          $("#resultado").html("ENVIANDO...");
     }
  })
}


  $('.enviar').click(function(event){
      $('tbody').html(" ");
      event.preventDefault();
     let  arq = $('input[type=file]').val().split('\\').pop(); 

     if (getFileExtension1(arq) != "xml"){
        alert("arquivo inválido")
     } else {
      var resultado = confirm("Deseja cadastrar essa nota ?");
      if(resultado) {
        cadastrar_no_banco(arq);
      }
   
        $.ajax({
        method: "POST",
        url: "controller.php",
          data: {enviar:arq},
          success:function(data){
          json = JSON.parse(data);
           string =  
              `<tr>
                  <td>${json.CNF[0]}</td>
                  <td>${converter_data(json.data[0])}</td>               
              
                  <td>
                  Nome: ${json.nome[0]}<br>
                  CPF: ${json.Cpf[0]}<br>
                  CEP: ${json.Cep[0]}<br>
                  UF: ${json.Uf[0]}<br>
                  logadora: ${json.logadora[0]}<br>
                  numero: ${json.numero[0]}<br>
                </td>
                  <td>${json.valorTotal[0]}</td>
              </tr>`;
            $('tbody').append(string);
     }
    })
  }
  });
</script>
