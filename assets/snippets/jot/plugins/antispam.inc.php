<?php
function antispam(&$object,$params){
	global $modx;

	$fields= array('address','captcha','emailConfirm','emailCheck','easyCaptcha','email-retype','emailRetype','email2','nickName','siteAddress','siteUrl','url1');
	$fieldName=$fields[array_rand($fields)];
	$className = 'jot-form-'.generatePassword();
	$found = false;
	switch($object->event) {
		case 'onBeforePOSTProcess':
			foreach ($fields as $val) {
				if (isset($_POST[$val]) && empty($_POST[$val])) $found = true; 
			}
			if (!$found) {
				$object->form['error'] = 4;
				$object->form['confirm'] = 0;
				return true;
			}
			break;
		case 'onSetFormOutput':
			$block='
	<div class="'.$className.'">
		<input type="text" name="'.$fieldName.'" value="" size="40" />
	</div>
</form>';
			$modx->regClientCSS('<style type="text/css">.'.$className.' {left:0; position:absolute; top:-500px; width:1px; height:1px; overflow:hidden; visibility:hidden;}</style>');
			$output_form = $object->config["html"]["form"];
			$output_form = str_replace('</form>',$block,$output_form);
			if ($object->config["output"]) return $output_form;
			break;
	}
}
function generatePassword($length = 8){
	$chars = 'abdefhiknrstyz23456789';
	$numChars = strlen($chars);
	$string = '';
	for ($i = 0; $i < $length; $i++) {
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	}
	return $string;
}
?>