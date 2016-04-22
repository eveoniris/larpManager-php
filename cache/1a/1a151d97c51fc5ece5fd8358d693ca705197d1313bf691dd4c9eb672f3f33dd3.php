<?php

/* competenceFamily/fragment/list.twig */
class __TwigTemplate_bc0fdaa1c0f066b27b56e31aa98db6aacab50940366e1b5e9dcf24891783d272 extends Twig_Template
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
            echo $this->env->getExtension('routing')->getPath("competence.family.add");
            echo "\" class=\"list-group-item active\">
\t\t\t<span class=\"badge\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></span>
\t\t\t<h4 class=\"list-group-item-heading\">Ajouter une famille de compétence</h4>
\t\t</a>
\t";
        }
        // line 8
        echo "\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["competenceFamilies"]) ? $context["competenceFamilies"] : $this->getContext($context, "competenceFamilies")));
        foreach ($context['_seq'] as $context["_key"] => $context["competenceFamily"]) {
            // line 9
            echo "\t\t<li class=\"list-group-item\">
\t\t\t<h4 class=\"list-group-item-heading\">
\t\t\t\t";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "label", array()), "html", null, true);
            echo "
\t\t\t\t<div class=\"btn-group pull-right\" role=\"group\" aria-label=\"...\">
\t\t\t\t\t<a href=\"";
            // line 13
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.family.detail", array("index" => $this->getAttribute($context["competenceFamily"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-primary\" role=\"button\">Voir</a>
\t\t\t\t    <a href=\"";
            // line 14
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.family.update", array("index" => $this->getAttribute($context["competenceFamily"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-default\" role=\"button\">Modifier</a>
\t\t\t\t</div>
\t\t\t</h4>
\t\t\t";
            // line 17
            if ($this->env->getExtension('markdown')->markdown($this->getAttribute($context["competenceFamily"], "description", array()))) {
                // line 18
                echo "\t\t\t\t<p class=\"list-group-item-text text-default\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["competenceFamily"], "description", array()), "html", null, true);
                echo "</p>
\t\t\t";
            } else {
                // line 20
                echo "\t\t\t\t<p class=\"list-group-item-text text-warning\">Attention, cette famille de compétence n'a pas description</p>
\t\t\t";
            }
            // line 22
            echo "\t\t\t
\t\t\t<p class=\"list-group-item-text\">
\t\t\t";
            // line 24
            if ((twig_length_filter($this->env, $this->getAttribute($context["competenceFamily"], "competences", array())) > 0)) {
                // line 25
                echo "\t\t\t\tNiveaux définis : 
\t\t\t\t";
                // line 26
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["competenceFamily"], "competences", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["competence"]) {
                    // line 27
                    echo "\t\t\t\t\t";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["competence"], "level", array()), "label", array()), "html", null, true);
                    echo ",
\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competence'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 29
                echo "\t\t\t\t
\t\t\t";
            } else {
                // line 31
                echo "\t\t\t\t<span class=\"text-warning\">Attention, aucune compétence n'est définie dans cette famille !</span>
\t\t\t";
            }
            // line 33
            echo "\t\t\t";
            if ($this->getAttribute($context["competenceFamily"], "lastCompetence", array())) {
                // line 34
                echo "\t\t\t\t<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.add", array("competenceFamily" => $this->getAttribute($context["competenceFamily"], "id", array()), "level" => $this->getAttribute($this->getAttribute($this->getAttribute($context["competenceFamily"], "lastCompetence", array()), "level", array()), "index", array()))), "html", null, true);
                echo "\">Ajouter une compétence</a>
\t\t\t";
            } else {
                // line 36
                echo "\t\t\t\t<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("competence.add", array("competenceFamily" => $this->getAttribute($context["competenceFamily"], "id", array()))), "html", null, true);
                echo "\">Ajouter une compétence</a>
\t\t\t";
            }
            // line 38
            echo "\t\t\t
\t\t\t</p>
\t\t</li>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['competenceFamily'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "</ul>";
    }

    public function getTemplateName()
    {
        return "competenceFamily/fragment/list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 42,  114 => 38,  108 => 36,  102 => 34,  99 => 33,  95 => 31,  91 => 29,  82 => 27,  78 => 26,  75 => 25,  73 => 24,  69 => 22,  65 => 20,  59 => 18,  57 => 17,  51 => 14,  47 => 13,  42 => 11,  38 => 9,  33 => 8,  24 => 3,  22 => 2,  19 => 1,);
    }
}
/* <ul class="list-group">*/
/* 	{%  if add != false %}*/
/* 		<a href="{{ path('competence.family.add') }}" class="list-group-item active">*/
/* 			<span class="badge"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>*/
/* 			<h4 class="list-group-item-heading">Ajouter une famille de compétence</h4>*/
/* 		</a>*/
/* 	{%  endif %}*/
/* 	{%  for competenceFamily in competenceFamilies %}*/
/* 		<li class="list-group-item">*/
/* 			<h4 class="list-group-item-heading">*/
/* 				{{ competenceFamily.label }}*/
/* 				<div class="btn-group pull-right" role="group" aria-label="...">*/
/* 					<a href="{{ path('competence.family.detail', {index: competenceFamily.id}) }}" class="btn btn-primary" role="button">Voir</a>*/
/* 				    <a href="{{ path('competence.family.update', {index: competenceFamily.id}) }}" class="btn btn-default" role="button">Modifier</a>*/
/* 				</div>*/
/* 			</h4>*/
/* 			{% if competenceFamily.description|markdown %}*/
/* 				<p class="list-group-item-text text-default">{{ competenceFamily.description }}</p>*/
/* 			{% else %}*/
/* 				<p class="list-group-item-text text-warning">Attention, cette famille de compétence n'a pas description</p>*/
/* 			{%  endif %}*/
/* 			*/
/* 			<p class="list-group-item-text">*/
/* 			{% if competenceFamily.competences|length > 0 %}*/
/* 				Niveaux définis : */
/* 				{% for competence in competenceFamily.competences %}*/
/* 					{{ competence.level.label }},*/
/* 				{% endfor %}*/
/* 				*/
/* 			{% else %}*/
/* 				<span class="text-warning">Attention, aucune compétence n'est définie dans cette famille !</span>*/
/* 			{% endif %}*/
/* 			{% if competenceFamily.lastCompetence %}*/
/* 				<a href="{{ path('competence.add', {competenceFamily:competenceFamily.id, level:competenceFamily.lastCompetence.level.index}) }}">Ajouter une compétence</a>*/
/* 			{% else %}*/
/* 				<a href="{{ path('competence.add', {competenceFamily:competenceFamily.id }) }}">Ajouter une compétence</a>*/
/* 			{% endif %}*/
/* 			*/
/* 			</p>*/
/* 		</li>*/
/* 	{%  endfor %}*/
/* </ul>*/
