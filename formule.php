<?php
// calcul de la note + couleur pour un projet
function calc_note($nb_jours_restants_eval=30,$arrForNote){
    $nxps = $arrForNote['nxps']; // nbre xperts sollicités
    $nxpr = $arrForNote['nxpr']; // nbre xperts refus (refus,conflit), toutes les réponses négatives
    $nea = $arrForNote['nea']; // nbre d'évaluations acceptées
    $ned = $arrForNote['ned']; // nbre d'évaluations débutées
    $nes = $arrForNote['nes']; // nbre d'évaluations soumises, validées
    $nb_duree_eval = 30; // durée évaluation en jours , peut être surchargé pour un projet particulier
    $nb_min_eval = 3; // nbre minimum d'évaluations requises, peut être surchargé pour un projet particulier
    $nb_xp = 15; // nbre d'xperts affectés au projet
    $note = 0;
    $couleur = '';
    if($nes>=$nb_min_eval){
        $note = 3000*$nes;
        $couleur = '#99ccff';
    }
    if($ned>=$nb_min_eval){
        $note = 100*$ned;
        $couleur='#ccffff';
    }
    if($nea>=$nb_min_eval){
        $note = 50*$nea;
        $couleur = '#99ff99';
    }
    if( empty($note) ){
        // si la note est vide
        if($nb_jours_restants_eval>0){
            // nbre de jours restants supérieur à zéro
            /*
            Dans le cas d'un gradient de couleur
            $note_min =  - ($nb_xp)*($nb_min_eval)*$nb_duree_eval/$nb_jours_restants_eval;
            $note_max = 3000*$nb_min_eval;
            */
            $note = $nes*1000+$ned*100+$nea*50+$nxps*20 - (($nb_xp-($nes+$nea+$ned+$nxps))*5+$nxpr*20)*($nb_min_eval-($nes+$ned+$nea))*$nb_duree_eval/$nb_jours_restants_eval;
            /*
            si gradient de couleur
            $pourcent = ($note_max-$note_min)/$note_calc;
            $note = $pourcent;
            */
            $couleur = '#ffcc99';
        } else {
            $note = (-3000*($nb_xp-($nes+$nea+$ned+$nxps)+$nxpr*2)+1*($nes*1000+$ned*100+$nea*10+$nxps*5)) * (1+abs($nb_jours_restants_eval));
            $couleur = '#ff9999';
        }
    }
    return ['couleur'=>$couleur,'note'=>ceil($note)];
}