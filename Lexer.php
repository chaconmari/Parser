<?php
class Lexer{
	public $letters = 'abcdefghijklmnopqrstuvwxyz';
	public $digits = '0123456789';
	public $prog;
	public $i;

	public function __construct($s){
		 $this->prog = str_split($s);
		 $this->i = 0;
	}

	public function next() {
		while ($this->i<sizeof($this->prog) && ($this->prog[$this->i]==' ' || $this->prog[$this->i]=='\n')){
			$this->i++;
		}
		if ($this->i>=sizeof($this->prog)){
			return new Token(Token::EOF);
		}
		switch ($this->prog[$this->i]) {
			case '(':
				$this->i++;
				return new Token(Token::LPAREN, "(");
			case ')':
				$this->i++;
				return new Token(Token::RPAREN,")");
			case '{':
				$this->i++;
				return new Token(Token::LBRACKET, "{");
			case '}':
				$this->i++;
				return new Token(Token::RBRACKET,"}");
			case '<':
				$this->i++;
				return new Token(Token::LESS,"<");
			case '=':
				$this->i++;
				return new Token(Token::EQUAL,"=");
			case ':':
				$this->i++;
				return new Token(Token::COLON,":");
		}
		if (strpos($this->digits, $this->prog[$this->i]) !== FALSE){
			$digit = $this->prog[$this->i];
			$this->i++;
			return new Token(Token::VALUE, $digit, intval($digit));
		}
		if (strpos($this->letters, $this->prog[$this->i]) !== FALSE){
			$id = "";
			while ($this->i<sizeof($this->prog) && strpos($this->letters, $this->prog[$this->i]) !== FALSE){
				$id.=$this->prog[$this->i];
				$this->i++;
      }
		    if ("if" == $id){
          return new Token(Token::IFF, $id);
      }
      if ("else" == $id){
        return new Token(Token::ELSEE,$id);
      }
      if (strlen($id) == 1) {
          return new Token(Token::ID, $id);
      }
      return new Token(Token::INVALID,"");
		}
    return new Token(Token::INVALID,"");
	}
}
?>
