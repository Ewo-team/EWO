"use strict";

var cloneToDoc = function(node,doc){
    if (!doc){ 
        doc = document;
    }
    var clone = doc.createElementNS(node.namespaceURI,node.nodeName);
    for (var i=0,len=node.attributes.length;i<len;++i){
        var a = node.attributes[i];
        if (/^xmlns\b/.test(a.nodeName)){
            continue;
        }
        clone.setAttributeNS(a.namespaceURI,a.nodeName,a.nodeValue);
    }
    for (var i=0,len=node.childNodes.length;i<len;++i){
        var c = node.childNodes[i];
        clone.insertBefore(c.nodeType==1 ? cloneToDoc(c,doc) : doc.createTextNode(c.nodeValue), null);
    }
    return clone;
};

var _lib = {
    'humain'    :   'Humains', 
    'ange'      :   'Anges',
    'demon'     :   'Démons',
    'bouclier'  :   'Boucliers',
    'porte'     :   'Portes',
    'viseurs'   :   'Mes personnages'
 };

var _d = {
    'm_althian' : {
        'x' : {'d' : 5, 'm' : 100},
        'y' : {'d' : 3, 'm' : 100}
    },
    'm_celestia' : {
        'x' : {'d' : 5, 'm' : 50},
        'y' : {'d' : 3, 'm' : 100}
    },
    'm_ciferis' : {
        'x' : {'d' : 5, 'm' : 50},
        'y' : {'d' : 3, 'm' : 0}
    }
};

$('.getsvg').each(function(){
    var o_p = $(this);      
    $.ajax({
        type    :   'get',
        url     :   o_p.attr('data-cible'),
        dataType:   'xml',
        success :   function(xml){
            var c           = cloneToDoc(xml.documentElement);
            window.svgRoot  = c;
            document.getElementById(o_p.attr('id')).appendChild(c);
            delete window.svgRoot;
            var o_svg   = o_p.find('svg').css({'display':'block','margin':'0 auto'});
            var _e      = $('g', o_svg);
            $.each(_e, function(){
                var $this = $(this);
                if($this.attr('class') != 'noir'){
                    var clas    =   $this.attr('class');
                    o_p.prepend("<input type='button' class='"+clas+"' value='"+(_lib[clas] ? _lib[clas] : clas)+"' />");
                }
            });
            o_p.find('input[type=button]').click(function(){
                var calque = $('g[class='+$(this).attr('class')+']', o_p);
                calque.css('display') == 'none' ? calque.show() : calque.hide()
            }).last().after('<div class="lib_loc">Position X : <span class="px">DTC</span>  |  Position Y : <span class="py">DTC</span></div>');

            o_svg.mousemove(function(e){
                o_p.find('.lib_loc').css('visibility', 'visible');
                var c = {
                    x: Math.floor((e.pageX - $(this).offset().left) / _d[o_p.attr('id')]['x']['d'] - _d[o_p.attr('id')]['x']['m']),
                    y: Math.floor((e.pageY - $(this).offset().top) / _d[o_p.attr('id')]['y']['d'] - _d[o_p.attr('id')]['y']['m'])
                };
                c.y = (c.y <= 0) ? Math.abs(c.y) : '-' + c.y;
                o_p.find('.lib_loc .px').html(c.x);
                o_p.find('.lib_loc .py').html(c.y);
            }).mouseout(function(){
                o_p.find('.lib_loc').css('visibility', 'hidden');
            });
        }   
    });
});