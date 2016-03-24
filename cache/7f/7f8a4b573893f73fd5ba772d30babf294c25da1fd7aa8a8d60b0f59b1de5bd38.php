<?php

/* personnage/religion_add.twig */
class __TwigTemplate_6a18cdf9ae297b05f391f734f716dffee68a5e26ea563961797903a12dc81df6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "personnage/religion_add.twig", 1);
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
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "<div class=\"container-fluid\">

\t<ol class=\"breadcrumb\">
\t\t<li><a href=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">Accueil</a></li>
\t\t<li><a href=\"";
        // line 10
        echo $this->env->getExtension('routing')->getPath("personnage");
        echo "\">Votre personnage</a></li>
\t\t<li class=\"active\">Choix d'une religion</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t\t<form action=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.religion.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t<fieldset>
\t\t\t\t<legend>";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo " - <small>Choix d'une religion</small></legend>
\t\t\t\t<p class=\"text-warning\">Attention ! une fois votre religion choisie, vous ne pourrez plus revenir sur votre choix.</p>
\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\tVous pouvez choisir autant de religions que vous voulez. Attention toutefois, les règles suivantes s'appliquent :
\t\t\t\t\t<ul>
\t\t\t\t\t\t<li>Vous ne pouvez avoir qu'une seule religion au niveau \"Fervent\"</li>
\t\t\t\t\t\t<li>Si vous choisissez une religion au niveau \"Fanatique\", vous perdez toutes vos autres religions (un Fanatique n'a qu'une seule religion).</li>
\t\t\t\t\t</ul>
\t\t\t\t</p>
\t\t\t\t";
        // line 26
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 27
        echo "\t
\t\t\t\t";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "religion", array()), 'row');
        echo "
\t\t\t\t";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "religionLevel", array()), 'row');
        echo "
\t\t\t\t";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'rest');
        echo "
\t\t\t</fieldset>
\t\t</form>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "personnage/religion_add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 30,  83 => 29,  79 => 28,  76 => 27,  74 => 26,  62 => 17,  55 => 15,  47 => 10,  43 => 9,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}{{ personnage.publicName }}{% endblock title %}*/
/* */
/* {% block content %}*/
/* <div class="container-fluid">*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage')  }}">Accueil</a></li>*/
/* 		<li><a href="{{ path('personnage')  }}">Votre personnage</a></li>*/
/* 		<li class="active">Choix d'une religion</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		<form action="{{ path('personnage.religion.add',{'personnage':personnage.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 			<fieldset>*/
/* 				<legend>{{ personnage.publicName }} - <small>Choix d'une religion</small></legend>*/
/* 				<p class="text-warning">Attention ! une fois votre religion choisie, vous ne pourrez plus revenir sur votre choix.</p>*/
/* 				<p class="list-group-item-text">*/
/* 					Vous pouvez choisir autant de religions que vous voulez. Attention toutefois, les règles suivantes s'appliquent :*/
/* 					<ul>*/
/* 						<li>Vous ne pouvez avoir qu'une seule religion au niveau "Fervent"</li>*/
/* 						<li>Si vous choisissez une religion au niveau "Fanatique", vous perdez toutes vos autres religions (un Fanatique n'a qu'une seule religion).</li>*/
/* 					</ul>*/
/* 				</p>*/
/* 				{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 	*/
/* 				{{ form_row(form.religion) }}*/
/* 				{{ form_row(form.religionLevel) }}*/
/* 				{{ form_rest(form) }}*/
/* 			</fieldset>*/
/* 		</form>*/
/* 	</div>*/
/* {% endblock content %}*/
