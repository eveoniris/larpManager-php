<?php

/* admin/territoire/list.twig */
class __TwigTemplate_da895fd937c975578725a22654f193188ac0853980397a8e36242c017c4ca761 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin/territoire/list.twig", 1);
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
<div class=\"container-fluid\">
\t<div class=\"row\">
\t\t<div class=\"col-xs-12 col-md-12\">
\t\t\t<div class=\"well well-sm\">
\t\t\t\t<h4>
\t\t\t\t\tGestion des territoires
\t\t\t\t\t<small>
\t\t\t\t\t\t( ";
        // line 12
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["territoires"]) ? $context["territoires"] : $this->getContext($context, "territoires"))), "html", null, true);
        echo " territoires )
\t\t\t\t\t</small>
\t\t\t\t</h4>
\t\t\t</div>
\t\t\t
\t\t\t<ul class=\"list-group\">
\t\t\t\t\t<a href=\"";
        // line 18
        echo $this->env->getExtension('routing')->getPath("territoire.admin.add");
        echo "\" class=\"list-group-item active\">
\t\t\t\t\t\t<span class=\"badge\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></span>
\t\t\t\t\t\t<h4 class=\"list-group-item-heading\">Ajouter un territoire</h4>
\t\t\t\t\t</a>

\t\t\t\t";
        // line 23
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["territoires"]) ? $context["territoires"] : $this->getContext($context, "territoires")));
        foreach ($context['_seq'] as $context["_key"] => $context["territoire"]) {
            // line 24
            echo "\t\t\t\t\t
\t\t\t\t\t";
            // line 25
            $context["step"] = $this->getAttribute($context["territoire"], "stepCount", array());
            // line 26
            echo "\t\t\t\t\t<li class=\"list-group-item\" ";
            if (((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) > 0)) {
                echo " style=\"padding-left: ";
                echo twig_escape_filter($this->env, ((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) * 50), "html", null, true);
                echo "px\"";
            }
            echo ">
\t\t\t\t\t\t<h4 class=\"list-group-item-heading\">
\t\t\t\t\t\t\t";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute($context["territoire"], "nom", array()), "html", null, true);
            echo " (";
            if ($this->getAttribute($context["territoire"], "appelation", array())) {
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["territoire"], "appelation", array()), "label", array()), "html", null, true);
            } else {
                echo "<span class=\"text-warning\">Attention, ce territoire n'a pas d'appelation</span>";
            }
            echo ")
\t\t\t\t\t\t\t<div class=\"btn-group pull-right\" role=\"group\" aria-label=\"...\">
\t\t\t\t\t\t\t\t<a href=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.detail", array("territoire" => $this->getAttribute($context["territoire"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-primary\" role=\"button\">Détail</a>
\t\t\t\t\t\t\t    <a href=\"";
            // line 31
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.update", array("territoire" => $this->getAttribute($context["territoire"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-default\" role=\"button\">Modifier</a>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</h4>
\t\t\t\t\t\t<p class=\"list-group-item-text\">\t\t
\t\t\t\t\t\t\t";
            // line 35
            if ($this->getAttribute($context["territoire"], "description", array())) {
                // line 36
                echo "\t\t\t\t\t\t\t\t<p class=\"text-default\">";
                echo twig_escape_filter($this->env, twig_slice($this->env, $this->getAttribute($context["territoire"], "description", array()), 0, 500), "html", null, true);
                echo " ...</p>
\t\t\t\t\t\t\t";
            } else {
                // line 38
                echo "\t\t\t\t\t\t\t\t<p class=\"text-warning\">Attention, ce territoire n'a pas description</p>
\t\t\t\t\t\t\t";
            }
            // line 40
            echo "\t\t\t\t\t\t</p>
\t\t\t\t\t\t";
            // line 41
            if ((twig_length_filter($this->env, $this->getAttribute($context["territoire"], "territoires", array())) > 0)) {
                // line 42
                echo "\t\t\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t\t\tContient les territoires suivants : ";
                // line 43
                echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["territoire"], "territoires", array()), ", "), "html", null, true);
                echo "
\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t";
            }
            // line 46
            echo "\t\t\t\t\t</li>\t
\t\t\t\t\t";
            // line 47
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["territoire"], "territoires", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["region"]) {
                // line 48
                echo "\t\t\t\t\t\t";
                $context["step"] = $this->getAttribute($context["region"], "stepCount", array());
                // line 49
                echo "\t\t\t\t\t\t<li class=\"list-group-item\" ";
                if (((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) > 0)) {
                    echo " style=\"padding-left: ";
                    echo twig_escape_filter($this->env, ((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) * 50), "html", null, true);
                    echo "px\"";
                }
                echo ">
\t\t\t\t\t\t\t<h4 class=\"list-group-item-heading\">
\t\t\t\t\t\t\t\t";
                // line 51
                echo twig_escape_filter($this->env, $this->getAttribute($context["region"], "nom", array()), "html", null, true);
                echo " (";
                if ($this->getAttribute($context["region"], "appelation", array())) {
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["region"], "appelation", array()), "label", array()), "html", null, true);
                } else {
                    echo "<span class=\"text-warning\">Attention, ce territoire n'a pas d'appelation</span>";
                }
                echo ")
\t\t\t\t\t\t\t\t<div class=\"btn-group pull-right\" role=\"group\" aria-label=\"...\">
\t\t\t\t\t\t\t\t\t<a href=\"";
                // line 53
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.detail", array("territoire" => $this->getAttribute($context["region"], "id", array()))), "html", null, true);
                echo "\" class=\"btn btn-primary\" role=\"button\">Détail</a>
\t\t\t\t\t\t\t\t    <a href=\"";
                // line 54
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.update", array("territoire" => $this->getAttribute($context["region"], "id", array()))), "html", null, true);
                echo "\" class=\"btn btn-default\" role=\"button\">Modifier</a>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</h4>
\t\t\t\t\t\t\t<p class=\"list-group-item-text\">\t\t
\t\t\t\t\t\t\t\t";
                // line 58
                if ($this->getAttribute($context["region"], "description", array())) {
                    // line 59
                    echo "\t\t\t\t\t\t\t\t\t<p class=\"text-default\">";
                    echo twig_escape_filter($this->env, twig_slice($this->env, $this->getAttribute($context["region"], "description", array()), 0, 500), "html", null, true);
                    echo " ...</p>
\t\t\t\t\t\t\t\t";
                } else {
                    // line 61
                    echo "\t\t\t\t\t\t\t\t\t<p class=\"text-warning\">Attention, ce territoire n'a pas description</p>
\t\t\t\t\t\t\t\t";
                }
                // line 63
                echo "\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t";
                // line 64
                if ((twig_length_filter($this->env, $this->getAttribute($context["region"], "territoires", array())) > 0)) {
                    // line 65
                    echo "\t\t\t\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t\t\t\tContient les territoires suivants : ";
                    // line 66
                    echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["region"], "territoires", array()), ", "), "html", null, true);
                    echo "
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t";
                }
                // line 69
                echo "\t\t\t\t\t\t</li>
\t\t\t\t\t\t";
                // line 70
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["region"], "territoires", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["fief"]) {
                    // line 71
                    echo "\t\t\t\t\t\t\t";
                    $context["step"] = $this->getAttribute($context["fief"], "stepCount", array());
                    // line 72
                    echo "\t\t\t\t\t\t\t<li class=\"list-group-item\" ";
                    if (((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) > 0)) {
                        echo " style=\"padding-left: ";
                        echo twig_escape_filter($this->env, ((isset($context["step"]) ? $context["step"] : $this->getContext($context, "step")) * 50), "html", null, true);
                        echo "px\"";
                    }
                    echo ">
\t\t\t\t\t\t\t\t<h4 class=\"list-group-item-heading\">
\t\t\t\t\t\t\t\t\t";
                    // line 74
                    echo twig_escape_filter($this->env, $this->getAttribute($context["fief"], "nom", array()), "html", null, true);
                    echo " (";
                    if ($this->getAttribute($context["fief"], "appelation", array())) {
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["fief"], "appelation", array()), "label", array()), "html", null, true);
                    } else {
                        echo "<span class=\"text-warning\">Attention, ce territoire n'a pas d'appelation</span>";
                    }
                    echo ")
\t\t\t\t\t\t\t\t\t<div class=\"btn-group pull-right\" role=\"group\" aria-label=\"...\">
\t\t\t\t\t\t\t\t\t\t<a href=\"";
                    // line 76
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.detail", array("territoire" => $this->getAttribute($context["fief"], "id", array()))), "html", null, true);
                    echo "\" class=\"btn btn-primary\" role=\"button\">Détail</a>
\t\t\t\t\t\t\t\t\t    <a href=\"";
                    // line 77
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("territoire.admin.update", array("territoire" => $this->getAttribute($context["fief"], "id", array()))), "html", null, true);
                    echo "\" class=\"btn btn-default\" role=\"button\">Modifier</a>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</h4>
\t\t\t\t\t\t\t\t<p class=\"list-group-item-text\">\t\t
\t\t\t\t\t\t\t\t\t";
                    // line 81
                    if ($this->getAttribute($context["fief"], "description", array())) {
                        // line 82
                        echo "\t\t\t\t\t\t\t\t\t\t<p class=\"text-default\">";
                        echo twig_escape_filter($this->env, twig_slice($this->env, $this->getAttribute($context["fief"], "description", array()), 0, 500), "html", null, true);
                        echo " ...</p>
\t\t\t\t\t\t\t\t\t";
                    } else {
                        // line 84
                        echo "\t\t\t\t\t\t\t\t\t\t<p class=\"text-warning\">Attention, ce territoire n'a pas description</p>
\t\t\t\t\t\t\t\t\t";
                    }
                    // line 86
                    echo "\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t";
                    // line 87
                    if ((twig_length_filter($this->env, $this->getAttribute($context["fief"], "territoires", array())) > 0)) {
                        // line 88
                        echo "\t\t\t\t\t\t\t\t\t<p class=\"list-group-item-text\">
\t\t\t\t\t\t\t\t\t\tContient les territoires suivants : ";
                        // line 89
                        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["fief"], "territoires", array()), ", "), "html", null, true);
                        echo "
\t\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t";
                    }
                    // line 92
                    echo "\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['fief'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 94
                echo "\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['region'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 95
            echo "\t\t\t\t\t
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['territoire'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 97
        echo "\t\t\t</ul>
\t\t</div>
\t</div>

</div>
\t
";
    }

    public function getTemplateName()
    {
        return "admin/territoire/list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  281 => 97,  274 => 95,  268 => 94,  261 => 92,  255 => 89,  252 => 88,  250 => 87,  247 => 86,  243 => 84,  237 => 82,  235 => 81,  228 => 77,  224 => 76,  213 => 74,  203 => 72,  200 => 71,  196 => 70,  193 => 69,  187 => 66,  184 => 65,  182 => 64,  179 => 63,  175 => 61,  169 => 59,  167 => 58,  160 => 54,  156 => 53,  145 => 51,  135 => 49,  132 => 48,  128 => 47,  125 => 46,  119 => 43,  116 => 42,  114 => 41,  111 => 40,  107 => 38,  101 => 36,  99 => 35,  92 => 31,  88 => 30,  77 => 28,  67 => 26,  65 => 25,  62 => 24,  58 => 23,  50 => 18,  41 => 12,  31 => 4,  28 => 3,  11 => 1,);
    }
}
/* {% extends "layout.twig" %}*/
/* */
/* {% block content %}*/
/* */
/* <div class="container-fluid">*/
/* 	<div class="row">*/
/* 		<div class="col-xs-12 col-md-12">*/
/* 			<div class="well well-sm">*/
/* 				<h4>*/
/* 					Gestion des territoires*/
/* 					<small>*/
/* 						( {{ territoires|length }} territoires )*/
/* 					</small>*/
/* 				</h4>*/
/* 			</div>*/
/* 			*/
/* 			<ul class="list-group">*/
/* 					<a href="{{ path('territoire.admin.add') }}" class="list-group-item active">*/
/* 						<span class="badge"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>*/
/* 						<h4 class="list-group-item-heading">Ajouter un territoire</h4>*/
/* 					</a>*/
/* */
/* 				{%  for territoire in territoires %}*/
/* 					*/
/* 					{%  set step = territoire.stepCount %}*/
/* 					<li class="list-group-item" {% if step > 0 %} style="padding-left: {{ step * 50 }}px"{% endif %}>*/
/* 						<h4 class="list-group-item-heading">*/
/* 							{{ territoire.nom }} ({% if territoire.appelation %}{{ territoire.appelation.label }}{% else %}<span class="text-warning">Attention, ce territoire n'a pas d'appelation</span>{% endif %})*/
/* 							<div class="btn-group pull-right" role="group" aria-label="...">*/
/* 								<a href="{{ path('territoire.admin.detail', {'territoire': territoire.id}) }}" class="btn btn-primary" role="button">Détail</a>*/
/* 							    <a href="{{ path('territoire.admin.update', {'territoire': territoire.id}) }}" class="btn btn-default" role="button">Modifier</a>*/
/* 							</div>*/
/* 						</h4>*/
/* 						<p class="list-group-item-text">		*/
/* 							{% if territoire.description %}*/
/* 								<p class="text-default">{{ territoire.description|slice(0,500) }} ...</p>*/
/* 							{% else %}*/
/* 								<p class="text-warning">Attention, ce territoire n'a pas description</p>*/
/* 							{%  endif %}*/
/* 						</p>*/
/* 						{%  if territoire.territoires|length > 0 %}*/
/* 							<p class="list-group-item-text">*/
/* 								Contient les territoires suivants : {{ territoire.territoires|join(', ') }}*/
/* 							</p>*/
/* 						{% endif %}*/
/* 					</li>	*/
/* 					{% for region in territoire.territoires %}*/
/* 						{%  set step = region.stepCount %}*/
/* 						<li class="list-group-item" {% if step > 0 %} style="padding-left: {{ step * 50 }}px"{% endif %}>*/
/* 							<h4 class="list-group-item-heading">*/
/* 								{{ region.nom }} ({% if region.appelation %}{{ region.appelation.label }}{% else %}<span class="text-warning">Attention, ce territoire n'a pas d'appelation</span>{% endif %})*/
/* 								<div class="btn-group pull-right" role="group" aria-label="...">*/
/* 									<a href="{{ path('territoire.admin.detail', {'territoire': region.id}) }}" class="btn btn-primary" role="button">Détail</a>*/
/* 								    <a href="{{ path('territoire.admin.update', {'territoire': region.id}) }}" class="btn btn-default" role="button">Modifier</a>*/
/* 								</div>*/
/* 							</h4>*/
/* 							<p class="list-group-item-text">		*/
/* 								{% if region.description %}*/
/* 									<p class="text-default">{{ region.description|slice(0,500) }} ...</p>*/
/* 								{% else %}*/
/* 									<p class="text-warning">Attention, ce territoire n'a pas description</p>*/
/* 								{%  endif %}*/
/* 							</p>*/
/* 							{%  if region.territoires|length > 0 %}*/
/* 								<p class="list-group-item-text">*/
/* 									Contient les territoires suivants : {{ region.territoires|join(', ') }}*/
/* 								</p>*/
/* 							{% endif %}*/
/* 						</li>*/
/* 						{% for fief in region.territoires %}*/
/* 							{%  set step = fief.stepCount %}*/
/* 							<li class="list-group-item" {% if step > 0 %} style="padding-left: {{ step * 50 }}px"{% endif %}>*/
/* 								<h4 class="list-group-item-heading">*/
/* 									{{ fief.nom }} ({% if fief.appelation %}{{ fief.appelation.label }}{% else %}<span class="text-warning">Attention, ce territoire n'a pas d'appelation</span>{% endif %})*/
/* 									<div class="btn-group pull-right" role="group" aria-label="...">*/
/* 										<a href="{{ path('territoire.admin.detail', {'territoire': fief.id}) }}" class="btn btn-primary" role="button">Détail</a>*/
/* 									    <a href="{{ path('territoire.admin.update', {'territoire': fief.id}) }}" class="btn btn-default" role="button">Modifier</a>*/
/* 									</div>*/
/* 								</h4>*/
/* 								<p class="list-group-item-text">		*/
/* 									{% if fief.description %}*/
/* 										<p class="text-default">{{ fief.description|slice(0,500) }} ...</p>*/
/* 									{% else %}*/
/* 										<p class="text-warning">Attention, ce territoire n'a pas description</p>*/
/* 									{%  endif %}*/
/* 								</p>*/
/* 								{%  if fief.territoires|length > 0 %}*/
/* 									<p class="list-group-item-text">*/
/* 										Contient les territoires suivants : {{ fief.territoires|join(', ') }}*/
/* 									</p>*/
/* 								{% endif %}*/
/* 							</li>*/
/* 						{% endfor %}*/
/* 					{% endfor %}*/
/* 					*/
/* 				{%  endfor %}*/
/* 			</ul>*/
/* 		</div>*/
/* 	</div>*/
/* */
/* </div>*/
/* 	*/
/* {% endblock %}*/
