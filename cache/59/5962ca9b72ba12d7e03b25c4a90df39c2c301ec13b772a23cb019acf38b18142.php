<?php

/* layout.twig */
class __TwigTemplate_e245b243811a109363c1e27f33bc41ede6d53e3463d7c9299c72745ba8d0eae6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'style' => array($this, 'block_style'),
            'title' => array($this, 'block_title'),
            'menu' => array($this, 'block_menu'),
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
        // line 22
        echo "    </head>
    <body>
    
    \t<div id=\"menu\" >
    \t\t";
        // line 26
        $this->displayBlock('menu', $context, $blocks);
        // line 29
        echo "    \t</div>
\t
        <div id=\"content\" class=\"container-fluid\">
        
        \t";
        // line 33
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session", array()), "getFlashBag", array()), "get", array(0 => "success"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 34
            echo "    \t\t\t<div class=\"alert alert-success\" role=\"alert\">";
            echo twig_escape_filter($this->env, $context["message"], "html", null, true);
            echo "</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "\t\t\t
\t\t\t";
        // line 37
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session", array()), "getFlashBag", array()), "get", array(0 => "alert"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 38
            echo "    \t\t\t<div class=\"alert alert-warning\" role=\"alert\">";
            echo twig_escape_filter($this->env, $context["message"], "html", null, true);
            echo "</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo "\t\t\t
\t\t\t";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session", array()), "getFlashBag", array()), "get", array(0 => "error"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 42
            echo "    \t\t\t<div class=\"alert alert-danger\" role=\"alert\">";
            echo twig_escape_filter($this->env, $context["message"], "html", null, true);
            echo "</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 44
        echo "\t\t\t
        \t";
        // line 45
        $this->displayBlock('content', $context, $blocks);
        // line 46
        echo "        </div>
\t\t\t
         
         
        <script src=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/jquery-1.11.3.min.js\"></script>
        <script src=\"";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/bootstrap.min.js\"></script>
        
        ";
        // line 54
        echo "        <script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/moment.js\"></script>
        <script>
        \tmoment.locale('fr');
        </script>
                
        ";
        // line 60
        echo "        <script type=\"text/javascript\">
\t    \t\$(function () {
\t        \t\$(\"[data-toggle='tooltip']\").tooltip();
\t    \t});
\t\t</script>
\t\t
\t\t";
        // line 67
        echo "\t\t<script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/js/tinymce/tinymce.min.js\"></script>
\t
\t\t<script type=\"text/javascript\">
\t\t\ttinyMCE.init({
\t\t\t\tselector: '.tinymce',
\t\t\t\ttheme: \"modern\",
\t\t\t\tplugins : \"spellchecker,insertdatetime,preview,link,autolink\",
\t\t\t\tbrowser_spellcheck: true,
\t\t\t\tmenubar: \"edit, insert, view, format, tools\",
\t\t\t\ttoolbar:  \"undo, redo, formatselect, bold, italic, alignright, aligncenter, alignright, alignjustify, bullist, numlist  link\",
\t\t\t\tlink_assume_external_targets: true
\t\t\t});
\t\t</script>
\t\t\t\t
\t\t\t\t\t        \t
    \t";
        // line 82
        $this->displayBlock('javascript', $context, $blocks);
        // line 84
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
\t\t\t<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\">
\t\t\t<link rel=\"icon\" href=\"/favicon.ico\" type=\"image/x-icon\">
\t\t\t
\t\t\t<link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/style.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/normalize.css\" />\t\t\t                        
            <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/font-awesome.min.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/bootstrap.min.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "basepath", array()), "html", null, true);
        echo "/css/cyborg_bootstrap.min.css\" />
                                    
\t\t\t";
        // line 18
        $this->displayBlock('style', $context, $blocks);
        // line 19
        echo "\t\t\t\t\t\t            
            <title>LarpManager - ";
        // line 20
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
    }

    // line 18
    public function block_style($context, array $blocks = array())
    {
    }

    // line 20
    public function block_title($context, array $blocks = array())
    {
    }

    // line 26
    public function block_menu($context, array $blocks = array())
    {
        // line 27
        echo "    \t\t\t";
        $this->loadTemplate("menu.twig", "layout.twig", 27)->display($context);
        // line 28
        echo "    \t\t";
    }

    // line 45
    public function block_content($context, array $blocks = array())
    {
    }

    // line 82
    public function block_javascript($context, array $blocks = array())
    {
        echo "\t
\t\t";
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  224 => 82,  219 => 45,  215 => 28,  212 => 27,  209 => 26,  204 => 20,  199 => 18,  193 => 20,  190 => 19,  188 => 18,  183 => 16,  179 => 15,  175 => 14,  171 => 13,  167 => 12,  158 => 5,  155 => 4,  149 => 84,  147 => 82,  128 => 67,  120 => 60,  111 => 54,  106 => 51,  102 => 50,  96 => 46,  94 => 45,  91 => 44,  82 => 42,  78 => 41,  75 => 40,  66 => 38,  62 => 37,  59 => 36,  50 => 34,  46 => 33,  40 => 29,  38 => 26,  32 => 22,  30 => 4,  25 => 1,);
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
/* 			<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">*/
/* 			<link rel="icon" href="/favicon.ico" type="image/x-icon">*/
/* 			*/
/* 			<link rel="stylesheet" href="{{ app.request.basepath }}/css/style.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/normalize.css" />			                        */
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/font-awesome.min.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/bootstrap.min.css" />*/
/*             <link rel="stylesheet" href="{{ app.request.basepath }}/css/cyborg_bootstrap.min.css" />*/
/*                                     */
/* 			{% block style %}{% endblock style %}*/
/* 						            */
/*             <title>LarpManager - {% block title %}{% endblock title %}</title>*/
/*         {% endblock head %}*/
/*     </head>*/
/*     <body>*/
/*     */
/*     	<div id="menu" >*/
/*     		{% block menu %}*/
/*     			{% include 'menu.twig' %}*/
/*     		{% endblock menu %}*/
/*     	</div>*/
/* 	*/
/*         <div id="content" class="container-fluid">*/
/*         */
/*         	{% for message in app.session.getFlashBag.get('success') %}*/
/*     			<div class="alert alert-success" role="alert">{{ message }}</div>*/
/* 			{% endfor %}*/
/* 			*/
/* 			{% for message in app.session.getFlashBag.get('alert') %}*/
/*     			<div class="alert alert-warning" role="alert">{{ message }}</div>*/
/* 			{% endfor %}*/
/* 			*/
/* 			{% for message in app.session.getFlashBag.get('error') %}*/
/*     			<div class="alert alert-danger" role="alert">{{ message }}</div>*/
/* 			{% endfor %}*/
/* 			*/
/*         	{% block content %}{% endblock content %}*/
/*         </div>*/
/* 			*/
/*          */
/*          */
/*         <script src="{{ app.request.basepath }}/js/jquery-1.11.3.min.js"></script>*/
/*         <script src="{{ app.request.basepath }}/js/bootstrap.min.js"></script>*/
/*         */
/*         {# manipulation des dates #}*/
/*         <script src="{{ app.request.basepath }}/js/moment.js"></script>*/
/*         <script>*/
/*         	moment.locale('fr');*/
/*         </script>*/
/*                 */
/*         {# Active les tooltip de bootstrap #}*/
/*         <script type="text/javascript">*/
/* 	    	$(function () {*/
/* 	        	$("[data-toggle='tooltip']").tooltip();*/
/* 	    	});*/
/* 		</script>*/
/* 		*/
/* 		{# editeur tinyMCE #}*/
/* 		<script src="{{ app.request.basepath }}/js/tinymce/tinymce.min.js"></script>*/
/* 	*/
/* 		<script type="text/javascript">*/
/* 			tinyMCE.init({*/
/* 				selector: '.tinymce',*/
/* 				theme: "modern",*/
/* 				plugins : "spellchecker,insertdatetime,preview,link,autolink",*/
/* 				browser_spellcheck: true,*/
/* 				menubar: "edit, insert, view, format, tools",*/
/* 				toolbar:  "undo, redo, formatselect, bold, italic, alignright, aligncenter, alignright, alignjustify, bullist, numlist  link",*/
/* 				link_assume_external_targets: true*/
/* 			});*/
/* 		</script>*/
/* 				*/
/* 					        	*/
/*     	{% block javascript %}	*/
/* 		{% endblock javascript %}*/
/*     </body>*/
/* </html>*/
/* */
