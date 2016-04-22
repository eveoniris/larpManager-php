<?php

/* link.twig */
class __TwigTemplate_c7a19ed40fc2dd8b03957dd21771d40ed263d143a7e0c968a3ae46377648d79f extends Twig_Template
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
        echo "<div class=\"col-lg-3 col-md-6\">
\t<div class=\"link\">
\t\t<a href=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["link"]) ? $context["link"] : $this->getContext($context, "link")), "html", null, true);
        echo "\">
\t\t\t<div class=\"row\">
\t\t\t\t<div class=\"col-xs-2\">
\t\t\t\t\t<i class=\"fa ";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["icon"]) ? $context["icon"] : $this->getContext($context, "icon")), "html", null, true);
        echo " fa-3x\"></i>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-xs-10\">
\t\t\t\t\t<h6>
\t\t\t\t\t\t";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo "
\t\t\t\t\t</h6>
\t\t\t\t</div>
\t\t\t</div>
\t\t</a>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "link.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  36 => 10,  29 => 6,  23 => 3,  19 => 1,);
    }
}
/* <div class="col-lg-3 col-md-6">*/
/* 	<div class="link">*/
/* 		<a href="{{ link }}">*/
/* 			<div class="row">*/
/* 				<div class="col-xs-2">*/
/* 					<i class="fa {{ icon }} fa-3x"></i>*/
/* 				</div>*/
/* 				<div class="col-xs-10">*/
/* 					<h6>*/
/* 						{{ title }}*/
/* 					</h6>*/
/* 				</div>*/
/* 			</div>*/
/* 		</a>*/
/* 	</div>*/
/* </div>*/
