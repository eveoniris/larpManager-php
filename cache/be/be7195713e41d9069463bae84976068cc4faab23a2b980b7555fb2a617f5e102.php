<?php

/* admin/competence/fragment/list.twig */
class __TwigTemplate_11be8582ece0e61eb71f413bfeccfa15a3d329c227804bf9911d87b22aa5ac40 extends Twig_Template
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
        echo "<ul class=\"list-group\">
\t";
        // line 2
        if (((isset($context["add"]) ? $context["add"] : $this->getContext($context, "add")) != false)) {
            // line 3
            echo "\t\t<a href=\"";
            echo $this->env->getExtension('routing')->getPath("competence.add");
            echo "\" class=\"list-group-item active\">
\t\t\t<span class=\"badge\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></span>
\t\t\t<h4 class=\"list-group-item-heading\">Ajouter une compétence</h4>
\t\t</a>
\t";
        }
        // line 8
        echo "\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["competences"]) ? $context["competences"] : $this->getContext($context, "competences")));
        foreach ($context['_seq'] as $context["_key"] => $context["competence"]) {
            // line 9
            echo "\t<li class=\"list-group-item\">
\t\t<h4 class=\"list-group-item-heading\">
\t\t\t";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "competenceFamily", array()), "label", array()), "html", null, true);
            echo " - <small>Niveau ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "level", array()), "label", array()), "html", null, true);
            echo "</small>
\t\t\t<div class=\"pull-right btn-group\" role=\"group\" aria-label=\"...\">
\t\t\t\t<a href=\"";
            // line 13
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.detail", array("competence" => $this->getAttribute($context["competence"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-primary\" role=\"button\">Voir</a>
\t\t\t    <a href=\"";
            // line 14
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.update", array("competence" => $this->getAttribute($context["competence"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-default\" role=\"button\">Modifier</a>
\t\t\t</div>
\t\t</h4>
\t
\t\t";
            // line 18
            if ($this->getAttribute($context["competence"], "description", array())) {
                // line 19
                echo "\t\t\t<p class=\"list-group-item-text text-default\">";
                echo $this->env->getExtension('markdown')->markdown($this->getAttribute($context["competence"], "description", array()));
                echo "</p>
\t\t";
            } else {
                // line 21
                echo "\t\t\t<p class=\"list-group-item-text text-warning\">Attention, cette compétence n'a pas description</p>
\t\t";
            }
            // line 23
            echo "\t\t
\t\t";
            // line 24
            if ( !$this->getAttribute($context["competence"], "documentUrl", array())) {
                // line 25
                echo "\t\t\t<p class=\"list-group-item-text text-warning\">Attention, cette compétence n'a pas de document associé</p>
\t\t";
            } else {
                // line 27
                echo "\t\t\t<p class=\"list-group-item-text text-default\"><a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.document", array("competence" => $this->getAttribute($context["competence"], "id", array()), "document" => $this->getAttribute($context["competence"], "documentUrl", array()))), "html", null, true);
                echo "\">Téléchargez le document</a></p>
\t\t";
            }
            // line 29
            echo "\t\t
\t</li>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competence'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "</ul>";
    }

    public function getTemplateName()
    {
        return "admin/competence/fragment/list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  95 => 32,  87 => 29,  81 => 27,  77 => 25,  75 => 24,  72 => 23,  68 => 21,  62 => 19,  60 => 18,  53 => 14,  49 => 13,  42 => 11,  38 => 9,  33 => 8,  24 => 3,  22 => 2,  19 => 1,);
    }
}
/* <ul class="list-group">*/
/* 	{%  if add != false %}*/
/* 		<a href="{{ path('competence.add') }}" class="list-group-item active">*/
/* 			<span class="badge"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>*/
/* 			<h4 class="list-group-item-heading">Ajouter une compétence</h4>*/
/* 		</a>*/
/* 	{%  endif %}*/
/* 	{%  for competence in competences %}*/
/* 	<li class="list-group-item">*/
/* 		<h4 class="list-group-item-heading">*/
/* 			{{ competence.competenceFamily.label }} - <small>Niveau {{ competence.level.label }}</small>*/
/* 			<div class="pull-right btn-group" role="group" aria-label="...">*/
/* 				<a href="{{ path('competence.detail', {'competence': competence.id}) }}" class="btn btn-primary" role="button">Voir</a>*/
/* 			    <a href="{{ path('competence.update', {'competence': competence.id}) }}" class="btn btn-default" role="button">Modifier</a>*/
/* 			</div>*/
/* 		</h4>*/
/* 	*/
/* 		{% if competence.description %}*/
/* 			<p class="list-group-item-text text-default">{{ competence.description|markdown }}</p>*/
/* 		{% else %}*/
/* 			<p class="list-group-item-text text-warning">Attention, cette compétence n'a pas description</p>*/
/* 		{%  endif %}*/
/* 		*/
/* 		{% if not competence.documentUrl %}*/
/* 			<p class="list-group-item-text text-warning">Attention, cette compétence n'a pas de document associé</p>*/
/* 		{% else %}*/
/* 			<p class="list-group-item-text text-default"><a href="{{ path('competence.document',{'competence' : competence.id, 'document':competence.documentUrl}) }}">Téléchargez le document</a></p>*/
/* 		{% endif %}*/
/* 		*/
/* 	</li>*/
/* 	{%  endfor %}*/
/* </ul>*/
