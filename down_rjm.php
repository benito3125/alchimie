<?php

// connexion à la base
include "config.php";

// Appel de la librairie FPDF
require("fpdf/fpdf.php");

// Création de la class PDF
class PDF extends FPDF {
	// Header
	function Header() {
		// Logo : 8 >position à gauche du document (en mm), 2 >position en haut du document, 80 >largeur de l'image en mm). La hauteur est calculée automatiquement.
		$this->Image('logo.png',8,2);
		// Saut de ligne 20 mm
		$this->Ln(20);

		// Titre gras (B) police Helbetica de 11
		$this->SetFont('Helvetica','B',11);
		// fond de couleur gris (valeurs en RGB)
		$this->setFillColor(230,230,230);
 		// position du coin supérieur gauche par rapport à la marge gauche (mm)
		$this->SetX(70);
		// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok	
		$this->Cell(60,8,'Liste des repas disponible le Jeudi Midi',0,1,'C',1);
		// Saut de ligne 10 mm
		$this->Ln(10);		
	}
	// Footer
	function Footer() {
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont('Helvetica','I',9);
		// Numéro de page, centré (C)
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

// On active la classe une fois pour toutes les pages suivantes
// Format portrait (>P) ou paysage (>L), en mm (ou en points > pts), A4 (ou A5, etc.)
$pdf = new PDF('P','mm','A4');

// Nouvelle page A4 (incluant ici logo, titre et pied de page)
$pdf->AddPage();
// Polices par défaut : Helvetica taille 9
$pdf->SetFont('Helvetica','',9);
// Couleur par défaut : noir
$pdf->SetTextColor(0);
// Compteur de pages {nb}
$pdf->AliasNbPages();

// Fonction en-tête des tableaux en 3 colonnes de largeurs variables
function entete_table($position_entete) {
	global $pdf;
	$pdf->SetDrawColor(183); // Couleur du fond RVB
	$pdf->SetFillColor(221); // Couleur des filets RVB
	$pdf->SetTextColor(0); // Couleur du texte noir
	$pdf->SetY($position_entete);
	// position de colonne 1 (30mm à gauche)	
	$pdf->SetX(30);
	$pdf->Cell(60,8,'Prenom',1,0,'C',1);	// 90 >largeur colonne, 8 >hauteur colonne
	// position de la colonne 2 (90 = 30+60)
	$pdf->SetX(90); 
	$pdf->Cell(60,8,'Nom',1,0,'C',1);
	// position de la colonne 3 (150 = 90+60)
	$pdf->SetX(150); 
	$pdf->Cell(30,8,'Vege',1,0,'C',1);

	$pdf->Ln(); // Retour à la ligne
}
// AFFICHAGE EN-TÊTE DU TABLEAU
// Position ordonnée de l'entête en valeur absolue par rapport au sommet de la page (60 mm)
$position_entete = 70;
// police des caractères
$pdf->SetFont('Helvetica','',9);
$pdf->SetTextColor(4);
// on affiche les en-têtes du tableau
entete_table($position_entete);


$sql = "SELECT * FROM benevoles WHERE RJM = '1'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$position_detail = 78; // Position ordonnée = $position_entete+hauteur de la cellule d'en-tête (60+8)
	while ($row = $result->fetch_assoc()) {
		// position abcisse de la colonne 1 (30mm du bord)
		$pdf->SetY($position_detail);
		$pdf->SetX(30);
		$pdf->MultiCell(60,8,utf8_decode($row['family_name']),1,'C');
		// position abcisse de la colonne 2 (90 = 10 + 60)	
		$pdf->SetY($position_detail);
		$pdf->SetX(90); 
		$pdf->MultiCell(60,8,utf8_decode($row['first_name']),1,'C');
		// position abcisse de la colonne 3 (500 = 70+ 60)
		$pdf->SetY($position_detail);
		$pdf->SetX(150); 
		$pdf->MultiCell(30,8,$row['vege'],1,'C');

		// on incrémente la position ordonnée de la ligne suivante (+8mm = hauteur des cellules)	
		$position_detail += 8; 
	}
	$pdf->AddPage();
//mysqli_free_result($result);
} else {
	$pdf->Cell(500,20,utf8_decode('Plus rien à vous dire ;-)'));
}

// Nouvelle page PDF
//$pdf->AddPage();
// Polices par défaut : Helvetica taille 9
$pdf->SetFont('Helvetica','',11);
// Couleur par défaut : noir
$pdf->SetTextColor(25,134,23);
// Compteur de pages {nb}
$pdf->AliasNbPages();

$pdf->Output('liste.pdf','I', TRUE); // affichage à l'écran
// Ou export sur le serveur
// $pdf->Output('F', '../test.pdf');
?>