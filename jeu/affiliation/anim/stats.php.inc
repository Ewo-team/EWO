<style type="text/css">
    .ange{color : blue;}
    .dem{color : red;}
    .hum{color : green;}
    .paria{color : pink;}
</style>

<table align='center' border="1" width="90%" style="text-align:center">
    <thead>
        <tr>
            <th></th>
            <th>
                    Grade
            </th>
            <th>
                    Galon
            </th>
            <th>
                    Anges
            </th>
            <th>
                    Démons
            </th>
            <th>
                    Humains
            </th>
            <th>
                    Parias
            </th>
            <th>
                    Autres
            </th>
        </tr>
    </thead
    <tbody>
<?php
	$actRa = -100;
	$actGr = -100;

	$nbr = 0;

	$datas = array();

	$sql = 'SELECT p.race_id,c.nom,p.grade_id, p.galon_id,COUNT(p.id)
	FROM `persos` p
	JOIN `camps` c
		ON(c.id = p.race_id)
	GROUP BY p.race_id,p.grade_id,p.galon_id
	ORDER BY p.race_id ASC,p.grade_id DESC,p.galon_id DESC;';
	$recherche	= mysql_query($sql) or die (mysql_error());
	while($donnees = mysql_fetch_row($recherche)){
		$datas[$donnees[2]][$donnees[3]][$donnees[1]] = $donnees[4];
	}

	$gr = -100;
	$ga = -100;
	krsort($datas);
	foreach($datas as $grade => $queue1){
		krsort($queue1);
		foreach($queue1 as $galon => $donnes){
			$grAff 		= '';
			$grBorder 	= 'style="border:none;"';
			if($grade != $gr){
				$gr = $grade;
				$grAff = $gr;
				$grBorder = '';
				$ga = -100;
			}
                        $galonImg = $galon;
                        if($galonImg < 1)
                            $galonImg = 1;
				echo '
        <tr class="trAff">
                <td>';
                if($grade > 0)
                    echo '<img src="../images/galons/grade'.$grade.'/galon'.$galonImg.'.png" /></td>';
                echo '
                <td ',$grBorder,'>
                        ',$grAff,'
                </td>
                <td>
                        ',$galon,'
                </td>
                <td class="ange">
                        ',(isset($donnes['Ange']))? $donnes['Ange'] : '0','
                </td>
                <td class="dem">
                        ',(isset($donnes['Demon']))? $donnes['Demon'] : '0','
                </td>
                <td class="hum">
                        ',(isset($donnes['Humain']))? $donnes['Humain'] : '0','
                </td>
                <td class="paria">
                        ',(isset($donnes['Paria']))? $donnes['Paria'] : '0','
                </td>
                <td>
                        ',(isset($donnes['Autre']))? $donnes['Autre'] : '0','
                </td>
        </tr>';
		}
	}
?>
    </body>
</table>
