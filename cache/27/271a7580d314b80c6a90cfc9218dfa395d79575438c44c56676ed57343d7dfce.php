<?php

/* admin/competence/detail.twig */
class __TwigTemplate_3ae81667a767f433635ee3b94f2ef182f97248456b80e85ae44172f952bdc94a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/competence/detail.twig", 1);
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
        echo "\t
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-8\">
\t\t
\t\t\t<a href=\"";
        // line 11
        echo $this->env->getExtension('routing')->getPath("competence");
        echo "\">Retour à la liste des compétences</a>
\t\t\t\t\t\t
\t\t\t<div class=\"well well-sm bs-component\">
\t\t\t\t<h4>
\t\t\t\t\t";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "label", array()), "html", null, true);
        echo "
\t\t\t\t</h4>
\t\t\t</div>

\t\t\t<div class=\"list-group\">\t\t\t\t

\t\t\t\t<div class=\"list-group-item\">
\t\t\t    \t<h4 class=\"list-group-item-heading\">Description</h4>
\t    \t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t";
        // line 24
        if ( !$this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "description", array())) {
            // line 25
            echo "    \t\t\t\t<span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span>
    \t\t\t\tAttention, cette compétence n'a pas de description.
    \t\t\t\t";
        } else {
            // line 28
            echo "    \t\t\t\t\t";
            echo $this->env->getExtension('markdown')->markdown($this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "description", array()));
            echo "
    \t\t\t\t";
        }
        // line 30
        echo "    \t\t\t\t</p>   \t\t\t\t
    \t\t\t\t
    \t\t\t\t<h4 class=\"list-group-item-heading\">Document</h4>
    \t\t\t\t<p class=\"list-group-item-text\">
\t    \t\t\t\t";
        // line 34
        if ( !$this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "documentUrl", array())) {
            // line 35
            echo "\t    \t\t\t\t\tAucun document n'est disponible
\t    \t\t\t\t";
        } else {
            // line 37
            echo "\t    \t\t\t\t\t<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.document", array("competence" => $this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "id", array()), "document" => $this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "documentUrl", array()))), "html", null, true);
            echo "\">Téléchargez le document</a>
\t    \t\t\t\t";
        }
        // line 39
        echo "    \t\t\t\t</p>
\t    \t\t</div>
\t    \t\t
  \t\t\t\t<div class=\"list-group-item\">
  \t\t\t\t\t<div class=\"btn-group\" role=\"group\" aria-label=\"...\">
  \t\t\t\t\t\t<a  class=\"btn btn-primary\" role=\"button\" href=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.update", array("competence" => $this->getAttribute((isset($context["competence"]) ? $context["competence"] : $this->getContext($context, "competence")), "id", array()))), "html", null, true);
        echo "\">Modifier</a>
\t\t\t\t\t</div>
  \t\t\t\t</div>
\t\t\t</div>
\t\t\t
\t\t\t
\t\t</div>
\t</div>
</div>
\t
\t\t\t
";
    }

    public function getTemplateName()
    {
        return "admin/competence/detail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 44,  95 => 39,  89 => 37,  85 => 35,  83 => 34,  77 => 30,  71 => 28,  66 => 25,  64 => 24,  52 => 15,  45 => 11,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block title %}Compétences{% endblock title %}*/
/* */
/* {% block content %}*/
/* 	*/
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-8">*/
/* 		*/
/* 			<a href="{{ path("competence") }}">Retour à la liste des compétences</a>*/
/* 						*/
/* 			<div class="well well-sm bs-component">*/
/* 				<h4>*/
/* 					{{ competence.label }}*/
/* 				</h4>*/
/* 			</div>*/
/* */
/* 			<div class="list-group">				*/
/* */
/* 				<div class="list-group-item">*/
/* 			    	<h4 class="list-group-item-heading">Description</h4>*/
/* 	    			<p class="list-group-item-text">*/
/* 	    			{% if not competence.description %}*/
/*     				<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>*/
/*     				Attention, cette compétence n'a pas de description.*/
/*     				{% else %}*/
/*     					{{ competence.description|markdown }}*/
/*     				{% endif %}*/
/*     				</p>   				*/
/*     				*/
/*     				<h4 class="list-group-item-heading">Document</h4>*/
/*     				<p class="list-group-item-text">*/
/* 	    				{% if not competence.documentUrl %}*/
/* 	    					Aucun document n'est disponible*/
/* 	    				{% else %}*/
/* 	    					<a href="{{ path('competence.document',{'competence' : competence.id, 'document':competence.documentUrl}) }}">Téléchargez le document</a>*/
/* 	    				{% endif %}*/
/*     				</p>*/
/* 	    		</div>*/
/* 	    		*/
/*   				<div class="list-group-item">*/
/*   					<div class="btn-group" role="group" aria-label="...">*/
/*   						<a  class="btn btn-primary" role="button" href="{{ path('competence.update', {'competence' : competence.id}) }}">Modifier</a>*/
/* 					</div>*/
/*   				</div>*/
/* 			</div>*/
/* 			*/
/* 			*/
/* 		</div>*/
/* 	</div>*/
/* </div>*/
/* 	*/
/* 			*/
/* {% endblock %}*/
