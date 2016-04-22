<?php

/* admin/competence/add.twig */
class __TwigTemplate_69d7ada72d297248e3f4153b7e16f6d7f191779bf98f04e67c556dd8ed7056c6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/competence/add.twig", 1);
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
        echo "Compétences";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "\t<div class=\"container-fluid\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-xs-12 col-md-8\">
\t\t\t\t<a href=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("competence");
        echo "\">Retour à la liste des compétences</a>
\t\t\t\t";
        // line 10
        echo twig_include($this->env, $context, "admin/competence/fragment/form.twig", array("legend" => "Ajout d'une compétence", "action" => $this->env->getExtension('routing')->getPath("competence.add"), "form" =>         // line 13
(isset($context["form"]) ? $context["form"] : $this->getContext($context, "form"))));
        echo "
\t\t\t</div>
\t\t</div>
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "admin/competence/add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 13,  47 => 10,  43 => 9,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Compétences{% endblock title %}*/
/* */
/* {% block content %}*/
/* 	<div class="container-fluid">*/
/* 		<div class="row">*/
/* 			<div class="col-xs-12 col-md-8">*/
/* 				<a href="{{ path("competence") }}">Retour à la liste des compétences</a>*/
/* 				{{ include("admin/competence/fragment/form.twig",{*/
/* 					'legend': 'Ajout d\'une compétence',*/
/* 					'action': path('competence.add'), */
/* 					'form' : form}) }}*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
