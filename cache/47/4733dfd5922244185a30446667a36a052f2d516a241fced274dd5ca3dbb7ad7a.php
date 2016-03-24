<?php

/* competenceFamily/index.twig */
class __TwigTemplate_6bbb724dd98e989156cd7ceb5da09cc4a4a97f37c984f2befc42a9a8719bd96f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "competenceFamily/index.twig", 1);
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
        echo "Familles de compétence";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-12\">
\t\t\t<div class=\"well well-sm\">
\t\t\t\t<h4>
\t\t\t\t\tGestion des familles de compétences
\t\t\t\t\t<small>
\t\t\t\t\t\t( ";
        // line 14
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["competenceFamilies"]) ? $context["competenceFamilies"] : $this->getContext($context, "competenceFamilies"))), "html", null, true);
        echo " familles )
\t\t\t\t\t</small>
\t\t\t\t</h4>
\t\t\t</div>
\t\t\t";
        // line 18
        echo twig_include($this->env, $context, "competenceFamily/fragment/list.twig", array("competenceFamilies" => (isset($context["competenceFamilies"]) ? $context["competenceFamilies"] : $this->getContext($context, "competenceFamilies")), "add" => true));
        echo "
\t\t</div>
\t</div>

</div>
\t
";
    }

    public function getTemplateName()
    {
        return "competenceFamily/index.twig";
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
/* {% block title %}Familles de compétence{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-12">*/
/* 			<div class="well well-sm">*/
/* 				<h4>*/
/* 					Gestion des familles de compétences*/
/* 					<small>*/
/* 						( {{ competenceFamilies|length }} familles )*/
/* 					</small>*/
/* 				</h4>*/
/* 			</div>*/
/* 			{{ include("competenceFamily/fragment/list.twig", {'competenceFamilies' : competenceFamilies, 'add' : true}) }}*/
/* 		</div>*/
/* 	</div>*/
/* */
/* </div>*/
/* 	*/
/* {% endblock %}*/
