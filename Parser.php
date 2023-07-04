<html>
  <title>Program Evaluator</title>
  <pre>
    <?php
    include 'Token.php';
    include 'Lexer.php';

    $run = new Fall17Program();
    $run->main();

    class Fall17Program {

      public $letters = 'abcdefghijklmnopqrstuvwxyz';
      public $digits = '0123456789';
      public $currentToken;
      public $values = [];
      public $lex;
      public $oneIndent = " ";

      function main(){
        $a = 0;
        $url = file("http://cs5339.cs.utep.edu/longpre/assignment2/programs.txt");
        while(sizeOf($url)> $a){
          echo "<br>";
          echo $url[$a];
          $program = file_get_contents(trim($url[$a]));
          $program = preg_replace('/\s+/', ' ', $program);
          echo "<br>";

          $this->lex = new Lexer($program);
          $this->currentToken = $this->lex->next();

          try{
            $this->execProg($this->oneIndent);
            if($this->currentToken->type != Token::EOF){
              echo "Unexpected characters at the end of the program";
              throw new Exception();
            }
          }
          catch(Exception $ex){
            echo "<br>Program Parsing aborted";
          }
          echo "<br>";
        $a++;
      }
    }
      function execProg($indent) {
        while($this->currentToken->type == Token::ID|| $this->currentToken->type == Token::IFF){
          $this->execStatement($indent, true);
        }
        echo "<br>";
        $this->execResults($indent);
      }

      function execStatement($indent, $executing){
        if($this->currentToken->type == Token::ID){
          $this->execAssign($indent, $executing);
        }
        else{
          $this->execConditional($indent, $executing);
        }
      }

      function execAssign($indent, $executing){
        $c = substr($this->currentToken->str, 0);
        $this->currentToken = $this->lex->next();
        if($this->currentToken->type != Token::EQUAL){
          echo"<br> Equal sign expected";
          throw new Exception;
        }
        $this->currentToken = $this->lex->next();
        echo $indent . $c . " = ";
        $value = $this->execExpr($indent);
        echo "<br>";
        if($executing){
          $this->values[$c] = $value;
        }
      }

      function execConditional($indent, $executing){
        echo $indent . "if ";
        $this->currentToken = $this->lex->next();
        $condResult = $this->execCond($indent);
        echo " {<br>";
        if($this->currentToken->type != Token::LBRACKET){
          echo "Left bracket expected.";
          throw new Exception();
        }
        $this->currentToken = $this->lex->next();
        while($this->currentToken->type == Token::ID || $this->currentToken->type == Token::IFF){
          $this->execStatement($indent . $this->oneIndent, $condResult);
        }
        if($this->currentToken->type != Token::RBRACKET){
          echo "Right bracket or statement expected.";
          throw new Exception();
        }
        echo $indent . "}";
        $this->currentToken = $this->lex->next();
        if($this->currentToken->type == Token::ELSEE){
          $this->currentToken= $this->lex->next();
          if($this->currentToken->type != Token::LBRACKET){
            echo "Left bracket expected.";
            throw new Exception();
          }
          $this->currentToken = $this->lex->next();
          echo " else { <br>";
          while($this->currentToken->type == Token::ID || $this->currentToken->type == Token::IFF){
            $this->execStatement($indent . $this->oneIndent, !$condResult);
          }
          if($this->currentToken->type != Token::RBRACKET){
            echo "Right bracket or statement expected";
            throw new Exception();
          }
          echo $indent . "}";
          $this->currentToken = $this->lex->next();
        }
        echo "<br>";
      }

      function execCond($indent){
        if($this->currentToken->type != Token::LPAREN){
          echo "Left parenthesis expected.";
          throw new Exception();
        }
        echo "(";
        $this->currentToken = $this->lex->next();
        $v1 = $this->execExpr($indent);
        if($this->currentToken->type != Token::LESS){
          echo "Less than expected";
          throw new Exception();
        }
        echo " < ";
        $this->currentToken = $this->lex->next();
        $v2 = $this->execExpr($indent);
        if($this->currentToken->type != Token::RPAREN){
          echo "Right parenthesis expected";
          throw new Exception();
        }
        echo ")";
        $this->currentToken = $this->lex->next();
        return ($v1 < $v2);
      }

      function execExpr($indent){
        if($this->currentToken->type == Token::VALUE){
          $val = $this->currentToken->val;
          echo $val;
          $this->currentToken = $this->lex->next();
          return $val;
        }
        if($this->currentToken->type == Token::ID){
          $c = substr($this->currentToken->str, 0);
          echo $c;
          if(isset($this->values[$c])){
            $this->currentToken = $this->lex->next();
            return intval($this->values[$c]);
          }
          else{
            echo "Reference to an undefined variable";
            throw new Exception();
          }
        }
        echo "<br>An expression should be either a digit or a letter";
        throw new Exception();
      }

      function execResults($indent){
        if($this->currentToken->type != Token::COLON){
          echo "<br>Colon or statement expected";
          throw new Exception();
        }
        $this->currentToken = $this->lex->next();
        while($this->currentToken->type == Token::ID){
          $c = substr($this->currentToken->str, 0);
          $this->currentToken = $this->lex->next();
          if(isset($this->values[$c])){
            echo "The value of ". $c . " is " . $this->values[$c] . "<br>";
          }
          else{
            echo "The value of " . $c ." is undefined<br>";
          }
        }
      }
    }
    ?>
  </pre>
</html>
