<?php

/* admin/groupe/list.twig */
class __TwigTemplate_3227a40f3a1689ad39f675c393eb04194a76afaa4d8b32282b96aaabb8f9dc5b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/groupe/list.twig", 1);
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
        echo "Groupes";
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
\t\t<li class=\"active\">Liste des groupes</li>
\t</ol>

\t<div class=\"well well-sm\">
\t
\t\t<ul class=\"list-group\">
\t\t\t<li class=\"list-group-item\">
\t\t\t\t";
        // line 16
        if (($this->getAttribute((isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator")), "totalItems", array()) == 1)) {
            // line 17
            echo "\t\t        \t<strong>1</strong> groupe trouvé.
\t\t    \t";
        } else {
            // line 19
            echo "\t\t        \t<strong>";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator")), "totalItems", array()), "html", null, true);
            echo "</strong> groupes trouvés.
\t\t    \t";
        }
        // line 21
        echo "\t\t
\t\t    \tMontre <strong>";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator")), "currentPageFirstItem", array()), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator")), "currentPageLastItem", array()), "html", null, true);
        echo "</strong>.
\t\t   \t</li>
\t\t   \t<li class=\"list-group-item\">
\t\t   \t\t<a href=\"";
        // line 25
        echo $this->env->getExtension('routing')->getPath("groupe.add");
        echo "\">Ajouter un groupe</a>
\t\t   \t</li>
\t\t   \t<li class=\"list-group-item\">
\t\t   \t\t<div class=\"list-group-item\"><h6>Rechercher</h6></div>
\t\t   \t\t
\t\t   \t\t<form class=\"form\" action=\"";
        // line 30
        echo $this->env->getExtension('routing')->getPath("groupe.admin.list");
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t\t\t";
        // line 31
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_horizontal_layout.html.twig"));
        // line 32
        echo "\t\t\t\t\t";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form');
        echo "
\t\t   \t\t</form>
\t\t   \t\t<a href=\"";
        // line 34
        echo $this->env->getExtension('routing')->getPath("groupe.admin.list");
        echo "\">Reset</a>
\t\t   \t</li>
\t\t</ul>
\t\t 
\t\t";
        // line 38
        echo (isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator"));
        echo "
\t\t 
\t\t<table class=\"table table-striped table-bordered table-condensed table-hover\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>
\t\t\t\t\t\t";
        // line 44
        if ((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC") && ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "pj"))) {
            // line 45
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "pj", "order_dir" => "DESC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        } else {
            // line 47
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "pj", "order_dir" => "ASC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        }
        // line 49
        echo "\t\t\t\t\t\t\tNumero
\t\t\t\t\t\t";
        // line 50
        if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "pj")) {
            // line 51
            echo "\t\t\t\t\t\t\t";
            if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC")) {
                // line 52
                echo "\t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t";
            } else {
                // line 54
                echo "\t\t\t\t\t\t\t\t<span class=\"dropup\">
    \t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t";
            }
            // line 58
            echo "\t\t\t\t\t\t";
        }
        // line 59
        echo "\t\t\t\t\t\t</a>
\t\t\t\t\t</th>
\t\t\t\t\t<th>
\t\t\t\t\t\t";
        // line 62
        if ((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC") && ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "numero"))) {
            // line 63
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "numero", "order_dir" => "DESC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        } else {
            // line 65
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "numero", "order_dir" => "ASC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        }
        // line 67
        echo "\t\t\t\t\t\t\tNumero
\t\t\t\t\t\t";
        // line 68
        if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "numero")) {
            // line 69
            echo "\t\t\t\t\t\t\t";
            if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC")) {
                // line 70
                echo "\t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t";
            } else {
                // line 72
                echo "\t\t\t\t\t\t\t\t<span class=\"dropup\">
    \t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t";
            }
            // line 76
            echo "\t\t\t\t\t\t";
        }
        // line 77
        echo "\t\t\t\t\t\t</a>
\t\t\t\t\t</th>

\t\t\t\t\t<th>
\t\t\t\t\t\t";
        // line 81
        if ((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC") && ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "nom"))) {
            // line 82
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "nom", "order_dir" => "DESC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        } else {
            // line 84
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "nom", "order_dir" => "ASC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        }
        // line 86
        echo "\t\t\t\t\t\t\tNom
\t\t\t\t\t\t";
        // line 87
        if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "nom")) {
            // line 88
            echo "\t\t\t\t\t\t\t";
            if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC")) {
                // line 89
                echo "\t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t";
            } else {
                // line 91
                echo "\t\t\t\t\t\t\t\t<span class=\"dropup\">
    \t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t";
            }
            // line 95
            echo "\t\t\t\t\t\t";
        }
        // line 96
        echo "\t\t\t\t\t\t</a>
\t\t\t\t\t</th>

\t\t\t\t\t<th>
\t\t\t\t\t\t";
        // line 100
        if ((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC") && ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "classe_open"))) {
            // line 101
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "classe_open", "order_dir" => "DESC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        } else {
            // line 103
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.admin.list", array("order_by" => "classe_open", "order_dir" => "ASC")), "html", null, true);
            echo "\">
\t\t\t\t\t\t";
        }
        // line 105
        echo "\t\t\t\t\t\t\tPlaces ouvertes
\t\t\t\t\t\t";
        // line 106
        if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_by"), "method") == "classe_open")) {
            // line 107
            echo "\t\t\t\t\t\t\t";
            if (($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "get", array(0 => "order_dir"), "method") == "ASC")) {
                // line 108
                echo "\t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t";
            } else {
                // line 110
                echo "\t\t\t\t\t\t\t\t<span class=\"dropup\">
    \t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t";
            }
            // line 114
            echo "\t\t\t\t\t\t";
        }
        // line 115
        echo "\t\t\t\t\t\t
\t\t\t\t\t</th>
\t\t\t\t\t<th>Scénariste</th>
\t\t\t\t\t<th>Chef de groupe</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t";
        // line 122
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["groupes"]) ? $context["groupes"] : $this->getContext($context, "groupes")));
        foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
            // line 123
            echo "\t\t\t\t<tr>
\t\t\t\t\t<td>";
            // line 124
            if ($this->getAttribute($context["groupe"], "pj", array())) {
                echo "PJ";
            } else {
                echo "PNJ";
            }
            echo "</td>
\t\t\t\t\t<td><a href=\"";
            // line 125
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.update", array("index" => $this->getAttribute($context["groupe"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["groupe"], "numero", array()), "html", null, true);
            echo "</a></td>
\t\t\t\t\t<td><a href=\"";
            // line 126
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.detail", array("index" => $this->getAttribute($context["groupe"], "id", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["groupe"], "nom", array()), "html", null, true);
            echo "</a></td>
\t\t\t\t\t<td>";
            // line 127
            echo twig_escape_filter($this->env, $this->getAttribute($context["groupe"], "classeOpen", array()), "html", null, true);
            if ($this->env->getExtension('security')->isGranted("ROLE_ADMIN")) {
                echo "&nbsp;(<a href=\" ";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.place", array("index" => $this->getAttribute($context["groupe"], "id", array()))), "html", null, true);
                echo "\">Modifier</a>)";
            }
            echo " </td>
\t\t\t\t\t<td>";
            // line 128
            if ($this->getAttribute($context["groupe"], "scenariste", array())) {
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "scenariste", array()), "username", array()), "html", null, true);
                echo " ";
            } else {
                echo "pas de scénariste";
            }
            echo "</td>
\t\t\t\t\t<td>";
            // line 129
            if ($this->getAttribute($context["groupe"], "responsable", array())) {
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["groupe"], "responsable", array()), "username", array()), "html", null, true);
            } else {
                echo "pas de scénariste";
            }
            echo "</td>
\t\t\t\t</tr>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 132
        echo "\t\t\t</tbody>
\t\t</table>
\t\t
\t\t";
        // line 135
        echo (isset($context["paginator"]) ? $context["paginator"] : $this->getContext($context, "paginator"));
        echo "
\t</div>

";
    }

    public function getTemplateName()
    {
        return "admin/groupe/list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  343 => 135,  338 => 132,  325 => 129,  316 => 128,  307 => 127,  301 => 126,  295 => 125,  287 => 124,  284 => 123,  280 => 122,  271 => 115,  268 => 114,  262 => 110,  258 => 108,  255 => 107,  253 => 106,  250 => 105,  244 => 103,  238 => 101,  236 => 100,  230 => 96,  227 => 95,  221 => 91,  217 => 89,  214 => 88,  212 => 87,  209 => 86,  203 => 84,  197 => 82,  195 => 81,  189 => 77,  186 => 76,  180 => 72,  176 => 70,  173 => 69,  171 => 68,  168 => 67,  162 => 65,  156 => 63,  154 => 62,  149 => 59,  146 => 58,  140 => 54,  136 => 52,  133 => 51,  131 => 50,  128 => 49,  122 => 47,  116 => 45,  114 => 44,  105 => 38,  98 => 34,  92 => 32,  90 => 31,  84 => 30,  76 => 25,  68 => 22,  65 => 21,  59 => 19,  55 => 17,  53 => 16,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Groupes{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Accueil</a></li>*/
/* 		<li class="active">Liste des groupes</li>*/
/* 	</ol>*/
/* */
/* 	<div class="well well-sm">*/
/* 	*/
/* 		<ul class="list-group">*/
/* 			<li class="list-group-item">*/
/* 				{% if paginator.totalItems == 1 %}*/
/* 		        	<strong>1</strong> groupe trouvé.*/
/* 		    	{% else %}*/
/* 		        	<strong>{{ paginator.totalItems }}</strong> groupes trouvés.*/
/* 		    	{% endif %}*/
/* 		*/
/* 		    	Montre <strong>{{ paginator.currentPageFirstItem }} - {{ paginator.currentPageLastItem }}</strong>.*/
/* 		   	</li>*/
/* 		   	<li class="list-group-item">*/
/* 		   		<a href="{{ path('groupe.add') }}">Ajouter un groupe</a>*/
/* 		   	</li>*/
/* 		   	<li class="list-group-item">*/
/* 		   		<div class="list-group-item"><h6>Rechercher</h6></div>*/
/* 		   		*/
/* 		   		<form class="form" action="{{ path('groupe.admin.list') }}" method="POST" {{ form_enctype(form) }}>*/
/* 					{% form_theme form 'Form/bootstrap_3_horizontal_layout.html.twig' %}*/
/* 					{{ form(form) }}*/
/* 		   		</form>*/
/* 		   		<a href="{{ path('groupe.admin.list') }}">Reset</a>*/
/* 		   	</li>*/
/* 		</ul>*/
/* 		 */
/* 		{{ paginator|raw }}*/
/* 		 */
/* 		<table class="table table-striped table-bordered table-condensed table-hover">*/
/* 			<thead>*/
/* 				<tr>*/
/* 					<th>*/
/* 						{% if app.request.get('order_dir') == 'ASC' and app.request.get('order_by') == 'pj' %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'pj', 'order_dir': 'DESC'}) }}">*/
/* 						{%  else %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'pj', 'order_dir': 'ASC'}) }}">*/
/* 						{% endif %}*/
/* 							Numero*/
/* 						{% if app.request.get('order_by') == 'pj'  %}*/
/* 							{% if app.request.get('order_dir') == 'ASC' %}*/
/* 								<span class="caret"></span>*/
/* 							{%else%}*/
/* 								<span class="dropup">*/
/*     								<span class="caret"></span>*/
/* 								</span>*/
/* 							{% endif %}*/
/* 						{% endif %}*/
/* 						</a>*/
/* 					</th>*/
/* 					<th>*/
/* 						{% if app.request.get('order_dir') == 'ASC' and app.request.get('order_by') == 'numero' %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'numero', 'order_dir': 'DESC'}) }}">*/
/* 						{%  else %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'numero', 'order_dir': 'ASC'}) }}">*/
/* 						{% endif %}*/
/* 							Numero*/
/* 						{% if app.request.get('order_by') == 'numero'  %}*/
/* 							{% if app.request.get('order_dir') == 'ASC' %}*/
/* 								<span class="caret"></span>*/
/* 							{%else%}*/
/* 								<span class="dropup">*/
/*     								<span class="caret"></span>*/
/* 								</span>*/
/* 							{% endif %}*/
/* 						{% endif %}*/
/* 						</a>*/
/* 					</th>*/
/* */
/* 					<th>*/
/* 						{% if app.request.get('order_dir') == 'ASC' and app.request.get('order_by') == 'nom' %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'nom', 'order_dir': 'DESC'}) }}">*/
/* 						{%  else %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'nom', 'order_dir': 'ASC'}) }}">*/
/* 						{% endif %}*/
/* 							Nom*/
/* 						{% if app.request.get('order_by') == 'nom'  %}*/
/* 							{% if app.request.get('order_dir') == 'ASC' %}*/
/* 								<span class="caret"></span>*/
/* 							{%else%}*/
/* 								<span class="dropup">*/
/*     								<span class="caret"></span>*/
/* 								</span>*/
/* 							{% endif %}*/
/* 						{% endif %}*/
/* 						</a>*/
/* 					</th>*/
/* */
/* 					<th>*/
/* 						{% if app.request.get('order_dir') == 'ASC' and app.request.get('order_by') == 'classe_open' %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'classe_open', 'order_dir': 'DESC'}) }}">*/
/* 						{%  else %}*/
/* 							<a href="{{ path('groupe.admin.list', {'order_by': 'classe_open', 'order_dir': 'ASC'}) }}">*/
/* 						{% endif %}*/
/* 							Places ouvertes*/
/* 						{% if app.request.get('order_by') == 'classe_open'  %}*/
/* 							{% if app.request.get('order_dir') == 'ASC' %}*/
/* 								<span class="caret"></span>*/
/* 							{%else%}*/
/* 								<span class="dropup">*/
/*     								<span class="caret"></span>*/
/* 								</span>*/
/* 							{% endif %}*/
/* 						{% endif %}*/
/* 						*/
/* 					</th>*/
/* 					<th>Scénariste</th>*/
/* 					<th>Chef de groupe</th>*/
/* 				</tr>*/
/* 			</thead>*/
/* 			<tbody>*/
/* 			{% for groupe in groupes %}*/
/* 				<tr>*/
/* 					<td>{% if groupe.pj %}PJ{%else%}PNJ{% endif %}</td>*/
/* 					<td><a href="{{ path('groupe.update', {'index':groupe.id}) }}">{{ groupe.numero }}</a></td>*/
/* 					<td><a href="{{ path('groupe.detail', {'index':groupe.id}) }}">{{ groupe.nom }}</a></td>*/
/* 					<td>{{ groupe.classeOpen }}{% if is_granted('ROLE_ADMIN') %}&nbsp;(<a href=" {{ path('groupe.place', {'index':groupe.id}) }}">Modifier</a>){% endif %} </td>*/
/* 					<td>{% if groupe.scenariste %}{{ groupe.scenariste.username }} {% else %}pas de scénariste{% endif %}</td>*/
/* 					<td>{% if groupe.responsable %}{{ groupe.responsable.username }}{% else %}pas de scénariste{% endif %}</td>*/
/* 				</tr>*/
/* 			{% endfor %}*/
/* 			</tbody>*/
/* 		</table>*/
/* 		*/
/* 		{{ paginator|raw }}*/
/* 	</div>*/
/* */
/* {% endblock content %}*/
/* */
/* */
/* */
/* */
/* */
/* */
