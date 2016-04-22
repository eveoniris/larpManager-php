<?php

/* admin/territoire/update.twig */
class __TwigTemplate_6ea6fce30309d136e247dd6ddb8de1d32db97bd029a1e35c06db5897479f2a78 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/territoire/update.twig", 1);
        $this->blocks = array(
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
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
\t<div class=\"container-fluid\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-xs-12 col-md-8\">
\t\t\t\t<div class=\"well bs-component\">
\t\t\t\t
\t\t\t\t\t<form action=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.update", array("territoire" => $this->getAttribute((isset($context["territoire"]) ? $context["territoire"] : $this->getContext($context, "territoire")), "id", array()))), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
\t\t\t\t\t\t<fieldset>
\t\t\t\t\t\t\t<legend>Modification d'un territoire</legend>
\t\t\t\t\t\t\t";
        // line 13
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 14
        echo "\t\t\t\t\t\t\t";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form');
        echo "
\t\t\t\t\t\t</fieldset>
\t\t\t\t\t</form>
\t\t\t\t\t
\t\t\t\t\t
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>
\t
";
    }

    public function getTemplateName()
    {
        return "admin/territoire/update.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 14,  47 => 13,  39 => 10,  31 => 4,  28 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block content %}*/
/* */
/* 	<div class="container-fluid">*/
/* 		<div class="row">*/
/* 			<div class="col-xs-12 col-md-8">*/
/* 				<div class="well bs-component">*/
/* 				*/
/* 					<form action="{{ path('territoire.admin.update', {'territoire': territoire.id}) }}" method="POST" {{ form_enctype(form) }}>*/
/* 						<fieldset>*/
/* 							<legend>Modification d'un territoire</legend>*/
/* 							{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 							{{ form(form) }}*/
/* 						</fieldset>*/
/* 					</form>*/
/* 					*/
/* 					*/
/* 				</div>*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
