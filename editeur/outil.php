<br />
<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
			<table>
				<tr>
					<td width='180px'><form name="tab_taille"><img src='../images/editeur/brush.png' /> Taille : <input name='taille' type='text' value='1' size='1' /></form></td>
					<td width='180px'>
						<form name="tab_forme">
						<img src='../images/editeur/52.png' /> : 
						<select name="forme">
							<option value='normal'>----</option>
							<option value='ligneh'>Ligne H</option>
							<option value='lignev'>Ligne V</option>
							<option value='carre'>Carré</option>
						</select>
						</form>
					</td>
					<td width='180px'>		
						<form name="tab_plan" action='index.php' method='post'>
						Carte :
						<select name="plan">
							<option value='1' <?php if(isset($_SESSION['plan']) AND $_SESSION['plan'] == 1){ echo 'selected';}else{ echo '';} ?>>Terre</option>
							<option value='3' <?php if(isset($_SESSION['plan']) AND $_SESSION['plan'] == 3){ echo 'selected';}else{ echo '';} ?>>Enfer</option>
							<option value='2' <?php if(isset($_SESSION['plan']) AND $_SESSION['plan'] == 2){ echo 'selected';}else{ echo '';} ?>>Paradis</option>
						</select>
						<br />
						X :
						<input type='text' value='<?php if(isset($_SESSION['coordX'])){ echo $_SESSION['coordX'];}else{ echo 0;} ?>' name='coordX' size='1' />
						Y :
						<input type='text' value='<?php if(isset($_SESSION['coordY'])){ echo $_SESSION['coordY'];}else{ echo 0;} ?>' name='coordY' size='1' />
						<br />
						<img src='../images/editeur/search.png' /> : 
						<input type='text' value='<?php if(isset($_SESSION['Vision'])){ echo $_SESSION['Vision'];}else{ echo 5;} ?>' name='Vision' size='1' />
						<input type='submit' value="Go"/>
						</form>
					</td>
				</tr>
				<tr>
					<td><img src='../images/editeur/026.png' /> <input type='button' onClick="reset('deco')" value="Reset decor"/></td>
					<td><img src='../images/editeur/027.png' /> <input type='button' onClick="reset('objet')" value="Reset objet"/></td>
					<td width='180px'><img src='../images/editeur/028.png' /> <input type='button' onClick="reset('artefact')" value="Reset art"/></td>
				</tr>
				<tr>
					<td><img src='../images/editeur/trash_can.png' /> <input type='button' onClick="gomme('deco')" value="Gomm decor"/></td>
					<td><img src='../images/editeur/trash_can.png' /> <input type='button' onClick="gomme('objet')" value="Gomm objet"/></td>
					<td width='180px'><img src='../images/editeur/trash_can.png' /> <input type='button' onClick="gomme('artefact')" value="Gomm art"/></td>
				</tr>	
			</table>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
