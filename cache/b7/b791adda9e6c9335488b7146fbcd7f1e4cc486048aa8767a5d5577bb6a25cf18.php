<?php

/* public/groupe/declareWar.twig */
class __TwigTemplate_1411303a180570fcff68f74acf26e88212441862e6fd0102e665920c721c05ed extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "public/groupe/declareWar.twig", 1);
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
        echo "Déclarer la guerre";
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
\t\t<li><a href=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("groupe");
        echo "\">Votre groupe</a></li>
\t\t<li class=\"active\">Déclarer la guerre</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t
\t\t<form action=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe.declareWar", array("groupe" => $this->getAttribute((isset($context["groupe"]) ? $context["groupe"] : $this->getContext($context, "groupe")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo " novalidate>
\t\t\t";
        // line 16
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 17
        echo "
\t\t\t";
        // line 18
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form');
        echo "
\t\t\t
\t\t\t<p>Le chef de groupe recevra une notification pour le prévenir de votre déclaration de guerre.</p>
\t\t\t 
\t\t</form>
\t
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "public/groupe/declareWar.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 18,  63 => 17,  61 => 16,  55 => 15,  46 => 9,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Déclarer la guerre{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage') }}">Page d'accueil</a></li>*/
/* 		<li><a href="{{ path('groupe') }}">Votre groupe</a></li>*/
/* 		<li class="active">Déclarer la guerre</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 	*/
/* 		<form action="{{ path('groupe.declareWar', {'groupe': groupe.id}) }}" method="POST" {{ form_enctype(form) }} novalidate>*/
/* 			{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* */
/* 			{{ form(form) }}*/
/* 			*/
/* 			<p>Le chef de groupe recevra une notification pour le prévenir de votre déclaration de guerre.</p>*/
/* 			 */
/* 		</form>*/
/* 	*/
/* 	</div>*/
/* 	*/
/* {% endblock %}*/
