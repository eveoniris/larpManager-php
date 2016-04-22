<?php

/* error/layout.twig */
class __TwigTemplate_cd323421b1ca7730a8d87b7c852f660e3cbf37afea5fb845f53e3142bebc2cde extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        ";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 19
        echo "    </head>
    <body>
\t
\t<nav class=\"navbar navbar-default\">
\t  <div class=\"container-fluid\">
\t  
\t  \t<!-- Brand and toggle get grouped for better mobile display -->
\t    <div class=\"navbar-header\">
\t      <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\" aria-expanded=\"false\">
\t        <span class=\"sr-only\">Toggle navigation</span>
\t        <span class=\"icon-bar\"></span>
\t        <span class=\"icon-bar\"></span>
\t        <span class=\"icon-bar\"></span>
\t      </button>
\t      <a class=\"navbar-brand\" href=\"";
        // line 33
        echo $this->env->getExtension('routing')->getPath("homepage");
        echo "\">LarpManager</a>
\t    </div>
\t    </div>
\t   </nav>
\t
        <div id=\"content\" class=\"container-fluid\">
        \t";
        // line 39
        $this->displayBlock('content', $context, $blocks);
        // line 40
        echo "        </div>
\t\t\t
         
         ";
        // line 43
        $this->displayBlock('javascript', $context, $blocks);
        // line 53
        echo "    </body>
</html>
";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "            <meta charset=\"utf-8\">
        \t<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        \t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
\t\t\t
\t\t\t
\t\t\t<link rel=\"stylesheet\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/style.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/normalize.css\" />\t\t\t                        
            <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/font-awesome.min.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/bootstrap.min.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/cyborg_bootstrap.min.css\" />

            
            <title>";
        // line 17
        $this->displayBlock('title', $context, $blocks);
        echo " - LarpManager</title>
        ";
    }

    public function block_title($context, array $blocks = array())
    {
    }

    // line 39
    public function block_content($context, array $blocks = array())
    {
    }

    // line 43
    public function block_javascript($context, array $blocks = array())
    {
        // line 44
        echo "\t         <script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/jquery-1.11.3.min.js\"></script>
\t         <script src=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/bootstrap.min.js\"></script>
\t         <script src=\"";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/jquery.bootgrid.min.js\"></script>
\t         <script src=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/jquery.bootgrid.fa.min.js\"></script>
\t         <script src=\"";
        // line 48
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/moment.js\"></script>
\t         <script>
\t         \tmoment.locale('fr');
\t         </script>
\t\t";
    }

    public function getTemplateName()
    {
        return "error/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  137 => 48,  133 => 47,  129 => 46,  125 => 45,  120 => 44,  117 => 43,  112 => 39,  102 => 17,  96 => 14,  92 => 13,  88 => 12,  84 => 11,  80 => 10,  73 => 5,  70 => 4,  64 => 53,  62 => 43,  57 => 40,  55 => 39,  46 => 33,  30 => 19,  28 => 4,  23 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html>*/
/*     <head>*/
/*         {% block head %}*/
/*             <meta charset="utf-8">*/
/*         	<meta http-equiv="X-UA-Compatible" content="IE=edge">*/
/*         	<meta name="viewport" content="width=device-width, initial-scale=1">*/
/* 			*/
/* 			*/
/* 			<link rel="stylesheet" href="{{ app.request.basepath }}/css/style.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/normalize.css" />			                        */
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/font-awesome.min.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/bootstrap.min.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/cyborg_bootstrap.min.css" />*/
/* */
/*             */
/*             <title>{% block title %}{% endblock title %} - LarpManager</title>*/
/*         {% endblock head %}*/
/*     </head>*/
/*     <body>*/
/* 	*/
/* 	<nav class="navbar navbar-default">*/
/* 	  <div class="container-fluid">*/
/* 	  */
/* 	  	<!-- Brand and toggle get grouped for better mobile display -->*/
/* 	    <div class="navbar-header">*/
/* 	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">*/
/* 	        <span class="sr-only">Toggle navigation</span>*/
/* 	        <span class="icon-bar"></span>*/
/* 	        <span class="icon-bar"></span>*/
/* 	        <span class="icon-bar"></span>*/
/* 	      </button>*/
/* 	      <a class="navbar-brand" href="{{ path('homepage') }}">LarpManager</a>*/
/* 	    </div>*/
/* 	    </div>*/
/* 	   </nav>*/
/* 	*/
/*         <div id="content" class="container-fluid">*/
/*         	{% block content %}{% endblock content %}*/
/*         </div>*/
/* 			*/
/*          */
/*          {% block javascript %}*/
/* 	         <script src="{{ app.request.basepath }}/js/jquery-1.11.3.min.js"></script>*/
/* 	         <script src="{{ app.request.basepath }}/js/bootstrap.min.js"></script>*/
/* 	         <script src="{{ app.request.basepath }}/js/jquery.bootgrid.min.js"></script>*/
/* 	         <script src="{{ app.request.basepath }}/js/jquery.bootgrid.fa.min.js"></script>*/
/* 	         <script src="{{ app.request.basepath }}/js/moment.js"></script>*/
/* 	         <script>*/
/* 	         	moment.locale('fr');*/
/* 	         </script>*/
/* 		{% endblock javascript %}*/
/*     </body>*/
/* </html>*/
/* */
