<?php
  include "../database.php";

  // Iniciando uma sessão local para salvar dados temporariamente.
  session_start();

  if(isset($_POST['filter_button']) != '') 
  {
    if ($_SESSION['filter'] == "usuario")
    {
      $_SESSION['filter'] = "nome";
      $filtro = $_SESSION['filter'];
    }
    else
    {
      $_SESSION['filter'] = "usuario";
      $filtro = $_SESSION['filter'];
    }  
  }
  else {
    if ($_SESSION['filter'] != "nome")
    {
      $_SESSION['filter'] = "usuario"; // Salva na sessão o filtro padrão "usuario"
    }
    $filtro = $_SESSION['filter'];
  }

  echo "<p style=\"color: black\">{$filtro}</p>";

  if(isset($_POST['search-user']) != '') 
  {
    $sql = "SELECT * FROM tb_usuarios WHERE $filtro LIKE '{$_POST['search-user']}%' ORDER BY $filtro ASC";
    $result = mysqli_query($conn, $sql);
    $_SESSION['search-query'] = $_POST['search-user'];
  } 
  else 
  {
    if ($_SESSION['search-query'] == '')
    {
      $sql = "SELECT * FROM tb_usuarios ORDER BY nome ASC";
      $result = mysqli_query($conn, $sql);
    }
    else
    {
      $sql = "SELECT * FROM tb_usuarios WHERE $filtro LIKE '{$_SESSION['search-query']}%' ORDER BY $filtro ASC";
      $result = mysqli_query($conn, $sql);
    }
  }

?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Administrar Usuários</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="styles/listagem.css">
</head>
<main>

  <div class="search-div">

    <div class="search-bar">
      <form name="search-form" method="POST">
        <div class="search-icon">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="submit" value="">
        </div>

        <div class="search-input">
          <input type="text" name="search-user" placeholder="Pesquisar" value="<?php echo $_SESSION['search-query'] ?>">
        </div>
      </form>
    </div>

    <div class="search-buttons">
      <form class="filter-form" method="POST">
        <div class="inputIcon-div">
          <i class="fa-solid fa-filter"></i>
          <input type="submit" name="filter_button" value=".">  
        </div>
      </form>
    </div>
  </div>

  <div class="card-div">
    <?php
      if(mysqli_num_rows($result) > 0)
      {
        while($row = mysqli_fetch_assoc($result))
        {
          if ($row["divisao"] == "aluno")
          {
            $funcao_sql = "SELECT * FROM tb_aluno WHERE usuario = '{$row["usuario"]}'"; // Seleciona o usuario correto da tabela aluno
            $funcao_result = mysqli_query($conn, $funcao_sql);
            $funcao_row = mysqli_fetch_assoc($funcao_result);
            $string = "Turma: {$funcao_row["turma"]}";
          }
          else if ($row["divisao"] == "professor")
          {
            $funcao_sql = "SELECT * FROM tb_professor WHERE usuario = '{$row["usuario"]}'"; // Seleciona o usuario correto da tabela aluno
            $funcao_result = mysqli_query($conn, $funcao_sql);
            $funcao_row = mysqli_fetch_assoc($funcao_result);
            $string = "Especialização: {$funcao_row["especializacao"]}";
          }
          else 
          {
            $funcao_sql = "SELECT * FROM tb_secretaria WHERE usuario = '{$row["usuario"]}'"; // Seleciona o usuario correto da tabela aluno
            $funcao_result = mysqli_query($conn, $funcao_sql);
            $funcao_row = mysqli_fetch_assoc($funcao_result);
            $string = "Cargo: {$funcao_row["cargo"]}";
          }
          echo "
            <div class='user-card'>
              <div class='card-avatar'>
                <img src='../imagens/perfil_vazio.png' alt='Foto de Perfil'>
              </div>

              <div class='card-info'>
                <p class='card-name'>
                  {$row["nome"]}
                </p>

                <p class='card-name'>
                  @{$row["usuario"]}
                </p>

                <p>
                  {$string}
                </p>
              </div>
            </div>
          ";
        }
      }
    ?>

  </div>
</main>