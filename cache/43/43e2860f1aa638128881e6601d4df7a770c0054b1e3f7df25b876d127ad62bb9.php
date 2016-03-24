<?php

/* admin/personnage/removeReligion.twig */
class __TwigTemplate_fcd4cb5b2255466bfbe54ea806a8fefbf48ae8569bcd29881543ce747b2a546c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/personnage/removeReligion.twig", 1);
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
\t\t<li class=\"active\">Retrait d'une religion</li>
\t</ol>

\t<div class=\"well bs-component\">\t
\t\t<form action=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.admin.religion.delete", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()), "personnageReligion" => $this->getAttribute((isset($context["personnageReligion"]) ? $context["personnageReligion"] : $this->getContext($context, "personnageReligion")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t<fieldset>
\t\t\t\t<legend>Retrait d'une religion</legend>
\t\t\t\t";
        // line 18
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 19
        echo "\t\t\t\tAttention, vous vous appretez à retirer la religion <b>";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["personnageReligion"]) ? $context["personnageReligion"] : $this->getContext($context, "personnageReligion")), "religion", array()), "label", array()), "html", null, true);
        echo "</b> du personnage ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo ".
\t\t\t\t";
        // line 20
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
        return "admin/personnage/removeReligion.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 20,  70 => 19,  68 => 18,  60 => 15,  50 => 10,  46 => 9,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
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
/* 		<li class="active">Retrait d'une religion</li>*/
/* 	</ol>*/
/* */
/* 	<div class="well bs-component">	*/
/* 		<form action="{{ path('personnage.admin.religion.delete', {'personnage': personnage.id,'personnageReligion': personnageReligion.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 			<fieldset>*/
/* 				<legend>Retrait d'une religion</legend>*/
/* 				{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 				Attention, vous vous appretez à retirer la religion <b>{{ personnageReligion.religion.label }}</b> du personnage {{ personnage.publicName }}.*/
/* 				{{ form(form) }}*/
/* 			</fieldset>*/
/* 		</form>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
