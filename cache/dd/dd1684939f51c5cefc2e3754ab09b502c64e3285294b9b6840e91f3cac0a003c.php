<?php

/* homepage/orga.twig */
class __TwigTemplate_8d520d19df36dde6235f2117e39f3bd5b93c6c52009d360a22ebbceafc34884a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "homepage/orga.twig", 1);
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
        echo "\t   \t\t
\t<div class=\"row\">
\t
\t\t<div class=\"col-xs-12 col-md-6\">
\t\t
\t\t\t<div class=\"well well-sm\">
\t\t\t\t<h4>
\t\t\t\t\tBienvenue ";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "name", array()), "html", null, true);
        echo "
\t\t\t\t</h4>
\t\t\t</div>
\t\t\t\t\t\t
\t\t\t";
        // line 17
        if ($this->env->getExtension('security')->isGranted("ROLE_MODERATOR", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 18
            echo "\t\t\t\t";
            echo twig_include($this->env, $context, "homepage/fragment/new_posts.twig");
            echo "
\t\t\t";
        }
        // line 20
        echo "\t\t\t
\t\t\t";
        // line 23
        echo "\t
\t\t\t";
        // line 24
        if ($this->env->getExtension('security')->isGranted("ROLE_SCENARISTE", $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()))) {
            // line 25
            echo "\t\t\t\t";
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupeScenariste", array())) > 0)) {
                // line 26
                echo "\t\t\t\t<div class=\"panel panel-default\">
\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\tScénariste : groupes dont vous avez la charge
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t<div class=\"list-group\">
\t\t\t\t\t\t\t";
                // line 32
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "groupeScenariste", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["groupe"]) {
                    // line 33
                    echo "\t\t\t\t\t\t\t\t<a class=\"list-group-item\" href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.detail", array("index" => $this->getAttribute($context["groupe"], "id", array()))), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["groupe"], "nom", array()), "html", null, true);
                    echo "</a>
\t\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['groupe'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 35
                echo "\t\t\t\t\t\t</div>\t
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t";
            }
            // line 39
            echo "\t\t\t";
        }
        // line 40
        echo "\t\t</div>
\t\t
\t\t<div class=\"col-xs-12 col-md-6\">
\t\t
\t\t\t";
        // line 45
        echo "\t\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["annonces"]) ? $context["annonces"] : $this->getContext($context, "annonces")));
        foreach ($context['_seq'] as $context["_key"] => $context["annonce"]) {
            // line 46
            echo "\t\t\t\t<div class=\"panel panel-primary\">
\t\t\t\t\t<div class=\"panel-heading\">
\t\t\t\t\t\t";
            // line 48
            echo twig_escape_filter($this->env, $this->getAttribute($context["annonce"], "title", array()), "html", null, true);
            echo "
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"panel-body\">
\t\t\t\t\t\t";
            // line 51
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["annonce"], "text", array()));
            echo "
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['annonce'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        echo "\t\t\t\t\t\t
\t\t</div>
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "homepage/orga.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  136 => 55,  126 => 51,  120 => 48,  116 => 46,  111 => 45,  105 => 40,  102 => 39,  96 => 35,  85 => 33,  81 => 32,  73 => 26,  70 => 25,  68 => 24,  65 => 23,  62 => 20,  56 => 18,  54 => 17,  47 => 13,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Accueil{% endblock title %}*/
/* */
/* {% block content %}*/
/* 	   		*/
/* 	<div class="row">*/
/* 	*/
/* 		<div class="col-xs-12 col-md-6">*/
/* 		*/
/* 			<div class="well well-sm">*/
/* 				<h4>*/
/* 					Bienvenue {{ app.user.name }}*/
/* 				</h4>*/
/* 			</div>*/
/* 						*/
/* 			{% if is_granted('ROLE_MODERATOR', app.user) %}*/
/* 				{{ include("homepage/fragment/new_posts.twig") }}*/
/* 			{% endif %}*/
/* 			*/
/* 			{# si l'utilisateur est scénariste, lui afficher les liens vers les groupes dont*/
/* 			   il a la responsabilité #}*/
/* 	*/
/* 			{% if is_granted('ROLE_SCENARISTE', app.user) %}*/
/* 				{% if app.user.groupeScenariste|length > 0 %}*/
/* 				<div class="panel panel-default">*/
/* 					<div class="panel-heading">*/
/* 						Scénariste : groupes dont vous avez la charge*/
/* 					</div>*/
/* 					<div class="panel-body">*/
/* 						<div class="list-group">*/
/* 							{% for groupe in app.user.groupeScenariste %}*/
/* 								<a class="list-group-item" href="{{ path('groupe.detail', {'index': groupe.id}) }}">{{ groupe.nom }}</a>*/
/* 							{% endfor %}*/
/* 						</div>	*/
/* 					</div>*/
/* 				</div>*/
/* 				{%  endif %}*/
/* 			{% endif %}*/
/* 		</div>*/
/* 		*/
/* 		<div class="col-xs-12 col-md-6">*/
/* 		*/
/* 			{# affichage de l'annonce la plus récente et du lien vers les archives des annonces #}*/
/* 			{% for annonce in annonces %}*/
/* 				<div class="panel panel-primary">*/
/* 					<div class="panel-heading">*/
/* 						{{ annonce.title }}*/
/* 					</div>*/
/* 					<div class="panel-body">*/
/* 						{{ annonce.text|markdown }}*/
/* 					</div>*/
/* 				</div>*/
/* 			{% endfor %}*/
/* 						*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
