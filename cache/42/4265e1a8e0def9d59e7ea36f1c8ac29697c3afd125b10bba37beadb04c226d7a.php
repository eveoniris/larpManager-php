<?php

/* competenceFamily/add.twig */
class __TwigTemplate_3df135b5aa142d632c694de9467a01d118779f5f0464c3dd54816d41f6529454 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "competenceFamily/add.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'javascript' => array($this, 'block_javascript'),
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
        echo "Famille de compétence";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
\t<div class=\"container-fluid\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-xs-12 col-md-8\">
\t\t\t\t";
        // line 10
        echo twig_include($this->env, $context, "competenceFamily/fragment/form.twig", array("legend" => "Ajout d'une famille de compétence", "action" => $this->env->getExtension('routing')->getPath("competence.family.add"), "form" =>         // line 13
(isset($context["form"]) ? $context["form"] : $this->getContext($context, "form"))));
        echo "
\t\t\t</div>
\t\t</div>
\t</div>
\t
";
    }

    // line 20
    public function block_javascript($context, array $blocks = array())
    {
        // line 21
        echo "
\t";
        // line 22
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "

\t";
        // line 24
        echo "   
\t<script src=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/tinymce/tinymce.min.js\"></script>

\t<script type=\"text/javascript\">
\t\ttinyMCE.init({
\t\t\t\tmode: \"textareas\",
\t\t\t\ttheme: \"modern\",
\t\t\t\tplugins : \"spellchecker,insertdatetime,preview\", 
\t\t});
\t\t
\t</script>

";
    }

    public function getTemplateName()
    {
        return "competenceFamily/add.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 25,  67 => 24,  62 => 22,  59 => 21,  56 => 20,  46 => 13,  45 => 10,  39 => 6,  36 => 5,  30 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Famille de compétence{% endblock title %}*/
/* */
/* {% block content %}*/
/* */
/* 	<div class="container-fluid">*/
/* 		<div class="row">*/
/* 			<div class="col-xs-12 col-md-8">*/
/* 				{{ include("competenceFamily/fragment/form.twig",{*/
/* 					'legend': 'Ajout d\'une famille de compétence',*/
/* 					'action': path('competence.family.add'), */
/* 					'form' : form}) }}*/
/* 			</div>*/
/* 		</div>*/
/* 	</div>*/
/* 	*/
/* {% endblock content %}*/
/* */
/* {% block javascript %}*/
/* */
/* 	{{ parent() }}*/
/* */
/* 	{# inclusion du plugin tinymce pour la saisie du post #}   */
/* 	<script src="{{ app.request.basepath }}/js/tinymce/tinymce.min.js"></script>*/
/* */
/* 	<script type="text/javascript">*/
/* 		tinyMCE.init({*/
/* 				mode: "textareas",*/
/* 				theme: "modern",*/
/* 				plugins : "spellchecker,insertdatetime,preview", */
/* 		});*/
/* 		*/
/* 	</script>*/
/* */
/* {% endblock javascript %}*/
