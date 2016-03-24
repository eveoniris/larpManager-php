<?php

/* personnage/competence.twig */
class __TwigTemplate_7a3cb555de7333b9055184fd404dfea4829482741f158b8e119076ee041b3d5a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "personnage/competence.twig", 1);
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
        echo "
<div class=\"container-fluid\">

\t<ol class=\"breadcrumb\">
\t\t<li><a href=\"";
        // line 10
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">Accueil</a></li>
\t\t<li><a href=\"";
        // line 11
        echo $this->env->getExtension('routing')->getPath("personnage");
        echo "\">Votre personnage</a></li>
\t\t<li class=\"active\">Achat d'une compétence</li>
\t</ol>
\t
\t<div class=\"well bs-component\">
\t\t<form action=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("personnage.competence.add", array("personnage" => $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t<fieldset>
\t\t\t\t<legend>";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "publicName", array()), "html", null, true);
        echo " - <small>Achat d'une compétence</small></legend>
\t\t\t\t
\t\t\t\t<ul class=\"list-group\">
\t\t\t\t\t<li class=\"list-group-item\">Vous disposez de <strong>";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "xp", array()), "html", null, true);
        echo "</strong> Points d'expériences.</li>
\t\t\t\t\t<li class=\"list-group-item\">Vos compétences favorites sont : ";
        // line 22
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "competenceFamilyFavorites", array()), ", "), "html", null, true);
        echo "</li>
\t\t\t\t\t<li class=\"list-group-item\">Vos compétences normales sont : ";
        // line 23
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($this->getAttribute((isset($context["personnage"]) ? $context["personnage"] : $this->getContext($context, "personnage")), "classe", array()), "competenceFamilyNormales", array()), ", "), "html", null, true);
        echo "</li>
\t\t\t\t\t<li class=\"list-group-item\">Les autres compétences sont méconnues</li>
\t\t\t\t</ul> 
\t\t
\t\t\t\t";
        // line 27
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 28
        echo "\t
\t\t\t\t";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "competenceId", array()), 'row');
        echo "
\t\t\t\t";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'rest');
        echo "
\t\t\t</fieldset>
\t\t</form>
\t\t
\t\t<h4>Détail des competences accessibles</h4>
\t\t<div class=\"list-group\">
\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_sort_filter((isset($context["competences"]) ? $context["competences"] : $this->getContext($context, "competences"))));
        foreach ($context['_seq'] as $context["_key"] => $context["competence"]) {
            // line 37
            echo "\t\t\t\t<div class=\"list-group-item\">
\t\t\t\t\t<div class=\"list-group-item-heading\"><h6>";
            // line 38
            echo twig_escape_filter($this->env, $this->getAttribute($context["competence"], "label", array()), "html", null, true);
            echo "</h6></div>
\t\t\t\t\t<div class=\"list-group-item-text\">";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute($context["competence"], "description", array()), "html", null, true);
            echo "</div>
\t\t\t\t</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competence'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "personnage/competence.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 42,  113 => 39,  109 => 38,  106 => 37,  102 => 36,  93 => 30,  89 => 29,  86 => 28,  84 => 27,  77 => 23,  73 => 22,  69 => 21,  63 => 18,  56 => 16,  48 => 11,  44 => 10,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}{{ personnage.publicName }}{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* */
/* 	<ol class="breadcrumb">*/
/* 		<li><a href="{{ path('homepage')  }}">Accueil</a></li>*/
/* 		<li><a href="{{ path('personnage')  }}">Votre personnage</a></li>*/
/* 		<li class="active">Achat d'une compétence</li>*/
/* 	</ol>*/
/* 	*/
/* 	<div class="well bs-component">*/
/* 		<form action="{{ path('personnage.competence.add',{'personnage':personnage.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 			<fieldset>*/
/* 				<legend>{{ personnage.publicName }} - <small>Achat d'une compétence</small></legend>*/
/* 				*/
/* 				<ul class="list-group">*/
/* 					<li class="list-group-item">Vous disposez de <strong>{{ personnage.xp }}</strong> Points d'expériences.</li>*/
/* 					<li class="list-group-item">Vos compétences favorites sont : {{ personnage.classe.competenceFamilyFavorites|join(', ') }}</li>*/
/* 					<li class="list-group-item">Vos compétences normales sont : {{ personnage.classe.competenceFamilyNormales|join(', ') }}</li>*/
/* 					<li class="list-group-item">Les autres compétences sont méconnues</li>*/
/* 				</ul> */
/* 		*/
/* 				{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 	*/
/* 				{{ form_row(form.competenceId) }}*/
/* 				{{ form_rest(form) }}*/
/* 			</fieldset>*/
/* 		</form>*/
/* 		*/
/* 		<h4>Détail des competences accessibles</h4>*/
/* 		<div class="list-group">*/
/* 			{% for competence in competences|sort %}*/
/* 				<div class="list-group-item">*/
/* 					<div class="list-group-item-heading"><h6>{{ competence.label }}</h6></div>*/
/* 					<div class="list-group-item-text">{{ competence.description }}</div>*/
/* 				</div>*/
/* 			{% endfor %}*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* {% endblock content %}*/
