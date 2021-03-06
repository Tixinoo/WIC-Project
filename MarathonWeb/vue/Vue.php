<?php

class Vue {

    private $obj;

    public function __construct($param) {
        $this->obj = $param;
    }

    public function afficherplat() {
        include 'html/header.html';
        $r = Restaurant::findById($this->obj->id_resto);
        $t = Theme::findById($r->id_theme);
        echo '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a> > <a href="' . $r->getUrl() . '" > ' . $r->nom . '</a> > <a href="' . $this->obj->getUrl() . '" > ' . $this->obj->nom . '</a>';   
        $_SESSION['arianne'] = '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a> > <a href="' . $r->getUrl() . '" > ' . $r->nom . '</a> > <a href="' . $this->obj->getUrl() . '" > ' . $this->obj->nom . '</a>';
        echo "<h1 class=\"ptitre\">" . $this->obj->nom . "</h1>";

        echo '<div id="dplat"><div class="dimage"><figure><a href="images/originales/' . $this->obj->photo . '" target="_blank"><img src="images/originales/' . $this->obj->photo . '" alt="" class="imagelimitee" /></a><figcaption><a href="images/originales/' . $this->obj->photo . '" target="_blank">(Cliquez pour agrandir)</a></figcaption></figure></div><div class="dcaract"><ul><li>Thème : ' . $t->nom . '</li><li>Restaurant : ' . $r->nom . ' </li><li>Prix : ' . $this->obj->prix . '</li></ul></div></div>';
        echo '<p class="lienpanier"><a href="panier.php?a=addPanier&idPlat=' . $this->obj->id . '&qte=' . 1 . '">Ajouter (1x) au panier</a></p></br>';
        include 'html/footer.html';
    }

    public function vuedefault() {
        include 'html/header.html';
        $_SESSION['arianne'] = '<br/>';
        include ('html/accueil.html');
        foreach ($this->obj as $t) {
            echo $this->vue_theme($t);
        }
        include 'html/footer.html';
    }

    public function vue_all_theme() {
        include 'html/header.html';
        $_SESSION['arianne'] = '<br/>';
        foreach ($this->obj as $t) {
            echo $this->vue_theme($t);
        }
        include 'html/footer.html';
    }

    public function vue_theme($theme) {
        $res = "<div class=\"contenu\">\n";
        $id = $theme->id;
        $res = $res . "<h1 class=\"titretheme\"><a href=\"index.php?a=listResto&idtheme=$id\">" . $theme->nom . "</a></h1>";

        $res = $res . "<p class=catdescription>" . $theme->description . "</p>";
        $res = $res . "</div>";
        return $res;
    }

    public function vue_all_resto($idtheme) {
        include 'html/header.html';
        $t = Theme::findById($idtheme);
        echo '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a><br/>';
        echo "<h2>Voici les restaurants correspondants au thème : " . $t->nom . "</h2>";
        $_SESSION['arianne'] = '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a><br/>';
        foreach ($this->obj as $r) {
            echo $this->vue_resto($r);
        }
        include 'html/footer.html';
    }

    public function vue_resto($resto) {
        $res = "<div class=\"contenu\">\n";
        $id = $resto->id;
        $res = $res . "<h1 class=\"titretheme\"><a href=\"index.php?a=listPlat&idresto=$id\">" . $resto->nom . "</a></h1>";

        $res = $res . "<p class=catdescription>" . $resto->description . "</p>";
        $res = $res . "</div>";
        return $res;
    }

    public function vue_all_plat($idresto) {
        include 'html/header.html';
        $r = Restaurant::findById($idresto);
        $t = Theme::findById($r->id_theme);

        echo '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a> > <a href="' . $r->getUrl() . '">' . $r->nom . '</a> ><br/>';
        $_SESSION['arianne'] = '<a href="' . $t->getUrl() . '" > ' . $t->nom . '</a> > <a href="' . $r->getUrl() . '">' . $r->nom . '</a> ><br/>';

        echo "<h2>Voici les plats correspondants au restaurant : " . $r->nom . "</h2>";
        //$nb = $this->obj->getSize();
        $nb = 0;
        $nb = sizeof($this->obj);
        echo "<h3>- Ce restaurant possède $nb plats à la carte ! Voici la liste : </h3>";
        foreach ($this->obj as $p) {
            echo $this->vue_plat($p);
        }
        include 'html/footer.html';
    }

    public function vue_plat($plat) {
        $res = "<div class=\"contenu\">\n";
        $id = $plat->id;

        $res = $res . "<h1 class=\"titretheme\"><a href=\"index.php?a=affPlat&idplat=$id\">" . $plat->nom . "</a></h1>";

        $res = $res . "<p class=catdescription>" . $plat->description . "</p>";
        $res = $res . '<a href="index.php?a=affPlat&idplat=' . $id . '"><img src="images/petites/' . $plat->photo . '" alt="image" class="petiteimage" /></a>';
        $res = $res . '<p class="pprix">Prix : ' . $plat->prix . '€</p>';
        $res = $res . '<p class="lienpanier"><a href="panier.php?a=addPanier&idPlat=' . $plat->id . '&qte=' . 1 . '">Ajouter (1x) au panier</a></p></br>';
        $res = $res . "</div>";
        return $res;
    }

    public function vue_panier() {
        $montant_total = 0;
        include 'html/header.html';
        if(isset($_SESSION['arianne'])) echo $_SESSION['arianne'];
        $panier = $this->obj;
        if (sizeof($panier)!=0) {
            $res = '<h3>Récapitulatif de votre commande : </h3><div id=panier><table><tr class="headerrow"><td>Thème</td><td>Plat</td><td>Restaurant</td><td>Quantité</td><td>P.U.</td><td>Total</td><td>Supprimer</td></tr>';
            foreach ($panier as $p) {
                $resto = Restaurant::findByNom($p['restaurant']);
                $tabplat = Plat::findByResto($resto->id);
                $idplat = 0;
                foreach($tabplat as $pl) {
                    if ($pl->nom == $p['plat'])
                        $idplat = $pl->id;
                }
                $res = $res . '<tr><td>'. $p['type'] .'</td><td>'. $p['plat'] .'</td><td>'. $p['restaurant'] .'</td><td>'. $p['nbre'] .'x</td><td>'. $p['pu'] .'€</td><td>'. $p['total'] .'€</td><td><a href="panier.php?a=suppPanier&idPlat='.
                $idplat . '">Supprimer (1x) parce que c\'est pas bon</a></td></tr>';
                $montant_total = $montant_total + $p['nbre'] * $p['total'];
            }
            $res = $res . '<tr><td colspan="5"><p align="right"><b>Montant Total</b></p></td><td colspan="2"><p align="left"><b>' . $montant_total . '€</b></p></td></tr>';
            $res = $res . '</table></div><br/><br/><a href="index.php">Retour à l\'accueil</a>';
            echo $res;
        }
        else
            echo '<h3>Vous n\'avez rien commandé !<br/>Même pas une petite faim ?</h3>';
        include 'html/footer.html';
    }

}
