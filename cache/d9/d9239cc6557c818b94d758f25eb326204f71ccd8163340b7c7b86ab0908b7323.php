<?php

/* admin/competence/index.twig */
class __TwigTemplate_3b20c22cc180dd938d993d4b6206645112181998f89c3c5435267aa86fb087ef extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/competence/index.twig", 1);
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
        echo "
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-12\">
\t\t\t\t<div class=\"well well-sm\">
\t\t\t\t<h4>
\t\t\t\t\tGestion des compétences
\t\t\t\t\t<small>
\t\t\t\t\t\t( ";
        // line 14
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["competences"]) ? $context["competences"] : $this->getContext($context, "competences"))), "html", null, true);
        echo " competences )
\t\t\t\t\t</small>
\t\t\t\t</h4>
\t\t\t</div>
\t\t\t";
        // line 18
        echo twig_include($this->env, $context, "admin/competence/fragment/list.twig", array("competences" => (isset($context["competences"]) ? $context["competences"] : $this->getContext($context, "competences")), "add" => true));
        echo "
\t\t</div>
\t</div>
</div>
\t
";
    }

    public function getTemplateName()
    {
        return "admin/competence/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 18,  48 => 14,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Compétences{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-12">*/
/* 				<div class="well well-sm">*/
/* 				<h4>*/
/* 					Gestion des compétences*/
/* 					<small>*/
/* 						( {{ competences|length }} competences )*/
/* 					</small>*/
/* 				</h4>*/
/* 			</div>*/
/* 			{{ include("admin/competence/fragment/list.twig", {'competences' : competences, 'add' : true}) }}*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* 	*/
/* {% endblock %}*/
