<?php

/* admin/territoire/detail.twig */
class __TwigTemplate_ef0e4664ac28ad5f19e161f7ddd1046a3f89ad16aa4ff6a7e7d3282d465ce5bf extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/territoire/detail.twig", 1);
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
        echo "Territoire";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-8\">
\t\t
\t\t\t<div class=\"well well-sm bs-component\">
\t\t\t<a href=\"";
        // line 12
        echo $this->env->getExtension('routing')->getPath("territoire.admin.list");
        echo "\">Retour à la liste des territoires</a>
\t\t\t\t<h4>
\t\t\t\t\t";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "nom", array()), "html", null, true);
        echo "
\t\t\t\t</h4>
\t\t\t</div>
\t\t
  \t\t\t<div class=\"list-group\">
\t\t    \t\t
\t\t    \t<div class=\"list-group-item\">
\t\t\t    \t<h4 class=\"list-group-item-heading\">Description</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t";
        // line 23
        if ( !$this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "description", array())) {
            // line 24
            echo "    \t\t\t\t<span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span>
    \t\t\t\tAttention, ce territoire n'a pas de description.
    \t\t\t\t";
        } else {
            // line 27
            echo "    \t\t\t\t\t";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "description", array()), "html", null, true);
            echo "
    \t\t\t\t";
        }
        // line 29
        echo "    \t\t\t\t</p>
\t    \t\t</div>
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Informations politiques</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t<ul>
\t    \t\t\t\t\t<li><strong>Capitale : </strong>";
        // line 36
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "capitale", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "capitale", array()), "Aucune")) : ("Aucune")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Système politique : </strong>";
        // line 37
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "politique", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "politique", array()), "Inconnu")) : ("Inconnu")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Dirigeant : </strong>";
        // line 38
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "dirigeant", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "dirigeant", array()), "Aucun")) : ("Aucun")), "html", null, true);
        echo "</li>
\t    \t\t\t\t</ul>
\t    \t\t\t</p>
\t    \t\t</div>
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Information d'interprétation</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t<ul>
\t    \t\t\t\t\t<li><strong>Type racial : </strong>";
        // line 47
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "typeRacial", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "typeRacial", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Inspiration : </strong>";
        // line 48
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "inspiration", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "inspiration", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Armes de prédilection : </strong>";
        // line 49
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "armesPredilection", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "armesPredilection", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Vétements : </strong>";
        // line 50
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "vetements", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "vetements", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Nom masculin : </strong>";
        // line 51
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "nomsMasculin", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "nomsMasculin", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li> 
\t    \t\t\t\t\t<li><strong>Nom féminin : </strong>";
        // line 52
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "nomsFeminin", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "nomsFeminin", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Fontières : </strong>";
        // line 53
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "frontieres", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "frontieres", array()), "Non défini")) : ("Non défini")), "html", null, true);
        echo "</li>
\t    \t\t\t\t</ul>
\t    \t\t\t</p>
\t    \t\t</div>
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Autres informations</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t<ul>
\t    \t\t\t\t\t<li><strong>Population : </strong>";
        // line 62
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "population", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "population", array()), "Inconnue")) : ("Inconnue")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Symbole : </strong>";
        // line 63
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "symbole", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "symbole", array()), "Aucun")) : ("Aucun")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Niveau technologique : </strong>";
        // line 64
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "techLevel", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : null), "techLevel", array()), "Aucun")) : ("Aucun")), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Langues parlée : </strong>";
        // line 65
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "langues", array()), ", "), "html", null, true);
        echo "</li>
\t    \t\t\t\t\t<li><strong>Langue principale : </strong>";
        // line 66
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "langue", array()), "html", null, true);
        echo "</li> 
\t    \t\t\t\t</ul>
\t    \t\t\t</p>
\t    \t\t</div>
\t    \t\t\t    \t\t
\t     \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Cultes</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t<ul>
    \t\t\t\t\t\t<li><strong>Religion dominante : </strong>";
        // line 75
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "religion", array()), "html", null, true);
        echo "</li>
    \t\t\t\t\t\t<li><strong>Religion secondaires : </strong>";
        // line 76
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "religions", array()), ", "), "html", null, true);
        echo "</li>
    \t\t\t\t\t</ul>
    \t\t\t\t</p>
    \t\t\t</div>
\t    \t\t
\t    \t\t<div class=\"list-group-item\">
\t    \t\t\t<h4 class=\"list-group-item-heading\">Economie</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t<ul>
    \t\t\t\t\t\t<li><strong>Importations : </strong>";
        // line 85
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "importations", array())) > 0)) {
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "importations", array()), ", "), "html", null, true);
        } else {
            echo "Rien";
        }
        echo "</li>
    \t\t\t\t\t\t<li><strong>Exportations : </strong>";
        // line 86
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "exportations", array())) > 0)) {
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "exportations", array()), ", "), "html", null, true);
        } else {
            echo "Rien";
        }
        echo "</li>
    \t\t\t\t\t</ul>
    \t\t\t\t</p>
    \t\t\t</div>
\t    \t\t
\t    \t\t";
        // line 91
        if ($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "territoire", array())) {
            echo "\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Ce territoire dépend de</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
    \t\t\t\t\t<a href=\"";
            // line 95
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.detail", array("territoire" => $this->getAttribute($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "territoire", array()), "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "territoire", array()), "nom", array()), "html", null, true);
            echo "</a>
    \t\t\t\t</p>
    \t\t\t</div>
    \t\t\t";
        }
        // line 99
        echo "    \t\t\t
\t    \t\t<div class=\"list-group-item\">
\t\t    \t\t<h4 class=\"list-group-item-heading\">Liste des territoires rattachés à ce territoire (";
        // line 101
        echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "territoires", array())), "html", null, true);
        echo " territoires)</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t";
        // line 104
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["territoire"], "territoires", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["territoire"]) {
            // line 105
            echo "\t\t\t\t\t\t\t<li><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.detail", array("territoire" => $this->getAttribute($context["territoire"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["territoire"], "nomTree", array()), "html", null, true);
            echo "</a></li>
\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['territoire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 107
        echo "\t\t\t\t\t\t</ul>
\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t\t
\t\t\t\t<div class=\"list-group-item\">
  \t\t\t\t\t<div class=\"btn-group\" role=\"group\" aria-label=\"...\">
  \t\t\t\t\t\t<a  class=\"btn btn-primary\" role=\"button\" href=\"";
        // line 113
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.update", array("territoire" => $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "id", array()))), "html", null, true);
        echo "\">Modifier</a>
  \t\t\t\t\t\t<a  class=\"btn btn-danger\" role=\"button\" href=\"";
        // line 114
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.delete", array("territoire" => $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "id", array()))), "html", null, true);
        echo "\">Supprimer</a>
  \t\t\t\t\t\t
  \t\t\t\t\t\t";
        // line 116
        if ($this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "topic", array())) {
            // line 117
            echo "  \t\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.topic.delete", array("territoire" => $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "id", array()))), "html", null, true);
            echo "\">Supprimer le topic</a>
  \t\t\t\t\t\t";
        } else {
            // line 119
            echo "\t\t\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.topic.add", array("territoire" => $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "id", array()))), "html", null, true);
            echo "\">Ajouter un topic</a>  \t\t\t\t\t\t\t\t\t\t\t
  \t\t\t\t\t\t";
        }
        // line 121
        echo "  \t\t\t\t\t\t
\t\t\t\t\t</div>
  \t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>
</div>

";
    }

    public function getTemplateName()
    {
        return "admin/territoire/detail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  277 => 121,  271 => 119,  265 => 117,  263 => 116,  258 => 114,  254 => 113,  246 => 107,  235 => 105,  231 => 104,  225 => 101,  221 => 99,  212 => 95,  205 => 91,  193 => 86,  185 => 85,  173 => 76,  169 => 75,  157 => 66,  153 => 65,  149 => 64,  145 => 63,  141 => 62,  129 => 53,  125 => 52,  121 => 51,  117 => 50,  113 => 49,  109 => 48,  105 => 47,  93 => 38,  89 => 37,  85 => 36,  76 => 29,  70 => 27,  65 => 24,  63 => 23,  51 => 14,  46 => 12,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Territoire{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-8">*/
/* 		*/
/* 			<div class="well well-sm bs-component">*/
/* 			<a href="{{ path('territoire.admin.list') }}">Retour à la liste des territoires</a>*/
/* 				<h4>*/
/* 					{{ territoire.nom }}*/
/* 				</h4>*/
/* 			</div>*/
/* 		*/
/*   			<div class="list-group">*/
/* 		    		*/
/* 		    	<div class="list-group-item">*/
/* 			    	<h4 class="list-group-item-heading">Description</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    			{% if not territoire.description %}*/
/*     				<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>*/
/*     				Attention, ce territoire n'a pas de description.*/
/*     				{% else %}*/
/*     					{{ territoire.description }}*/
/*     				{% endif %}*/
/*     				</p>*/
/* 	    		</div>*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Informations politiques</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    				<ul>*/
/* 	    					<li><strong>Capitale : </strong>{{ territoire.capitale|default('Aucune') }}</li>*/
/* 	    					<li><strong>Système politique : </strong>{{ territoire.politique|default('Inconnu') }}</li>*/
/* 	    					<li><strong>Dirigeant : </strong>{{ territoire.dirigeant|default('Aucun') }}</li>*/
/* 	    				</ul>*/
/* 	    			</p>*/
/* 	    		</div>*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Information d'interprétation</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    				<ul>*/
/* 	    					<li><strong>Type racial : </strong>{{ territoire.typeRacial|default('Non défini') }}</li>*/
/* 	    					<li><strong>Inspiration : </strong>{{ territoire.inspiration|default('Non défini') }}</li>*/
/* 	    					<li><strong>Armes de prédilection : </strong>{{ territoire.armesPredilection|default('Non défini') }}</li>*/
/* 	    					<li><strong>Vétements : </strong>{{ territoire.vetements|default('Non défini') }}</li>*/
/* 	    					<li><strong>Nom masculin : </strong>{{ territoire.nomsMasculin|default('Non défini') }}</li> */
/* 	    					<li><strong>Nom féminin : </strong>{{ territoire.nomsFeminin|default('Non défini') }}</li>*/
/* 	    					<li><strong>Fontières : </strong>{{ territoire.frontieres|default('Non défini') }}</li>*/
/* 	    				</ul>*/
/* 	    			</p>*/
/* 	    		</div>*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Autres informations</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    				<ul>*/
/* 	    					<li><strong>Population : </strong>{{ territoire.population|default('Inconnue') }}</li>*/
/* 	    					<li><strong>Symbole : </strong>{{ territoire.symbole|default('Aucun') }}</li>*/
/* 	    					<li><strong>Niveau technologique : </strong>{{ territoire.techLevel|default('Aucun') }}</li>*/
/* 	    					<li><strong>Langues parlée : </strong>{{ territoire.langues|join(', ') }}</li>*/
/* 	    					<li><strong>Langue principale : </strong>{{ territoire.langue }}</li> */
/* 	    				</ul>*/
/* 	    			</p>*/
/* 	    		</div>*/
/* 	    			    		*/
/* 	     		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Cultes</h4>*/
/*     				<p class="list-group-item-text">*/
/*     					<ul>*/
/*     						<li><strong>Religion dominante : </strong>{{ territoire.religion }}</li>*/
/*     						<li><strong>Religion secondaires : </strong>{{territoire.religions|join(', ') }}</li>*/
/*     					</ul>*/
/*     				</p>*/
/*     			</div>*/
/* 	    		*/
/* 	    		<div class="list-group-item">*/
/* 	    			<h4 class="list-group-item-heading">Economie</h4>*/
/*     				<p class="list-group-item-text">*/
/*     					<ul>*/
/*     						<li><strong>Importations : </strong>{% if territoire.importations|length > 0 %}{{ territoire.importations|join(', ') }}{% else %}Rien{% endif %}</li>*/
/*     						<li><strong>Exportations : </strong>{% if territoire.exportations|length > 0 %}{{ territoire.exportations|join(', ') }}{% else %}Rien{% endif %}</li>*/
/*     					</ul>*/
/*     				</p>*/
/*     			</div>*/
/* 	    		*/
/* 	    		{% if territoire.territoire %}	*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Ce territoire dépend de</h4>*/
/*     				<p class="list-group-item-text">*/
/*     					<a href="{{ path('territoire.admin.detail',{'territoire': territoire.territoire.id}) }}">{{ territoire.territoire.nom }}</a>*/
/*     				</p>*/
/*     			</div>*/
/*     			{% endif %}*/
/*     			*/
/* 	    		<div class="list-group-item">*/
/* 		    		<h4 class="list-group-item-heading">Liste des territoires rattachés à ce territoire ({{ territoire.territoires|length }} territoires)</h4>*/
/*     				<p class="list-group-item-text">*/
/* 						<ul>*/
/* 						{% for territoire in territoire.territoires %}*/
/* 							<li><a href="{{ path('territoire.admin.detail',{'territoire': territoire.id}) }}">{{ territoire.nomTree }}</a></li>*/
/* 						{% endfor %}*/
/* 						</ul>*/
/* 					</p>*/
/* 				</div>*/
/* 				*/
/* 				<div class="list-group-item">*/
/*   					<div class="btn-group" role="group" aria-label="...">*/
/*   						<a  class="btn btn-primary" role="button" href="{{ path('territoire.admin.update', {'territoire' : territoire.id}) }}">Modifier</a>*/
/*   						<a  class="btn btn-danger" role="button" href="{{ path('territoire.admin.delete', {'territoire' : territoire.id}) }}">Supprimer</a>*/
/*   						*/
/*   						{% if territoire.topic %}*/
/*   							<a class="btn btn-default" href="{{ path('territoire.admin.topic.delete', {'territoire':territoire.id}) }}">Supprimer le topic</a>*/
/*   						{% else %}*/
/* 							<a class="btn btn-default" href="{{ path('territoire.admin.topic.add', {'territoire':territoire.id}) }}">Ajouter un topic</a>  											*/
/*   						{% endif %}*/
/*   						*/
/* 					</div>*/
/*   				</div>*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* */
/* {% endblock %}*/
