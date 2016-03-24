<?php

/* admin/personnage/detail.twig */
class __TwigTemplate_ddaf75759f4dea053e31830525d1d42b340e9e7f74ccbe5e7de000260e9d7409 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/personnage/detail.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Personnages";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
\t<ol class=\"breadcrumb\">
\t\t<li><a href=\"";
        // line 8
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">Accueil</a></li>
\t\t<li><a href=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("personnage.admin.list");
        echo "\">Liste des personnages</a></li>
\t\t<li class=\"active\">Détail de ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo "</li>
\t</ol>
\t\t
\t<div class=\"panel panel-success\">
\t\t<div class=\"panel-heading\">";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo " - <small>";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classeName", array()), "html", null, true);
        echo "</small></div>
\t\t<div class=\"panel-body\">
\t\t\t<div class=\"list-group\">
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Informations</h6>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\tNom : ";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "nom", array()), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\tSurnom : ";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "surnom", array()), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\tAge : ";
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "age", array()), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\tGenre : ";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "genre", array()), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t";
        // line 32
        if ($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "intrigue", array())) {
            // line 33
            echo "\t\t\t\t\t\t\tParticipe aux intrigues
\t\t\t\t\t\t";
        } else {
            // line 35
            echo "\t\t\t\t\t\t\tNe participe pas aux intrigues
\t\t\t\t\t\t";
        }
        // line 37
        echo "\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t<a href=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.update", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Modifier</a>
\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t\t
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Renommée</h6>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t";
        // line 46
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "renomme", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "renomme", array()), 0)) : (0)), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t<a href=\"";
        // line 49
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.update.renomme", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Modifier la renommée</a>
\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t\t
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Religion</h6>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t";
        // line 56
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "personnagesReligions", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["personnageReligion"]) {
            // line 57
            echo "\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t<li>";
            // line 58
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageReligion"], "religion", array()), "label", array()), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["personnageReligion"], "religionLevel", array()), "label", array()), "html", null, true);
            echo " - <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.religion.delete", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()), "personnageReligion" => $this->getAttribute($context["personnageReligion"], "id", array()))), "html", null, true);
            echo "\">Supprimer</a></li>
\t\t\t\t\t\t</ul>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['personnageReligion'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "\t\t\t\t\t<a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.religion.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Ajouter une religion</a>
\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t\t
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Origine</h6>
\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t";
        // line 68
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "territoire", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "territoire", array()), "non définie")) : ("non définie")), "html", null, true);
        echo "
\t\t\t\t\t</p>
\t\t\t\t\t<a href=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.origine.update", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Modifier l'origine</a>
\t\t\t\t</div>
\t\t\t\t
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Compétences <small>(";
        // line 74
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : null), "xp", array()), 0)) : (0)), "html", null, true);
        echo " xp)</small></h6>
\t\t\t\t\t<p><a href=\"";
        // line 75
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.xp", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Ajouter des points d'expérience</a></p>
\t\t\t\t\t";
        // line 76
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "competences", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["competence"]) {
            // line 77
            echo "\t\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t\t";
            // line 78
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "competenceFamily", array()), "label", array()), "html", null, true);
            echo "&nbsp(";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "level", array()), "label", array()), "html", null, true);
            echo ") : ";
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["competence"], "description", array()));
            echo "
\t\t\t\t\t\t</p>\t\t\t\t\t
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competence'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 81
        echo "\t\t\t\t\t<p><a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.competence.remove", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Retirer la dernière compétence acquise</a></p>
\t\t\t\t</div>
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Historique</h6>\t\t
\t\t\t\t\t";
        // line 85
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "experienceGains", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["historique"]) {
            // line 86
            echo "\t\t\t\t\t\t<p class=\"list-group-item-text\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($context["historique"], "operationDate", array()), "Y-m-d h:i:s"), "html", null, true);
            echo " : + ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "xpGain", array()), "html", null, true);
            echo " xp ";
            if ($this->getAttribute($context["historique"], "explanation", array())) {
                echo " pour la raison suivante : \"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "explanation", array()), "html", null, true);
                echo "\"";
            }
            echo ".</p>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['historique'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 88
        echo "\t\t\t\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "experienceUsages", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["historique"]) {
            // line 89
            echo "\t\t\t\t\t\t<p class=\"list-group-item-text\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($context["historique"], "operationDate", array()), "Y-m-d h:i:s"), "html", null, true);
            echo " : - ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["historique"], "xpUse", array()), "html", null, true);
            echo " xp pour acquérir ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["historique"], "competence", array()), "label", array()), "html", null, true);
            echo ".</p>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['historique'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 91
        echo "\t\t\t\t</div>
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6 class=\"list-group-item-heading\">Groupes secondaires</h6>
\t\t\t\t\t";
        // line 94
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "secondaryGroups", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["groupeSecondaire"]) {
            // line 95
            echo "\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupeSecondaire.admin.detail", array("groupe" => $this->getAttribute($context["groupeSecondaire"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["groupeSecondaire"], "label", array()), "html", null, true);
            echo "</a>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupeSecondaire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 97
        echo "\t\t\t\t\t";
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "postulants", array())) > 0)) {
            // line 98
            echo "\t\t\t\t\t\t";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "postulants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["postulant"]) {
                // line 99
                echo "\t\t\t\t\t\t\t<p class=\"list-group-item-text\">Votre candidature au groupe ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["postulant"], "secondaryGroup", array()), "label", array()), "html", null, true);
                echo " est en attente de validation.</p>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postulant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 101
            echo "\t\t\t\t\t";
        }
        // line 102
        echo "\t\t\t\t</div>
\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<h6>Téléchargement</h6>
\t\t\t\t\t<a href=\"";
        // line 105
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.export", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Télécharger le pdf</a>
\t\t\t\t\t
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>
\t
\t<div class=\"panel panel-default\">
\t\t<div class=\"panel-heading\">
\t\t\t<h4 class=\"list-group-item-heading\">Actions</h4>
\t\t</div>
\t\t<div class=\"panel-body\">
\t\t\t<ul class=\"list-group\">
\t\t\t\t<li class=\"list-group-item\">
\t\t\t\t\t<a class=\"btn btn-danger\" href=\"";
        // line 119
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.delete", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Supprimer</a>
\t\t\t\t</li>
\t\t\t</ul>
\t\t</div>
\t</div>
\t\t\t
";
    }

    public function getTemplateName()
    {
        return "admin/personnage/detail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  312 => 119,  295 => 105,  290 => 102,  287 => 101,  278 => 99,  273 => 98,  270 => 97,  259 => 95,  255 => 94,  250 => 91,  237 => 89,  232 => 88,  215 => 86,  211 => 85,  203 => 81,  190 => 78,  187 => 77,  183 => 76,  179 => 75,  175 => 74,  168 => 70,  163 => 68,  152 => 61,  139 => 58,  136 => 57,  132 => 56,  122 => 49,  116 => 46,  106 => 39,  102 => 37,  98 => 35,  94 => 33,  92 => 32,  86 => 29,  80 => 26,  74 => 23,  68 => 20,  57 => 14,  50 => 10,  46 => 9,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Personnages{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Accueil</a></li>*/
/* 		<li><a href="{{ path('personnage.admin.list') }}">Liste des personnages</a></li>*/
/* 		<li class="active">Détail de {{ personnage.publicName }}</li>*/
/* 	</ol>*/
/* 		*/
/* 	<div class="panel panel-success">*/
/* 		<div class="panel-heading">{{ personnage.publicName }} - <small>{{ personnage.classeName }}</small></div>*/
/* 		<div class="panel-body">*/
/* 			<div class="list-group">*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Informations</h6>*/
/* 					<p class="list-group-item-text">*/
/* 						Nom : {{ personnage.nom }}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						Surnom : {{ personnage.surnom }}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						Age : {{ personnage.age }}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						Genre : {{ personnage.genre }}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						{% if personnage.intrigue %}*/
/* 							Participe aux intrigues*/
/* 						{% else %}*/
/* 							Ne participe pas aux intrigues*/
/* 						{% endif %}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						<a href="{{ path('personnage.admin.update', {'personnage':personnage.id}) }}">Modifier</a>*/
/* 					</p>*/
/* 				</div>*/
/* 				*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Renommée</h6>*/
/* 					<p class="list-group-item-text">*/
/* 						{{ personnage.renomme|default(0) }}*/
/* 					</p>*/
/* 					<p class="list-group-item-text">*/
/* 						<a href="{{ path('personnage.admin.update.renomme', {'personnage': personnage.id}) }}">Modifier la renommée</a>*/
/* 					</p>*/
/* 				</div>*/
/* 				*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Religion</h6>*/
/* 					<p class="list-group-item-text">*/
/* 					{% for personnageReligion in personnage.personnagesReligions %}*/
/* 						<ul>*/
/* 							<li>{{ personnageReligion.religion.label }} - {{ personnageReligion.religionLevel.label }} - <a href="{{ path('personnage.admin.religion.delete', {'personnage': personnage.id, 'personnageReligion': personnageReligion.id }) }}">Supprimer</a></li>*/
/* 						</ul>*/
/* 					{% endfor %}*/
/* 					<a href="{{ path('personnage.admin.religion.add', {'personnage': personnage.id}) }}">Ajouter une religion</a>*/
/* 					</p>*/
/* 				</div>*/
/* 				*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Origine</h6>*/
/* 					<p class="list-group-item-text">*/
/* 						{{ personnage.territoire|default("non définie") }}*/
/* 					</p>*/
/* 					<a href="{{ path('personnage.admin.origine.update', {'personnage': personnage.id}) }}">Modifier l'origine</a>*/
/* 				</div>*/
/* 				*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Compétences <small>({{ personnage.xp|default(0) }} xp)</small></h6>*/
/* 					<p><a href="{{ path('personnage.admin.xp', {'personnage':personnage.id}) }}">Ajouter des points d'expérience</a></p>*/
/* 					{% for competence in personnage.competences %}*/
/* 						<p class="list-group-item-text">*/
/* 							{{ competence.competenceFamily.label }}&nbsp({{ competence.level.label }}) : {{ competence.description|markdown }}*/
/* 						</p>					*/
/* 					{% endfor %}*/
/* 					<p><a href="{{ path('personnage.competence.remove', {'personnage':personnage.id}) }}">Retirer la dernière compétence acquise</a></p>*/
/* 				</div>*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Historique</h6>		*/
/* 					{% for historique in personnage.experienceGains %}*/
/* 						<p class="list-group-item-text">{{ historique.operationDate|date("Y-m-d h:i:s") }} : + {{ historique.xpGain }} xp {% if historique.explanation %} pour la raison suivante : "{{ historique.explanation }}"{% endif %}.</p>*/
/* 					{% endfor %}*/
/* 					{% for historique in personnage.experienceUsages %}*/
/* 						<p class="list-group-item-text">{{ historique.operationDate|date("Y-m-d h:i:s") }} : - {{ historique.xpUse }} xp pour acquérir {{ historique.competence.label }}.</p>*/
/* 					{% endfor %}*/
/* 				</div>*/
/* 				<div class="list-group-item">*/
/* 					<h6 class="list-group-item-heading">Groupes secondaires</h6>*/
/* 					{% for groupeSecondaire in personnage.secondaryGroups %}*/
/* 						<a href="{{ path('groupeSecondaire.admin.detail', {'groupe':groupeSecondaire.id}) }}">{{ groupeSecondaire.label }}</a>*/
/* 					{% endfor %}*/
/* 					{% if personnage.postulants|length > 0 %}*/
/* 						{% for postulant in personnage.postulants %}*/
/* 							<p class="list-group-item-text">Votre candidature au groupe {{ postulant.secondaryGroup.label }} est en attente de validation.</p>*/
/* 						{% endfor %}*/
/* 					{% endif %}*/
/* 				</div>*/
/* 				<div class="list-group-item">*/
/* 					<h6>Téléchargement</h6>*/
/* 					<a href="{{ path('personnage.export', {'personnage':personnage.id}) }}">Télécharger le pdf</a>*/
/* 					*/
/* 				</div>*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* 	<div class="panel panel-default">*/
/* 		<div class="panel-heading">*/
/* 			<h4 class="list-group-item-heading">Actions</h4>*/
/* 		</div>*/
/* 		<div class="panel-body">*/
/* 			<ul class="list-group">*/
/* 				<li class="list-group-item">*/
/* 					<a class="btn btn-danger" href="{{ path('personnage.admin.delete', {'personnage':personnage.id}) }}">Supprimer</a>*/
/* 				</li>*/
/* 			</ul>*/
/* 		</div>*/
/* 	</div>*/
/* 			*/
/* {% endblock content %}*/
