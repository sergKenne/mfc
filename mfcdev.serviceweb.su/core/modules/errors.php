<?
class errors extends baseModule{
	
	public static function show($error){
		new templater('err404',false,false,$error);
		exit;
	}
}
			?>