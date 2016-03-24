<?php

/* menu.twig */
class __TwigTemplate_ca660d7180964096d2bec471beadccf88de2fb3823f0f9411913369d2d4bb90e extends Twig_Template
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
        echo "<nav class=\"navbar navbar-default\">
  <div class=\"container-fluid\">
  
  \t<!-- Brand and toggle get grouped for better mobile display -->
    <div class=\"navbar-header\">
      <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\" aria-expanded=\"false\">
        <span class=\"sr-only\">Toggle navigation</span>
        <span class=\"icon-bar\"></span>
        <span class=\"icon-bar\"></span>
        <span class=\"icon-bar\"></span>
      </button>
      <a class=\"navbar-brand\" href=\"";
        // line 12
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">LarpManager</a>
    </div>

\t<div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
\t\t<ul class=\"nav navbar-nav\">
\t\t\t<li><a href=\"http://www.eveoniris.com\">Eve-Oniris</a></li>
\t\t\t
\t\t\t";
        // line 20
        echo "\t\t\t";
        if ($this->env->getExtension('security')->isGranted("ROLE_SCENARISTE", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 21
            echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Admin<span class=\"caret\"></span></a>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t<li><a href=\"";
            // line 24
            echo $this->env->getExtension('routing')->getPath("annonce.list");
            echo "\">Liste des annonces</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 25
            echo $this->env->getExtension('routing')->getPath("gn.list");
            echo "\">Liste des grandeurs natures</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 26
            echo $this->env->getExtension('routing')->getPath("user.admin.list");
            echo "\">Liste des utilisateurs</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 27
            echo $this->env->getExtension('routing')->getPath("groupe.admin.list");
            echo "\">Liste des groupes</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 28
            echo $this->env->getExtension('routing')->getPath("groupeSecondaire.admin.list");
            echo "\">Liste des groupes secondaires</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 29
            echo $this->env->getExtension('routing')->getPath("personnage.admin.list");
            echo "\">Liste des personnages</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 30
            echo $this->env->getExtension('routing')->getPath("right.admin.list");
            echo "\">Liste des droits</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 31
            echo $this->env->getExtension('routing')->getPath("statistique");
            echo "\">Statistiques</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 32
            echo $this->env->getExtension('routing')->getPath("admin");
            echo "\">Administration</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 33
            echo $this->env->getExtension('routing')->getPath("trombinoscope");
            echo "\">Trombinoscope</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 34
            echo $this->env->getExtension('routing')->getPath("admin.rappels");
            echo "\">rappels</a></li>
\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t";
        }
        // line 38
        echo "\t\t\t
\t\t\t";
        // line 40
        echo "\t\t\t";
        if ($this->env->getExtension('security')->isGranted("ROLE_SCENARISTE", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 41
            echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Scénario<span class=\"caret\"></span></a>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t<li><a href=\"";
            // line 44
            echo $this->env->getExtension('routing')->getPath("appelation");
            echo "\">Appelations</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 45
            echo $this->env->getExtension('routing')->getPath("territoire.admin.list");
            echo "\">Territoires</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 46
            echo $this->env->getExtension('routing')->getPath("world");
            echo "\">Cartographie</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 47
            echo $this->env->getExtension('routing')->getPath("langue");
            echo "\">Langues</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 48
            echo $this->env->getExtension('routing')->getPath("ressource");
            echo "\">Ressources</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 49
            echo $this->env->getExtension('routing')->getPath("religion");
            echo "\">Religions</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 50
            echo $this->env->getExtension('routing')->getPath("religion.level");
            echo "\">Fanatisme</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 51
            echo $this->env->getExtension('routing')->getPath("background.list");
            echo "\">Backgrounds</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 52
            echo $this->env->getExtension('routing')->getPath("chronologie");
            echo "\">Chronologie</a></li>
\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t";
        }
        // line 56
        echo "\t\t\t
\t\t\t";
        // line 58
        echo "\t\t\t";
        if ($this->env->getExtension('security')->isGranted("ROLE_REGLE", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 59
            echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Règles<span class=\"caret\"></span></a>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t<li><a href=\"";
            // line 62
            echo $this->env->getExtension('routing')->getPath("competence.family");
            echo "\">Famille de compétences</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 63
            echo $this->env->getExtension('routing')->getPath("level");
            echo "\">Niveaux</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 64
            echo $this->env->getExtension('routing')->getPath("competence");
            echo "\">Compétences</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 65
            echo $this->env->getExtension('routing')->getPath("classe");
            echo "\">Classes</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 66
            echo $this->env->getExtension('routing')->getPath("age");
            echo "\">Ages</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 67
            echo $this->env->getExtension('routing')->getPath("genre");
            echo "\">Genres</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 68
            echo $this->env->getExtension('routing')->getPath("personnageSecondaire.admin.list");
            echo "\">Personnage secondaire</a></li>
\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t";
        }
        // line 72
        echo "\t\t\t
\t\t\t";
        // line 74
        echo "\t\t\t";
        if ($this->env->getExtension('security')->isGranted("ROLE_STOCK", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 75
            echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Stock<span class=\"caret\"></span></a>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t<li><a href=\"";
            // line 78
            echo $this->env->getExtension('routing')->getPath("stock_homepage");
            echo "\">Tableau de bord</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 79
            echo $this->env->getExtension('routing')->getPath("stock_etat_index");
            echo "\">Etats</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 80
            echo $this->env->getExtension('routing')->getPath("stock_tag_index");
            echo "\">Tags</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 81
            echo $this->env->getExtension('routing')->getPath("stock_localisation_index");
            echo "\">Localisations</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 82
            echo $this->env->getExtension('routing')->getPath("stock_rangement_index");
            echo "\">Rangements</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 83
            echo $this->env->getExtension('routing')->getPath("stock_proprietaire_index");
            echo "\">Proprietaires</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 84
            echo $this->env->getExtension('routing')->getPath("stock_objet_list", array("page" => 1));
            echo "\">Objets</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 85
            echo $this->env->getExtension('routing')->getPath("stock_objet_export");
            echo "\">Exporter</a></li>
\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t";
        }
        // line 89
        echo "\t\t\t
\t\t\t";
        // line 91
        echo "\t\t\t";
        if ($this->env->getExtension('security')->isGranted("ROLE_USER", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 92
            echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Joueur<span class=\"caret\"></span></a>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t<li><a href=\"";
            // line 95
            echo $this->env->getExtension('routing')->getPath("forum");
            echo "\">Forum</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 96
            echo $this->env->getExtension('routing')->getPath("competence.list");
            echo "\">Les compétences</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 97
            echo $this->env->getExtension('routing')->getPath("classe.list");
            echo "\">Les classes</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 98
            echo $this->env->getExtension('routing')->getPath("groupeSecondaire.list");
            echo "\">Les groupes secondaires</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 99
            echo $this->env->getExtension('routing')->getPath("religion.list");
            echo "\">Les religions</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 100
            echo $this->env->getExtension('routing')->getPath("personnageSecondaire.list");
            echo "\">Les personnages secondaires</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 101
            echo $this->env->getExtension('routing')->getPath("world");
            echo "\">Carte</a></li>
\t\t\t\t\t\t
\t\t\t\t\t</ul
\t\t\t\t</li>
\t\t\t";
        }
        // line 106
        echo "\t\t\t
\t\t\t<li class=\"dropdown\">
\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Compte<span class=\"caret\"></span></a>
\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t";
        // line 110
        if ($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array())) {
            echo "\t\t\t
\t\t\t\t\t\t";
            // line 111
            if ($this->env->getExtension('security')->isGranted("ROLE_USER", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
                // line 112
                echo "\t\t\t\t\t\t\t<li><a href=\"";
                echo $this->env->getExtension('routing')->getPath("etatCivil");
                echo "\">Mon état-civil</a></li>
\t\t\t\t\t\t";
            }
            // line 114
            echo "\t\t\t\t\t\t<li><a href=\"";
            echo $this->env->getExtension('routing')->getPath("user");
            echo "\">Mon compte</a></li>\t
\t\t\t\t\t\t<li><a href=\"";
            // line 115
            echo $this->env->getExtension('routing')->getPath("user.messagerie");
            echo "\">Ma messagerie</a></li>\t\t\t\t
\t\t\t\t\t\t<li><a href=\"";
            // line 116
            echo $this->env->getExtension('routing')->getPath("user.logout");
            echo "\">Déconnection</a></li>
\t\t\t\t\t";
        } else {
            // line 118
            echo "\t\t\t\t\t\t<li><a href=\"";
            echo $this->env->getExtension('routing')->getPath("user.login");
            echo "\">Connection</a></li>
\t\t\t\t\t\t<li><a href=\"";
            // line 119
            echo $this->env->getExtension('routing')->getPath("user.register");
            echo "\">S'enregistrer</a></li>
\t\t\t\t\t";
        }
        // line 121
        echo "\t\t\t\t\t<li><a href=\"";
        echo $this->env->getExtension('routing')->getPath("legal");
        echo "\">Mentions légales</a></li>
\t\t\t\t</ul>
\t\t\t</li>\t
\t\t\t\t\t\t
\t\t\t";
        // line 125
        if ($this->env->getExtension('security')->isGranted("ADMIN_USER", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 126
            echo "\t\t\t<li class=\"dropdown\">
\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Larp</a>
\t\t\t\t<ul class=\"dropdown-menu\">\t\t\t
\t\t\t\t\t<li><a href=\"";
            // line 129
            echo $this->env->getExtension('routing')->getPath("install");
            echo "\">Tableau de bord</a></li>
\t\t\t\t</ul>
\t\t\t</li>
\t\t\t";
        }
        // line 133
        echo "\t\t</ul>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  342 => 133,  335 => 129,  330 => 126,  328 => 125,  320 => 121,  315 => 119,  310 => 118,  305 => 116,  301 => 115,  296 => 114,  290 => 112,  288 => 111,  284 => 110,  278 => 106,  270 => 101,  266 => 100,  262 => 99,  258 => 98,  254 => 97,  250 => 96,  246 => 95,  241 => 92,  238 => 91,  235 => 89,  228 => 85,  224 => 84,  220 => 83,  216 => 82,  212 => 81,  208 => 80,  204 => 79,  200 => 78,  195 => 75,  192 => 74,  189 => 72,  182 => 68,  178 => 67,  174 => 66,  170 => 65,  166 => 64,  162 => 63,  158 => 62,  153 => 59,  150 => 58,  147 => 56,  140 => 52,  136 => 51,  132 => 50,  128 => 49,  124 => 48,  120 => 47,  116 => 46,  112 => 45,  108 => 44,  103 => 41,  100 => 40,  97 => 38,  90 => 34,  86 => 33,  82 => 32,  78 => 31,  74 => 30,  70 => 29,  66 => 28,  62 => 27,  58 => 26,  54 => 25,  50 => 24,  45 => 21,  42 => 20,  32 => 12,  19 => 1,);
    }
}
/* <nav class="navbar navbar-default">*/
/*   <div class="container-fluid">*/
/*   */
/*   	<!-- Brand and toggle get grouped for better mobile display -->*/
/*     <div class="navbar-header">*/
/*       <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">*/
/*         <span class="sr-only">Toggle navigation</span>*/
/*         <span class="icon-bar"></span>*/
/*         <span class="icon-bar"></span>*/
/*         <span class="icon-bar"></span>*/
/*       </button>*/
/*       <a class="navbar-brand" href="{{ path('homepage') }}">LarpManager</a>*/
/*     </div>*/
/* */
/* 	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">*/
/* 		<ul class="nav navbar-nav">*/
/* 			<li><a href="http://www.eveoniris.com">Eve-Oniris</a></li>*/
/* 			*/
/* 			{# Menus reservé aux admins #}*/
/* 			{% if is_granted('ROLE_SCENARISTE', app.user) %}*/
/* 				<li class="dropdown">*/
/* 					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin<span class="caret"></span></a>*/
/* 					<ul class="dropdown-menu">*/
/* 						<li><a href="{{ path('annonce.list') }}">Liste des annonces</a></li>*/
/* 						<li><a href="{{ path('gn.list') }}">Liste des grandeurs natures</a></li>*/
/* 						<li><a href="{{ path('user.admin.list') }}">Liste des utilisateurs</a></li>*/
/* 						<li><a href="{{ path('groupe.admin.list') }}">Liste des groupes</a></li>*/
/* 						<li><a href="{{ path('groupeSecondaire.admin.list') }}">Liste des groupes secondaires</a></li>*/
/* 						<li><a href="{{ path('personnage.admin.list') }}">Liste des personnages</a></li>*/
/* 						<li><a href="{{ path('right.admin.list') }}">Liste des droits</a></li>*/
/* 						<li><a href="{{ path('statistique') }}">Statistiques</a></li>*/
/* 						<li><a href="{{ path('admin') }}">Administration</a></li>*/
/* 						<li><a href="{{ path('trombinoscope') }}">Trombinoscope</a></li>*/
/* 						<li><a href="{{ path('admin.rappels') }}">rappels</a></li>*/
/* 					</ul>*/
/* 				</li>*/
/* 			{% endif %}*/
/* 			*/
/* 			{# Menus reservé aux scénaristes #}*/
/* 			{% if is_granted('ROLE_SCENARISTE', app.user) %}*/
/* 				<li class="dropdown">*/
/* 					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Scénario<span class="caret"></span></a>*/
/* 					<ul class="dropdown-menu">*/
/* 						<li><a href="{{ path('appelation') }}">Appelations</a></li>*/
/* 						<li><a href="{{ path('territoire.admin.list') }}">Territoires</a></li>*/
/* 						<li><a href="{{ path('world') }}">Cartographie</a></li>*/
/* 						<li><a href="{{ path('langue') }}">Langues</a></li>*/
/* 						<li><a href="{{ path('ressource') }}">Ressources</a></li>*/
/* 						<li><a href="{{ path('religion') }}">Religions</a></li>*/
/* 						<li><a href="{{ path('religion.level') }}">Fanatisme</a></li>*/
/* 						<li><a href="{{ path('background.list') }}">Backgrounds</a></li>*/
/* 						<li><a href="{{ path('chronologie') }}">Chronologie</a></li>*/
/* 					</ul>*/
/* 				</li>*/
/* 			{% endif %}*/
/* 			*/
/* 			{# Menus reservé aux règles #}*/
/* 			{% if is_granted('ROLE_REGLE', app.user) %}*/
/* 				<li class="dropdown">*/
/* 					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Règles<span class="caret"></span></a>*/
/* 					<ul class="dropdown-menu">*/
/* 						<li><a href="{{ path('competence.family') }}">Famille de compétences</a></li>*/
/* 						<li><a href="{{ path('level') }}">Niveaux</a></li>*/
/* 						<li><a href="{{ path('competence') }}">Compétences</a></li>*/
/* 						<li><a href="{{ path('classe') }}">Classes</a></li>*/
/* 						<li><a href="{{ path('age') }}">Ages</a></li>*/
/* 						<li><a href="{{ path('genre') }}">Genres</a></li>*/
/* 						<li><a href="{{ path('personnageSecondaire.admin.list') }}">Personnage secondaire</a></li>*/
/* 					</ul>*/
/* 				</li>*/
/* 			{% endif %}*/
/* 			*/
/* 			{# Menus reservé au stock #}*/
/* 			{% if is_granted('ROLE_STOCK', app.user) %}*/
/* 				<li class="dropdown">*/
/* 					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Stock<span class="caret"></span></a>*/
/* 					<ul class="dropdown-menu">*/
/* 						<li><a href="{{ path('stock_homepage') }}">Tableau de bord</a></li>*/
/* 						<li><a href="{{ path('stock_etat_index') }}">Etats</a></li>*/
/* 						<li><a href="{{ path('stock_tag_index') }}">Tags</a></li>*/
/* 						<li><a href="{{ path('stock_localisation_index') }}">Localisations</a></li>*/
/* 						<li><a href="{{ path('stock_rangement_index') }}">Rangements</a></li>*/
/* 						<li><a href="{{ path('stock_proprietaire_index') }}">Proprietaires</a></li>*/
/* 						<li><a href="{{ path('stock_objet_list',{'page':1}) }}">Objets</a></li>*/
/* 						<li><a href="{{ path('stock_objet_export') }}">Exporter</a></li>*/
/* 					</ul>*/
/* 				</li>*/
/* 			{% endif %}*/
/* 			*/
/* 			{# Menus reservé aux utilisateurs #}*/
/* 			{% if is_granted('ROLE_USER', app.user) %}*/
/* 				<li class="dropdown">*/
/* 					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Joueur<span class="caret"></span></a>*/
/* 					<ul class="dropdown-menu">*/
/* 						<li><a href="{{ path('forum') }}">Forum</a></li>*/
/* 						<li><a href="{{ path('competence.list') }}">Les compétences</a></li>*/
/* 						<li><a href="{{ path('classe.list') }}">Les classes</a></li>*/
/* 						<li><a href="{{ path('groupeSecondaire.list') }}">Les groupes secondaires</a></li>*/
/* 						<li><a href="{{ path('religion.list') }}">Les religions</a></li>*/
/* 						<li><a href="{{ path('personnageSecondaire.list') }}">Les personnages secondaires</a></li>*/
/* 						<li><a href="{{ path('world') }}">Carte</a></li>*/
/* 						*/
/* 					</ul*/
/* 				</li>*/
/* 			{% endif %}*/
/* 			*/
/* 			<li class="dropdown">*/
/* 				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Compte<span class="caret"></span></a>*/
/* 				<ul class="dropdown-menu">*/
/* 					{%  if app.user %}			*/
/* 						{% if is_granted('ROLE_USER', app.user) %}*/
/* 							<li><a href="{{ path('etatCivil') }}">Mon état-civil</a></li>*/
/* 						{% endif %}*/
/* 						<li><a href="{{ path('user') }}">Mon compte</a></li>	*/
/* 						<li><a href="{{ path('user.messagerie') }}">Ma messagerie</a></li>				*/
/* 						<li><a href="{{ path('user.logout') }}">Déconnection</a></li>*/
/* 					{%  else %}*/
/* 						<li><a href="{{ path('user.login') }}">Connection</a></li>*/
/* 						<li><a href="{{ path('user.register') }}">S'enregistrer</a></li>*/
/* 					{%  endif %}*/
/* 					<li><a href="{{ path('legal') }}">Mentions légales</a></li>*/
/* 				</ul>*/
/* 			</li>	*/
/* 						*/
/* 			{% if is_granted('ADMIN_USER', app.user) %}*/
/* 			<li class="dropdown">*/
/* 				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Larp</a>*/
/* 				<ul class="dropdown-menu">			*/
/* 					<li><a href="{{ path('install') }}">Tableau de bord</a></li>*/
/* 				</ul>*/
/* 			</li>*/
/* 			{% endif %}*/
/* 		</ul>*/
/* 	</div>*/
/* </div>*/
