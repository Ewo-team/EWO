var lvlInit	= 0;

function clear(){
	
	if($('#formAdmin form .view').length != 0)
		$('#formAdmin form .view').remove();
	if($('#formAdmin form .delete').length != 0)
		$('#formAdmin form .delete').remove();
	if($('#formAdmin form .submit').length != 0)
		$('#formAdmin form .submit').remove();
}

function display(id,name,lvl){
	lvlInit = lvl;
	clear();
	
	$('#sectionAdmin').css('visibility','visible');
	$('#formAdmin').css('visibility','visible');
	$('#formAdmin').slideUp(100,
		function(){
			$('#formAdmin h3').html(name);
			$('#formAdmin select option[value="'+lvl+'"]').attr('selected', 'selected');
			
			$('#formAdmin form').append('<input type="submit" value="modifier" name="mod" class="submit"/>');
			$('#formAdmin form').append('<input type="hidden" value="'+id+'" name="id"/>');
			$('#formAdmin form').append('<input type="submit" value="virer" name="virer" class="delete" onclick="return confirm(\'Etes vous certain de vouloir virer ce membre ?\');"/>');
			$('#formAdmin form .submit').attr('disabled', 'disabled');
			$('#formAdmin form').append('<input type="button" value="logs" class="view" onclick="getLogs('+id+')"/>');


			$('#logArea').html('');
			$(this).slideDown(100);
		}
	);
}

function add(){
	lvlInit = 0;
	clear();
	$('#sectionAdmin').css('visibility','visible');
	$('#formAdmin').css('visibility','visible');
	$('#formAdmin').slideUp(100,
		function(){
			$('#formAdmin h3').html('Ajouter');
			$('#formAdmin form').append('<input type="text" class="submit" id="nameAdd" name="nameAdd"/>');
				getCompl('nameAdd');
			$('#formAdmin form').append('<input type="submit" value="ajouter" name="add" class="submit" />');
			$(this).slideDown(100);
		}
	);
}

function lvlChanged(v){

	if(v != lvlInit)
		$('#formAdmin form .submit').removeAttr('disabled');
	else
		$('#formAdmin form .submit').attr('disabled', 'disabled');
}

function getLogs(id){
	$.post('ajax/admin.php.inc',
		{
			'action' : 'getLog',
			'member' : id
		},
		function(data){
			$('#logArea').html(data);
			ajusteLogArea();
			$('#logArea').css('border-top','1px solid #414141');
			$('#logArea').css('border-bottom','1px solid #414141');
		}
		);
}

function getCompl(id){
	var v = $('#'+id).val();
	$.post('ajax/admin.php.inc',
		{
			'action' : 'getName',
			'begin'  : v
		}
		,
		function(data){
			var tbl = data.split(' ');
			var i;
			for(i = 0;i < tbl.length;++i)
				tbl[i] = tbl[i].replace(/&nbsp;/g,' ');
			$('#'+id).autocomplete(tbl);
		}
	);
}
