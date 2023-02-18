<?php
require 'function.php';
if (isset($_COOKIE["matches"])) {
    $matches = json_decode($_COOKIE['matches'], true);
} else {
    $matches = array(
        "MaroccontreBrazil" => array("Maroc" => 0, "Brazil" => 0, "joue" => false),
        "MaroccontreEspagne" => array("Maroc" => 0, "Espagne" => 0, "joue" => false),
        "MaroccontreCanada" => array("Maroc" => 0, "Canada" => 0, "joue" => false),
        "BrazilcontreCanada" => array("Brazil" => 0, "Canada" => 0, "joue" => false),
        "BrazilcontreEspagne" => array("Brazil" => 0, "Espagne" => 0, "joue" => false),
        "CanadacontreEspagne" => array("Canada" => 0, "Espagne" => 0, "joue" => false),
    );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World cup simulator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1 class="text-center m-5" style="color: white;">Simulateur coupe du monde</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == 'GET' && isset($_GET['gameName'])) {
            foreach ($matches as $game => $gameInfo) {
                $matches[$game][$_GET[$game][0]] = $_GET[$game][1];
                $matches[$game][$_GET[$game][2]] = $_GET[$game][3];
                $matches[$game]['joue'] = true;
            }
            setcookie('matches', json_encode($matches));
        } elseif ($_SERVER["REQUEST_METHOD"] == 'GET' && isset($_GET['reset'])) {
            $matches = array(
                "MaroccontreBrazil" => array("Maroc" => 0, "Brazil" => 0, "joue" => false),
                "MaroccontreEspagne" => array("Maroc" => 0, "Espagne" => 0, "joue" => false),
                "MaroccontreCanada" => array("Maroc" => 0, "Canada" => 0, "joue" => false),
                "BrazilcontreCanada" => array("Brazil" => 0, "Canada" => 0, "joue" => false),
                "BrazilcontreEspagne" => array("Brazil" => 0, "Espagne" => 0, "joue" => false),
                "CanadacontreEspagne" => array("Canada" => 0, "Espagne" => 0, "joue" => false),
            );
            +setcookie('matches', json_encode($matches));
        }
        ?>
    </header>
    <!-- all matches section -->
    <main class="d-flex justify-content-center">
        <section class="col-md-4 mx-1" id="matches">
            <?php
            foreach ($matches as $game => $gameInfo) : ?>
                <?php
                $nationalTeams = [];
                $nationalTeamsInfo = [];
                foreach ($gameInfo as $key => $value) :
                    array_push($nationalTeams, $key);
                    array_push($nationalTeamsInfo, $value);
                ?>
                <?php endforeach ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" class="navy_blue_bg p-3 mb-3">
                    <div id="match" class="d-flex m-2 p-2 justify-content-center align-items-center baby_blue_bg " style="background-color: orange;color:white">
                        <div class="team1 w-50 text-center">
                            <?php echo $nationalTeams[0] ?>
                        </div>
                        <div id="teamsScores" class="d-flex justify-content-center mt-5 mb-3">
                            <!-- game name -->
                            <input type="hidden" name="gameName" value="<?php echo $game ?>">
                            <!-- first team name -->
                            <input type="hidden" name="<?php echo $game ?>[]" value="<?php echo $nationalTeams[0] ?>">
                            <!-- first team score -->
                            <input type="number" min="0" name="<?php echo $game ?>[]" <?php if ($gameInfo["joue"] == true) {
                                                                                            echo "readonly";
                                                                                        } ?> value="<?php echo $nationalTeamsInfo[0] ?>" class="w-25 text-center">
                            <!-- second team name -->
                            <input type="hidden" name="<?php echo $game ?>[]" value="<?php echo $nationalTeams[1] ?>">
                            <!-- second team score -->
                            <input type="number" min="0" name="<?php echo $game ?>[]" value="<?php echo $nationalTeamsInfo[1] ?>" <?php if ($gameInfo["joue"] == true) {
                                                                                                                                        echo "readonly";
                                                                                                                                    } ?> class="w-25 text-center">
                        </div>
                        <div class="team2 w-50 text-center">
                            <?php echo $nationalTeams[1] ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <input type="submit" name="" value="Start" class="d-block btn px-4 py-2 mx-auto" style="background-color: orange;">
                </form>
                <div class="text-center">
                    <form method='GET' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="reset" value="reset">
                        <input type="submit" class="btn text-center btn-danger mb-5" value="Annuler les matches">
                    </form>
                </div>
        </section>
        <section class="col-md-6 px-3 py-4 mx-1">
            <h2 class="py-2 text-center" style="color:white; ">Table de classification</h2>
            <div class="table-responsive">
                <table class="table text-center" style="color: white;background-color: orange;">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>TEAM</th>
                            <th>POINTS</th>
                            <th>matches PLAYED</th>
                            <th>matches WON</th>
                            <th>matches EQUAL</th>
                            <th>GAME LOSTS</th>
                            <th>Goals Scored</th>
                            <th>Goals Recieved</th>
                            <th>DIFF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_REQUEST['simulate']) &&  $_REQUEST['simulate'] == "simulate") {
                            foreach (trierTable(createTeams($matches)) as  $game => $gameInfo) {

                        ?>
                                <tr>
                                    <td><?php echo $game + 1; ?> </td>
                                    <td><?php echo $gameInfo["Team"];  ?></td>
                                    <td><?php echo $gameInfo["POINTS"];  ?></td>
                                    <td><?php echo $gameInfo["GAMES_PLAYED"];  ?></td>
                                    <td><?php echo $gameInfo["GAMES_WON"];  ?></td>
                                    <td><?php echo $gameInfo["GAMES_EQUAL"];  ?></td>
                                    <td><?php echo $gameInfo["GAME_LOSTS"];  ?></td>
                                    <td><?php echo $gameInfo["GOALS_SCORED"];  ?></td>
                                    <td><?php echo $gameInfo["GOALS_RECEIVED"];  ?></td>
                                    <td><?php echo $gameInfo["DIFF"]; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "
<tr>
<td>1</td>
<td>Maroc</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
</tr>
<tr>
<td>2</td>
<td>Canada</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
</tr>
<tr>
<td>3</td>
<td>Espagne</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
</tr>
<tr>
<td>4</td>
<td>Brazil</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
<td>0</td>
</tr> 
";
                        }
                        ?>
                        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="simulate" value="simulate" class="m-auto">
                            <input type="submit" class="btn text-center mx-auto blue_bg my-2 simulate" value="simulate" style="background-color: orange;">
                        </form>
        </section>
    </main>
</body>

</html>