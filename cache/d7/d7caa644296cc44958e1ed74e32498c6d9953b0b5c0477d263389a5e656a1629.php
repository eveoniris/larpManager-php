<?php

/* homepage/index.twig */
class __TwigTemplate_987835a9d9d1d9f2dfcbb455437cf3bb6d72c1cb6670bb69857d9be93ad9d953 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "homepage/index.twig", 1);
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
        echo "Accueil";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
\t<div class=\"well bs-component\">
\t
\t\t";
        // line 10
        echo "\t\t";
        if ((( !$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "etatCivil", array()) || (twig_length_filter($this->env, $this->getAttribute($this->getAttribute(        // line 11
(isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) == 0)) || (twig_length_filter($this->env, $this->getAttribute($this->getAttribute(        // line 12
(isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnages", array())) == 0))) {
            // line 13
            echo "\t\t\t<div class=\"well well-sm\">
\t\t\t\t<h6>Liste des choses importantes à faire :</h6>
\t\t\t\t<ol>
\t\t\t\t";
            // line 16
            if ( !$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "etatCivil", array())) {
                // line 17
                echo "\t\t\t\t\t<li>Enregistrer mon état-civil</li>
\t\t\t\t";
            }
            // line 19
            echo "\t\t\t\t\t\t\t\t
\t\t\t\t";
            // line 20
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) == 0)) {
                // line 21
                echo "\t\t\t\t\t<li>S'inscrire dans un groupe</li>
\t\t\t\t";
            }
            // line 23
            echo "\t\t\t\t
\t\t\t\t";
            // line 24
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnages", array())) == 0)) {
                // line 25
                echo "\t\t\t\t\t<li>Créer mon personnage</li>
\t\t\t\t";
            }
            // line 27
            echo "\t\t\t\t</ol>
\t\t\t</div>
\t\t";
        }
        // line 30
        echo "\t\t
\t\t";
        // line 32
        echo "\t\t";
        if ( !$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "etatCivil", array())) {
            // line 33
            echo "\t\t\t<div class=\"panel panel-success\">
\t\t\t\t<div class=\"panel-heading\">";
            // line 34
            echo $this->env->getExtension('translator')->getTranslator()->trans("record_player_title", array(), "messages");
            echo "</div>
\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t<p>";
            // line 36
            echo $this->env->getExtension('translator')->getTranslator()->trans("record_player_explain", array(), "messages");
            echo "</p>
\t\t\t\t\t<a class=\"btn btn-default\" href=\"";
            // line 37
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("user.information.add", array("id" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "id", array()))), "html", null, true);
            echo "\">";
            echo $this->env->getExtension('translator')->getTranslator()->trans("record_player_link", array(), "messages");
            echo "</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t";
        }
        // line 41
        echo "\t\t
\t\t<div class=\"row\">
\t\t\t";
        // line 43
        $this->loadTemplate("link.twig", "homepage/index.twig", 43)->display(array_merge($context, array("title" => "Votre groupe", "link" => $this->env->getExtension('routing')->getPath("groupe"), "color" => "bg-primary", "icon" => "fa-users")));
        // line 49
        echo "\t\t\t\t
\t\t\t";
        // line 50
        $this->loadTemplate("link.twig", "homepage/index.twig", 50)->display(array_merge($context, array("title" => "Vos groupes secondaires", "link" => $this->env->getExtension('routing')->getPath("groupeSecondaire"), "color" => "bg-success", "icon" => "fa-users")));
        // line 56
        echo "\t\t\t\t
\t\t\t";
        // line 57
        $this->loadTemplate("link.twig", "homepage/index.twig", 57)->display(array_merge($context, array("title" => "Votre personnage", "link" => $this->env->getExtension('routing')->getPath("personnage"), "color" => "bg-primary", "icon" => "fa-user")));
        // line 63
        echo "\t\t\t\t
\t\t\t";
        // line 64
        $this->loadTemplate("link.twig", "homepage/index.twig", 64)->display(array_merge($context, array("title" => "Votre personnage secondaire", "link" => $this->env->getExtension('routing')->getPath("personnageSecondaire"), "color" => "bg-success", "icon" => "fa-user-plus")));
        // line 70
        echo "\t\t\t\t
\t\t\t";
        // line 71
        $this->loadTemplate("link.twig", "homepage/index.twig", 71)->display(array_merge($context, array("title" => "Discuter", "link" => $this->env->getExtension('routing')->getPath("discuter"), "color" => "bg-primary", "icon" => "fa-comments")));
        // line 77
        echo "\t\t\t\t
\t\t\t";
        // line 78
        $this->loadTemplate("link.twig", "homepage/index.twig", 78)->display(array_merge($context, array("title" => "Evenements", "link" => $this->env->getExtension('routing')->getPath("evenement"), "color" => "bg-success", "icon" => "fa-calendar")));
        // line 84
        echo "\t\t\t\t
\t\t\t";
        // line 85
        $this->loadTemplate("link.twig", "homepage/index.twig", 85)->display(array_merge($context, array("title" => "Ma trombine", "link" => $this->env->getExtension('routing')->getPath("trombine"), "color" => "bg-success", "icon" => "fa-camera")));
        // line 90
        echo "\t
\t\t</div>
\t\t
\t\t
\t\t
\t\t";
        // line 96
        echo "\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["annonces"]) ? $context["annonces"] : $this->getContext($context, "annonces")));
        foreach ($context['_seq'] as $context["_key"] => $context["annonce"]) {
            // line 97
            echo "\t\t\t<div class=\"header\">
\t\t\t\t<h5>";
            // line 98
            echo twig_escape_filter($this->env, $this->getAttribute($context["annonce"], "title", array()), "html", null, true);
            echo "</h5>
\t\t\t</div>
\t\t\t";
            // line 100
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["annonce"], "text", array()));
            echo "
\t\t\t<br/>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['annonce'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 102
        echo "\t\t\t\t\t\t
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "homepage/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  174 => 102,  165 => 100,  160 => 98,  157 => 97,  152 => 96,  145 => 90,  143 => 85,  140 => 84,  138 => 78,  135 => 77,  133 => 71,  130 => 70,  128 => 64,  125 => 63,  123 => 57,  120 => 56,  118 => 50,  115 => 49,  113 => 43,  109 => 41,  100 => 37,  96 => 36,  91 => 34,  88 => 33,  85 => 32,  82 => 30,  77 => 27,  73 => 25,  71 => 24,  68 => 23,  64 => 21,  62 => 20,  59 => 19,  55 => 17,  53 => 16,  48 => 13,  46 => 12,  45 => 11,  43 => 10,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Accueil{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<div class="well bs-component">*/
/* 	*/
/* 		{# Liste des choses à faire #}*/
/* 		{% if not app.user.etatCivil  */
/* 			or app.user.groupes|length == 0 */
/* 			or app.user.personnages|length == 0 %}*/
/* 			<div class="well well-sm">*/
/* 				<h6>Liste des choses importantes à faire :</h6>*/
/* 				<ol>*/
/* 				{% if not app.user.etatCivil %}*/
/* 					<li>Enregistrer mon état-civil</li>*/
/* 				{% endif %}*/
/* 								*/
/* 				{% if app.user.groupes|length == 0 %}*/
/* 					<li>S'inscrire dans un groupe</li>*/
/* 				{% endif %}*/
/* 				*/
/* 				{% if app.user.personnages|length == 0 %}*/
/* 					<li>Créer mon personnage</li>*/
/* 				{% endif %}*/
/* 				</ol>*/
/* 			</div>*/
/* 		{% endif %}*/
/* 		*/
/* 		{# enregistrement de l'état-civil #}*/
/* 		{% if not app.user.etatCivil %}*/
/* 			<div class="panel panel-success">*/
/* 				<div class="panel-heading">{% trans %}record_player_title{% endtrans %}</div>*/
/* 				<div class="panel-body">*/
/* 					<p>{% trans %}record_player_explain{% endtrans %}</p>*/
/* 					<a class="btn btn-default" href="{{ path('user.information.add', {'id': app.user.id}) }}">{% trans %}record_player_link{% endtrans %}</a>*/
/* 				</div>*/
/* 			</div>*/
/* 		{% endif %}*/
/* 		*/
/* 		<div class="row">*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Votre groupe",*/
/* 				'link' :  path('groupe'),*/
/* 				'color' : "bg-primary",*/
/* 				'icon': 'fa-users' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Vos groupes secondaires",*/
/* 				'link' :  path('groupeSecondaire'),*/
/* 				'color' : "bg-success",*/
/* 				'icon': 'fa-users' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Votre personnage",*/
/* 				'link' :  path('personnage'),*/
/* 				'color' : "bg-primary",*/
/* 				'icon': 'fa-user' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Votre personnage secondaire",*/
/* 				'link' :  path('personnageSecondaire'),*/
/* 				'color' : "bg-success",*/
/* 				'icon': 'fa-user-plus' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Discuter",*/
/* 				'link' :  path('discuter'),*/
/* 				'color' : "bg-primary",*/
/* 				'icon': 'fa-comments' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Evenements",*/
/* 				'link' :  path('evenement'),*/
/* 				'color' : "bg-success",*/
/* 				'icon': 'fa-calendar' */
/* 				} %}*/
/* 				*/
/* 			{% include "link.twig" with {*/
/* 				'title' : "Ma trombine",*/
/* 				'link' :  path('trombine'),*/
/* 				'color' : "bg-success",*/
/* 				'icon': 'fa-camera' */
/* 				} %}	*/
/* 		</div>*/
/* 		*/
/* 		*/
/* 		*/
/* 		{# affichage de l'annonce la plus récente et du lien vers les archives des annonces #}*/
/* 		{% for annonce in annonces %}*/
/* 			<div class="header">*/
/* 				<h5>{{ annonce.title }}</h5>*/
/* 			</div>*/
/* 			{{ annonce.text|markdown }}*/
/* 			<br/>*/
/* 		{% endfor %}						*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
/* */
