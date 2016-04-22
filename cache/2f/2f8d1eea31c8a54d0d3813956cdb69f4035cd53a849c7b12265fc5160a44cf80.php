<?php

/* admin/personnage/delete.twig */
class __TwigTemplate_bd00b0a69b4945b96f2dda19163afad04501ea29e33aa74889a790e920bd34a1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/personnage/delete.twig", 1);
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
\t\t<li><a href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.detail", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\">Détail de ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo "</a></li>
\t\t<li class=\"active\">Supression du personnage</li>
\t</ol>

\t<div class=\"well bs-component\">\t
\t\t<form action=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.delete", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t<fieldset>
\t\t\t\t<legend>Supression du personnage ";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo "</legend>
\t\t\t\t";
        // line 18
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 19
        echo "\t\t\t\t<p class=\"text-danger\">
\t\t\t\t\t<span class=\"fa-stack fa-lg\">
\t\t\t\t\t\t<i class=\"fa fa-square-o fa-stack-2x\"></i>
\t\t\t\t\t\t<i class=\"fa fa-exclamation fa-stack-1x\"></i>
\t\t\t\t\t</span>
\t\t\t\t\tAttention, vous vous appretez à supprimer définitivement un personnage</p>
\t\t\t\t";
        // line 25
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form');
        echo "
\t\t\t</fieldset>
\t\t</form>
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "admin/personnage/delete.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 25,  73 => 19,  71 => 18,  67 => 17,  60 => 15,  50 => 10,  46 => 9,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
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
/* 		<li><a href="{{ path('personnage.admin.detail', {'personnage': personnage.id}) }}">Détail de {{ personnage.publicName }}</a></li>*/
/* 		<li class="active">Supression du personnage</li>*/
/* 	</ol>*/
/* */
/* 	<div class="well bs-component">	*/
/* 		<form action="{{ path('personnage.admin.delete', {'personnage': personnage.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 			<fieldset>*/
/* 				<legend>Supression du personnage {{ personnage.publicName }}</legend>*/
/* 				{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 				<p class="text-danger">*/
/* 					<span class="fa-stack fa-lg">*/
/* 						<i class="fa fa-square-o fa-stack-2x"></i>*/
/* 						<i class="fa fa-exclamation fa-stack-1x"></i>*/
/* 					</span>*/
/* 					Attention, vous vous appretez à supprimer définitivement un personnage</p>*/
/* 				{{ form(form) }}*/
/* 			</fieldset>*/
/* 		</form>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
