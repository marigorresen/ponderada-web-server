<?php 
include "../inc/dbinfo.inc"; 
?>
<html>
<body>
<h1>Página de Produtos</h1>
<?php

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

VerifyProductsTable($connection, DB_DATABASE);

$products_nome = htmlentities($_POST['nome']);
$products_preco = htmlentities($_POST['preco']);
$products_quantidade = htmlentities($_POST['quantidade']);
$products_validade = htmlentities($_POST['validade']);
$products_fornecedor = htmlentities($_POST['fornecedor']);

if (strlen($products_nome) && strlen($products_preco) && strlen($products_quantidade) && strlen($products_validade) && strlen($products_fornecedor)) {
    AddProduct($connection, $products_nome, $products_preco, $products_quantidade, $products_validade, $products_fornecedor);
}

?>

<h2>Adicionar Novo Produto</h2>
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
    <table>
        <tr>
            <td>Nome:</td>
            <td><input type="text" name="nome" required></td>
        </tr>
        <tr>
            <td>Preço:</td>
            <td><input type="number" name="preco" step="0.01" required></td>
        </tr>
        <tr>
            <td>Quantidade:</td>
            <td><input type="number" name="quantidade" min="1" required></td>
        </tr>
        <tr>
            <td>Validade:</td>
            <td><input type="date" name="validade" required></td>
        </tr>
        <tr>
            <td>Fornecedor:</td>
            <td><input type="text" name="fornecedor" required></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Adicionar Produto"></td>
        </tr>
    </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>Nome</td>
    <td>Preço</td>
    <td>Quantidade</td>
    <td>Validade</td>
    <td>Fornecedor</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM PRODUCTS");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[5], "</td>";
  echo "</tr>";
}

mysqli_free_result($result);
mysqli_close($connection);

?>

</table>
</body>
</html>

<?php
function VerifyProductsTable($connection, $dbName) {
  if (!TableExists("PRODUCTS", $connection, $dbName)) {
      $query = "CREATE TABLE PRODUCTS (
          ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          nome VARCHAR(45) NOT NULL,
          preco DECIMAL(10, 2) NOT NULL,
          quantidade INT NOT NULL,
          validade DATE NOT NULL,
          fornecedor VARCHAR(45) NOT NULL
      )";
      if (!mysqli_query($connection, $query)) echo("<p>Error creating PRODUCTS table.</p>");
  }
}

function AddProduct($connection, $nome, $preco, $quantidade, $validade, $fornecedor) {
  $n = mysqli_real_escape_string($connection, $nome);
  $p = mysqli_real_escape_string($connection, $preco);
  $q = mysqli_real_escape_string($connection, $quantidade);
  $v = mysqli_real_escape_string($connection, $validade);
  $f = mysqli_real_escape_string($connection, $fornecedor);

  $query = "INSERT INTO PRODUCTS (nome, preco, quantidade, validade, fornecedor) VALUES ('$n', '$p', '$q', '$v', '$f');";

  if (!mysqli_query($connection, $query)) echo("<p>Error adding product data.</p>");
}

function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
