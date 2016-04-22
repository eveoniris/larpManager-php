<?php

/* public/personnage/accueil.twig */
class __TwigTemplate_668dbe3c6f244b5931905fb8783bf3b08a75230ed400313a20100612667327b6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/personnage/accueil.twig", 1);
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
        echo "Personnage";
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
\t\t<li class=\"active\">Votre personnage</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t\t";
        // line 13
        if (( !$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "etatCivil", array()) || (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array())) == 0))) {
            // line 14
            echo "\t\t\tVous devez remplir votre état civil et rejoindre un groupe avant de pouvoir créer un personnage.
\t\t";
        } else {
            // line 16
            echo "\t\t\t";
            if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array())) {
                // line 17
                echo "\t\t\t\t";
                echo twig_include($this->env, $context, "homepage/fragment/personnage.twig", array("personnage" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "personnage", array())));
                echo "
\t\t\t";
            } else {
                // line 19
                echo "\t\t\t\tVous n'avez pas encore de personnage. Rejoignez un groupe pour y créer un personnage.
\t\t\t\t
\t\t\t\t";
                // line 21
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupes", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
                    // line 22
                    echo "\t\t\t\t\t";
                    if ( !$this->getAttribute($context["groupe"], "getPersonnage", array(0 => $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array())), "method")) {
                        // line 23
                        echo "\t\t\t\t\t\t<a class=\"btn btn default\" href=\"";
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.personnage.add", array("index" => $this->getAttribute($context["groupe"], "id", array()))), "html", null, true);
                        echo "\">Créer votre personnage</a>
\t\t\t\t\t";
                    }
                    // line 25
                    echo "\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 26
                echo "\t\t\t";
            }
            // line 27
            echo "\t\t";
        }
        // line 28
        echo "\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "public/personnage/accueil.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  94 => 28,  91 => 27,  88 => 26,  82 => 25,  76 => 23,  73 => 22,  69 => 21,  65 => 19,  59 => 17,  56 => 16,  52 => 14,  50 => 13,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Personnage{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li class="active">Votre personnage</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		{% if not app.user.etatCivil or app.user.groupes|length == 0  %}*/
/* 			Vous devez remplir votre état civil et rejoindre un groupe avant de pouvoir créer un personnage.*/
/* 		{% else %}*/
/* 			{% if app.user.personnage %}*/
/* 				{{ include("homepage/fragment/personnage.twig",{'personnage':app.user.personnage}) }}*/
/* 			{% else %}*/
/* 				Vous n'avez pas encore de personnage. Rejoignez un groupe pour y créer un personnage.*/
/* 				*/
/* 				{% for groupe in app.user.groupes %}*/
/* 					{% if not groupe.getPersonnage(app.user) %}*/
/* 						<a class="btn btn default" href="{{ path('groupe.personnage.add', {'index': groupe.id}) }}">Créer votre personnage</a>*/
/* 					{% endif %}*/
/* 				{% endfor %}*/
/* 			{% endif %}*/
/* 		{% endif %}*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
