<?php

//-------------------CONEXÃO---------------------------
try {
    $pdo = new PDO("mysql:dbname=CRUDPDO;host=localhost","root",""); 
    /*Ao conectar ao PDO é necessário passar 3 parâmetros
      o primeiro parâmetro informa qual o tipo de banco de dados estará utilizando, o dbname e host
      depois é necessário passar mais 2 parâmetros: usuario e senha */
} catch(PDOException $e){ //por padrão só é criado um catch para qualquer exceção, porém o PDO permite uma exceção própria a erros relacionados ao banco de dados
    echo "Erro com o banco de dados: ".$e->getMessage();
} catch (Exception $e) {
    echo "Erro genérico: ".$e->getMessage();
}


//-------------------INSERT---------------------------

/*É possivel utilizar duas formas "$pdo->prepare();" e "$pdo->query();"
  O primeiro é utilizando quando é necessário utilizar algum parâmetro e depois substitu-lo, 
  já o segundo é quando é passado um comando sem ser necessário fazer nenhuma substituição.*/

//1ºforma
$res = $pdo->prepare("INSERT INTO PESSOA(nome, telefone, email) VALUES(:n, :t, :e)"); //no VALUES você indica que será substituido por algum valor externo através de dois pontos, pode passar qualquer nome

/*para substituir os parametro é possivel utilizar duas opções bindValue e bindParam:
    1ºopção
    $res->bindValue(":n","Miriam"); //Aceita valor passado diretamente, variáveis e funções, por isso é o mais utilizado
    2ºopção
    $nome = "Miriam";
    $res->bindParam(":n",$nome);  - Não aceita um valor passado diretamente, só variáveis */


$res->bindValue(":n","Miriam");
$res->bindValue(":t","988554692");
$res->bindValue(":e","teste@gmail.com");
$res->execute();//Uma vez que foi substituido todos os parametro, o execute finaliza a inserção

//2ºforma
$pdo->query("INSERT INTO PESSOA(nome, telefone, email) VALUES('Andre','988632545','teste2@gmail.com')");

//-------------------DELETE e UPDATE---------------------------
$cmd = $pdo->prepare("DELETE FROM PESSOA WHERE id = :id");
$id = 3;
$cmd->bindValue(":id",$id);
$cmd->execute();
//ou

$cmd = $pdo->query("DELETE FROM PESSOA WHERE id = '5' ");

//---

$cmd = $pdo->prepare("UPDATE PESSOA SET email = :e WHERE id = :id");
$id = 6;
$cmd->bindValue(":e","andre567@outlook.com.br");
$cmd->bindValue(":id",$id);
$cmd->execute();
$cmd = $pdo->query("UPDATE PESSOA SET email = 'dd@gmail.com' WHERE id = '4' ");



//-------------------SELECT---------------------------

$cmd= $pdo->prepare("SELECT * FROM PESSOA WHERE id = :id");
$cmd->bindValue(":id",6);
$cmd->execute();// Neste caso a informação já está aramazenada na variável cmd, porém ainda não está em formato de array

/*Para transformar em array existem dois métodos:
$cmd->fetch();   -  Utilizado quando vem apenas uma linha no resultado do banco
//ou
$cmd->fetchAll(); - Utilizado quando retorna varias linhas no resultado
*/

$resultado = $cmd->fetch(PDO::FETCH_ASSOC);// É necessário passar o resultado para uma variável
var_dump($resultado);

//vale sinalizar que a função fetch retorna dois resultados: 
//tanto pelo nome da coluna quanto a posição do array, 
//basta realizar a seguinte tratativa PDO::FETCH_ASSOC

foreach ($resultado as $key => $value) {
    echo $key.": ".$value."</br>"; 
}

?>