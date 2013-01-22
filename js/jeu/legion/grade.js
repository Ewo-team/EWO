
var i = 0;
var j = 0;
$(window).ready(
    function(){
        $('.nom').each(function(){
            $(this).click(function(){
                var txt = $(this).parent().children('span').html();
                if(txt.substring(0, 1) != '<'){
                    $(this).parent().children('span').html('<input type="text" value="'+txt+'" id="nomGrade'+i+'" />\
                        <img src="img/valid.png" onclick="valideModif(\'nomGrade'+i+'\',0);" class="cursor" />');

                    i++;
                }
            });
        });
        $('.descr').each(function(){
            $(this).click(function(){
                var txt = $(this).parent().children('span').html();
                if(txt.substring(0, 1) != '<'){
                    $(this).parent().children('span').html('<input type="text" value="'+txt+'" id="descrGrade'+j+'" />\
                        <img src="img/valid.png" onclick="valideModif(\'descrGrade'+j+'\',1);" class="cursor" />');
                    j++;
                }
            });
        });
    }
    );

function valideModif(div,cVar){
    var val = 0;
    if(cVar < 2)
        val = $('#'+div).val();
    else{
        val = $('#'+div).is(':checked');
        if(val)
            val = 1;
        else
            val = 0;
    }
    var id  = $('#'+div).parent().attr('class');
    var id = id.substring(1);

    $.post(
        '../ajax/grade.php',
        {
            c:cVar,
            i:id,
            'va':val,
            mat:matVar
        },
        function(data){
            if(cVar < 2)
                $('#'+div).parent().html(val);
        }
        );
}


function add(){
    if($('#newNom').val() != '')
        $('#addForm').submit();
    else
        alert('Il faut un nom');
}

function sup(id){
    if(confirm("Voulez vous vraiment supprimer ce grade ?")){
        document.location.href =  window.location+"&d="+id;
    }
}