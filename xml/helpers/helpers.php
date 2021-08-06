<?php 

function CNPJVerifique($file){
    $extensao = strtolower(substr($file, -4));
    if($extensao == ".xml"){
    $xml = simplexml_load_file($file);
           if(isset($xml->NFe->infNFe->emit)){
               foreach($xml->NFe->infNFe->emit as $value){
                   if($value->CNPJ == "09066241000884"){
                   return true;
                   }
           } 
        
       }
   }
}

function ProtocoloVerifique($file){
    $extensao = strtolower(substr($file, -4));
    if($extensao == ".xml")
    {
        $xml = simplexml_load_file($file);
        if(isset($xml->protNFe->infProt)){
            foreach($xml->protNFe->infProt as $value)
            {
                if($value->nProt != "")
                {
                   return true;
                } 
            }
        }
    }
}

function Inserir_no_banco($file,$conn){
    
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
                                                 $conn->query($sql_code);
                                          
    }

    function return_objeto($file){
        $xml = simplexml_load_file($file);      
        $destino = ($xml->infNFe->dest == null ? $xml->NFe->infNFe->dest : $xml->infNFe->dest );
        $ide = ($xml->infNFe->ide == null ? $xml->NFe->infNFe->ide : $xml->infNFe->ide );
        $valorTotal = ($xml->nfeProc == null ? $xml->NFe->infNFe : $xml->nfeProc );
        
        @$valorTotal = ($xml->NFe->infNFe->total->ICMSTot->vNF == null ?  $xml->infNFe->total->ICMSTot->vNF : $xml->NFe->infNFe->total->ICMSTot->vNF);
        
        
        
        $dados = $destino->enderDest;
        
        $xNome = $destino->xNome;
        $Cpf = $destino->CPF;
        $endereco = $dados->enderDest;
        $xLgr = $dados->xLgr;
        $nro =  $dados->nro;
        $Cpl = $dados->xCpl;
        $Bairro= $dados->xBairro;
        $Mun = $dados->cMun;
        $Uf = $dados->UF;
        $Cep= $dados->CEP;
        $cPais= $dados->cPais;
        $xpais= $dados->cPais;
        
        $CNF = $ide->cNF;
        $data = $ide->dhEmi;
        $Json = ['nome'=>$xNome , 'Cpf'=>$Cpf , 'endereco'=>$endereco, "logadora"=>$xLgr , 
        'numero'=>$nro, 'Cpl'=>$Cpl, 'Bairro'=>$Bairro,'municipio'=>$Mun, 
        'Uf'=>$Uf , 'Cep'=>$Cep, 'Cpais'=>$cPais ,'Xpais'=>$xpais,
        'CNF'=>$CNF];

        return json_encode($Json);
        }




?>