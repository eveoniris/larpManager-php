<?php

/* competenceFamily/fragment/form.twig */
class __TwigTemplate_4e88a8f3659ac784d51430b7ad0712d772efc1b81a9cbd0e660f26344731c011 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"well bs-component\">
\t<form action=\"";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["action"]) ? $context["action"] : $this->getContext($context, "action")), "html", null, true);
        echo "\" method=\"POST\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo " novalidate>
\t\t<fieldset>
\t\t\t<legend>";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["legend"]) ? $context["legend"] : $this->getContext($context, "legend")), "html", null, true);
        echo "</legend>
\t\t\t\t";
        // line 5
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "Form/bootstrap_3_layout.html.twig"));
        // line 6
        echo "\t\t\t\t";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form');
        echo "
\t\t</fieldset>
\t</form>
</div>";
    }

    public function getTemplateName()
    {
        return "competenceFamily/fragment/form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 6,  33 => 5,  29 => 4,  22 => 2,  19 => 1,);
    }
}
/* <div class="well bs-component">*/
/* 	<form action="{{ action }}" method="POST" {{ form_enctype(form) }} novalidate>*/
/* 		<fieldset>*/
/* 			<legend>{{ legend }}</legend>*/
/* 				{% form_theme form 'Form/bootstrap_3_layout.html.twig' %}*/
/* 				{{ form(form) }}*/
/* 		</fieldset>*/
/* 	</form>*/
/* </div>*/
