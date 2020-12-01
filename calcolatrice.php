<?php

session_start();

if ($_GET['delete']) {
  if ($_GET['delete']=='all'){
      session_destroy();
      session_start();
   }
}

if(!isset($_SESSION["calcolatrice"])) {
    $_SESSION["calcolatrice"] = new Calcolatrice();
}else{
    $calcolatrice = $_SESSION["calcolatrice"];
}

class Calcolatrice
{
    public $arrayOperazioni = array();


    public function somma($firstNumber, $secondNumber){

        array_push($this->arrayOperazioni,new Operazione("somma",$firstNumber,$secondNumber,$firstNumber+$secondNumber)) ;
    }

    public function differenza( $firstNumber, $secondNumber)
    {
        array_push($this->arrayOperazioni,new Operazione("differenza",$firstNumber,$secondNumber,$firstNumber-$secondNumber));  
    }

    public function divisione( $firstNumber,$secondNumber)
    {
       if($secondNumber==0 || $firstNumber==0){
          echo "Non puoi dividere per zero<br>";
        }else{
            array_push($this->arrayOperazioni, new Operazione("divisione",$firstNumber,$secondNumber,$firstNumber/$secondNumber));
       }  
    }

    public function moltiplicazione($firstNumber, $secondNumber)
    {
        array_push($this->arrayOperazioni, new Operazione("moltiplicazione",$firstNumber,$secondNumber,$firstNumber*$secondNumber));
    }

    public function potenza( $firstNumber, $secondNumber)
    {
        array_push($this->arrayOperazioni,new Operazione("potenza",$firstNumber,$secondNumber,pow($firstNumber, $secondNumber)));
        
    }

    public function radiceQuadrata($firstNumber,$secondNumber)
    {
        array_push($this->arrayOperazioni, new Operazione("radiceQuadrata",$firstNumber,$secondNumber,sqrt($firstNumber)));  
    }

    public function decimaleBinario($firstNumber,$secondNumber)
    {
        array_push($this->arrayOperazioni, new Operazione("decimale",$firstNumber,$secondNumber,decbin($firstNumber)));
        
    }

    public function binarioDecimale( $firstNumber,$secondNumber)
    {
        array_push($this->arrayOperazioni, new Operazione("binario",$firstNumber,$secondNumber,bindec($firstNumber)));
        
    }



    public function getMostUsedOperation(){
        $array_counter = ["somma" => 0, "moltiplicazione" => 0, "radiceQuadrata" => 0, "potenza" => 0, "differenza" => 0, "divisione" => 0, "binario" => 0, "decimale"=>0 ];
        foreach ($this->arrayOperazioni as $operation) {
            $array_counter[$operation->typeOfOperation]++;
        }
        return array_keys($array_counter,max($array_counter));
        }



    public function getLessUsedOperation(){
        $array_counter = ["somma" => 0, "moltiplicazione" => 0, "radiceQuadrata" => 0, "potenza" => 0, "differenza" => 0, "divisione" => 0, "binario" => 0, "decimale" =>0];
        foreach ($this->arrayOperazioni as $operation) {
            $array_counter[$operation->typeOfOperation]++;
        }
        return array_keys($array_counter,min($array_counter));
        }


    public function deleteOperation(){
        if($_GET['delete']){
            session_destroy();
        }else{
            unset($_SESSION['operazione']);
            echo "<a href='?delete=all'>Elimina tutto</a>";
        }
    }
}



class Operazione {
    public $typeOfOperation;
    public $numberOne;
    public $numberTwo;
    public $result;

    public function __construct($typeOfOperation, $numberOne, $numberTwo, $result){
        $this->typeOfOperation = $typeOfOperation;
        $this->numberOne = $numberOne;
        $this->numberTwo = $numberTwo;
        $this->result = $result;
    }

public function __toString(){
    switch($this->typeOfOperation){
        case "somma": 
        return "somma " . $this->numberOne . " e " . $this->numberTwo . " = " . $this->result;
        case "divisione": 
        return "divisione " .  $this->numberOne . " e " . $this->numberTwo . " = " . $this->result;
        case "moltiplicazione": 
        return "moltiplicazione " . $this->numberOne . " e " . $this->numberTwo . " = " . $this->result;
        case "differenza": 
        return  "differenza " . $this->numberOne . " e " . $this->numberTwo . " = " . $this->result;
        case "potenza ": 
        return "potenza" . $this->numberOne . " e " . $this->numberTwo . " = " . $this->result;
        case "radiceQuadrata": 
        return "radiceQuadrata " . $this->numberOne ." = " .  $this->result;
        case "binario": 
        return  "binario "  . " = " . $this->result;
        case "decimale": 
        return "decimale " . " = " . $this->result;
        }
    }
}


?>

<html>
<form method="post" action="calcolatrice.php">
    <label> Numero 1: </label> <input type="text" placeholder="inserisci il numero" name="numero_1">
    <label> Numero 2: </label> <input type="text" placeholder="inserisci il numero" name="numero_2">
    <label>Operazione: </label>
    <select name="operazione">
        <option value="somma">   </option>
        <option value="somma"> Somma </option>
        <option value="differenza">Differenza</option>
        <option value="moltiplicazione">Moltiplicazione</option>
        <option value="divisione">Divisione</option>
        <option value="potenza">Potenza</option>
        <option value="radiceQuadrata">Radice quadrata</option>
        <option value="binario">Binario</option>
        <option value="decimale">Decimale</option>
    </select>
    <button type="submit" value="invia"> Invia </button>
</form>
</html>


<?php


if(isset($_POST['operazione'])){
    $firstNumber = $_POST['numero_1'];
    $secondNumber= $_POST['numero_2'];

if($_POST['operazione']=="somma"){
    $calcolatrice->somma($firstNumber,$secondNumber);
}else if($_POST['operazione']=="differenza"){
    $calcolatrice->differenza($firstNumber,$secondNumber);
}else if($_POST['operazione']=="moltiplicazione"){
    $calcolatrice->moltiplicazione($firstNumber,$secondNumber);
}else if($_POST['operazione']=="divisione"){
    $calcolatrice->divisione($firstNumber,$secondNumber);
}else if($_POST['operazione']=="potenza"){
    $calcolatrice->potenza($firstNumber,$secondNumber);
}else if($_POST['operazione']=="radiceQuadrata"){
    $calcolatrice->radiceQuadrata($firstNumber,$secondNumber);
}else if($_POST['operazione']=="binario"){
    $calcolatrice->decimaleBinario($firstNumber,$secondNumber);
}else if($_POST['operazione']=="decimale"){
    $calcolatrice->binarioDecimale($firstNumber,$secondNumber);
    }
}

echo "STORICO OPERAZIONI: ";
$calcolatrice->deleteOperation();
foreach($calcolatrice->arrayOperazioni as $operazioni){
  echo "<p>".$operazioni."</p>";
}

echo "<br>  MENO USATA: " . implode(" , " ,$calcolatrice->getLessUsedOperation());
echo " <br> PIU' USATA: " . implode(" , " ,$calcolatrice->getMostUsedOperation()) ;


?>

