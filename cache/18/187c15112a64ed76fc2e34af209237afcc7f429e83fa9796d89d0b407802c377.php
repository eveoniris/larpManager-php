<?php

/* homepage/fragment/personnage.twig */
class __TwigTemplate_29796885dd6c230325f1143a5eb929ed4c63211e37e666a9250fc2aba9ca9cd2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "\t\t";
        // line 2
        echo "\t\t<div class=\"header\">
\t\t\t<h5>Informations</h5>
\t\t</div>
\t\t
\t\t<div class=\"row\">
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Nom :</strong> ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "nom", array()), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Surnom :</strong> ";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "surnom", array()), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Classe :</strong> ";
        // line 16
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classeName", array()), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Age :</strong> ";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "age", array()), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Genre :</strong> ";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "genre", array()), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t";
        // line 25
        if ($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "intrigue", array())) {
            // line 26
            echo "\t\t\t\t\t\t\tParticipe aux intrigues
\t\t\t\t\t\t";
        } else {
            // line 28
            echo "\t\t\t\t\t\t\tNe participe pas aux intrigues
\t\t\t\t\t\t";
        }
        // line 30
        echo "\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Expérience :</strong> ";
        // line 32
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array()), 0)) : (0)), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<strong>Renommée :</strong> ";
        // line 35
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "renomme", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "renomme", array()), 0)) : (0)), "html", null, true);
        echo "
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t\t
\t\t\t";
        // line 41
        echo "\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<div class=\"pull-right\">
\t\t\t\t\t";
        // line 43
        if (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "genre", array()) == "Masculin")) {
            // line 44
            echo "\t\t\t\t\t\t<img width=\"216\" height=\"300\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "labelMasculin", array()), "html", null, true);
            echo "\" src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
            echo "/img/";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "imageM", array()), "html", null, true);
            echo "\" />
\t\t\t\t\t";
        } else {
            // line 46
            echo "\t\t\t\t\t\t<img width=\"216\" height=\"200\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "labelMasculin", array()), "html", null, true);
            echo "\" src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
            echo "/img/";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "imageM", array()), "html", null, true);
            echo "\" />
\t\t\t\t\t";
        }
        // line 48
        echo "\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t
\t\t";
        // line 53
        echo "\t\t<div class=\"header\">
\t\t\t<h5>Religion</h5>
\t\t</div>
\t\t
\t\t<div class=\"row\">
\t\t\t<div class=\"col-sm-6\">
\t\t\t\tVous pouvez choisir autant de religions que vous voulez. Attention toutefois, les règles suivantes s'appliquent :
\t\t\t\t<ul>
\t\t\t\t\t<li>Vous ne pouvez avoir qu'une seule religion au niveau \"Fervent\"</li>
\t\t\t\t\t<li>Si vous choisissez une religion au niveau \"Fanatique\", vous perdez toutes vos autres religions (un Fanatique n'a qu'une seule religion).</li>
\t\t\t\t</ul>\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t";
        // line 67
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "personnagesReligions", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["personnageReligion"]) {
            // line 68
            echo "\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t";
            // line 69
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageReligion"], "religion", array()), "label", array()), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageReligion"], "religionLevel", array()), "label", array()), "html", null, true);
            echo " - <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("forum.topic", array("index" => $this->getAttribute($this->getAttribute($this->getAttribute($context["personnageReligion"], "religion", array()), "topic", array()), "id", array()))), "html", null, true);
            echo "\">Forum</a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['personnageReligion'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 72
        echo "\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
        // line 73
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.religion.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-plus\"></i>
\t\t\t\t\t\t\tChoisir une nouvelle religion
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t</div>
\t\t
\t\t";
        // line 83
        echo "\t\t<div class=\"header\">
\t\t\t<h5>Origine</h5>
\t\t</div>
\t\t
\t\t<div class=\"row\">
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t";
        // line 89
        if ($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "territoire", array())) {
            // line 90
            echo "\t\t\t\t\t<p><strong>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "territoire", array()), "nom", array()), "html", null, true);
            echo "</strong></p>
\t\t\t\t";
        } else {
            // line 92
            echo "\t\t\t\t\t<p>
\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
            // line 93
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.origin.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
            echo "\">Choisir une origine</a>
\t\t\t\t\t</p>
\t\t\t\t";
        }
        // line 96
        echo "\t\t\t</div>
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t";
        // line 98
        if ($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "territoire", array())) {
            // line 99
            echo "\t\t\t\t\t";
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "territoire", array()), "description", array()));
            echo "
\t\t\t\t";
        }
        // line 101
        echo "\t\t\t</div>
\t\t</div>
\t\t
\t\t
\t\t";
        // line 106
        echo "\t\t<div class=\"header\">
\t\t\t<h5>Compétences</h5>
\t\t</div>
\t\t
\t\t<div class=\"row\">
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<h6>Compétences <small>(";
        // line 112
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array()), 0)) : (0)), "html", null, true);
        echo " xp)</small></h6>
\t\t\t\t
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t
\t\t\t\t\t";
        // line 116
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "competences", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["competence"]) {
            // line 117
            echo "\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t<strong>";
            // line 118
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "competenceFamily", array()), "label", array()), "html", null, true);
            echo "</strong>&nbsp(";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "level", array()), "label", array()), "html", null, true);
            echo ") : ";
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["competence"], "description", array()));
            echo "
\t\t\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t    \t\t\t\t";
            // line 120
            if ($this->getAttribute($context["competence"], "documentUrl", array())) {
                // line 121
                echo "\t\t\t    \t\t\t\t\t<a class=\"btn btn-default btn-sm\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.document", array("competence" => $this->getAttribute($context["competence"], "id", array()), "document" => $this->getAttribute($context["competence"], "documentUrl", array()))), "html", null, true);
                echo "\">
\t\t\t    \t\t\t\t\t\t<i class=\"fa fa-file\"></i> Téléchargez l'aide de jeu
\t\t\t    \t\t\t\t\t</a>
\t\t\t    \t\t\t\t";
            }
            // line 125
            echo "\t\t    \t\t\t\t</p>\t\t\t\t\t\t\t
\t\t\t\t\t\t</li>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competence'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 128
        echo "\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
        // line 129
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.competence.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-plus\"></i>
\t\t\t\t\t\t\tAcheter une nouvelle compétence
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t\t
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<h6>Historique</h6>
\t\t\t\t<ul class=\"list-group\">\t\t
\t\t\t\t";
        // line 140
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "experienceGains", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["historique"]) {
            // line 141
            echo "\t\t\t\t\t<li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($context["historique"], "operationDate", array()), "Y-m-d h:i:s"), "html", null, true);
            echo " : + ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "xpGain", array()), "html", null, true);
            echo " xp ";
            if ($this->getAttribute($context["historique"], "explanation", array())) {
                echo " pour la raison suivante : \"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "explanation", array()), "html", null, true);
                echo "\"";
            }
            echo ".</li>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['historique'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 143
        echo "\t\t\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "experienceUsages", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["historique"]) {
            // line 144
            echo "\t\t\t\t\t<li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($context["historique"], "operationDate", array()), "Y-m-d h:i:s"), "html", null, true);
            echo " : - ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "xpUse", array()), "html", null, true);
            echo " xp pour acquérir ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["historique"], "competence", array()), "label", array()), "html", null, true);
            echo ".</li>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['historique'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 146
        echo "\t\t\t</div>
\t\t</div>
\t\t\t
\t\t";
        // line 150
        echo "\t\t<div class=\"header\">
\t\t\t<h5>Groupes secondaires</h5>
\t\t</div>
\t\t
\t\t<div class=\"row\">
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t\t
\t\t\t\t\t";
        // line 158
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "secondaryGroups", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["groupeSecondaire"]) {
            // line 159
            echo "\t\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t\t<a href=\"";
            // line 160
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupeSecondaire.joueur", array("groupe" => $this->getAttribute($context["groupeSecondaire"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["groupeSecondaire"], "label", array()), "html", null, true);
            echo "</a>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupeSecondaire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 163
        echo "\t\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
        // line 164
        echo $this->env->getExtension('routing')->getPath("groupeSecondaire.list");
        echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-list\"></i>
\t\t\t\t\t\t\tListe des groupes secondaires
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t
\t\t\t<div class=\"col-sm-6\">
\t\t\t\t";
        // line 173
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "postulants", array())) > 0)) {
            // line 174
            echo "\t\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t\t";
            // line 175
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "postulants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["postulant"]) {
                // line 176
                echo "\t\t\t\t\t\t\t<li class=\"list-group-item\">Votre candidature au groupe ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["postulant"], "secondaryGroup", array()), "label", array()), "html", null, true);
                echo " est en attente de validation.</li>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postulant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 178
            echo "\t\t\t\t\t</ul>
\t\t\t\t";
        }
        // line 180
        echo "\t\t\t</div>
\t\t</div>

\t\t";
        // line 190
        echo "\t
";
    }

    public function getTemplateName()
    {
        return "homepage/fragment/personnage.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  395 => 190,  390 => 180,  386 => 178,  377 => 176,  373 => 175,  370 => 174,  368 => 173,  356 => 164,  353 => 163,  342 => 160,  339 => 159,  335 => 158,  325 => 150,  320 => 146,  307 => 144,  302 => 143,  285 => 141,  281 => 140,  267 => 129,  264 => 128,  256 => 125,  248 => 121,  246 => 120,  237 => 118,  234 => 117,  230 => 116,  223 => 112,  215 => 106,  209 => 101,  203 => 99,  201 => 98,  197 => 96,  191 => 93,  188 => 92,  182 => 90,  180 => 89,  172 => 83,  160 => 73,  157 => 72,  144 => 69,  141 => 68,  137 => 67,  121 => 53,  115 => 48,  105 => 46,  95 => 44,  93 => 43,  89 => 41,  81 => 35,  75 => 32,  71 => 30,  67 => 28,  63 => 26,  61 => 25,  55 => 22,  49 => 19,  43 => 16,  37 => 13,  31 => 10,  21 => 2,  19 => 1,);
    }
}
/* 		{# informations générales #}*/
/* 		<div class="header">*/
/* 			<h5>Informations</h5>*/
/* 		</div>*/
/* 		*/
/* 		<div class="row">*/
/* 			<div class="col-sm-6">*/
/* 				<ul class="list-group">*/
/* 					<li class="list-group-item">*/
/* 						<strong>Nom :</strong> {{ personnage.nom }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Surnom :</strong> {{ personnage.surnom }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Classe :</strong> {{ personnage.classeName }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Age :</strong> {{ personnage.age }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Genre :</strong> {{ personnage.genre }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						{% if personnage.intrigue %}*/
/* 							Participe aux intrigues*/
/* 						{% else %}*/
/* 							Ne participe pas aux intrigues*/
/* 						{% endif %}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Expérience :</strong> {{ personnage.xp|default(0) }}*/
/* 					</li>*/
/* 					<li class="list-group-item">*/
/* 						<strong>Renommée :</strong> {{ personnage.renomme|default(0) }}*/
/* 					</li>*/
/* 				</ul>*/
/* 			</div>*/
/* 			*/
/* 			{# Illustration de la classe #}*/
/* 			<div class="col-sm-6">*/
/* 				<div class="pull-right">*/
/* 					{% if personnage.genre == 'Masculin' %}*/
/* 						<img width="216" height="300" alt="{{ personnage.classe.labelMasculin }}" src="{{ app.request.basepath }}/img/{{ personnage.classe.imageM }}" />*/
/* 					{% else %}*/
/* 						<img width="216" height="200" alt="{{ personnage.classe.labelMasculin }}" src="{{ app.request.basepath }}/img/{{ personnage.classe.imageM }}" />*/
/* 					{% endif %}*/
/* 				</div>*/
/* 			</div>*/
/* 		</div>*/
/* 		*/
/* 		{# Religion #}*/
/* 		<div class="header">*/
/* 			<h5>Religion</h5>*/
/* 		</div>*/
/* 		*/
/* 		<div class="row">*/
/* 			<div class="col-sm-6">*/
/* 				Vous pouvez choisir autant de religions que vous voulez. Attention toutefois, les règles suivantes s'appliquent :*/
/* 				<ul>*/
/* 					<li>Vous ne pouvez avoir qu'une seule religion au niveau "Fervent"</li>*/
/* 					<li>Si vous choisissez une religion au niveau "Fanatique", vous perdez toutes vos autres religions (un Fanatique n'a qu'une seule religion).</li>*/
/* 				</ul>			*/
/* 			</div>*/
/* 			<div class="col-sm-6">*/
/* 				<ul class="list-group">*/
/* 					{% for personnageReligion in personnage.personnagesReligions %}*/
/* 						<li class="list-group-item">*/
/* 							{{ personnageReligion.religion.label }} - {{ personnageReligion.religionLevel.label }} - <a href="{{ path('forum.topic',{'index': personnageReligion.religion.topic.id }) }}">Forum</a>*/
/* 						</li>*/
/* 					{% endfor %}*/
/* 					<li class="list-group-item">*/
/* 						<a class="btn btn-default" href="{{ path('personnage.religion.add',{'personnage': personnage.id}) }}">*/
/* 							<i class="fa fa-plus"></i>*/
/* 							Choisir une nouvelle religion*/
/* 						</a>*/
/* 					</li>*/
/* 				</ul>*/
/* 			</div>*/
/* 		</div>*/
/* 		*/
/* 		{# Origine #}*/
/* 		<div class="header">*/
/* 			<h5>Origine</h5>*/
/* 		</div>*/
/* 		*/
/* 		<div class="row">*/
/* 			<div class="col-sm-6">*/
/* 				{% if personnage.territoire %}*/
/* 					<p><strong>{{ personnage.territoire.nom }}</strong></p>*/
/* 				{% else %}*/
/* 					<p>*/
/* 						<a class="btn btn-default" href="{{ path('personnage.origin.add',{'personnage': personnage.id}) }}">Choisir une origine</a>*/
/* 					</p>*/
/* 				{% endif %}*/
/* 			</div>*/
/* 			<div class="col-sm-6">*/
/* 				{% if personnage.territoire %}*/
/* 					{{ personnage.territoire.description|markdown }}*/
/* 				{% endif %}*/
/* 			</div>*/
/* 		</div>*/
/* 		*/
/* 		*/
/* 		{# Compétences #}*/
/* 		<div class="header">*/
/* 			<h5>Compétences</h5>*/
/* 		</div>*/
/* 		*/
/* 		<div class="row">*/
/* 			<div class="col-sm-6">*/
/* 				<h6>Compétences <small>({{ personnage.xp|default(0) }} xp)</small></h6>*/
/* 				*/
/* 				<ul class="list-group">*/
/* 					*/
/* 					{% for competence in personnage.competences %}*/
/* 						<li class="list-group-item">*/
/* 							<strong>{{ competence.competenceFamily.label }}</strong>&nbsp({{ competence.level.label }}) : {{ competence.description|markdown }}*/
/* 							<p class="list-group-item-text">*/
/* 			    				{% if competence.documentUrl %}*/
/* 			    					<a class="btn btn-default btn-sm" href="{{ path('competence.document',{'competence' : competence.id, 'document':competence.documentUrl}) }}">*/
/* 			    						<i class="fa fa-file"></i> Téléchargez l'aide de jeu*/
/* 			    					</a>*/
/* 			    				{% endif %}*/
/* 		    				</p>							*/
/* 						</li>*/
/* 					{% endfor %}*/
/* 					<li class="list-group-item">*/
/* 						<a class="btn btn-default" href="{{ path('personnage.competence.add', {'personnage': personnage.id}) }}">*/
/* 							<i class="fa fa-plus"></i>*/
/* 							Acheter une nouvelle compétence*/
/* 						</a>*/
/* 					</li>*/
/* 				</ul>*/
/* 			</div>*/
/* 			*/
/* 			<div class="col-sm-6">*/
/* 				<h6>Historique</h6>*/
/* 				<ul class="list-group">		*/
/* 				{% for historique in personnage.experienceGains %}*/
/* 					<li class="list-group-item">{{ historique.operationDate|date("Y-m-d h:i:s") }} : + {{ historique.xpGain }} xp {% if historique.explanation %} pour la raison suivante : "{{ historique.explanation }}"{% endif %}.</li>*/
/* 				{% endfor %}*/
/* 				{% for historique in personnage.experienceUsages %}*/
/* 					<li class="list-group-item">{{ historique.operationDate|date("Y-m-d h:i:s") }} : - {{ historique.xpUse }} xp pour acquérir {{ historique.competence.label }}.</li>*/
/* 				{% endfor %}*/
/* 			</div>*/
/* 		</div>*/
/* 			*/
/* 		{# Groupes secondaires #}*/
/* 		<div class="header">*/
/* 			<h5>Groupes secondaires</h5>*/
/* 		</div>*/
/* 		*/
/* 		<div class="row">*/
/* 			<div class="col-sm-6">*/
/* 				<ul class="list-group">*/
/* 						*/
/* 					{% for groupeSecondaire in personnage.secondaryGroups %}*/
/* 						<li class="list-group-item">*/
/* 							<a href="{{ path('groupeSecondaire.joueur', {'groupe':groupeSecondaire.id}) }}">{{ groupeSecondaire.label }}</a>*/
/* 						</li>*/
/* 					{% endfor %}*/
/* 					<li class="list-group-item">*/
/* 						<a class="btn btn-default" href="{{ path('groupeSecondaire.list') }}">*/
/* 							<i class="fa fa-list"></i>*/
/* 							Liste des groupes secondaires*/
/* 						</a>*/
/* 					</li>*/
/* 				</ul>*/
/* 			</div>*/
/* 		*/
/* 			<div class="col-sm-6">*/
/* 				{% if personnage.postulants|length > 0 %}*/
/* 					<ul class="list-group">*/
/* 						{% for postulant in personnage.postulants %}*/
/* 							<li class="list-group-item">Votre candidature au groupe {{ postulant.secondaryGroup.label }} est en attente de validation.</li>*/
/* 						{% endfor %}*/
/* 					</ul>*/
/* 				{% endif %}*/
/* 			</div>*/
/* 		</div>*/
/* */
/* 		{# Autre */
/* 		<div class="header">*/
/* 			<h5>Autre</h5>*/
/* 		</div>*/
/* 		*/
/* 		<h6>Téléchargement</h6>*/
/* 		<a class="btn btn-default" href="{{ path('personnage.export', {'personnage':personnage.id}) }}">Télécharger le pdf</a>*/
/* 		#}	*/
/* */
