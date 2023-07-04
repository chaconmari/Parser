<?php
class Token{

	const LPAREN = 0;
	const RPAREN = 1;
	const LBRACKET = 2;
	const RBRACKET = 3;
	const LESS = 4;
	const EQUAL = 5;
	const COLON = 6;
	const ID  = 7;
	const VALUE = 8;
	const IFF = 9;
	const ELSEE = 10;
	const EOF = 11;
	const INVALID = 12;

    public $type;
    public $str;
    public $val;

    public function __construct($theType, $theString = null, $theVal = null) {
        $this->type = $theType;
				$this->str = $theString;
				$this->val = $theVal;
    }
}
?>
