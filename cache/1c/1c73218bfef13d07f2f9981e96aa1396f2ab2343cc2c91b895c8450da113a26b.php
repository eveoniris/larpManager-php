<?php

/* public/groupeSecondaire/accueil.twig */
class __TwigTemplate_46065353d59965acbc01c9006c93913da385bcb8b50cdd0d60dcb5d88e53664f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/groupeSecondaire/accueil.twig", 1);
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
        echo "Groupe secondaire";
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
        echo "\">Page d'accueil</a></li>
\t\t<li class=\"active\">Vos groupes secondaires</li>
\t</ol>
\t
\t<div class=\"well bs-component\">\t
\t\t";
        // line 13
        if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array())) {
            // line 14
            echo "\t\t\t\t\t\t\t\t
\t\t\t<div class=\"header\">
\t\t\t\t<h5>Groupes secondaires</h5>
\t\t\t</div>
\t\t\t
\t\t\t";
            // line 19
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "postulants", array())) > 0)) {
                // line 20
                echo "\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t";
                // line 21
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "postulants", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["postulant"]) {
                    // line 22
                    echo "\t\t\t\t\t\t<li class=\"list-group-item\">Votre candidature au groupe <strong>";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["postulant"], "secondaryGroup", array()), "label", array()), "html", null, true);
                    echo "</strong> est en attente de validation.</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postulant'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 24
                echo "\t\t\t\t</ul>
\t\t\t";
            }
            // line 26
            echo "\t\t\t
\t\t\t";
            // line 27
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "membres", array())) > 0)) {
                // line 28
                echo "\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t";
                // line 29
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array()), "membres", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["membre"]) {
                    // line 30
                    echo "\t\t\t\t\t\t<a \thref=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupeSecondaire.joueur", array("groupe" => $this->getAttribute($this->getAttribute($context["membre"], "secondaryGroup", array()), "id", array()))), "html", null, true);
                    echo "\" 
\t\t\t\t\t\t\tclass=\"list-group-item\">";
                    // line 31
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["membre"], "secondaryGroup", array()), "label", array()), "html", null, true);
                    echo "</a>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['membre'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 33
                echo "\t\t\t\t</ul>
\t\t\t";
            }
            // line 35
            echo "\t\t\t<p>Si vous faites partie d'un groupe secondaire (secte, confrérie, guilde, etc ...), ou avez l'intention de rejoindre l'un de ces groupes, n'oubliez pas de vous y inscrire ! <a href=\"";
            echo $this->env->getExtension('routing')->getPath("groupeSecondaire.list");
            echo "\">Liste des groupes secondaires</a></p> 
\t\t";
        }
        // line 37
        echo "\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "public/groupeSecondaire/accueil.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 37,  110 => 35,  106 => 33,  98 => 31,  93 => 30,  89 => 29,  86 => 28,  84 => 27,  81 => 26,  77 => 24,  68 => 22,  64 => 21,  61 => 20,  59 => 19,  52 => 14,  50 => 13,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Groupe secondaire{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li class="active">Vos groupes secondaires</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">	*/
/* 		{% if app.user.personnage  %}*/
/* 								*/
/* 			<div class="header">*/
/* 				<h5>Groupes secondaires</h5>*/
/* 			</div>*/
/* 			*/
/* 			{% if app.user.personnage.postulants|length > 0 %}*/
/* 				<ul class="list-group">*/
/* 					{% for postulant in app.user.personnage.postulants %}*/
/* 						<li class="list-group-item">Votre candidature au groupe <strong>{{ postulant.secondaryGroup.label }}</strong> est en attente de validation.</li>*/
/* 					{% endfor %}*/
/* 				</ul>*/
/* 			{% endif %}*/
/* 			*/
/* 			{% if app.user.personnage.membres|length > 0 %}*/
/* 				<ul class="list-group">*/
/* 					{% for membre in app.user.personnage.membres %}*/
/* 						<a 	href="{{ path('groupeSecondaire.joueur', {'groupe': membre.secondaryGroup.id}) }}" */
/* 							class="list-group-item">{{ membre.secondaryGroup.label }}</a>*/
/* 					{% endfor %}*/
/* 				</ul>*/
/* 			{% endif %}*/
/* 			<p>Si vous faites partie d'un groupe secondaire (secte, confrérie, guilde, etc ...), ou avez l'intention de rejoindre l'un de ces groupes, n'oubliez pas de vous y inscrire ! <a href="{{ path('groupeSecondaire.list') }}">Liste des groupes secondaires</a></p> */
/* 		{% endif %}*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
