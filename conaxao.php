<?php
class conexao{
	
	public $ip='localhost';
	public $banco='seu_banco';
	public $usuario='root';
	public $senha='';

//--------------------------------------------------
	public function sql_con_query($sql=NULL,$con_verif=NULL){
		$banco=$this->banco;
		if($con_verif==true){
			$banco='';
		}//if
		try{	
			@$con = new PDO('mysql:host='.$this->ip.';dbname='.$banco, $this->usuario, $this->senha);
    		@$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			if($sql!=NULL){
				$con=$con->query($sql);
				if($banco!=''){
					$re = $con->fetchAll();
				}else{
					$re=$con;
				}//else
				   
			}else{
				$re=$con;
			}//else
			
		}catch (PDOException $re) {
			$re=false;
		}//catch

		return $re;
	}//metodo
//---------------------------------------------------------
	public function create_database(){
		if( $this->sql_con_query(NULL,true)==true && $this->sql_con_query()==false ){
			$re=$this->sql_con_query('create database '.$this->banco.' CHARACTER SET utf8 COLLATE utf8_bin',true);
		}else{
			$re=false;
		}//else
		
		return $re;
	}//metodo
//------------------------------------------------------------
	public function importa_tabelas($diretorio){
	
		$arquivo = fopen ($diretorio, 'r');
		$sql=feof($arquivo);
		while(!feof($arquivo)){
			$linha = fgets($arquivo, 1024);
			@$sql=$sql.$linha.' ';
		}//while
		fclose($arquivo);
		return $this->sql_con_query($sql);
	}//metodo
//------------------------------------------------------------

}//class
/////////////////////////////////////////////////////////////////////////////////

//aplicando cod
//------------------------------------------------------------
$con= new conexao();

//vc pode modificar sua conexao
$con->ip='localhost';
$con->banco='seu_banco';
$con->usuario='root';
$con->senha='';

//cria uma conexao
if($con->create_database()!=false){
	$con->importa_tabelas("diretorio/nome_arquivo_sql.sql");
	echo 'foi criado um banco de dados chamado ('.$con->banco.') para estabelecer a sua conexao';
	
}//if

//vc pode iniciar uma consulta com 
$dao= $con->sql_con_query('select * from sua_tabela');

//isso ira retornar em uma array
print_r($dao);
/*exemplo: 
0=>
	['nome_coluna_1']=>'valor coluna 1 linha 1',
	[0]=>'valor coluna 1 linha 1',
	[nome_coluna_2]=>'valor coluna 2 linha 1',
	[1]=>'valor coluna 2 linha 1'	
	
1=>
	['nome_coluna_1']=>'valor coluna 1 linha 2',
	[0]=>'valor coluna 1 linha 2',
	[nome_coluna_2]=>'valor coluna 2 linha 2',
	[1]=>'valor coluna 2 linha 2'
*/



?>
