<?php

/* error/notfound.twig */
class __TwigTemplate_9b953244492ef0a54b3c16a61c45d7e7a7994dc1b1ab283cf8cc4ef0dc40ce7d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("error/layout.twig", "error/notfound.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "error/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Erreur 404";
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "\t<div class=\"panel panel-danger\">
\t\t<div class=\"panel-heading\">
\t\t\t<h6>Par Crom ! une erreur 404 (page non trouvée) !</h6>
\t\t</div>
\t  \t<div class=\"panel-body\">
\t  \t\t<blockquote>
\t  \t\t\t<p>Crom, je ne t'ai jamais prié auparavant. Je n'ai pas les mots pour cela. Personne, pas même toi, ne se souviendra si nous étions des hommes bons ou mauvais, pourquoi nous nous battions ou pourquoi nous sommes morts. Non, tout ce qui compte c'est que deux hommes se sont battus contre un grand nombre, voilà ce qui importe. Tu aimes la bravoure, Crom, alors accorde moi une chose, accorde-moi la VENGEANCE ! Et si tu n’entends pas ma prière, va au diable !\"
\t  \t\t</blockquote>
\t  \t\t<center>
\t  \t\t\t<img src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/img/conan.jpg\" class=\"img-responsive img-circle\" alt=\"\">
\t  \t\t</center>
\t\t</div>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "error/notfound.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 13,  38 => 4,  35 => 3,  29 => 2,  11 => 1,);
    }
}
/* {% extends "error/layout.twig" %}*/
/* {% block title %}Erreur 404{% endblock title %}*/
/* {% block content %}*/
/* 	<div class="panel panel-danger">*/
/* 		<div class="panel-heading">*/
/* 			<h6>Par Crom ! une erreur 404 (page non trouvée) !</h6>*/
/* 		</div>*/
/* 	  	<div class="panel-body">*/
/* 	  		<blockquote>*/
/* 	  			<p>Crom, je ne t'ai jamais prié auparavant. Je n'ai pas les mots pour cela. Personne, pas même toi, ne se souviendra si nous étions des hommes bons ou mauvais, pourquoi nous nous battions ou pourquoi nous sommes morts. Non, tout ce qui compte c'est que deux hommes se sont battus contre un grand nombre, voilà ce qui importe. Tu aimes la bravoure, Crom, alors accorde moi une chose, accorde-moi la VENGEANCE ! Et si tu n’entends pas ma prière, va au diable !"*/
/* 	  		</blockquote>*/
/* 	  		<center>*/
/* 	  			<img src="{{ app.request.basepath }}/img/conan.jpg" class="img-responsive img-circle" alt="">*/
/* 	  		</center>*/
/* 		</div>*/
/* 	</div>*/
/* {% endblock %}*/
